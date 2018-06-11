<?php
class ModelModuleIsearchcorporate extends Model {
	
	public function inWeights($name) {
		$config = $this->config->get('isearch');
		if (!empty($config['CorporateCustomWeight'])) {
			foreach ($config['CorporateCustomWeight'] as $weight) {
				if ($name == $weight['field']) return true;
			}
		}
		return false;
	}
	
	public function generateCreateStatement() {
		$this->load->model('setting/store');
		$stores_results = $this->model_setting_store->getStores();
		$stores = array("'0'");
		if (!empty($stores_results)) {
			foreach ($stores_results as $store) {
				$stores[] = "'" . $store['store_id'] . "'";
			}
		}
		
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		
		$create_names = array();
		$create_descriptions = array();
		$create_search_datas = array();
		$create_name_lengths = array();
		
		foreach ($languages as $language) {
			$create_names[] = "`name_" . $language['language_id'] . "` varchar(255) NOT NULL";
			$create_category_names[] = "`category_name_" . $language['language_id'] . "` varchar(255) NOT NULL";
			$create_descriptions[] = "`description_" . $language['language_id'] . "` text NOT NULL";
			$create_search_datas[] = "`search_data_" . $language['language_id'] . "` mediumtext NOT NULL";
			$create_name_lengths[] = "`name_length_" . $language['language_id'] . "` int(11) NOT NULL";
			$create_stock_statuses[] = "`stock_status_" . $language['language_id'] . "` varchar(255) NOT NULL";
		}
		
		$config = $this->config->get('isearch');
		
		$searchIn = $this->getSearchIn();
		$weight_creates = array();
		$escape = array('name', 'description', 'model');
		
		$multilingual = array('attributes', 'attributes_values', 'categories', 'filters', 'tags', 'optionname', 'optionvalue', 'metadescription', 'metakeyword', 'stock_status');
		foreach ($searchIn as $name => $search) {
			if (!$search || in_array($name, $escape) || !$this->inWeights($name)) continue;
			
			$weight_creates[] = '`' . $name . '` text NOT NULL,';
			if (in_array($name, $multilingual)) {
				foreach ($languages as $language) {
					$weight_creates[] = '`' . $name . '_' . $language['language_id'] . '` text NOT NULL,';
				}
			}
		}
		
		$result = "
			`id` int(11) NOT NULL,
			`product_id` int(11) NOT NULL,
			`category_id` int(11) NOT NULL,
			" . implode('', $weight_creates) . "
			`stores` SET(" . implode(',', $stores) . ") NOT NULL,
			`name` text NOT NULL,
			" . implode(",", $create_names) . ",
			" . implode(",", $create_category_names) . ",
			`model` varchar(64) NOT NULL,
			`seo_url` varchar(255) NOT NULL,
			`description` text NOT NULL,
			" . implode(",", $create_descriptions) . ",
			" . implode(",", $create_stock_statuses) . ",
			`search_data` mediumtext NOT NULL,
			" . implode(",", $create_search_datas) . ",
			`product_attributes` mediumtext NOT NULL,
			`price` decimal(15,4) NOT NULL,
			`stock_status_id` int(11) NOT NULL,
			`special` text NOT NULL,
			`discount` text NOT NULL,
			`image` varchar(255) NOT NULL,
			`tax_class_id` int(11) NOT NULL,
			`rating` double NOT NULL,
			`date_available` DATE NOT NULL,
			`reviews` int(11) NOT NULL,
			" . implode(",", $create_name_lengths) . ",
			`in_stock` tinyint(1) NOT NULL,
			`sales_amount` int(11) NOT NULL,
			`orders_amount` int(11) NOT NULL,
			`viewed` int(11) NOT NULL,
			`status` tinyint(1) NOT NULL,
			`quantity` int(11) NOT NULL
		";
		
		return $result;
	}
	
	public function getSearchIn() {
		$config = $this->config->get('isearch');
		
		$set = $config;
		$searchIn = array(
			'name' => !empty($set['SearchIn']['ProductName']),
			'model' => !empty($set['SearchIn']['ProductModel']),
			'upc' => !empty($set['SearchIn']['UPC']),
			'sku' => !empty($set['SearchIn']['SKU']),
			'ean' => !empty($set['SearchIn']['EAN']),
			'jan' => !empty($set['SearchIn']['JAN']),
			'isbn' => !empty($set['SearchIn']['ISBN']),
			'mpn' => !empty($set['SearchIn']['MPN']),
			'manufacturer' => !empty($set['SearchIn']['Manufacturer']),
			'attributes' => !empty($set['SearchIn']['AttributeNames']),
			'attributes_values' => !empty($set['SearchIn']['AttributeValues']),
			'categories' => !empty($set['SearchIn']['Categories']),
			'filters' => !empty($set['SearchIn']['Filters']),
			'description' => !empty($set['SearchIn']['Description']),
			'tags' => !empty($set['SearchIn']['Tags']),
			'location' => !empty($set['SearchIn']['Location']),
			'optionname' => !empty($set['SearchIn']['OptionName']),
			'optionvalue' => !empty($set['SearchIn']['OptionValue']),
			'metadescription' => !empty($set['SearchIn']['MetaDescription']),
			'metakeyword' => !empty($set['SearchIn']['MetaKeyword'])
		);
		
		return $searchIn;
	}
	
	public function generateSetStatement() {
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		
		$config = $this->config->get('isearch');
		
		$tags_select = "(SELECT GROUP_CONCAT(tag SEPARATOR ' ') FROM `" . DB_PREFIX . "product_tag` WHERE product_id = isc.product_id GROUP BY product_id)";
			
		if (VERSION >= '1.5.4') $tags_select = "(SELECT GROUP_CONCAT(`tag` SEPARATOR ' ') FROM `" . DB_PREFIX . "product_description` WHERE product_id = isc.product_id GROUP BY product_id)";
		
		$searchIn = $this->getSearchIn();
		
		$selectMap = array(
			'name' => "(SELECT GROUP_CONCAT(name SEPARATOR ' ') FROM `" . DB_PREFIX . "product_description` WHERE product_id = isc.product_id GROUP BY product_id LIMIT 0,1)",
			'model' => "(SELECT `model` FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id LIMIT 0,1)",
			'upc' => "(SELECT `upc` FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id)",
			'sku' => "(SELECT `sku` FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id)",
			'ean' => "(SELECT `ean` FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id)",
			'jan' => "(SELECT `jan` FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id)",
			'isbn' => "(SELECT `isbn` FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id)",
			'mpn' => "(SELECT `mpn` FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id)",
			'manufacturer' => "(SELECT `name` FROM `" . DB_PREFIX . "manufacturer` WHERE manufacturer_id = (SELECT manufacturer_id FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id))",
			'attributes' => "(SELECT GROUP_CONCAT(ad.name SEPARATOR ' ') FROM `" . DB_PREFIX . "product_attribute` AS pa LEFT JOIN `" . DB_PREFIX . "attribute_description` AS ad ON (pa.attribute_id = ad.attribute_id) WHERE pa.product_id = isc.product_id GROUP BY pa.product_id)",
			'attributes_values' => "(SELECT GROUP_CONCAT(pa.text SEPARATOR ' ') FROM `" . DB_PREFIX . "product_attribute` AS pa WHERE pa.product_id = isc.product_id GROUP BY pa.product_id)",
			'categories' => "(SELECT GROUP_CONCAT(cd.name SEPARATOR ' ') FROM `" . DB_PREFIX . "product_to_category` AS ptocat LEFT JOIN `" . DB_PREFIX . "category_description` AS cd ON (ptocat.category_id = cd.category_id) WHERE ptocat.product_id = isc.product_id GROUP BY ptocat.product_id)",
			'filters' => "(SELECT GROUP_CONCAT(fd.name SEPARATOR ' ') FROM `" . DB_PREFIX . "product_filter` AS pf LEFT JOIN `" . DB_PREFIX . "filter_description` AS fd ON (pf.filter_id = fd.filter_id) WHERE pf.product_id = isc.product_id GROUP BY pf.product_id)",
			'tags' => $tags_select,
			'location' => "(SELECT `location` FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id)",
			'optionname' => "(SELECT GROUP_CONCAT(od.name SEPARATOR ' ') FROM `" . DB_PREFIX . "product_option` AS po LEFT JOIN `" . DB_PREFIX . "option_description` AS od ON (od.option_id = po.option_id) WHERE po.product_id = isc.product_id GROUP BY po.product_id)",
			'optionvalue' => "(SELECT GROUP_CONCAT(ovd.name SEPARATOR ' ') FROM `" . DB_PREFIX . "product_option_value` AS pov LEFT JOIN `" . DB_PREFIX . "option_value_description` AS ovd ON (pov.option_value_id = ovd.option_value_id) WHERE pov.product_id = isc.product_id GROUP BY pov.product_id)",
			'metadescription' => "(SELECT GROUP_CONCAT(`meta_description` SEPARATOR ' ') FROM `" . DB_PREFIX . "product_description` WHERE product_id = isc.product_id GROUP BY product_id)",
      'metakeyword' => "(SELECT GROUP_CONCAT(`meta_keyword` SEPARATOR ' ') FROM `" . DB_PREFIX . "product_description` WHERE product_id = isc.product_id GROUP BY product_id)"
		);
		
		$selectLanguageMap = array();
		foreach ($languages as $language) {
			$tags_select = "(SELECT GROUP_CONCAT(tag SEPARATOR ' ') FROM `" . DB_PREFIX . "product_tag` WHERE product_id = isc.product_id GROUP BY product_id)";
		
			if (VERSION >= '1.5.4') $tags_select = "(SELECT `tag` FROM `" . DB_PREFIX . "product_description` WHERE product_id = isc.product_id AND language_id='" . $language['language_id'] . "')";
		
			$selectLanguageMap[$language['language_id']] = array(
				'name' => "(SELECT `name` FROM `" . DB_PREFIX . "product_description` WHERE product_id = isc.product_id AND language_id='" . $language['language_id'] . "' LIMIT 0,1)",
				'model' => "(SELECT `model` FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id LIMIT 0,1)",
				'upc' => "(SELECT `upc` FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id)",
				'sku' => "(SELECT `sku` FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id)",
				'ean' => "(SELECT `ean` FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id)",
				'jan' => "(SELECT `jan` FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id)",
				'isbn' => "(SELECT `isbn` FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id)",
				'mpn' => "(SELECT `mpn` FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id)",
				'manufacturer' => "(SELECT `name` FROM `" . DB_PREFIX . "manufacturer` WHERE manufacturer_id = (SELECT manufacturer_id FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id))",
				'attributes' => "(SELECT GROUP_CONCAT(ad.name SEPARATOR ' ') FROM `" . DB_PREFIX . "product_attribute` AS pa LEFT JOIN `" . DB_PREFIX . "attribute_description` AS ad ON (pa.attribute_id = ad.attribute_id AND ad.language_id = " . $language['language_id'] . ") WHERE pa.product_id = isc.product_id GROUP BY pa.product_id)",
				'attributes_values' => "(SELECT GROUP_CONCAT(pa.text SEPARATOR ' ') FROM `" . DB_PREFIX . "product_attribute` AS pa WHERE pa.product_id = isc.product_id AND pa.language_id = " . $language['language_id'] . " GROUP BY pa.product_id)",
				'categories' => "(SELECT GROUP_CONCAT(cd.name SEPARATOR ' ') FROM `" . DB_PREFIX . "product_to_category` AS ptocat LEFT JOIN `" . DB_PREFIX . "category_description` AS cd ON (ptocat.category_id = cd.category_id AND cd.language_id = " . $language['language_id'] . ") WHERE ptocat.product_id = isc.product_id GROUP BY ptocat.product_id)",
				'filters' => "(SELECT GROUP_CONCAT(fd.name SEPARATOR ' ') FROM `" . DB_PREFIX . "product_filter` AS pf LEFT JOIN `" . DB_PREFIX . "filter_description` AS fd ON (pf.filter_id = fd.filter_id AND fd.language_id = " . $language['language_id'] . ") WHERE pf.product_id = isc.product_id GROUP BY pf.product_id)",
				'tags' => $tags_select,
				'location' => "(SELECT `location` FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id)",
				'optionname' => "(SELECT GROUP_CONCAT(od.name SEPARATOR ' ') FROM `" . DB_PREFIX . "product_option` AS po LEFT JOIN `" . DB_PREFIX . "option_description` AS od ON (od.option_id = po.option_id AND od.language_id = " . $language['language_id'] . ") WHERE po.product_id = isc.product_id GROUP BY po.product_id)",
				'optionvalue' => "(SELECT GROUP_CONCAT(ovd.name SEPARATOR ' ') FROM `" . DB_PREFIX . "product_option_value` AS pov LEFT JOIN `" . DB_PREFIX . "option_value_description` AS ovd ON (pov.option_value_id = ovd.option_value_id AND ovd.language_id = " . $language['language_id'] . ") WHERE pov.product_id = isc.product_id GROUP BY pov.product_id)",
				'metadescription' => "(SELECT `meta_description` FROM `" . DB_PREFIX . "product_description` WHERE product_id = isc.product_id AND language_id='" . $language['language_id'] . "')",
				'metakeyword' => "(SELECT `meta_keyword` FROM `" . DB_PREFIX . "product_description` WHERE product_id = isc.product_id AND language_id='" . $language['language_id'] . "')"
			);
		}
		
		$selectDescriptionMap = array();
		foreach ($languages as $language) {
			$selectDescriptionMap[$language['language_id']] = "(SELECT `description` FROM `" . DB_PREFIX . "product_description` WHERE product_id = isc.product_id AND language_id = '" . $language['language_id'] . "')";
		}
		
		$selectNameMap = array();
		foreach ($languages as $language) {
			$selectNameMap[$language['language_id']] = "(SELECT `name` FROM `" . DB_PREFIX . "product_description` WHERE product_id = isc.product_id AND language_id = '" . $language['language_id'] . "')";
		}

		$selectCategoryMap = array();
		foreach ($languages as $language) {
			$selectCategoryMap[$language['language_id']] = "(SELECT `name` FROM `" . DB_PREFIX . "category_description` WHERE category_id = isc.category_id AND language_id = '" . $language['language_id'] . "')";
		}
		
		foreach ($selectMap as $name => $value) {
			if ($searchIn[$name] == FALSE) unset($selectMap[$name]);
			foreach ($languages as $language) {
				if ($searchIn[$name] == FALSE) unset($selectLanguageMap[$language['language_id']][$name]);
			}
		}
		
		$searchDescriptions = array();
		foreach ($languages as $language) {
			$searchDescriptions[] = "isc.`description_" . $language['language_id'] . "` = " . $selectDescriptionMap[$language['language_id']] . "";	
		}
		
		$searchNames = array();
		$searchNameLengths = array();
		$searchStockStatuses = array();
		foreach ($languages as $language) {
			$searchStockStatuses[] = "isc.`stock_status_" . $language['language_id'] . "` = (SELECT ss.name FROM `" . DB_PREFIX . "stock_status ss LEFT JOIN `" . DB_PREFIX . "product` p ON (p.product_id = isc.product_id)` WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id='" . $language['language_id'] . "')";
			$searchNames[] = "isc.`name_" . $language['language_id'] . "` = " . $selectNameMap[$language['language_id']] . "";
			$searchNameLengths[] = "isc.`name_length_" . $language['language_id'] . "` = LENGTH(" . $selectNameMap[$language['language_id']] . ")";
		}

		$searchCategories = array();
		foreach ($languages as $language) {
			$searchCategories[] = "isc.`category_name_" . $language['language_id'] . "` = " . $selectCategoryMap[$language['language_id']] . "";
		}

		$searchSearchDatas = array();
		foreach ($languages as $language) {
			$searchSearchDatas[] = "isc.`search_data_" . $language['language_id'] . "` = (SELECT CONCAT_WS(' ', ".implode(", ", $selectLanguageMap[$language['language_id']])."))";	
		}
		
		$weightSets = array();
		
		$escape = array('name', 'description', 'model');
		$multilingual = array('attributes', 'attributes_values', 'categories', 'filters', 'tags', 'optionname', 'optionvalue', 'metadescription', 'metakeyword');
		foreach ($selectMap as $name => $select) {
			if (in_array($name, $escape) || !$this->inWeights($name)) continue;
			$weightSets[] = 'isc.`' . $name . '` = (' . $select . ')';
			if (in_array($name, $multilingual)) {
				foreach ($languages as $language) {
					$weightSets[] = 'isc.`' . $name . '_' . $language['language_id'] . '` = (' . $selectLanguageMap[$language['language_id']][$name] . ')';
				}
			}
		}
		
		$url_alias_query = "";
		$has_url_alias_language_id_query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "url_alias' AND COLUMN_NAME = 'language_id'");
		if ($has_url_alias_language_id_query->num_rows) {
			$alterLimit = 10000;

			$product_count_query = $this->db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product");
			if ((int)$product_count_query->row['count'] <= $alterLimit) {
				$url_alias_query = "SELECT GROUP_CONCAT(CONCAT_WS(':', ua.language_id, ua.keyword) ORDER BY ua.language_id ASC SEPARATOR '|') FROM `" . DB_PREFIX . "url_alias` AS ua WHERE ua.query = CONCAT('product_id=', isc.product_id) GROUP BY ua.query";
			} else {
				$url_alias_query = "''";
			}
		} else {
			$url_alias_query = "SELECT CONCAT_WS(':', '" . $this->config->get('config_language_id') . "', `keyword`) FROM `" . DB_PREFIX . "url_alias` WHERE query = CONCAT('product_id=', isc.product_id) LIMIT 0,1";
		}
		
		$selectSearchDataMap = array();
		
		foreach ($selectLanguageMap as $language_id => $languageMap) {
			$selectSearchDataMap = array_merge($selectSearchDataMap, array_values($languageMap));
		}

		$result = "
			isc.`stores` = (SELECT GROUP_CONCAT(store_id SEPARATOR ',') FROM `" . DB_PREFIX . "product_to_store` WHERE product_id = isc.product_id GROUP BY product_id),
			" . (!empty($weightSets) ? implode(",", $weightSets) . "," : "") . "
			isc.`name` = (SELECT CONCAT_WS(' ', ".implode(", ", $selectNameMap).")),
			" . implode(",", $searchNames) . ",
			" . implode(",", $searchCategories) . ",
			isc.`model` = (SELECT `model` FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id),
			isc.`seo_url` = (" . $url_alias_query . "),
			isc.`description` = (SELECT CONCAT_WS(' ', ".implode(", ", $selectDescriptionMap).")),
			" . implode(",", $searchDescriptions) . ",
			isc.`search_data` = (SELECT CONCAT_WS(' ', ".implode(", ", $selectSearchDataMap).")),
			" . implode(",", $searchSearchDatas) . ",
			isc.`product_attributes` = (SELECT GROUP_CONCAT(CONCAT_WS(':', ad.language_id, ad.name, pa.text) SEPARATOR '|') FROM `" . DB_PREFIX . "product_attribute` AS pa LEFT JOIN `" . DB_PREFIX . "attribute_description` AS ad ON (pa.attribute_id = ad.attribute_id AND pa.language_id = ad.language_id) WHERE pa.product_id = isc.product_id GROUP BY pa.product_id),
			isc.`price` = (SELECT price FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id),
			isc.`stock_status_id` = (SELECT stock_status_id FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id),
			isc.`special` = (SELECT GROUP_CONCAT(CONCAT_WS(':', pspe.customer_group_id, pspe.priority, pspe.price, pspe.date_start, pspe.date_end) ORDER BY pspe.priority ASC SEPARATOR '|') FROM `" . DB_PREFIX . "product_special` AS pspe WHERE pspe.product_id = isc.product_id GROUP BY pspe.product_id),
			isc.`discount` = (SELECT GROUP_CONCAT(CONCAT_WS(':', pdis.customer_group_id, pdis.quantity, pdis.priority, pdis.price, pdis.date_start, pdis.date_end) ORDER BY pdis.priority ASC SEPARATOR '|') FROM `" . DB_PREFIX . "product_discount` AS pdis WHERE pdis.product_id = isc.product_id GROUP BY pdis.product_id),
			isc.`image` = (SELECT image FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id),
			isc.`tax_class_id` = (SELECT tax_class_id FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id),
			isc.`rating` = (SELECT AVG(rating) FROM `" . DB_PREFIX . "review` WHERE product_id = isc.product_id AND status = 1),
			isc.`date_available` = (SELECT date_available FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id),
			isc.`reviews` = (SELECT COUNT(rating) FROM `" . DB_PREFIX . "review` WHERE product_id = isc.product_id AND status = 1),
			" . implode(",", $searchNameLengths) . ",
			isc.`in_stock` = IF((SELECT `quantity` FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id) > 0, 1, 0),
			isc.`sales_amount` = (SELECT COUNT(*) FROM `" . DB_PREFIX . "order_product` tempop LEFT JOIN `" . DB_PREFIX . "order` tempo ON (tempop.order_id = tempo.order_id) WHERE product_id = isc.product_id AND tempo.order_status_id = '" . $this->config->get('config_complete_status_id') . "'),
			isc.`orders_amount` = (SELECT COUNT(*) FROM `" . DB_PREFIX . "order_product` tempop LEFT JOIN `" . DB_PREFIX . "order` tempo ON (tempop.order_id = tempo.order_id) WHERE product_id = isc.product_id),
			isc.`viewed` = (SELECT `viewed` FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id),
			isc.`status` = (SELECT `status` FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id),
			isc.`quantity` = (SELECT `quantity` FROM `" . DB_PREFIX . "product` WHERE product_id = isc.product_id)
		";
		
		return $result;
	}
	
	public function doAlters() {
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		
		$config = $this->config->get('isearch');
	
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "isearch_cache` ADD FULLTEXT KEY `ft_search_data` (`search_data`)");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "isearch_cache` ADD FULLTEXT KEY `ft_description` (`description`)");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "isearch_cache` ADD INDEX `i_search_data` (`search_data` (" . $config['CorporateLikeIndexLength'] . "))");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "isearch_cache` ADD INDEX `i_description` (`description` (" . $config['CorporateLikeIndexLength'] . "))");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "isearch_cache` ADD INDEX `product_id` (`product_id`)");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "isearch_cache` ADD INDEX `category_id` (`category_id`)");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "isearch_cache` ADD INDEX `status` (`status`)");
		
		foreach ($languages as $language) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "isearch_cache` ADD FULLTEXT KEY `ft_search_data_" . $language['language_id'] . "` (`search_data_" . $language['language_id'] . "`)");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "isearch_cache` ADD FULLTEXT KEY `ft_description_" . $language['language_id'] . "` (`description_" . $language['language_id'] . "`)");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "isearch_cache` ADD INDEX `i_search_data_" . $language['language_id'] . "` (`search_data_" . $language['language_id'] . "` (" . $config['CorporateLikeIndexLength'] . "))");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "isearch_cache` ADD INDEX `i_description_" . $language['language_id'] . "` (`description_" . $language['language_id'] . "` (" . $config['CorporateLikeIndexLength'] . "))");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "isearch_cache` ADD INDEX `sort_" . $language['language_id'] . "` (`name_length_" . $language['language_id'] . "` , `name_" . $language['language_id'] . "`, `in_stock`, `sales_amount`, `orders_amount`, `viewed`, `status`, `quantity`)");
		}
		
		$searchIn = $this->getSearchIn();

		$escape = array('name', 'description', 'model', 'ean', 'jan', 'isbn', 'mpn', 'filters', 'location');
		$multilingual = array('attributes', 'attributes_values', 'categories', 'filters', 'tags', 'optionname', 'optionvalue', 'metadescription', 'metakeyword');
		
		foreach ($searchIn as $name => $search) {
			if (!$search || in_array($name, $escape) || !$this->inWeights($name)) continue;
			
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "isearch_cache` ADD FULLTEXT KEY `ft_" . $name . "` (`" . $name . "`)");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "isearch_cache` ADD INDEX `i_" . $name . "` (`" . $name . "` (" . $config['CorporateLikeIndexLength'] . "))");
			
			if (in_array($name, $multilingual)) {
				foreach ($languages as $language) {
					$this->db->query("ALTER TABLE `" . DB_PREFIX . "isearch_cache` ADD FULLTEXT KEY `ft_" . $name . "_" . $language['language_id'] . "` (`" . $name . "_" . $language['language_id'] . "`)");
					$this->db->query("ALTER TABLE `" . DB_PREFIX . "isearch_cache` ADD INDEX `i_" . $name . "_" . $language['language_id'] . "` (`" . $name . "_" . $language['language_id'] . "` (" . $config['CorporateLikeIndexLength'] . "))");
				}
			}
		}
	}

	public function refreshprogress() {
		global $argc;
		global $argv;
		
		$inline_mode = !empty($argc) && $argc === 1;
		
		if (!$inline_mode && !$this->user->hasPermission('modify', 'module/isearch')) {
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$status = array(
			'all' => 0,
			'current' => 0,
			'percent' => 0,
			'error' => 'false',
			'complete' => 'false'
		);
		
		$this->buildCache();
		
		$data = !empty($this->session->data['iSearchCache']) ? $this->session->data['iSearchCache'] : array();
		
		if (!empty($data['all']) && !empty($data['current']) && $data['error'] == 'false') {
			$status['error'] = false;
			$status['percent'] = round($data['current']*100/$data['all']);
			if ($data['current'] >= $data['all']) {
				$data['current'] = $data['all'];
			}
			$status['current'] = $data['current'];
			
			if ($data['all'] == $data['current']) {
				$status['complete'] = 'true';
				$this->cleanSystemCache();
				unset($this->session->data['iSearchCache']);
			} else {
				$status['complete'] = 'false';
			}
			
			$status['all'] = $data['all'];
		}
		
		if ($data['error'] != 'false') {
			$status['error'] = $data['error'];
			$status['complete'] = 'true';
			$this->cleanSystemCache();
			unset($this->session->data['iSearchCache']);
		}
		
		$response = json_encode($status);
		
		if ($inline_mode) {
			$log = new Log('isearch_corporate.txt');
			$log->write($response);

            if ($status['complete'] != 'true') {
                $this->refreshprogress();
            }
		} else {
			echo $response; exit;
		}
	}

	private function cleanSystemCache() {
		$this->cache->delete('*');
	}

	private function buildCache() {
		set_error_handler(
			create_function(
				'$severity, $message, $file, $line',
				'throw new Exception($message . " in file " . $file . " on line " . $line);'
			)
		);
		
		$this->session->data['iSearchCache']['error'] = 'false';
		
		try {
			$this->db->query("SET SESSION group_concat_max_len = 1000000;");
			
			$this->load->model('module/isearchcorporate');
			
			$config = $this->config->get('isearch');
			
			if (empty($this->session->data['iSearchCache']['current'])) {
				ini_set('max_execution_time', 450);
				
				$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "isearch_cache`;");
				$initTableSQL = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "isearch_cache` (
				  " . $this->generateCreateStatement() . "
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;
				";
				
				$this->db->query($initTableSQL);
				
				$index = $this->db->query("SHOW INDEX FROM `" . DB_PREFIX . "product_option` WHERE KEY_NAME = 'isearchcorp_product_id'");
				if ($index->num_rows == 0) $this->db->query("ALTER TABLE  `" . DB_PREFIX . "product_option` ADD INDEX  `isearchcorp_product_id` (  `product_id` )");
				
				$index = $this->db->query("SHOW INDEX FROM `" . DB_PREFIX . "product_option_value` WHERE KEY_NAME = 'isearchcorp_product_id'");
				if ($index->num_rows == 0) $this->db->query("ALTER TABLE  `" . DB_PREFIX . "product_option_value` ADD INDEX  `isearchcorp_product_id` (  `product_id` )");
			}
			
			if (empty($this->session->data['iSearchCache']['current'])) {
				//prepare cache
				
				$this->db->query("INSERT INTO `" . DB_PREFIX . "isearch_cache` (`id`, `product_id`, `category_id`) SELECT DISTINCT
  						@curRow := @curRow + 1 AS id,
					p.product_id AS product_id,
					p2c.category_id AS category_id
				FROM `" . DB_PREFIX . "product_to_category` AS p2c
				LEFT JOIN `" . DB_PREFIX . "product` AS p ON (p.product_id = p2c.product_id)
					JOIN (SELECT @curRow := 0) r");
					
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "isearch_cache` ADD PRIMARY KEY `id` (`id`)");
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "isearch_cache` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;");
			}
			
			//populate cache
			$step = 500;
			$alterLimit = 10000;
			$max = $this->db->query("SELECT COUNT(*) as count FROM `" . DB_PREFIX . "isearch_cache`");
			$max = $max->row['count'];
			
			$this->session->data['iSearchCache']['all'] = $max;
			
			if (empty($this->session->data['iSearchCache']['current']) && $max > $alterLimit) {
				$this->doAlters();
			}
			
			$offset = !empty($this->session->data['iSearchCache']['current']) ? $this->session->data['iSearchCache']['current'] : 0;
			$this->session->data['iSearchCache']['current'] = $offset;
			
			if ($this->session->data['iSearchCache']['current'] < $max) {
				
				$this->db->query("SET SESSION query_cache_type=0;");
				
				$result = $this->db->query("
				UPDATE `" . DB_PREFIX . "isearch_cache` AS isc
					
				SET
					" . $this->generateSetStatement() . "
				WHERE isc.id >'".$offset."' AND isc.id <='".($offset+$step)."'");
				
				if (!empty($config['CorporatePreresize'])) {
					$image_width = !empty($config['InstantResultsImageWidth']) ? (int)$config['InstantResultsImageWidth'] : 80;
					$image_height = !empty($config['InstantResultsImageHeight']) ? (int)$config['InstantResultsImageHeight'] : 80;
					$images_result = $this->db->query("SELECT isc.image FROM " . DB_PREFIX . "isearch_cache AS isc WHERE isc.id >'".$offset."' AND isc.id <='".($offset+$step)."'");
					$this->load->model('tool/image');
					if ($images_result->num_rows) {
						foreach ($images_result->rows as $row) {
							try {
								$image_file = DIR_IMAGE . $row['image'];
								if (file_exists($image_file) && filesize($image_file) > 10) {
									$image_info = getimagesize($image_file);
									if (in_array($image_info['mime'], array('image/gif', 'image/png', 'image/jpeg'))) {
										$this->model_tool_image->resize($row['image'], $image_width, $image_height);
									}
								}
							} catch(Exception $e) {
								// do nothing.    
							}
						}
					}
				}
				
				$offset += $step;
				
				$this->session->data['iSearchCache']['current'] = $offset;
			}
			
			if ($this->session->data['iSearchCache']['current'] >= $max) {
				$this->session->data['iSearchCache']['current'] = $max;
				
				if ($max <= $alterLimit) {
					$this->doAlters();
				}
				
				$index = $this->db->query("SHOW INDEX FROM `" . DB_PREFIX . "product_option` WHERE KEY_NAME = 'isearchcorp_product_id'");
				if ($index->num_rows > 0) $this->db->query("ALTER TABLE  `" . DB_PREFIX . "product_option` DROP INDEX  `isearchcorp_product_id`");
				
				$index = $this->db->query("SHOW INDEX FROM `" . DB_PREFIX . "product_option_value` WHERE KEY_NAME = 'isearchcorp_product_id'");
				if ($index->num_rows > 0) $this->db->query("ALTER TABLE  `" . DB_PREFIX . "product_option_value` DROP INDEX  `isearchcorp_product_id`");
				
				$index = $this->db->query("SHOW INDEX FROM `" . DB_PREFIX . "tax_rule` WHERE KEY_NAME = 'isearchcorp_based'");
				if ($index->num_rows == 0) $this->db->query('ALTER TABLE `' . DB_PREFIX . 'tax_rule` ADD INDEX `isearchcorp_based` (`based`);');
				
				$index = $this->db->query("SHOW INDEX FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE KEY_NAME = 'isearchcorp_country_id'");
				if ($index->num_rows == 0) $this->db->query('ALTER TABLE `' . DB_PREFIX . 'zone_to_geo_zone` ADD INDEX `isearchcorp_country_id` (`country_id`);');
			}
			
		} catch (Exception $e) {
			$this->session->data['iSearchCache']['error'] = $e->getMessage();
		}
		
		restore_error_handler();
		return true;
	}
}
?>