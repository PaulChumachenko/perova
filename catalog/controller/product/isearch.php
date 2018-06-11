<?php 
class ControllerProductISearch extends Controller { 	
	public function index() { 
		$isearch_settings = $this->getModuleSettings();
	
    $this->load->language('product/search');
		
		$this->load->model('catalog/category');
		
		$this->load->model('catalog/product');

		$this->load->model('catalog/isearch');

		$this->load->model('tool/image'); 

		if (isset($this->request->get['search'])) {
			$search = $this->request->get['search'];
		} else {
			$search = '';
		} 
		
		if (isset($this->request->get['tag'])) {
			$tag = $this->request->get['tag'];
		} elseif (isset($this->request->get['search'])) {
			$tag = $this->request->get['search'];
		} else {
			$tag = '';
		} 
				
		if (isset($this->request->get['description'])) {
			$description = $this->request->get['description'];
		} else {
			$description = '';
		} 
				
		if (isset($this->request->get['category_id'])) {
			$category_id = $this->request->get['category_id'];
		} else {
			$category_id = 0;
		} 
		
		if (isset($this->request->get['sub_category'])) {
			$sub_category = $this->request->get['sub_category'];
		} else {
			$sub_category = '';
		} 
								
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		} 

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
  		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
				
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_product_limit');
		}
		
		if (isset($this->request->get['search'])) {
			$this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->request->get['search']);
		} elseif (isset($this->request->get['tag'])) {
			$this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->language->get('heading_tag') . $this->request->get['tag']);
		} else {
			$this->document->setTitle($this->language->get('heading_title'));
		}
		
		$data['breadcrumbs'] = array();

   	$data['breadcrumbs'][] = array( 
      'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home')
   	);
		
		$url = '';
		
		if (isset($this->request->get['search'])) {
			$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['tag'])) {
			$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
		}
				
		if (isset($this->request->get['description'])) {
			$url .= '&description=' . $this->request->get['description'];
		}
				
		if (isset($this->request->get['category_id'])) {
			$url .= '&category_id=' . $this->request->get['category_id'];
		}
		
		if (isset($this->request->get['sub_category'])) {
			$url .= '&sub_category=' . $this->request->get['sub_category'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}	

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}	
		
		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}
						
 		$data['breadcrumbs'][] = array(
     		'text'  => $this->language->get('heading_title'),
				'href'  => $this->url->link('product/isearch', $url)
 		);
		
		if (isset($this->request->get['search'])) {
    	$data['heading_title'] = $this->language->get('heading_title') .  ' - ' . $this->request->get['search'];
		} else {
			$data['heading_title'] = $this->language->get('heading_title');
		}
		
		$data['text_empty'] = $this->language->get('text_empty');
  	$data['text_critea'] = $this->language->get('text_critea');
  	$data['text_search'] = $this->language->get('text_search');
		$data['text_keyword'] = $this->language->get('text_keyword');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_sub_category'] = $this->language->get('text_sub_category');
		$data['text_quantity'] = $this->language->get('text_quantity');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_model'] = $this->language->get('text_model');
		$data['text_price'] = $this->language->get('text_price');
		$data['text_tax'] = $this->language->get('text_tax');
		$data['text_points'] = $this->language->get('text_points');
		$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
		$data['text_display'] = $this->language->get('text_display');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_grid'] = $this->language->get('text_grid');		
		$data['text_sort'] = $this->language->get('text_sort');
		$data['text_limit'] = $this->language->get('text_limit');
		
		$data['entry_search'] = $this->language->get('entry_search');
    $data['entry_description'] = $this->language->get('entry_description');
		  
    $data['button_search'] = $this->language->get('button_search');
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$data['button_list'] = $this->language->get('button_list');
		$data['button_grid'] = $this->language->get('button_grid');

		$data['compare'] = $this->url->link('product/compare');

		$this->load->model('catalog/category');
		// 3 Level Category Search
		$data['categories'] = array();
					
		$categories_1 = $this->model_catalog_category->getCategories(0);
		
		foreach ($categories_1 as $category_1) {
			$level_2_data = array();
			
			$categories_2 = $this->model_catalog_category->getCategories($category_1['category_id']);
			
			foreach ($categories_2 as $category_2) {
				$level_3_data = array();
				
				$categories_3 = $this->model_catalog_category->getCategories($category_2['category_id']);
				
				foreach ($categories_3 as $category_3) {
					$level_3_data[] = array(
						'category_id' => $category_3['category_id'],
						'name'        => $category_3['name'],
					);
				}
				
				$level_2_data[] = array(
					'category_id' => $category_2['category_id'],	
					'name'        => $category_2['name'],
					'children'    => $level_3_data
				);					
			}
			
			$data['categories'][] = array(
				'category_id' => $category_1['category_id'],
				'name'        => $category_1['name'],
				'children'    => $level_2_data
			);
		}
		
		$data['products'] = array();
		
		if (isset($this->request->get['search'])) {

			$searchIn = array(
				'name' => !empty($isearch_settings['SearchIn']['ProductName']),
				'model' => !empty($isearch_settings['SearchIn']['ProductModel']),
				'upc' => !empty($isearch_settings['SearchIn']['UPC']),
				'sku' => !empty($isearch_settings['SearchIn']['SKU']),
				'ean' => !empty($isearch_settings['SearchIn']['EAN']),
				'jan' => !empty($isearch_settings['SearchIn']['JAN']),
				'isbn' => !empty($isearch_settings['SearchIn']['ISBN']),
				'mpn' => !empty($isearch_settings['SearchIn']['MPN']),
				'manufacturer' => !empty($isearch_settings['SearchIn']['Manufacturer']),
				'attributes' => !empty($isearch_settings['SearchIn']['AttributeNames']),
				'attributes_values' => !empty($isearch_settings['SearchIn']['AttributeValues']),
				'categories' => !empty($isearch_settings['SearchIn']['Categories']),
				'filters' => !empty($isearch_settings['SearchIn']['Filters']),
				'description' => /*!empty($isearch_settings['SearchIn']['Description']) || */!empty($description),
				'tags' => !empty($isearch_settings['SearchIn']['Tags']),
				'location' => !empty($isearch_settings['SearchIn']['Location']),
				'optionname' => !empty($isearch_settings['SearchIn']['OptionName']),
				'optionvalue' => !empty($isearch_settings['SearchIn']['OptionValue']),
				'metadescription' => !empty($isearch_settings['SearchIn']['MetaDescription']),
				'metakeyword' => !empty($isearch_settings['SearchIn']['MetaKeyword']),			
			);
			
			$strictSearch = ($isearch_settings['UseStrictSearch'] == 'yes') ? true: false;
			$singularize = ($isearch_settings['UseSingularize']=='yes') ? true : false;
			
			$spellCheckIndex = !empty($isearch_settings['ResultsSpellCheckSystem']) ? $isearch_settings['ResultsSpellCheckSystem'] : 'no';
			$checkRules = $spellCheckIndex == 'yes' ? (!empty($isearch_settings['SCWords']) ? $isearch_settings['SCWords'] : array()) : NULL;
			$excludeTerms = !empty($isearch_settings['ExcludeTerms']) ? $isearch_settings['ExcludeTerms'] : '';
			$excludeRules = !empty($isearch_settings['ExcludeProducts']) ? $isearch_settings['ExcludeProducts'] : array();
			
			//{HOOK_SEARCH_IN_CACHE_SETTING}
			$start = ($page - 1) * $limit;
			$results = $this->model_catalog_isearch->iSearchStandard($search,$searchIn,$strictSearch,$singularize,$checkRules,$sort,$order,$category_id,$sub_category,$excludeTerms,$excludeRules/*{HOOK_SEARCH_IN_CACHE_ATTRIBUTE}*/);
		
			$product_total = count($results);
		
			// Start and Limit
			$i = 0;
			$pageProducts = array();
			foreach ($results as $prod) {
				if ($i >= $start && $i < ($start+$limit)) {
					$pageProducts[] = $prod;
				}
				$i++;
			}

			$results = $pageProducts;
				
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				}
				
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
				
				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}	
				
				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}				
				
				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
			
				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => $this->model_catalog_isearch->isearch_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '...',
					'price'       => $price,
					'model'				=> $result['model'],
					'quantity'		=> $result['quantity'],
					'stock_status_id' => $result['stock_status_id'],
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $rating,
					'reviews'     => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'        => $this->url->link('product/product', $url . '&product_id=' . $result['product_id'], 'SSL')
				);
			}
					
			$url = '';
			
			if (isset($this->request->get['search'])) {
				$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
			}
					
			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}
			
			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}
			
			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}
					
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
						
			$data['sorts'] = array();
			
			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('product/isearch', 'sort=p.sort_order&order=ASC' . $url)
			);
			
			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/isearch', 'sort=pd.name&order=ASC' . $url)
			); 
	
			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/isearch', 'sort=pd.name&order=DESC' . $url)
			);
	
			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/isearch', 'sort=p.price&order=ASC' . $url)
			); 
	
			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/isearch', 'sort=p.price&order=DESC' . $url)
			); 
			
			if ($this->config->get('config_review_status')) {
				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('product/isearch', 'sort=rating&order=DESC' . $url)
				); 
				
				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('product/isearch', 'sort=rating&order=ASC' . $url)
				);
			}
			
			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('product/isearch', 'sort=p.model&order=ASC' . $url)
			); 
	
			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $this->url->link('product/isearch', 'sort=p.model&order=DESC' . $url)
			);
	
			$url = '';
			
			if (isset($this->request->get['search'])) {
				$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
			}
					
			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}
			
			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}
			
			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}
						
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	
	
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$data['limits'] = array();
			
			$data['limits'][] = array(
				'text'  => $this->config->get('config_product_limit'),
				'value' => $this->config->get('config_product_limit'),
				'href'  => $this->url->link('product/isearch', $url . '&limit=' . $this->config->get('config_product_limit'))
			);
						
			$data['limits'][] = array(
				'text'  => 25,
				'value' => 25,
				'href'  => $this->url->link('product/isearch', $url . '&limit=25')
			);
			
			$data['limits'][] = array(
				'text'  => 50,
				'value' => 50,
				'href'  => $this->url->link('product/isearch', $url . '&limit=50')
			);
	
			$data['limits'][] = array(
				'text'  => 75,
				'value' => 75,
				'href'  => $this->url->link('product/isearch', $url . '&limit=75')
			);
			
			$data['limits'][] = array(
				'text'  => 100,
				'value' => 100,
				'href'  => $this->url->link('product/isearch', $url . '&limit=100')
			);
					
			$url = '';
	
			if (isset($this->request->get['search'])) {
				$url .= '&search=' . urlencode(html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . urlencode(html_entity_decode($this->request->get['filter_tag'], ENT_QUOTES, 'UTF-8'));
			}
					
			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}
			
			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}
			
			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}
										
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	
	
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
			
            $search_term = trim(!empty($this->request->get['search']) ? $this->request->get['search'] : '');
            if ($product_total > 0 && !empty($search_term)) {
                $this->model_catalog_isearch->logSearchTerm($search_term, $product_total);
            }

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('product/isearch', $url . '&page={page}');
			
			$data['pagination'] = $pagination->render();

			$this->document->addLink($this->url->link('product/isearch', $url . '&page=' . $pagination->page), 'canonical');

			if ($pagination->limit && ceil($pagination->total / $pagination->limit) > $pagination->page) {
				$this->document->addLink($this->url->link('product/isearch', $url . '&page=' . ($pagination->page + 1)), 'next');
			}

			if ($pagination->page > 1) {
				$this->document->addLink($this->url->link('product/isearch', $url . '&page=' . ($pagination->page - 1)), 'prev');
			}

			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
		}	
		
		$data['search'] = $search;
		$data['description'] = $description;
		$data['category_id'] = $category_id;
		$data['sub_category'] = $sub_category;
				
		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['limit'] = $limit;
	
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/isearch2.tpl')) {
			$template = $this->config->get('config_template') . '/template/product/isearch2.tpl';
		} else {
			$template = 'default/template/product/isearch2.tpl';
		}
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$response = $this->load->view($template, $data);

		$this->response->setOutput(str_replace('product/search', 'product/isearch', $response));
  }
	
	function getModuleSettings() {
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			return str_replace('http', 'https', $this->config->get('iSearch'));
		} else {
			return $this->config->get('iSearch');
		}
	}
}
?>