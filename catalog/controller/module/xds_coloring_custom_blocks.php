<?php
class ControllerModuleXDSColoringCustomBlocks extends Controller {
	public function index($setting) {
		$this->load->language('module/xds_coloring_custom_blocks');

		$this->load->model('tool/image');
		
		$data['language_id'] = $this->config->get('config_language_id');
		
		$results = $setting['cust_blocks_item'];
		
		foreach ($results as $result) {
			$data['blocks'][] = array(
				'image' => $this->model_tool_image->resize($result['image'], 50, 50),
				'title' => $result['title'],
				'description' => $result['description'],
				'link'  => $result['link'],
				'sort'  => $result['sort']
			);	
		}
		
		if (!empty($data['blocks'])){
			foreach ($data['blocks'] as $key => $value) {
				$sort[$key] = $value['sort'];
			} 
			array_multisort($sort, SORT_ASC, $data['blocks']);
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/xds_coloring_custom_blocks.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/xds_coloring_custom_blocks.tpl', $data);
		} else {
			return $this->load->view('default/template/module/xds_coloring_custom_blocks.tpl', $data);
		}
	}
}