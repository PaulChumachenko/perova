<?php
class ControllerCommonSearch extends Controller {
	public function index() {
		$this->load->language('common/search');

				$this->load->language('coloring/coloring');
				
				$data['search_text_category'] = $this->language->get('search_text_category');
				$data['search_text_category_alt'] = $this->language->get('search_text_category');
				
        if (isset($this->request->get['category_id'])) {
					$category_id = $this->request->get['category_id'];
				} else {
					$category_id = 0;
				}
				
				$data['category_id'] = $category_id;
				
				$data['search_categories'] = array();
				$search_categories_1 = $this->model_catalog_category->getCategories(0);
				foreach ($search_categories_1 as $search_category) {
					$data['search_categories'][] = array(
						'category_id' => $search_category['category_id'],
						'name'        => $search_category['name']
					);
				}
      

		$data['text_search'] = $this->language->get('text_search');

		if (isset($this->request->get['search'])) {
			$data['search'] = $this->request->get['search'];
		} else {
			$data['search'] = '';
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/search.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/search.tpl', $data);
		} else {
			return $this->load->view('default/template/common/search.tpl', $data);
		}
	}
}