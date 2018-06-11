<?php
class ControllerCommonHeader extends Controller {
	public function index() {
		$data['title'] = $this->document->getTitle();

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');
		$data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$data['icon'] = $server . 'image/' . $this->config->get('config_icon');
		} else {
			$data['icon'] = '';
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$this->load->language('common/header');

				$this->load->language('coloring/coloring');
				$this->load->model('setting/setting');

				$data['header_email_text'] = $this->language->get('header_email_text');
				$data['header_phone_text'] = $this->language->get('header_phone_text');	
				$data['header_address_text'] = $this->language->get('header_address_text');	
				
				$data['header_product_compare'] = sprintf($this->language->get('coloring_product_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));		

				$data['header_product_compare_link'] = $this->url->link('product/compare');
				
				$data['language_id'] = $this->config->get('config_language_id');
				$language_id = $this->config->get('config_language_id');

				$xds_coloring = array();
				$xds_coloring = $this->model_setting_setting->getSetting('xds_coloring_theme');
				$data['stylesheet'] = $xds_coloring;
				
				$data['header_menu'] = array();
				
				$data['header_menu_toggle'] = false;
				
				if (isset($xds_coloring['xds_coloring_theme_main_menu_toggle'])) {
					$data['header_menu_toggle'] = $xds_coloring['xds_coloring_theme_main_menu_toggle'];
				}
				
				if (isset($xds_coloring['xds_coloring_theme_main_menu_item'])) {
					$data['header_menu'] = $xds_coloring['xds_coloring_theme_main_menu_item'];
				}
				
				if (!empty($data['header_menu'])){
					foreach ($data['header_menu'] as $key => $value) {
						$sort_heder_menu[$key] = $value['sort'];
					} 
					array_multisort($sort_heder_menu, SORT_ASC, $data['header_menu']);
				}

				$data['help_menu_text'] = $this->language->get('header_help_support');
				
				if (isset($xds_coloring['xds_coloring_theme_help_menu_text'])) {
					if ($xds_coloring['xds_coloring_theme_help_menu_text'][$language_id] != '') {
						$data['help_menu_text'] = $xds_coloring['xds_coloring_theme_help_menu_text'][$language_id];
					}
				}
				
				$data['help_menu'] = array();
				$data['help_menu_toggle'] = false;
				$data['help_menu_left'] = '';
				
				if (isset($xds_coloring['xds_coloring_theme_help_menu_toggle'])) {
					$data['help_menu_toggle'] = $xds_coloring['xds_coloring_theme_help_menu_toggle'];
				}
				if (isset($xds_coloring['xds_coloring_theme_help_menu_left'])& $xds_coloring['xds_coloring_theme_help_menu_left']) {
					$data['help_menu_left'] = 'pull-left';
				}
				
				if (isset($xds_coloring['xds_coloring_theme_help_menu_item'])) {
					$data['help_menu'] = $xds_coloring['xds_coloring_theme_help_menu_item'];
				}
				
				if (!empty($data['help_menu'])){
					foreach ($data['help_menu'] as $key => $value) {
						$sort_help_menu[$key] = $value['sort'];
					} 
					array_multisort($sort_help_menu, SORT_ASC, $data['help_menu']);
				}
				
				
				$data['category_mask'] = '';
				
				if (isset($xds_coloring['xds_coloring_theme_category_mask_toggle'])) {
					$data['category_mask'] = $xds_coloring['xds_coloring_theme_category_mask_toggle'];
				}
				
				$data['add_category_menu'] = array();
				$data['add_category_menu_toggle'] = false;
				
				if (isset($xds_coloring['xds_coloring_theme_add_cat_links_toggle'])) {
					$data['add_category_menu_toggle'] = $xds_coloring['xds_coloring_theme_add_cat_links_toggle'];
				}
				
				if (isset($xds_coloring['xds_coloring_theme_add_cat_links_item'])) {
					$data['add_category_menu'] = $xds_coloring['xds_coloring_theme_add_cat_links_item'];
				}
				
				if (!empty($data['add_category_menu'])){
					foreach ($data['add_category_menu'] as $key => $value) {
						$sort_add_category_menu[$key] = $value['sort'];
					} 
					array_multisort($sort_add_category_menu, SORT_ASC, $data['add_category_menu']);
				}
				
				$data['header_contacts_toggle'] = false;
				
				if (isset($xds_coloring['xds_coloring_theme_contact_main_toggle'])) {
					$data['header_contacts_toggle'] = $xds_coloring['xds_coloring_theme_contact_main_toggle'];
				}
				
				$data['header_add_contacts_toggle'] = false;
				
				if (isset($xds_coloring['xds_coloring_theme_contact_add_toggle'])) {
					$data['header_add_contacts_toggle'] = $xds_coloring['xds_coloring_theme_contact_add_toggle'];
				}

				$data['main_telephone'] = "";
				
				if (isset($xds_coloring['xds_coloring_theme_contact_main_phone'])) {
					$data['main_telephone'] = $xds_coloring['xds_coloring_theme_contact_main_phone'];
				}
				
				$data['contact_hint'] = "";
				
				if (isset($xds_coloring['xds_coloring_theme_contact_hint'])) {
					$data['contact_hint'] = $xds_coloring['xds_coloring_theme_contact_hint'];
				}
				
				$data['contact_schedule'] = "";
				
				if (isset($xds_coloring['xds_coloring_theme_schedule'])) {
					$data['contact_schedule'] = $xds_coloring['xds_coloring_theme_schedule'];
				}
				
				$data['contact_email'] = "";
				
				if (isset($xds_coloring['xds_coloring_theme_contact_email'])) {
					$data['contact_email'] = $xds_coloring['xds_coloring_theme_contact_email'];
				}

				$data['all_phones'] = array();
				
				if (isset($xds_coloring['xds_coloring_theme_header_contact'])) {
					$data['all_phones'] = $xds_coloring['xds_coloring_theme_header_contact'];
				}
				
				if (!empty($data['all_phones'])){
					foreach ($data['all_phones'] as $key => $value) {
						$sort_all_phones[$key] = $value['sort'];
					} 
					array_multisort($sort_all_phones, SORT_ASC, $data['all_phones']);
				}
				
				$data['other_contacts'] = array();
				
				if (isset($xds_coloring['xds_coloring_theme_additional_contact'])) {
					$data['other_contacts'] = $xds_coloring['xds_coloring_theme_additional_contact'];
				}
				
				if (!empty($data['other_contacts'])){
					foreach ($data['other_contacts'] as $key => $value) {
						$sort_other_contacts[$key] = $value['sort'];
					} 
					array_multisort($sort_other_contacts, SORT_ASC, $data['other_contacts']);
				}
				
				$data['user_name'] = $this->customer->getFirstName();

      

		$data['text_home'] = $this->language->get('text_home');
		$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));

				$data['text_wishlist'] = sprintf($this->language->get('coloring_text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
      
		$data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));

		$data['text_account'] = $this->language->get('text_account');
		$data['text_register'] = $this->language->get('text_register');
		$data['text_login'] = $this->language->get('text_login');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_logout'] = $this->language->get('text_logout');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_all'] = $this->language->get('text_all');

		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['register'] = $this->url->link('account/register', '', 'SSL');
		$data['login'] = $this->url->link('account/login', '', 'SSL');
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
		$data['download'] = $this->url->link('account/download', '', 'SSL');
		$data['logout'] = $this->url->link('account/logout', '', 'SSL');
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');

		$status = true;

		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$robots = explode("\n", str_replace(array("\r\n", "\r"), "\n", trim($this->config->get('config_robots'))));

			foreach ($robots as $robot) {
				if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
					$status = false;

					break;
				}
			}
		}

		// Menu
		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {

				$children2_data = array();
				$children2 = $this->model_catalog_category->getCategories($child['category_id']);
				foreach ($children2 as $child2) {
					$filter2_data = array(
						'filter_category_id'  => $child2['category_id'],
						'filter_sub_category' => true
					);
					$children2_data[] = array(
						'name'  => $child2['name'] . ($this->config->get('config_product_count') ? ' <span class="count">' . $this->model_catalog_product->getTotalProducts($filter2_data) . '</span>' : ''),
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'].'_'. $child2['category_id'])
					);
				}
      
					$filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					$children_data[] = array(

				'category_id' => $child['category_id'],
				'children2'    => $children2_data,
      
						
				'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' <span class="count">' . $this->model_catalog_product->getTotalProducts($filter_data) . '</span>' : ''),
				
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);
				}

				// Level 1
				$data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}

		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');

		// For page specific css
		if (isset($this->request->get['route'])) {
			if (isset($this->request->get['product_id'])) {
				$class = '-' . $this->request->get['product_id'];
			} elseif (isset($this->request->get['path'])) {
				$class = '-' . $this->request->get['path'];
			} elseif (isset($this->request->get['manufacturer_id'])) {
				$class = '-' . $this->request->get['manufacturer_id'];
			} else {
				$class = '';
			}

			$data['class'] = str_replace('/', '-', $this->request->get['route']) . $class;
		} else {
			$data['class'] = 'common-home';
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/header.tpl', $data);
		} else {
			return $this->load->view('default/template/common/header.tpl', $data);
		}
	}
}