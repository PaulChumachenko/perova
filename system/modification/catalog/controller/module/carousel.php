<?php
class ControllerModuleCarousel extends Controller {
	public function index($setting) {
		static $module = 0;

		$this->load->model('design/banner');
		$this->load->model('tool/image');

		
        $this->document->addStyle('catalog/view/theme/coloring/assets/owl-carousel/owl.carousel.css');
				$this->document->addStyle('catalog/view/theme/coloring/assets/owl-carousel/owl.theme.css');
      
		
        $this->document->addScript('catalog/view/theme/coloring/assets/owl-carousel/owl.carousel.min.js');
				$data['alt_text'] = $setting['name'];
      

		$data['banners'] = array();

		$results = $this->model_design_banner->getBanner($setting['banner_id']);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$data['banners'][] = array(
					'title' => $result['title'],
					'link'  => $result['link'],
					'image' => $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height'])
				);
			}
		}

		$data['module'] = $module++;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/carousel.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/carousel.tpl', $data);
		} else {
			return $this->load->view('default/template/module/carousel.tpl', $data);
		}
	}
}