<?php
#################################################################
#     ExtendedSearch module 1.03 for Opencart 2.x by AlexDW 	#
#################################################################
class ControllerModuleExtendedsearch extends Controller {

	private $error = array(); 

	public function index() {   
		$this->load->language('module/extendedsearch');

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('extendedsearch', $this->request->post);		

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$text_strings = array(
				'heading_title',
				'text_module',
				'text_edit',
				'text_extsearch',
				'text_enabled',
				'text_disabled',
				'entry_status',
				'entry_tag',
				'entry_model',
				'entry_sku',
				'entry_upc',
				'entry_ean',
				'entry_jan',
				'entry_isbn',
				'entry_mpn',
				'entry_location',
				'entry_attr',
				'button_save',
				'button_cancel',
				'button_add_module',
				'button_remove'
		);

		foreach ($text_strings as $text) {
			$data[$text] = $this->language->get($text);
		}

		$config_data = array(
				'extendedsearch_status',
				'extendedsearch_tag',
				'extendedsearch_model',
				'extendedsearch_sku',
				'extendedsearch_upc',
				'extendedsearch_ean',
				'extendedsearch_jan',
				'extendedsearch_isbn',
				'extendedsearch_mpn',
				'extendedsearch_location',
				'extendedsearch_attr'
		);

		foreach ($config_data as $conf) {
			if (isset($this->request->post[$conf])) {
				$data[$conf] = $this->request->post[$conf];
			} else {
				$data[$conf] = $this->config->get($conf);
			}
		}

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/extendedsearch', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('module/extendedsearch', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/extendedsearch.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/extendedsearch')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
}
?>