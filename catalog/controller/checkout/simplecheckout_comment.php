<?php
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

include_once(DIR_SYSTEM . 'library/simple/simple_controller.php');

class ControllerCheckoutSimpleCheckoutComment extends SimpleController { 
    private $_templateData = array();

    public function index() {
        $this->load->library('simple/simplecheckout');
        
        $this->simplecheckout = SimpleCheckout::getInstance($this->registry);
        $this->simplecheckout->setCurrentBlock('comment');

        $label       = '';
        $placeholder = '';
        $comment     = '';

        $tmp = $this->simplecheckout->getSettingValue('label');
        if (!empty($tmp[$this->simplecheckout->getCurrentLanguageCode()])) {
            $label = $tmp[$this->simplecheckout->getCurrentLanguageCode()];
        }

        $tmp = $this->simplecheckout->getSettingValue('placeholder');
        if (!empty($tmp[$this->simplecheckout->getCurrentLanguageCode()])) {
            $placeholder = $tmp[$this->simplecheckout->getCurrentLanguageCode()];
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $comment = !empty($this->request->post['comment']) ? $this->request->post['comment'] : '';
            $this->session->data['simple']['comment'] = $comment;
        } elseif (!empty($this->session->data['simple']['comment'])) {
            $comment = $this->session->data['simple']['comment'];
        }

        $this->_templateData['display_header'] = $this->simplecheckout->getSettingValue('displayHeader');
        $this->_templateData['label'] = $label;
        $this->_templateData['placeholder'] = $placeholder;
        $this->_templateData['comment'] = $comment;

        $this->simplecheckout->setComment($comment);

        $this->simplecheckout->resetCurrentBlock(); 

        $this->setOutputContent($this->renderPage('checkout/simplecheckout_comment.tpl', $this->_templateData));
    }
}


?>