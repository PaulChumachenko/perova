<?php
$data['sorts'] = array();

if ($this->config->get('sortslimits_order_ASC')) {	
	$data['sorts'][] = array(
		'text'  => $this->language->get('text_default'),
		'value' => 'p.sort_order-ASC',
		'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url)
	);
}
if ($this->config->get('sortslimits_name_ASC')) {	
	$data['sorts'][] = array(
		'text'  => $this->language->get('text_name_asc'),
		'value' => 'pd.name-ASC',
		'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
	);
}
if ($this->config->get('sortslimits_name_DESC')) {	
	$data['sorts'][] = array(
		'text'  => $this->language->get('text_name_desc'),
		'value' => 'pd.name-DESC',
		'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
	);
}
if ($this->config->get('sortslimits_price_ASC')) {	
	$data['sorts'][] = array(
		'text'  => $this->language->get('text_price_asc'),
		'value' => 'p.price-ASC',
		'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
	);
}
if ($this->config->get('sortslimits_price_DESC')) {	
	$data['sorts'][] = array(
		'text'  => $this->language->get('text_price_desc'),
		'value' => 'p.price-DESC',
		'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
	);
}
if ($this->config->get('config_review_status')) {
	if ($this->config->get('sortslimits_rating_DESC')) {	
	$data['sorts'][] = array(
		'text'  => $this->language->get('text_rating_desc'),
		'value' => 'rating-DESC',
		'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url)
	);
	}
	if ($this->config->get('sortslimits_rating_ASC')) {	
	$data['sorts'][] = array(
		'text'  => $this->language->get('text_rating_asc'),
		'value' => 'rating-ASC',
		'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url)
	);
	}
}
if ($this->config->get('sortslimits_model_ASC')) {
	$data['sorts'][] = array(
		'text'  => $this->language->get('text_model_asc'),
		'value' => 'p.model-ASC',
		'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=ASC' . $url)
	);
}
if ($this->config->get('sortslimits_model_DESC')) {
	$data['sorts'][] = array(
		'text'  => $this->language->get('text_model_desc'),
		'value' => 'p.model-DESC',
		'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=DESC' . $url)
	);
}
if ($this->config->get('sortslimits_quantity_ASC')) {
	$data['sorts'][] = array(
				'text'  => $this->language->get('text_quantity_asc'),
				'value' => 'p.quantity-ASC',
				'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.quantity&order=ASC' . $url)
	);
}
if ($this->config->get('sortslimits_quantity_DESC')) {
	$data['sorts'][] = array(
		'text'  => $this->language->get('text_quantity_desc'),
		'value' => 'p.quantity-DESC',
		'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.quantity&order=DESC' . $url)
	);
}
if ($this->config->get('sortslimits_date_added_ASC')) {
	$data['sorts'][] = array(
		'text'  => $this->language->get('text_date_added_asc'),
		'value' => 'p.date_added-ASC',
		'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.date_added&order=ASC' . $url)
	);
}
if ($this->config->get('sortslimits_date_added_DESC')) {
	$data['sorts'][] = array(
		'text'  => $this->language->get('text_date_added_desc'),
		'value' => 'p.date_added-DESC',
		'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.date_added&order=DESC' . $url)
	);
}
?>