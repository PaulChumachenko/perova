<?php
class ControllerModuleContactdesc extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/contactdesc');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('contactdesc', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$languageStrings = array('heading_title', 'text_module', 'text_success', 'error_permission', 'entry_description', 'button_save', 'button_cancel');
		foreach ($languageStrings as $languageString) {
			$data[$languageString] = $this->language->get($languageString);
		}

		$requiredFields = array();
		foreach ($requiredFields as $requiredField) {
			if (isset($this->error[$requiredField])) {
				$data['error_'.$requiredField] = $this->error[$requiredField];
			} else {
				$data['error_'.$requiredField] = '';
			}
		}

		$settings = $this->model_setting_setting->getSetting('contactdesc');

		$fields = array('contactdesc_description');
		foreach ($fields as $field) {
			if (isset($this->request->post[$field])) {
				$data[$field] = $this->request->post[$field];
			} elseif (isset($settings[$field])) {
				$data[$field] = $settings[$field];
			} else {
				$data[$field] = '';
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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/contactdesc', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('module/contactdesc', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/contactdesc.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/contactdesc')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$requiredFields = array();
		foreach ($requiredFields as $requiredField) {
			if (!$this->request->post[$requiredField]) {
				$this->error[$requiredField] = $this->language->get('error_'.$requiredField);
			}
		}

		return !$this->error;
	}

	public function install() {
		$this->load->model('extension/modification');

		$modification = array();
		$modification['name'] = 'ContactDesc';
		$modification['author'] = 'Quin Solutions';
		$modification['version'] = '1.0';
		$modification['link'] = 'http://quinsolutions.net';
		$modification['status'] = 1;

		if (VERSION == '2.0.0.0') {
			$modification['code'] = '<?xml version="1.0" encoding="UTF-8"?>
			<modification>
				<name>ContactDesc</name>
				<version>1.0</version>
				<author>Quin Solutions</author>
				<link>http://quinsolutions.net</link>
				<file path="catalog/controller/information/contact.php">
					<operation>
						<search><![CDATA[$data[\'button_map\'] = $this->language->get(\'button_map\');]]></search>
						<add position="after"><![CDATA[$data["contactdesc"] = html_entity_decode($this->config->get("contactdesc_description"), ENT_QUOTES, "UTF-8");]]></add>
					</operation>
				</file>
				<file path="catalog/view/theme/*/template/information/contact.tpl">
					<operation>
						<search><![CDATA[<h1><?php echo $heading_title; ?></h1>]]></search>
						<add position="after"><![CDATA[<?php echo isset($contactdesc)?$contactdesc:""; ?>]]></add>
					</operation>
				</file>
			</modification>';
		} else {
			$modification['code'] = 'contactdesc';
			$modification['xml'] = '<?xml version="1.0" encoding="UTF-8"?>
				<modification>
				<name>ContactDesc</name>
				<code>contactdesc</code>
				<version>1.0</version>
				<author>Quin Solutions</author>
				<link>http://quinsolutions.net</link>
				<file path="catalog/controller/information/contact.php">
					<operation>
						<search><![CDATA[$data[\'button_map\'] = $this->language->get(\'button_map\');]]></search>
						<add position="after"><![CDATA[$data["contactdesc"] = html_entity_decode($this->config->get("contactdesc_description"), ENT_QUOTES, "UTF-8");]]></add>
					</operation>
				</file>
				<file path="catalog/view/theme/*/template/information/contact.tpl">
					<operation>
						<search><![CDATA[<h1><?php echo $heading_title; ?></h1>]]></search>
						<add position="after"><![CDATA[<?php echo isset($contactdesc)?$contactdesc:""; ?>]]></add>
					</operation>
				</file>
			</modification>';
		}

		$this->model_extension_modification->addModification($modification);
		$this->load->controller('extension/modification/refresh');
	}

	public function uninstall() {
		$this->load->model('extension/modification');
		$mods = $this->model_extension_modification->getModifications();
		foreach ($mods as $mod) {
			if ($mod['name'] == 'ContactDesc') {
				$this->model_extension_modification->deleteModification($mod['modification_id']);
			}
		}
		$this->load->controller('extension/modification/refresh');
	}
}