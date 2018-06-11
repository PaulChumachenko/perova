<?php
class ModelCatalogIsearch extends Model {

                public function isearch_stripos($haystack, $needle) {
                    return (function_exists('mb_stripos')) ? mb_stripos($haystack, $needle) : stripos($haystack, $needle);
                }

                function filterByAttributes(&$result, $searchWords) {
                    $fullSearchTerm = implode(' ', $searchWords);
                    
                    $has_attribute_match = preg_match_all("~(\s|^)(\S+?)\s*=\s*(\S+)~i", $fullSearchTerm, $attribute_matches);
                    
                    $config = $this->config->get('isearch');
                    
                    if ($has_attribute_match) {
                        foreach ($result as $key => $myrow) {
                            if (empty($myrow['product_attributes'])) unset($result[$key]);
                            
                            $row_attributes = explode('|', $myrow['product_attributes']);
                            $has_match = false;
                            
                            foreach ($row_attributes as $row_attribute) {
                                $attribute_params = explode(':', $row_attribute);
                                
                                if ($config['CorporateSearchLanguages'] == 'single' && $attribute_params[0] != $this->config->get('config_language_id')) continue;
                                
                                foreach ($attribute_matches[2] as $i => $v) {
                                    $attribute = $v;
                                    $value = $attribute_matches[3][$i];
                                    
                                    if ($this->isearch_stripos($attribute_params[1], $attribute) !== FALSE && $this->isearch_stripos($attribute_params[2], $value) !== FALSE) {
                                        $has_match = true;
                                    }
                                }
                            }
                            
                            if (!$has_match) {
                                unset($result[$key]);
                            }
                        }
                    }
                }

                function constructQuery($selectFields, $sort, $matchFields, $searchWords, $limit, $category_id, $sub_category, $excludeRules, $category_query = false, $strictSearch = false) {
                    $this->load->model('localisation/language');
                    
                    $fullSearchTerm = implode(' ', $searchWords);
                                        
                    $fullSearchTerm = str_replace('=', ' ', $fullSearchTerm);
                    $fullSearchTerm = preg_replace('/\s+/', ' ', $fullSearchTerm);

                    $languages = $this->model_localisation_language->getLanguages();
                    
                    $config = $this->config->get('isearch');
                    
                    if ($config['UseStrictSearch'] == 'yes') {
                        $searchWords = array($fullSearchTerm);
                    } else {
                        $searchWords = explode(' ', $fullSearchTerm);
                    }

                    $language_suffixes = array('');

                    if ($config['CorporateSearchLanguages'] == 'single') {
                        $language_suffixes = array('_' . $this->config->get('config_language_id'));
                    }
                    
                    if (!empty($config['CorporateCustomOrder'])) {
                        foreach ($config['CorporateCustomOrder'] as $customOrder) {
                            if (!in_array($customOrder['order'], $selectFields)) $selectFields[] = $customOrder['order'];
                        }
                    }

                    if ($category_query) {
                        $selectFields[] = "isc.category_id";
                        $selectFields[] = "isc.category_name_" . $this->config->get('config_language_id') . " as category_name";
                    }

                    foreach ($selectFields as &$selectField) {
                        if (in_array($selectField, array('name', 'description', 'search_data', 'name_length', 'stock_status'))) $selectField .= '_' . $this->config->get('config_language_id') . ' as ' . $selectField;
                    }
                    
                    if (!empty($excludeRules)) {
                        $cond_exclude = array();
                        
                        foreach ($excludeRules as $rule) {
                            $operator = '';
                            switch ($rule['operator']) {
                                case 'lt' : $operator = '<'; break;
                                case 'gt' : $operator = '>'; break;
                                case 'eq' : $operator = '='; break; 
                            }
                            
                            switch($rule['type']) {
                                case 'category_status' : {
                                    $cond_exclude[] = 'cat.status ' . $operator . ' ' . trim($rule['value']);
                                } break;
                                case 'stock_status_id' : {
                                    $cond_exclude[] = 'isc.stock_status_id ' . $operator . ' ' . trim($rule['value']);
                                } break;
                                case 'status' : {
                                    $cond_exclude[] = 'isc.status ' . $operator . ' ' . trim($rule['value']);
                                } break;
                                case 'quantity' : {
                                    $cond_exclude[] = 'isc.quantity ' . $operator . ' ' . trim($rule['value']);
                                } break;
                            }
                        }
                        
                    } else {
                        $cond_exclude = array('isc.status = 0');    
                    }
                    
                    $match_type = $config['CorporateSearchMethod'];
                    
                    $compare_clauses_ready = array();

                    $ft_min_word_len_query = $this->db->query("SHOW VARIABLES LIKE 'ft_min_word_len'");
                    $ft_min_word_len = $ft_min_word_len_query->num_rows ? (int)$ft_min_word_len_query->row['Value'] : 4;
                    
                    $ft_max_word_len_query = $this->db->query("SHOW VARIABLES LIKE 'ft_max_word_len'");
                    $ft_max_word_len = $ft_max_word_len_query->num_rows ? (int)$ft_max_word_len_query->row['Value'] : 84;

                    foreach ($matchFields as $matchField) {
                        $compare_clauses = array();
                        foreach ($searchWords as $searchWord) {
                            foreach ($language_suffixes as $language_suffix) {
                                switch($match_type) {
                                    case 'fulltext' : {
                                        if ($this->isearch_strlen($searchWord) >= $ft_min_word_len && $this->isearch_strlen($searchWord) <= $ft_max_word_len) {
                                            if ($config['UseStrictSearch'] == 'yes') {
                                                $compare_clauses[] = "MATCH(" . $matchField . $language_suffix . ") AGAINST ('\"" . $searchWord . "\"' IN BOOLEAN MODE)";
                                            } else {
                                                $compare_clauses[] = "MATCH(" . $matchField . $language_suffix . ") AGAINST ('+" . $searchWord . "*' IN BOOLEAN MODE)";
                                            }
                                        } else {
                                            $compare_clauses[] = $matchField . $language_suffix . " LIKE \"" . $searchWord . "%\"";
                                        }
                                    } break;
                                    case 'fulltext_like_' : {
                                        if ($config['UseStrictSearch'] == 'yes') {
                                            $compare_clauses[] = "MATCH(" . $matchField . $language_suffix . ") AGAINST ('\"" . $searchWord . "\"' IN BOOLEAN MODE)";
                                        } else {
                                            if (preg_match("~^[a-z0-9]+$~i", $searchWord) && $this->isearch_strlen($searchWord) >= $ft_min_word_len && $this->isearch_strlen($searchWord) <= $ft_max_word_len) {
                                                $compare_clauses[] = "MATCH(" . $matchField . $language_suffix . ") AGAINST ('+" . $searchWord . "*' IN BOOLEAN MODE)";
                                            } else {
                                                $compare_clauses[] = $matchField . $language_suffix . " LIKE \"%" . $searchWord . "%\"";
                                            }
                                        }
                                    } break;
                                    case 'like_' : {
                                        $compare_clauses[] = $matchField . $language_suffix . " LIKE \"" . $searchWord . "%\"";
                                    } break;
                                    case '_like_' : {
                                        $compare_clauses[] = $matchField . $language_suffix . " LIKE \"%" . $searchWord . "%\"";
                                    } break;
                                }
                            }
                        }
                        $compare_clauses_ready[] = implode(' AND ', $compare_clauses);
                    }

                    $weight_clauses_ready = array();
                    if (!empty($config['CorporateCustomWeight'])) {
                        foreach ($config['CorporateCustomWeight'] as $matchField) {
                            $matchWeight = $matchField['weight'];
                            $matchField = $matchField['field'];
                            $multilingual = array('name', 'description', 'attributes', 'attributes_values', 'categories', 'filters', 'tags', 'optionname', 'optionvalue', 'metadescription', 'metakeyword', 'stock_status');
                        
                            $compare_clauses = array();
                            foreach ($searchWords as $searchWord) {
                                foreach ($language_suffixes as $language_suffix) {
                                    $language_suffix = in_array($matchField, $multilingual) ? $language_suffix : '';
                                
                                    switch($match_type) {
                                        case 'fulltext' : {
                                            if ($this->isearch_strlen($searchWord) >= $ft_min_word_len && $this->isearch_strlen($searchWord) <= $ft_max_word_len) {
                                                if ($config['UseStrictSearch'] == 'yes') {
                                                    $compare_clauses[] = "MATCH(" . $matchField . $language_suffix . ") AGAINST ('\"" . $searchWord . "\"' IN BOOLEAN MODE)";
                                                } else {
                                                    $compare_clauses[] = "MATCH(" . $matchField . $language_suffix . ") AGAINST ('+" . $searchWord . "*' IN BOOLEAN MODE)";
                                                }
                                            }
                                        } break;
                                        case 'fulltext_like_' : {
                                            if ($config['UseStrictSearch'] == 'yes') {
                                                $compare_clauses[] = "MATCH(" . $matchField . $language_suffix . ") AGAINST ('\"" . $searchWord . "\"' IN BOOLEAN MODE)";
                                            } else {
                                                if (preg_match("~^[a-z0-9]+$~i", $searchWord) && $this->isearch_strlen($searchWord) >= $ft_min_word_len && $this->isearch_strlen($searchWord) <= $ft_max_word_len) {
                                                    $compare_clauses[] = "MATCH(" . $matchField . $language_suffix . ") AGAINST ('+" . $searchWord . "*' IN BOOLEAN MODE)";
                                                } else {
                                                    $compare_clauses[] = $matchField . $language_suffix . " LIKE \"%" . $searchWord . "%\"";
                                                }
                                            }
                                        } break;
                                        case 'like_' : {
                                            $compare_clauses[] = $matchField . $language_suffix . " LIKE \"" . $searchWord . "%\"";
                                        } break;
                                        case '_like_' : {
                                            $compare_clauses[] = $matchField . $language_suffix . " LIKE \"%" . $searchWord . "%\"";
                                        } break;
                                    }
                                }
                            }
                            $weight_clauses_ready[] = 'WHEN ' . implode(' AND ', $compare_clauses) . ' THEN ' . $matchWeight;
                        }
                    }
                    
                    if (!empty($weight_clauses_ready)) {
                        $selectFields[] = "(CASE " . implode(' ', $weight_clauses_ready) . " ELSE -101 END) as CorporateWeight";
                    }
                    
                    $joins = array();
                    $cond = " AND status='1' AND date_available <= NOW() ";
                    
                    if (!empty($category_id)) {
                        if (defined('VERSION') && VERSION >= '1.5.5') {
                            if (!empty($sub_category)) {
                                $joins[] = " LEFT JOIN " . DB_PREFIX . "category_path cp ON (cp.category_id = isc.category_id)";        
                                $cond .= " AND cp.path_id = '" . (int)$category_id . "'";
                            } else {
                                $cond .= " AND isc.category_id = '" . (int)$category_id . "'";
                            }
                        } else {
                            if (!empty($sub_category)) {
                                $implode_data = array();
                                
                                $implode_data[] = (int)$category_id;
                                
                                $this->load->model('catalog/category');
                                
                                $categories = $this->model_catalog_category->getCategoriesByParentId($category_id);
                                                    
                                foreach ($categories as $my_category_id) {
                                    $implode_data[] = (int)$my_category_id;
                                }
                                            
                                $cond .= " AND isc.category_id IN (" . implode(', ', $implode_data) . ")";          
                            } else {
                                $cond .= " AND isc.category_id = '" . (int)$category_id . "'";
                            }
                        }
                    }
                    
                    $full_clauses = array();
                    $limits = !empty($limit) ? ' LIMIT 0, ' . $limit : '';
                    
                    if (empty($sort)) {
                        if (empty($config['CorporateCustomOrder'])) {
                            $sort = 'name_length ASC, name ASC';
                        } else {
                            $mysorts = array();
                            foreach ($config['CorporateCustomOrder'] as $customOrder) {
                                $mysorts[] = $customOrder['order'] . ' ' . strtoupper($customOrder['order_direction']);
                            }
                            $sort = implode(', ', $mysorts);
                        }
                    }

                    $selects = implode(', ', $selectFields);
                    
                    if (!empty($weight_clauses_ready)) {
                        $sort = 'CorporateWeight DESC, ' . $sort;
                    }
                    
                    $orders = ' ORDER BY ' . (!empty($sort) ? $sort : 'name_length ASC');
                    
                    if (!empty($compare_clauses_ready)) {
                        foreach ($compare_clauses_ready as $compare_clause) {
                            $full_clauses[] = 'SELECT ' . $selects . ' FROM ' . DB_PREFIX . 'isearch_cache AS isc ' . (!empty($joins) ? implode(' ', $joins) : '') . ' WHERE FIND_IN_SET(\'' . $this->config->get('config_store_id') . '\', isc.stores) AND ' . $compare_clause . $cond . ' AND !(' . implode(' OR ', $cond_exclude) . ')';
                        }
                    } else {
                        $full_clauses[] = 'SELECT ' . $selects . ' FROM ' . DB_PREFIX . 'isearch_cache AS isc ' . (!empty($joins) ? implode(' ', $joins) : '') . ' WHERE FIND_IN_SET(\'' . $this->config->get('config_store_id') . '\', isc.stores) ' . $cond . ' AND !(' . implode(' OR ', $cond_exclude) . ')';
                    }
                    
                    if ($category_query) {
                        $sql = "SELECT SQL_CALC_FOUND_ROWS category_id, category_name, COUNT(product_id) as category_count FROM (" . implode(' UNION ', $full_clauses) . ") as isr GROUP BY (category_id) ORDER BY category_count DESC" . $limits;
                    } else {
                        $sql = implode(' UNION ', $full_clauses) . " GROUP BY (product_id)" . $orders . $limits;
                        $sql = "SELECT SQL_CALC_FOUND_ROWS" . substr($sql, 6);
                    }

                    return $sql;
                }
            
    public function updateViewed($product_id) {
        $this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
    }
    
    public function iSearch($keywords = '', $searchIn, $useImages = true, $strictSearch = false, $singularize = false, $spellCheck = array(),$sort = '', $order = 'ASC', $category_id = 0, $sub_category = false, $excludeTerms, $excludeRules, $searchInCache = false, $searchInDescription = false, $showCategories = false, $categoriesLimit = 5) {
        if (function_exists('mb_internal_encoding')) mb_internal_encoding("UTF-8");
        foreach ($searchIn as $k => $s) {
            if (empty($s)) $searchIn[$k] = false;   
        }
        $this->fixParameters($sort, $order, $category_id, $sub_category);
        $keywords = $this->convertKeywords($keywords, $singularize, $spellCheck, $excludeTerms, $strictSearch);
        $customer_price = $this->config->get('config_customer_price');
        $attributes = compact('keywords', 'searchIn', 'useImages', 'strictSearch', 'singularize', 'spellCheck', 'customer_price', 'sort', 'order', 'category_id', 'sub_category', 'excludeTerms', 'excludeRules','searchInCache', 'searchInDescription', 'showCategories', 'categoriesLimit');
        
        if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getGroupId();;
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }
        
        $attributes['customer_group_id'] = $customer_group_id;
        $cache = md5(http_build_query($attributes));
        
        $products = $this->cache->get('product.' . (int)$this->config->get('config_language_id') . '.' . $this->session->data['currency'] . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache);
        
        if (!$products) {
            
            $keywords = trim($this->isearch_strtolower($keywords));
            
            $products = array();
            if($this->isearch_strlen($keywords) >= 2) {
                $words = explode(' ', $keywords);
                
                if ($strictSearch === true) {
                    $words = array($keywords);
                }
                
                //{HOOK_CHECK_SEARCH_IN_CACHE}

                if (!$searchInCache) { //NORMAL SEARCH
            
                
                $cond = '';
                foreach ($words as $word) {
                    if ($searchIn['name'] === true) { $cond .= ' AND (LOWER(pd.name) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                        else { $cond .= ' AND (1=0'; }
                    if ($searchIn['model'] === true) { $cond .= ' OR LOWER(p.model) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['sku'] === true) { $cond .= ' OR LOWER(p.sku) LIKE "' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; } 
                    if ($searchIn['upc'] === true) { $cond .= ' OR LOWER(p.upc) LIKE "' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['ean'] === true) { $cond .= ' OR LOWER(p.ean) LIKE "' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['jan'] === true) { $cond .= ' OR LOWER(p.jan) LIKE "' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['isbn'] === true) { $cond .= ' OR LOWER(p.isbn) LIKE "' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['mpn'] === true) { $cond .= ' OR LOWER(p.mpn) LIKE "' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['location'] === true) { $cond .= ' OR LOWER(p.location) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['tags'] === true) { $cond .= ' OR LOWER(ptag.tag) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['manufacturer'] === true) { $cond .= ' OR LOWER(manu.name) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['attributes'] === true) { $cond .= ' OR LOWER(ad.name) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['attributes_values'] === true) { $cond .= ' OR LOWER(pa.text) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['categories'] === true) { $cond .= ' OR LOWER(cd.name) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['filters'] === true) { $cond .= ' OR LOWER(fd.name) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['description'] === true) { $cond .= ' OR LOWER(pd.description) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['optionname'] === true) { $cond .= ' OR LOWER(ode.name) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['optionvalue'] === true) { $cond .= ' OR LOWER(ovde.name) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['metadescription'] === true) { $cond .= ' OR LOWER(pd.meta_description) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['metakeyword'] === true) { $cond .= ' OR LOWER(pd.meta_keyword) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    $cond .= ')';
                }
                $cond = $this->isearch_substr($cond, 4);
                
                $extra_sql = '';
                $extra_select = '';
                
                if ($searchIn['manufacturer'] === true) { 
                    $extra_select .= 'manu.name AS manufacturer, ';
                    $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'manufacturer AS manu ON manu.manufacturer_id = p.manufacturer_id '; 
                }
                
                if ($searchIn['tags'] === true) { 
                    $extra_select .= 'ptag.tag AS tag, ';
                    $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'product_tag AS ptag ON (ptag.product_id = pd.product_id AND pd.language_id = ptag.language_id) '; 
                }
                
                $needs_category_join = false;
                
                if (!empty($excludeRules)) {
                    $cond_exclude = array();
                    
                    foreach ($excludeRules as $rule) {
                        $operator = '';
                        switch ($rule['operator']) {
                            case 'lt' : $operator = '<'; break;
                            case 'gt' : $operator = '>'; break;
                            case 'eq' : $operator = '='; break; 
                        }
                        
                        switch($rule['type']) {
                            case 'category_status' : {
                                $cond_exclude[] = 'cat.status ' . $operator . ' ' . trim($rule['value']);
                            } break;
                            case 'status' : {
                                $cond_exclude[] = 'p.status ' . $operator . ' ' . trim($rule['value']);
                            } break;
                            case 'stock_status_id' : {
                                $cond_exclude[] = 'p.stock_status_id ' . $operator . ' ' . trim($rule['value']);
                            } break;
                            case 'quantity' : {
                                $cond_exclude[] = 'p.quantity ' . $operator . ' ' . trim($rule['value']);
                            } break;
                        }
                        if ($rule['type'] == 'category_status') {
                            $needs_category_join = true;
                        }
                    }
                    
                } else {
                    $cond_exclude = array('p.status = 0');  
                }
                
                if ($searchIn['categories'] === true || $needs_category_join) { 
                    $extra_select .= 'cd.name AS category, ';
                    $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'product_to_category AS p2c ON p2c.product_id = pd.product_id ';
                    $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'category_description AS cd ON (cd.category_id = p2c.category_id AND pd.language_id = cd.language_id) ';
                }
                
                if ($needs_category_join) {
                    $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'category AS cat ON (cat.category_id = p2c.category_id AND p2c.product_id = pd.product_id) ';
                }
                
                if ($searchIn['filters'] === true) { 
                    $extra_select .= 'fd.name AS filter, ';
                    $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'product_filter AS p2f ON p2f.product_id = pd.product_id ';
                    $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'filter_description AS fd ON (fd.filter_id = p2f.filter_id AND pd.language_id = fd.language_id) ';
                }
                
                if ($searchIn['attributes'] === true || $searchIn['attributes_values'] === true) { 
                    $extra_select .= 'ad.name AS attribute, ';
                    $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'product_attribute AS pa ON pa.product_id = pd.product_id ';
                    $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'attribute_description AS ad ON (ad.attribute_id = pa.attribute_id AND pd.language_id = ad.language_id) '; 
                }
                
                if ($searchIn['location'] === true) { 
                    $extra_select .= 'p.location, '; 
                }
                
                if ($searchIn['model'] === true) { 
                    $extra_select .= ''; 
                }
                
                if ($searchIn['name'] === true) { 
                    $extra_select .= ''; 
                }
    
                if ($searchIn['optionname'] === true) { 
                    $extra_select .= 'ode.name AS optionname, '; 
                    $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'product_option AS popt ON popt.product_id = pd.product_id ';
                    $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'option_description AS ode ON (ode.option_id = popt.option_id AND pd.language_id = ode.language_id)';
                }
    
                if ($searchIn['optionvalue'] === true) { 
                    $extra_select .= 'ovde.name AS optionvalue, '; 
                    if ($searchIn['optionname'] !== true) {
                        $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'product_option AS popt ON popt.product_id = pd.product_id ';  
                    }
                    $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'option_value_description AS ovde ON (ovde.option_id = popt.option_id AND pd.language_id = ovde.language_id)';
                }
    
                if ($useImages === true) { 
                    $extra_select .= 'p.image, '; 
                }
                
                if ($keywords == 'no-ajax-mode') {
                    $cond = '1=1';
                    $sql_limit = ' LIMIT 1000';
                } else {
                    $sql_limit = ' LIMIT 20';
                }

                if (defined('VERSION') && VERSION >= '1.5.4') {
                    if ($searchIn['tags'] === true) {
                        $extra_sql = str_replace('LEFT JOIN ' . DB_PREFIX . 'product_tag AS ptag ON (ptag.product_id = pd.product_id AND pd.language_id = ptag.language_id) ','',$extra_sql);
                        $extra_select = str_replace('ptag.tag AS tag, ','pd.tag AS tag, ',$extra_select);
                        $cond = str_replace('ptag.tag','pd.tag',$cond);
                    }   
                }
                
                $sql  = 'SELECT pd.product_id, p.model, pd.name,'.$extra_select.' p.price, p.tax_class_id, pspe.price AS special, (SELECT price FROM ' . DB_PREFIX . 'product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = \'' . (int)$customer_group_id . '\' AND pd2.quantity = \'1\' AND ((pd2.date_start = \'0000-00-00\' OR pd2.date_start < NOW()) AND (pd2.date_end = \'0000-00-00\' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount FROM ' . DB_PREFIX . 'product_description AS pd ';
                $sql .= 'LEFT JOIN ' . DB_PREFIX . 'product AS p ON p.product_id = pd.product_id ';
                $sql .= 'LEFT JOIN ' . DB_PREFIX . 'product_to_store AS ptostore ON ptostore.product_id = pd.product_id ';
                $sql .= 'LEFT JOIN ' . DB_PREFIX . 'product_special AS pspe ON (pspe.product_id = pd.product_id AND pspe.customer_group_id = "'.$customer_group_id.'" AND (pspe.date_start = "0000-00-00" OR pspe.date_start < NOW()) AND (pspe.date_end = "0000-00-00" OR pspe.date_end > NOW()) AND pspe.priority = (SELECT priority FROM ' . DB_PREFIX . 'product_special WHERE product_id = pd.product_id AND customer_group_id = "'.$customer_group_id.'" AND (date_start = "0000-00-00" OR date_start < NOW()) AND (date_end = "0000-00-00" OR date_end > NOW()) ORDER BY priority ASC, price ASC LIMIT 0,1) ) ';
                $sql .= $extra_sql;
                
                $sql .= 'WHERE ' . $cond . ' AND !(' . implode(' OR ', $cond_exclude) . ') ';
                $sql .= 'AND pd.language_id = ' . (int)$this->config->get('config_language_id');
                $sql .= ' AND p.date_available <= NOW() ';
                $sql .= ' AND ptostore.store_id =  ' . (int)$this->config->get('config_store_id'); 
                
                //if (($searchIn['categories'] === true || $searchIn['description'] === true ) && $searchIn['name'] === true)
                $sql .= ' GROUP BY pd.product_id';
                
                $sql .= ' ORDER BY Length(pd.name), pd.name ASC';
                
                $sql .= $sql_limit;
                
                //{HOOK_SEARCH_IN_CACHE_ISEARCH}

                } else { //SEARCH IN CACHE
                    $sql = '';
                    $selectFields = array('name', 'product_id', 'price', 'discount', 'tax_class_id', 'special', 'name_length', 'seo_url', 'model', 'stock_status', 'stock_status_id', 'quantity', 'viewed', 'product_attributes', 'date_available');
                    
                    if ($keywords == 'no-ajax-mode') {
                        $cond = false;
                        $sql_limit = '1000';
                    } else {
                        $cond = true;

                        $config = $this->config->get('isearch');

                        $sql_limit = (int)$config['ResultsLimit'] + 1;
                    }
                    
                    if ($useImages) $selectFields[] = 'image';
                    
                    $temp = array();
                    
                    if ($cond) {
                        if ($searchIn['description'] !== true) {
                            $sql = $this->constructQuery($selectFields, '', array('isc.search_data'), $words, $sql_limit, 0, 0, $excludeRules, false, $strictSearch);
                            $category_sql = $this->constructQuery($selectFields, '', array('isc.search_data'), $words, $categoriesLimit, 0, 0, $excludeRules, true, $strictSearch);
                        } else {
                            $sql = $this->constructQuery($selectFields, '', array('isc.search_data', 'isc.description'), $words, $sql_limit, 0, 0, $excludeRules, false, $strictSearch);
                            $category_sql = $this->constructQuery($selectFields, '', array('isc.search_data', 'isc.description'), $words, $categoriesLimit, 0, 0, $excludeRules, true, $strictSearch);
                        }
                    } else {
                        $sql = $this->constructQuery($selectFields, '', array(), array(), $sql_limit, 0, 0, $excludeRules, false, $strictSearch);
                        $category_sql = $this->constructQuery($selectFields, '', array(), array(), $categoriesLimit, 0, 0, $excludeRules, true, $strictSearch);
                    }
                }
            
                
                $result = $this->db->query($sql);

                $this->filterByAttributes($result->rows, $words);
            
                
                //{HOOK_SEARCH_AGAIN_ISEARCH}

                $total_products_result = $this->db->query("SELECT FOUND_ROWS() as count");
                $total_products = $total_products_result->num_rows ? (int)$total_products_result->row['count'] : 0;

                $categories_result = array();
                $total_categories = 0;

                if ($showCategories && $searchInCache) {
                    $categories_result_query = $this->db->query($category_sql);
                    $categories_result = $categories_result_query->num_rows ? $categories_result_query->rows : array();
                    $total_categories_result = $this->db->query("SELECT FOUND_ROWS() as count");
                    $total_categories = $total_categories_result->num_rows ? (int)$total_categories_result->row['count'] : 0;
                }
            
                
                if($result) {
                    $products = (isset($result->rows)) ? $result->rows : $result->row;
                    
                    if ($useImages === true) {
                        $this->load->model('tool/image');   
                    }
                    
                    foreach ($products as $k => $result) {
                        $result['price'] = !empty($result['discount']) ? $result['discount'] : $result['price'];

                        $image = (!empty($result['image'])) ? $result['image']: '' ;
                        
                        if ($useImages === true) {
                            $config = $this->config->get('iSearch');
                            $image = (!empty($result['image'])) ? $this->model_tool_image->resize($result['image'], empty($config['InstantResultsImageWidth']) ? 80 : $config['InstantResultsImageWidth'], empty($config['InstantResultsImageHeight']) ? 80 : $config['InstantResultsImageHeight']) : $this->model_tool_image->resize('no_image.jpg', empty($config['InstantResultsImageWidth']) ? 80 : $config['InstantResultsImageWidth'], empty($config['InstantResultsImageHeight']) ? 80 : $config['InstantResultsImageHeight']);
                        } else {
                            $image = false; 
                        }
                        
                        

                $specials = explode('|', $result['special']);
                $result['special'] = false;
                foreach ($specials as $temp_special) {
                    if (empty($temp_special)) continue;
                    $temp_special = explode(':', $temp_special);
                    if ($temp_special[0] == $customer_group_id && (strcmp($temp_special[3], date('Y-m-d')) <= 0 || $temp_special[3] == '0000-00-00') && (strcmp($temp_special[4], date('Y-m-d')) >= 0 || $temp_special[4] == '0000-00-00')) {
                        $result['special'] = $temp_special[2];
                        break;
                    }
                }

                $discounts = explode('|', $result['discount']);
                $result['discount'] = false;
                foreach ($discounts as $temp_discount) {
                    if (empty($temp_discount)) continue;
                    $temp_discount = explode(':', $temp_discount);
                    if ($temp_discount[1] == 1 && $temp_discount[0] == $customer_group_id && (strcmp($temp_discount[4], date('Y-m-d')) <= 0 || $temp_discount[4] == '0000-00-00') && (strcmp($temp_discount[5], date('Y-m-d')) >= 0 || $temp_discount[5] == '0000-00-00')) {
                        $result['price'] = $temp_discount[3];
                        break;
                    }
                }
            
                        $special = ((float)$result['special']) ? $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'))) : false;
                        
                        $model = (!empty($result['model'])) ? $result['model'] : '';
                        
                        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                        
                        if (empty($isearch_corporate_href)) {
                        $isearch_href = htmlspecialchars_decode(
                                $this->url->link(
                                    'product/product', 
                                    'product_id=' . $result['product_id'] . '&' . 
                                        (defined('VERSION') && VERSION >= '1.5.5' ? 'search' : 'filter_name') . '=' . urlencode($keywords), 
                                    'SSL'), 
                                ENT_QUOTES
                            );
                        }


                if (!empty($result['seo_url'])) {
                    $temp_seo_urls = explode('|', $result['seo_url']);
                    $result['seo_url'] = '';
                    foreach ($temp_seo_urls as $temp_seo_url) {
                        $temp_seo_parts = explode(':', $temp_seo_url);
                        if ($temp_seo_parts[0] == $this->config->get('config_language_id')) {
                            $result['seo_url'] = $temp_seo_parts[1]; break;
                        }
                    }
                }
                
                $isearch_corporate_href = 
                    empty($result['seo_url']) || $this->config->get('config_seo_url') != 1 ? 
                        $this->url->link(
                            'product/product', 
                            'product_id=' . $result['product_id'] . '&' . (defined('VERSION') && VERSION >= '1.5.5' ? 
                                'search' : 
                                'filter_name'
                            ) . '=' . implode(' ', $words), 'SSL') : (
                        (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')) ? 
                                $this->config->get('config_ssl') : 
                                $this->config->get('config_url')
                            ) . $result['seo_url'] . '&' . (defined('VERSION') && VERSION >= '1.5.5' ? 
                                'search' : 
                                'filter_name'
                            ) . '=' . implode(' ', $words));
            
                        $products[$k] = array(
                            'name'  => htmlspecialchars_decode($result['name'], ENT_QUOTES),
                            'model' => $model, 
                            'image' => $image,
                            'price' => (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) ? $price : '', 
                            'special' => (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) ? $special : '',
                            
                'href' => $isearch_corporate_href
            
                        ); 
                    }
                }
            }
            
            $products = $this->sortProducts($products,$keywords,$searchIn);
            
            $products = array(
                'products' => !empty($products) ? $products : array(),
                'suggestions' => $this->getSuggestions($keywords)
            );


                if (empty($products['products'])) {
                    $products = array(
                        'products' => !empty($products) ? $products : array(),
                        'total_products' => !empty($total_products) ? $total_products : 0,
                        'categories' => !empty($categories_result) ? $categories_result : array(),
                        'total_categories' => !empty($total_categories) ? $total_categories : 0
                    );
                } else {
                    $products['total_products'] = !empty($total_products) ? $total_products : 0;
                    $products['categories'] = !empty($categories_result) ? $categories_result : array();
                    $products['total_categories'] = !empty($total_categories) ? $total_categories : 0;
                }

                foreach ($products['categories'] as &$category) {
                    $category['href'] = str_replace('&amp;', '&', $this->url->link('product/category', 'path=' . $category['category_id'], 'SSL'));
                }
            
            $this->cache->set('product.' . (int)$this->config->get('config_language_id') . '.' . $this->session->data['currency'] . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache, $products);
            
        }
        
        if (empty($products['products'][0])) $products['products'] = array();
        return $products;
    }
    
    public function iSearchStandard($keywords = '', $searchIn, $strictSearch = false, $singularize = false, $spellCheck = array(), $sort = '', $order = 'ASC', $category_id = 0, $sub_category = false, $excludeTerms, $excludeRules, $searchInCache = false, $searchInDescription = false, $showCategories = false, $categoriesLimit = 5) {
        if (function_exists('mb_internal_encoding')) mb_internal_encoding("UTF-8");
        foreach ($searchIn as $k => $s) {
            if (empty($s)) $searchIn[$k] = false;   
        }
        $this->fixParameters($sort, $order, $category_id, $sub_category);
        $keywords = $this->convertKeywords($keywords, $singularize, $spellCheck, $excludeTerms, $strictSearch);
        $customer_price = $this->config->get('config_customer_price');
        $attributes = compact('keywords', 'searchIn', 'strictSearch', 'singularize', 'spellCheck', 'customer_price', 'sort', 'order', 'category_id', 'sub_category', 'excludeTerms', 'excludeRules','searchInCache', 'searchInDescription', 'showCategories', 'categoriesLimit');
        
        if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getGroupId();;
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }
        $attributes['customer_group_id'] = $customer_group_id;
        $cache = md5(http_build_query($attributes));
        $products = $this->cache->get('productstandard.' . (int)$this->config->get('config_language_id') . '.' . $this->session->data['currency'] . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache);
        
        if (!$products) {
            
            $keywords = trim($this->isearch_strtolower($keywords));
            
            $products = array();
            if($this->isearch_strlen($keywords) >= 2) {
                $words = explode(' ', $keywords);
                
                if ($strictSearch === true) {
                    $words = array($keywords);
                }
                
                //{HOOK_CHECK_SEARCH_IN_CACHE}

                if (!$searchInCache) { //NORMAL SEARCH
            
                
                $cond = '';
                foreach ($words as $word) {
                    if ($searchIn['name'] === true) { $cond .= ' AND (LOWER(pd.name) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                        else { $cond .= ' AND (1=0'; }
                    if ($searchIn['model'] === true) { $cond .= ' OR LOWER(p.model) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['sku'] === true) { $cond .= ' OR LOWER(p.sku) LIKE "' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; } 
                    if ($searchIn['upc'] === true) { $cond .= ' OR LOWER(p.upc) LIKE "' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['ean'] === true) { $cond .= ' OR LOWER(p.ean) LIKE "' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['jan'] === true) { $cond .= ' OR LOWER(p.jan) LIKE "' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['isbn'] === true) { $cond .= ' OR LOWER(p.isbn) LIKE "' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['mpn'] === true) { $cond .= ' OR LOWER(p.mpn) LIKE "' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['location'] === true) { $cond .= ' OR LOWER(p.location) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['tags'] === true) { $cond .= ' OR LOWER(ptag.tag) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['manufacturer'] === true) { $cond .= ' OR LOWER(manu.name) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['attributes'] === true) { $cond .= ' OR LOWER(ad.name) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['attributes_values'] === true) { $cond .= ' OR LOWER(pa.text) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['categories'] === true) { $cond .= ' OR LOWER(cd.name) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['filters'] === true) { $cond .= ' OR LOWER(fd.name) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['description'] === true) { $cond .= ' OR LOWER(pd.description) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['optionname'] === true) { $cond .= ' OR LOWER(ode.name) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['optionvalue'] === true) { $cond .= ' OR LOWER(ovde.name) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['metadescription'] === true) { $cond .= ' OR LOWER(pd.meta_description) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['metakeyword'] === true) { $cond .= ' OR LOWER(pd.meta_keyword) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    $cond .= ')';
                }
                $cond = $this->isearch_substr($cond, 4);
                
                $extra_sql = '';
                $extra_select = '';
                
                if ($searchIn['manufacturer'] === true) { 
                    $extra_select .= 'manu.name AS manufacturer, ';
                    $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'manufacturer AS manu ON manu.manufacturer_id = p.manufacturer_id '; 
                }
                
                if ($searchIn['tags'] === true) { 
                    $extra_select .= 'ptag.tag AS tag, ';
                    $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'product_tag AS ptag ON (ptag.product_id = pd.product_id AND pd.language_id = ptag.language_id) '; 
                }
                
                $needs_category_join = false;
                
                if (!empty($excludeRules)) {
                    $cond_exclude = array();
                    
                    foreach ($excludeRules as $rule) {
                        $operator = '';
                        switch ($rule['operator']) {
                            case 'lt' : $operator = '<'; break;
                            case 'gt' : $operator = '>'; break;
                            case 'eq' : $operator = '='; break; 
                        }
                        
                        switch($rule['type']) {
                            case 'category_status' : {
                                $cond_exclude[] = 'cat.status ' . $operator . ' ' . trim($rule['value']);
                            } break;
                            case 'status' : {
                                $cond_exclude[] = 'p.status ' . $operator . ' ' . trim($rule['value']);
                            } break;
                            case 'stock_status_id' : {
                                $cond_exclude[] = 'p.stock_status_id ' . $operator . ' ' . trim($rule['value']);
                            } break;
                            case 'quantity' : {
                                $cond_exclude[] = 'p.quantity ' . $operator . ' ' . trim($rule['value']);
                            } break;
                        }
                        if ($rule['type'] == 'category_status') {
                            $needs_category_join = true;
                        }
                    }
                    
                } else {
                    $cond_exclude = array('p.status = 0');  
                }
                
                if ($searchIn['categories'] === true || !empty($category_id) || $needs_category_join) { 
                    $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'product_to_category AS p2c ON p2c.product_id = pd.product_id ';
                }
                
                if ($needs_category_join) {
                    $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'category AS cat ON (cat.category_id = p2c.category_id AND p2c.product_id = pd.product_id) ';
                }
                
                if ($searchIn['filters'] === true) { 
                    $extra_select .= 'fd.name AS filter, ';
                    $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'product_filter AS p2f ON p2f.product_id = pd.product_id ';
                    $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'filter_description AS fd ON (fd.filter_id = p2f.filter_id AND pd.language_id = fd.language_id) ';
                }
                
                if (defined('VERSION') && VERSION >= '1.5.5') {
                    if (!empty($category_id)) {
                        if (!empty($sub_category)) {
                            $extra_sql .= " LEFT JOIN " . DB_PREFIX . "category_path cp ON (cp.category_id = p2c.category_id)";     
                            $cond .= " AND cp.path_id = '" . (int)$category_id . "'";
                        } else {
                            $cond .= " AND p2c.category_id = '" . (int)$category_id . "'";
                        }
                    }
                } else {
                    if (!empty($category_id)) {
                        if (!empty($sub_category)) {
                            $implode_data = array();
                            
                            $implode_data[] = (int)$category_id;
                            
                            $this->load->model('catalog/category');
                            
                            $categories = $this->model_catalog_category->getCategoriesByParentId($category_id);
                                                
                            foreach ($categories as $my_category_id) {
                                $implode_data[] = (int)$my_category_id;
                            }
                                        
                            $cond .= " AND p2c.category_id IN (" . implode(', ', $implode_data) . ")";          
                        } else {
                            $cond .= " AND p2c.category_id = '" . (int)$category_id . "'";
                        }
                    }
                }
                
                if ($searchIn['categories'] === true) { 
                    $extra_select .= 'cd.name AS category, ';
                    $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'category_description AS cd ON (cd.category_id = p2c.category_id AND pd.language_id = cd.language_id) ';
                }
                
                if ($searchIn['attributes'] === true || $searchIn['attributes_values'] === true) { 
                    $extra_select .= 'ad.name AS attribute, ';
                    $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'product_attribute AS pa ON pa.product_id = pd.product_id ';
                    $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'attribute_description AS ad ON (ad.attribute_id = pa.attribute_id AND pd.language_id = ad.language_id) '; 
                }
                
                if ($searchIn['location'] === true) { 
                    $extra_select .= 'p.location, '; 
                }
                
                if ($searchIn['model'] === true) { 
                    $extra_select .= ''; 
                }
                
                if ($searchIn['name'] === true) { 
                    $extra_select .= ''; 
                }
    
                if ($searchIn['optionname'] === true) { 
                    $extra_select .= 'ode.name AS optionname, '; 
                    $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'product_option AS popt ON popt.product_id = pd.product_id ';
                    $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'option_description AS ode ON (ode.option_id = popt.option_id AND pd.language_id = ode.language_id)';
                }
    
                if ($searchIn['optionvalue'] === true) { 
                    $extra_select .= 'ovde.name AS optionvalue, '; 
                    if ($searchIn['optionname'] !== true) {
                        $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'product_option AS popt ON popt.product_id = pd.product_id ';  
                    }
                    $extra_sql .= 'LEFT JOIN ' . DB_PREFIX . 'option_value_description AS ovde ON (ovde.option_id = popt.option_id AND pd.language_id = ovde.language_id)';
                }
    
                $extra_select .= 'p.image, '; 
                

                $sql_limit = ' '; //LIMIT 1000';
                
                if (defined('VERSION') && VERSION >= '1.5.4') {
                    if ($searchIn['tags'] === true) {
                        $extra_sql = str_replace('LEFT JOIN ' . DB_PREFIX . 'product_tag AS ptag ON (ptag.product_id = pd.product_id AND pd.language_id = ptag.language_id) ','',$extra_sql);
                        $extra_select = str_replace('ptag.tag AS tag, ','pd.tag AS tag, ',$extra_select);
                        $cond = str_replace('ptag.tag','pd.tag',$cond);
                    }   
                }
                
                $sql  = 'SELECT pd.product_id, pd.description AS description, AVG(r1.rating) AS rating, COUNT(r1.rating) AS reviews, p.model, p.stock_status_id, p.quantity, p.viewed, p.date_available, ss.name as stock_status, pd.name,'.$extra_select.' p.price, p.tax_class_id, pspe.price AS special, pspe.date_end AS ps_date_end, pspe.date_start AS ps_date_start, (SELECT price FROM ' . DB_PREFIX . 'product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = \'' . (int)$customer_group_id . '\' AND pd2.quantity = \'1\' AND ((pd2.date_start = \'0000-00-00\' OR pd2.date_start < NOW()) AND (pd2.date_end = \'0000-00-00\' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount FROM ' . DB_PREFIX . 'product_description AS pd ';
                $sql .= 'LEFT JOIN ' . DB_PREFIX . 'product AS p ON p.product_id = pd.product_id ';
                $sql .= 'LEFT JOIN ' . DB_PREFIX . 'review AS r1 ON r1.product_id = pd.product_id AND r1.status = 1 ';
                $sql .= 'LEFT JOIN ' . DB_PREFIX . 'product_to_store AS ptostore ON ptostore.product_id = pd.product_id ';
                $sql .= 'LEFT JOIN ' . DB_PREFIX . 'stock_status AS ss ON ss.stock_status_id = p.stock_status_id ';
                $sql .= 'LEFT JOIN ' . DB_PREFIX . 'product_special AS pspe ON (pspe.product_id = pd.product_id AND pspe.customer_group_id = "'.$customer_group_id.'" AND (pspe.date_start = "0000-00-00" OR pspe.date_start < NOW()) AND (pspe.date_end = "0000-00-00" OR pspe.date_end > NOW()) AND pspe.priority = (SELECT priority FROM ' . DB_PREFIX . 'product_special WHERE product_id = pd.product_id AND customer_group_id = "'.$customer_group_id.'" AND (date_start = "0000-00-00" OR date_start < NOW()) AND (date_end = "0000-00-00" OR date_end > NOW()) ORDER BY priority ASC, price ASC LIMIT 0,1) ) ';
                $sql .= $extra_sql;
                
                $sql .= 'WHERE ' . $cond . ' AND !(' . implode(' OR ', $cond_exclude) . ') ';
                $sql .= 'AND pd.language_id = ' . (int)$this->config->get('config_language_id');
                $sql .= ' AND p.date_available <= NOW() ';
                $sql .= ' AND ptostore.store_id =  ' . (int)$this->config->get('config_store_id'); 
                
                $sql .= ' GROUP BY pd.product_id';
                
                if (empty($sort)) {
                    $sql .= ' ORDER BY Length(pd.name), pd.name ASC';
                } else {
                    $sql .= ' ORDER BY ' . $sort . ' ' . $order;    
                }
                $sql .= $sql_limit;
                
                //{HOOK_SEARCH_IN_CACHE_ISEARCHSTANDARD}

                } else { //SEARCH IN CACHE
                    $sql = '';
                    $selectFields = array('isc.product_id as product_id', 'description', 'model', 'discount', 'name', 'image', 'price', 'tax_class_id', 'special', 'rating', 'reviews', 'name_length', 'seo_url', 'stock_status', 'stock_status_id', 'quantity', 'viewed', 'product_attributes', 'date_available');
                    
                    if (empty($sort)) {
                        $mysort = "";
                    } else {
                        switch ($sort) {
                            case 'pd.name' : $mysort = 'name ' . $order; break;
                            case 'p.price' : $mysort = 'price ' . $order; break;
                            case 'rating' : $mysort = 'rating ' . $order; break;
                            case 'p.model' : $mysort = 'model ' . $order; break;
                        }
                    }

                    if ($searchIn['description'] === true) {
                        $sql = $this->constructQuery($selectFields, $mysort, array('isc.search_data', 'isc.description'), $words, 5000, $category_id, $sub_category, $excludeRules, false, $strictSearch);
                        $category_sql = $this->constructQuery($selectFields, $mysort, array('isc.search_data', 'isc.description'), $words, $categoriesLimit, $category_id, $sub_category, $excludeRules, true, $strictSearch);
                    } else {
                        $sql = $this->constructQuery($selectFields, $mysort, array('isc.search_data'), $words, 5000, $category_id, $sub_category, $excludeRules, false, $strictSearch);
                        $category_sql = $this->constructQuery($selectFields, $mysort, array('isc.search_data'), $words, $categoriesLimit, $category_id, $sub_category, $excludeRules, true, $strictSearch);
                    }
                }
            
                
                $result = $this->db->query($sql);

                $this->filterByAttributes($result->rows, $words);
            
                
                //{HOOK_SEARCH_AGAIN_ISEARCHSTANDARD}

                $total_products_result = $this->db->query("SELECT FOUND_ROWS() as count");
                $total_products = $total_products_result->num_rows ? (int)$total_products_result->row['count'] : 0;

                $categories_result = array();
                $total_categories = 0;

                if ($showCategories && $searchInCache) {
                    $categories_result_query = $this->db->query($category_sql);
                    $categories_result = $categories_result_query->num_rows ? $categories_result_query->rows : array();
                    $total_categories_result = $this->db->query("SELECT FOUND_ROWS() as count");
                    $total_categories = $total_categories_result->num_rows ? (int)$total_categories_result->row['count'] : 0;
                }
            

                if($result) {
                    $products = (isset($result->rows)) ? $result->rows : $result->row;

                    foreach ($products as &$product) {
                        $product['price'] = !empty($product['discount']) ? $product['discount'] : $product['price'];
                    }
                }
            }
            
            if (empty($sort)) $products = $this->sortProducts($products,$keywords,$searchIn);


                if (empty($products['products'])) {
                    $products = array(
                        'products' => !empty($products) ? $products : array(),
                        'total_products' => !empty($total_products) ? $total_products : 0,
                        'categories' => !empty($categories_result) ? $categories_result : array(),
                        'total_categories' => !empty($total_categories) ? $total_categories : 0
                    );
                } else {
                    $products['total_products'] = !empty($total_products) ? $total_products : 0;
                    $products['categories'] = !empty($categories_result) ? $categories_result : array();
                    $products['total_categories'] = !empty($total_categories) ? $total_categories : 0;
                }

                foreach ($products['categories'] as &$category) {
                    $category['href'] = str_replace('&amp;', '&', $this->url->link('product/category', 'path=' . $category['category_id'], 'SSL'));
                }
            
            $this->cache->set('productstandard.' . (int)$this->config->get('config_language_id') . '.' . $this->session->data['currency'] . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache, $products);
            
        }
        if (empty($products['products'][0])) $products['products'] = array();
        return $products;
    }
    
    function iSearchFilter(&$products, $keywords, $searchIn, $strictSearch) {
        
        $keywords = trim($this->isearch_strtolower($keywords));
            
        if($this->isearch_strlen($keywords) >= 2) {
            $words = explode(' ', $keywords);
            
            if ($strictSearch === true) {
                $words = array($keywords); 
            }
                
            $searchMap = array();
            foreach ($searchIn as $searchField => $searchable) {
                if ($searchable) {
                    if ($searchField == 'metadescription') $searchMap[] = 'meta_description';
                    else if ($searchField == 'metakeyword') $searchMap[] = 'meta_keyword';
                    else if ($searchField == 'tags') $searchMap[] = 'tag';
                    else $searchMap[] = $searchField;
                }
            }
            
            foreach ($products as $index => $product) {
                
                $found = false;
                foreach ($searchMap as $field) {
                    foreach ($words as $word) {
                        if (!empty($product[$field]) && !empty($word)) {
                            //print_r($product['name'].' - '.$product[$field].' - '.$word.'<br>');
                            $ast = (in_array($field, array('sku', 'upc', 'ean', 'jan', 'isbn', 'mpn'))) ? '^' : $ast = '(.*)';
                            //$ast = '';
                            //print_r('/'.$ast.'('.$this->db->escape($word).')(.*)/mi'); 
                            if (preg_match('/'.$ast.'('.$this->db->escape($word).')(.*)/mi', $product[$field])) { $found = true; }
                        }
                    }
                }
                if (!$found) {
                    
                    unset($products[$index]);
                    
                }
            }
        }
        
    }
    
    function sortProducts($products,$keywords,$searchIn) {
        
        if ($searchIn['description'] === true || $searchIn['categories'] === true || $searchIn['attributes'] === true || $searchIn['optionname'] === true || $searchIn['optionvalue'] === true || $searchIn['tags'] === true || $searchIn['manufacturer'] === true || $searchIn['location'] === true) {
            $first = array_shift($products);
            
            array_unshift($products, $first);
            if (!empty($first)) {
                
                $words = explode(' ', $keywords);
                //print_r($words);
                if (!empty($words[0])) {
                    if ($this->isearch_strstr($first['name'],$words[0]) === false) {
                        $sortedProducts = array();
                        $otherProducts = array();
                        foreach ($products as $k => $p) {
                            
                            if ($this->isearch_strstr($this->isearch_strtolower($p['name']),$this->isearch_strtolower($words[0])) !== false) {
                                
                                array_push($sortedProducts,$p);
                            } else {
                                array_push($otherProducts,$p);  
                            }
                                
                        }
                        $sortedProducts = array_merge($sortedProducts, $otherProducts);
                        return $sortedProducts;
                    }
                }
            }
        }
        return $products;
    }
    
    public function addJoinsFromVersions(&$searchIn) {
        
        $return = '';
        if ($searchIn['attributes'] == true) $return .= 'LEFT JOIN product_attribute AS pa ON pa.product_id = pd.product_id LEFT JOIN attribute_description AS ad ON (ad.attribute_id = pa.attribute_id AND pd.language_id = ad.language_id) ';
        if ($searchIn['categories'] === true) {
            $return .= 'LEFT JOIN ' . DB_PREFIX . 'product_to_category AS p2c ON p2c.product_id = pd.product_id ';
            $return .= 'LEFT JOIN category_description AS cd ON (cd.category_id = p2c.category_id AND pd.language_id = cd.language_id) ';
        }
        
        if ($searchIn['optionname'] === true) {
            $return .= 'LEFT JOIN ' . DB_PREFIX . 'product_option AS popt ON popt.product_id = pd.product_id ';
            $return .= 'LEFT JOIN ' . DB_PREFIX . 'option_description AS ode ON (ode.option_id = popt.option_id AND pd.language_id = ode.language_id) ';
        }

        if ($searchIn['optionvalue'] === true) {
            if ($searchIn['optionname'] !== true) {
                $return .= 'LEFT JOIN ' . DB_PREFIX . 'product_option AS popt ON popt.product_id = pd.product_id '; 
            }
            $return .= 'LEFT JOIN ' . DB_PREFIX . 'option_value_description AS ovde ON (ovde.option_id = popt.option_id AND pd.language_id = ovde.language_id) ';
        }
        
        if ($searchIn['tags'] === true) $return .= 'LEFT JOIN ' . DB_PREFIX . 'product_tag AS ptag ON (ptag.product_id = pd.product_id AND pd.language_id = ptag.language_id) ';
        
        if (defined('VERSION') && VERSION >= '1.5.4') {
            if ($searchIn['tags'] === true) {
                $return = str_replace('LEFT JOIN ' . DB_PREFIX . 'product_tag AS ptag ON (ptag.product_id = pd.product_id AND pd.language_id = ptag.language_id) ','',$return);
            }   
        }
        
        return $return;
    }
    
    public function addSelectFromVersions(&$searchIn) {
        $return = '';
        if ($searchIn['description'] == true) $return .= ' pd.description AS description,';
        if ($searchIn['attributes'] == true) $return .= ' ad.name AS attribute,';
        if ($searchIn['categories'] === true) $return .= ' cd.name AS category,';
        if ($searchIn['optionvalue'] === true) $return .= ' ovde.name AS optionvalue,';
        if ($searchIn['optionname'] === true) $return .= ' ode.name AS optionname,';
        if ($searchIn['tags'] === true) $return .= ' ptag.tag AS tag,';
        
        if (defined('VERSION') && VERSION >= '1.5.4') {
            if ($searchIn['tags'] === true) {
                $return = str_replace('ptag.tag AS tag,','pd.tag AS tag,',$return);
            }
        }
        
        return $return;
    }
    
    public function addConditionsFromVersions(&$searchIn, $keywords, $strictSearch) {
        $return = '';
        
        $keywords = trim($this->isearch_strtolower($keywords));
        
        if($this->isearch_strlen($keywords) >= 2) {
            $words = explode(' ', $keywords);
            
            if ($strictSearch === true) {
                $words = array($keywords);
            }
            
            
            if (in_array(true, $searchIn)) {
                foreach ($words as $word) {
                    if ($searchIn['name'] === true) { $return .= ' AND (LOWER(pd.name) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                        else { $return .= ' AND (1=0'; }
                    if ($searchIn['model'] === true) { $return .= ' OR LOWER(p.model) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['sku'] === true) { $return .= ' OR LOWER(p.sku) LIKE "' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; } 
                    if ($searchIn['upc'] === true) { $return .= ' OR LOWER(p.upc) LIKE "' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['location'] === true) { $return .= ' OR LOWER(p.location) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['tags'] === true) { $return .= ' OR LOWER(ptag.tag) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['manufacturer'] === true) { $return .= ' OR LOWER(m.name) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['attributes'] === true) { $return .= ' OR LOWER(ad.name) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['categories'] === true) { $return .= ' OR LOWER(cd.name) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['description'] === true) { $return .= ' OR LOWER(pd.description) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['optionname'] === true) { $return .= ' OR LOWER(ode.name) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['optionvalue'] === true) { $return .= ' OR LOWER(ovde.name) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['metadescription'] === true) { $return .= ' OR LOWER(pd.meta_description) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    if ($searchIn['metakeyword'] === true) { $return .= ' OR LOWER(pd.meta_keyword) LIKE "%' . $this->db->escape($word) . '%" COLLATE utf8_unicode_ci'; }
                    $return .= ')';
                }
                $return = $this->isearch_substr($return, 4) . ' AND';
            }
            
            if (defined('VERSION') && VERSION >= '1.5.4') {
                if ($searchIn['tags'] === true) {
                    $return = str_replace('ptag.tag','pd.tag',$return);
                }
            }
            
        }
        return $return;
    }
    
    private function convertKeywords($keywords, $singularize, $spellCheck, $excludeTerms, $strictSearch) {
        if (function_exists('mb_convert_encoding')) $keywords = mb_convert_encoding($keywords, 'UTF-8');
        if (!empty($spellCheck)) {
            foreach ($spellCheck as $values) {
                if (!empty($values) && !empty($values['incorrect']) && !empty($values['correct'])) {
                    if ($values['incorrect'][0] == '/') {
                        $keywords = preg_replace($values['incorrect'], $values['correct'], $keywords);
                    } else {
                        $keywords = str_replace($values['incorrect'], $values['correct'], $keywords);   
                    }
                }
            }
        }
        
        if ($singularize && !$strictSearch) {
            $words = explode(' ', $keywords);
            foreach ($words as $i => $word) {
                $words[$i] = preg_replace('/(s|es)$/', '', $word);  
            }
            $keywords = implode(' ', $words);
        }
        
        if (!empty($excludeTerms)) {
            $terms = array_filter(explode("\n", $excludeTerms));

            foreach ($terms as $term) {
                $term = trim(trim($term), "\r");
                $keywords = explode(' ', $keywords);

                foreach ($keywords as $i => $v) {
                    if ($this->isearch_strtolower($v) == $this->isearch_strtolower($term)) {
                        unset($keywords[$i]);
                    }
                }

                $keywords = implode(' ', $keywords);
            }
        }

        $keywords = trim(preg_replace('/\s+/', ' ', $keywords));

        return $keywords;
    }
    
    private function fixParameters(&$sort, &$order, &$category_id, &$sub_category) {
        if (!in_array($sort, array('pd.name', 'p.price', 'rating', 'p.model'))) $sort = '';
        if (!in_array($order, array('ASC', 'DESC'))) $order = 'ASC';
        if (!is_numeric($category_id)) $category_id = 0;
        $sub_category = !empty($sub_category);
    }
    
    private function init_search_term_db() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "isearch_terms` (`id` int(11) NOT NULL AUTO_INCREMENT, `term` varchar(255) NOT NULL, `count` int(11) NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `term` (`term`), KEY `count` (`count`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");
    }

    public function logSearchTerm($term, $count) {
        $this->init_search_term_db();

        $settings = $this->config->get('iSearch');
        $limit = (int)$settings['SuggestionCount'];
        if (empty($limit)) return;

        $this->db->query("INSERT INTO " . DB_PREFIX . "isearch_terms SET term='" . $this->db->escape($term) . "', count = '" . (int)$count . "' ON DUPLICATE KEY UPDATE count = '" . $count . "'");

        $this->cache->delete('product');
        $this->cache->delete('productstandard');
    }

    public function getSuggestions($keywords) {
        $settings = $this->config->get('iSearch');
        $limit = (int)$settings['SuggestionCount'];

        if (empty($limit)) return 0;

        $this->init_search_term_db();

        $terms = array_filter(explode(' ', $keywords));

        $conditions = array();

        foreach ($terms as $term) {
            $conditions[] = "term LIKE '%" . $term . "%' COLLATE utf8_unicode_ci";
        }

        $results = $this->db->query("SELECT * FROM " . DB_PREFIX . "isearch_terms WHERE " . implode(' AND ', $conditions) . " ORDER BY count DESC LIMIT 0, " . $limit);

        $return = array();

        if (!empty($results->num_rows)) {
            if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
                $store_url = $this->config->get('config_url');
            } else {
                $store_url = $this->config->get('config_ssl');
            } 

            foreach ($results->rows as $result) {
                $return[] = array(
                    'url' => $store_url . 'index.php?route=product/isearch&search=' . urlencode($result['term']),
                    'term' => $result['term']
                );
            }
        }

        return $return;
    }

    /* Multibyte functions */
    
    public function isearch_strtolower($string) {
        return (function_exists('mb_strtolower')) ? mb_strtolower($string) : strtolower($string);
    }
    
    public function isearch_strlen($string) {
        return (function_exists('mb_strlen')) ? mb_strlen($string) : strlen($string);
    }
    
    public function isearch_substr($string, $start) {
        $arg = func_get_args();
        if (isset($arg[2])) return (function_exists('mb_substr')) ? mb_substr($string, $start, $arg[2]) : substr($string, $start, $arg[2]);
        else return (function_exists('mb_substr')) ? mb_substr($string, $start) : substr($string, $start);
    }
    
    public function isearch_strstr($string, $needle) {
        $arg = func_get_args();
        if (isset($arg[2])) return (function_exists('mb_strstr')) ? mb_strstr($string, $needle, $arg[2]) : strstr($string, $needle, $arg[2]);
        else return (function_exists('mb_strstr')) ? mb_strstr($string, $needle) : strstr($string, $needle);
    }

}
?>