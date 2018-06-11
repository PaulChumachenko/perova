<?php  
class Controllercommontecdocmodule extends Controller {
	public function index() {
		
		//Save customer group ID for TDMod 
		$_SESSION['TDM_CMS_USER_GROUP'] = intval($this->customer->getGroupId());
		$_SESSION['TDM_CMS_DEFAULT_CUR'] = $this->config->get('config_currency');
		
		//TecDoc
		if(defined('TDM_TITLE')){$this->document->setTitle(TDM_TITLE);}
		if(defined('TDM_KEYWORDS')){$this->document->setKeywords(TDM_KEYWORDS);}
		if(defined('TDM_DESCRIPTION')){$this->document->setDescription(TDM_DESCRIPTION);}
		
		if (isset($this->request->get['route'])) {
			$this->document->addLink(HTTP_SERVER, 'canonical');
		}
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/tecdoc_module.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/tecdoc_module.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/tecdoc_module.tpl', $data));
		}
		
		
	}
}
?>