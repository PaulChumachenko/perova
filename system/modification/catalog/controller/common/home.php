<?php
class ControllerCommonHome extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink(HTTP_SERVER, 'canonical');
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');


				$this->load->model('setting/setting');
			
				$xds_coloring_theme = array();
				$xds_coloring_theme = $this->model_setting_setting->getSetting('xds_coloring_theme');
				
				$this->load->model('extension/module');
				
				$new_modules = array();
				$data['new_modules_1'] = array();
				$data['new_modules_2'] = array();
				$data['new_modules_3'] = array();
				$data['new_modules_4'] = array();
				$data['new_modules_5'] = array();
				
				
				if (isset($xds_coloring_theme['xds_coloring_theme_new_home_layout_module'])) {
					$new_modules = $xds_coloring_theme['xds_coloring_theme_new_home_layout_module'];
				}
				
				foreach ($new_modules as $new_module) {
				
					if ($new_module['position'] == 'position_1') {
						$part = explode('.', $new_module['code']);
						if (isset($part[0]) && $this->config->get($part[0] . '_status')) {
							$data['new_modules_1'][] = array(
								'module'     => $this->load->controller('module/' . $part[0]),
								'sort_order' => $new_module['sort_order']
							);
						}
						if (isset($part[1])) {
							$setting_info = $this->model_extension_module->getModule($part[1]);
							if ($setting_info && $setting_info['status']) {
								$data['new_modules_1'][] = array(
									'module'     => $this->load->controller('module/' . $part[0], $setting_info),
									'sort_order' => $new_module['sort_order']
								);
							}
						}
					} elseif ($new_module['position'] == 'position_2') {
						$part = explode('.', $new_module['code']);
						if (isset($part[0]) && $this->config->get($part[0] . '_status')) {
							$data['new_modules_2'][] = array(
								'module'     => $this->load->controller('module/' . $part[0]),
								'sort_order' => $new_module['sort_order']
							);
						}
						if (isset($part[1])) {
							$setting_info = $this->model_extension_module->getModule($part[1]);
							if ($setting_info && $setting_info['status']) {
								$data['new_modules_2'][] = array(
									'module'     => $this->load->controller('module/' . $part[0], $setting_info),
									'sort_order' => $new_module['sort_order']
								);
							}
						}
					} elseif ($new_module['position'] == 'position_3') {
						$part = explode('.', $new_module['code']);
						if (isset($part[0]) && $this->config->get($part[0] . '_status')) {
							$data['new_modules_3'][] = array(
								'module'     => $this->load->controller('module/' . $part[0]),
								'sort_order' => $new_module['sort_order']
							);

						}
						if (isset($part[1])) {
							$setting_info = $this->model_extension_module->getModule($part[1]);
							if ($setting_info && $setting_info['status']) {
								$data['new_modules_3'][] = array(
									'module'     => $this->load->controller('module/' . $part[0], $setting_info),
									'sort_order' => $new_module['sort_order']
								);

							}
						}
					} elseif($new_module['position'] == 'position_4') {
						$part = explode('.', $new_module['code']);
						if (isset($part[0]) && $this->config->get($part[0] . '_status')) {
							$data['new_modules_4'][] = array(
								'module'     => $this->load->controller('module/' . $part[0]),
								'sort_order' => $new_module['sort_order']
							);

						}
						if (isset($part[1])) {
							$setting_info = $this->model_extension_module->getModule($part[1]);
							if ($setting_info && $setting_info['status']) {
								$data['new_modules_4'][] = array(
									'module'     => $this->load->controller('module/' . $part[0], $setting_info),
									'sort_order' => $new_module['sort_order']
								);

							}
						}
					} else {
						$part = explode('.', $new_module['code']);
						if (isset($part[0]) && $this->config->get($part[0] . '_status')) {
							$data['new_modules_5'][] = array(
								'module'     => $this->load->controller('module/' . $part[0]),
								'sort_order' => $new_module['sort_order']
							);

						}
						if (isset($part[1])) {
							$setting_info = $this->model_extension_module->getModule($part[1]);
							if ($setting_info && $setting_info['status']) {
								$data['new_modules_5'][] = array(
									'module'     => $this->load->controller('module/' . $part[0], $setting_info),
									'sort_order' => $new_module['sort_order']
								);

							}
						}
					}
					
					if (!empty($data['new_modules_1'])){
						foreach ($data['new_modules_1'] as $key => $value) {
							$sort_new_modules_1[$key] = $value['sort_order'];
						} 
						array_multisort($sort_new_modules_1, SORT_ASC, $data['new_modules_1']);
					}
					if (!empty($data['new_modules_2'])){
						foreach ($data['new_modules_2'] as $key => $value) {
							$sort_new_modules_2[$key] = $value['sort_order'];
						} 
						array_multisort($sort_new_modules_2, SORT_ASC, $data['new_modules_2']);
					}
					if (!empty($data['new_modules_3'])){
						foreach ($data['new_modules_3'] as $key => $value) {
							$sort_new_modules_3[$key] = $value['sort_order'];
						} 
						array_multisort($sort_new_modules_3, SORT_ASC, $data['new_modules_3']);
					}
					if (!empty($data['new_modules_4'])){
						foreach ($data['new_modules_4'] as $key => $value) {
							$sort_new_modules_4[$key] = $value['sort_order'];
						} 
						array_multisort($sort_new_modules_4, SORT_ASC, $data['new_modules_4']);
					}
					if (!empty($data['new_modules_5'])){
						foreach ($data['new_modules_5'] as $key => $value) {
							$sort_new_modules_5[$key] = $value['sort_order'];
						} 
						array_multisort($sort_new_modules_5, SORT_ASC, $data['new_modules_5']);
					}
					
				}
				
				
      
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/home.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/home.tpl', $data));
		}
	}
}