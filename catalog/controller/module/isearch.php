<?php  
class ControllerModuleIsearch extends Controller {
	public function index() {
		$this->language->load('module/isearch');

    $data['heading_title'] = $this->language->get('heading_title');
		
		$data['data']['iSearch'] = $this->getModuleSettings();
		$data['currenttemplate'] =  $this->config->get('config_template');
		$data['language_id'] = $this->config->get('config_language_id');

		if($data['data']['iSearch']['Enabled'] != 'no') {
			$this->document->addScript('catalog/view/javascript/isearch.js');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/stylesheet/isearch.css')) {
				$this->document->addStyle('catalog/view/theme/'.$this->config->get('config_template') . '/stylesheet/isearch.css');
			} else {
				$this->document->addStyle('catalog/view/theme/default/stylesheet/isearch.css');
			}
			
			if (!empty($_SERVER['HTTP_USER_AGENT'])) {
				if (stripos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7') !== FALSE) {
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/stylesheet/isearch_ie7.css')) {
						$this->document->addStyle('catalog/view/theme/'.$this->config->get('config_template') . '/stylesheet/isearch_ie7.css');
					} else {
						$this->document->addStyle('catalog/view/theme/default/stylesheet/isearch_ie7.css');
					}
				}
				
				if (stripos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8') !== FALSE) {
					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/stylesheet/isearch_ie8.css')) {
						$this->document->addStyle('catalog/view/theme/'.$this->config->get('config_template') . '/stylesheet/isearch_ie8.css');
					} else {
						$this->document->addStyle('catalog/view/theme/default/stylesheet/isearch_ie8.css');
					}
				}
			}
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/isearch.tpl')) {
				$template = $this->config->get('config_template') . '/template/module/isearch.tpl';
			} else {
				$template = 'default/template/module/isearch.tpl';
			}

			return $this->load->view($template, $data);
		} 
		
	}
	
	function getModuleSettings() {
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			return str_replace('http', 'https', $this->config->get('iSearch'));
		} else {
			return $this->config->get('iSearch');
		}
	}
	

	public function ajaxget() {
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: application/json');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
		
		$set = $this->getModuleSettings();
		
		$useImages = ($set['ResultsShowImages']=='yes') ? true : false;
		$strictSearch = ($set['UseStrictSearch']=='yes') ? true : false;
		$singularize = ($set['UseSingularize']=='yes') ? true : false;
		$index = !empty($set['ResultsSpellCheckSystem']) ? $set['ResultsSpellCheckSystem'] : 'no';
		$checkRules = $index == 'yes' ? (!empty($set['SCWords']) ? $set['SCWords'] : array()) : NULL;
		$excludeTerms = !empty($set['ExcludeTerms']) ? $set['ExcludeTerms'] : '';
		$excludeRules = !empty($set['ExcludeProducts']) ? $set['ExcludeProducts'] : array();
		
		//{HOOK_SEARCH_IN_CACHE_SETTING}
		
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
			'metakeyword' => !empty($set['SearchIn']['MetaKeyword']),			
		);
		
		$keywords = (empty($_GET['k'])) ? 'no-ajax-mode' : $_GET['k'];
		$this->load->model('catalog/isearch');
		$resultsByNameAndModel = $this->model_catalog_isearch->iSearch($keywords,$searchIn,$useImages,$strictSearch,$singularize,$checkRules,'','ASC',0,false,$excludeTerms,$excludeRules/*{HOOK_SEARCH_IN_CACHE_ATTRIBUTE}*/);
		$products = $resultsByNameAndModel;
		echo json_encode($products);

	}	

	
}
?>