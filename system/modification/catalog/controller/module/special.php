<?php
class ControllerModuleSpecial extends Controller {
	public function index($setting) {
		$this->load->language('module/special');

		$data['heading_title'] = $this->language->get('heading_title');

				$this->load->model('setting/setting');
				$xds_coloring = array();
				$xds_coloring = $this->model_setting_setting->getSetting('xds_coloring_theme');
				$language_id = $this->config->get('config_language_id');
				$data['disable_cart_button'] = false;
				if (isset($xds_coloring['xds_coloring_theme_disable_cart_button'])) {
					$data['disable_cart_button'] = $xds_coloring['xds_coloring_theme_disable_cart_button'];
				}
				$data['disable_cart_button_text'] = "";
				if (isset($xds_coloring['xds_coloring_theme_disable_cart_button_text'])) {
					$data['disable_cart_button_text'] = $xds_coloring['xds_coloring_theme_disable_cart_button_text'][$language_id];
				}
      

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

				static $module = 0;
				$this->document->addScript('catalog/view/theme/coloring/assets/owl-carousel/owl.carousel.min.js');
				$this->document->addStyle('catalog/view/theme/coloring/assets/owl-carousel/owl.carousel.css');
				$this->document->addStyle('catalog/view/theme/coloring/assets/owl-carousel/owl.theme.css');
      

		$data['products'] = array();

		$filter_data = array(
			'sort'  => 'pd.name',
			'order' => 'ASC',
			'start' => 0,
			'limit' => $setting['limit']
		);

		$results = $this->model_catalog_product->getProductSpecials($filter_data);

		if ($results) {
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
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
					$rating = $result['rating'];
				} else {
					$rating = false;
				}

				$data['products'][] = array(

				'quantity'  => $result['quantity'],
      
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}


				$data['module'] = $module++;
      
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/special.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/special.tpl', $data);
			} else {
				return $this->load->view('default/template/module/special.tpl', $data);
			}
		}
	}
}