<?php 
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

include_once(DIR_SYSTEM . 'library/simple/simple_controller.php');

class ControllerCheckoutSimpleCheckoutCustomer extends SimpleController {
    static $error = array();
    static $updated = false;

    private $_templateData = array();

    private function init() {
        $this->load->library('simple/simplecheckout');
        
        $this->simplecheckout = SimpleCheckout::getInstance($this->registry);
        $this->simplecheckout->setCurrentBlock('customer');

        $this->language->load('checkout/simplecheckout');
    }

    public function index() {
        if (!self::$updated) {
            $this->update();
        }

        $this->init();

        if ($this->simplecheckout->isBlockHidden()) {
            $this->simplecheckout->resetCurrentBlock();
            return;
        }

        $this->_templateData['text_checkout_customer']       = $this->language->get('text_checkout_customer');
        $this->_templateData['text_checkout_customer_login'] = $this->language->get('text_checkout_customer_login');
        $this->_templateData['text_you_will_be_registered']  = $this->language->get('text_you_will_be_registered');
        $this->_templateData['text_account_created']         = $this->language->get('text_account_created');
        $this->_templateData['entry_address_same']           = $this->language->get('entry_address_same');
        
        $this->_templateData['display_login']               = !$this->customer->isLogged() && $this->simplecheckout->getSettingValue('displayLogin');
        $this->_templateData['display_registered']          = !empty($this->session->data['simple']['registered']) ? true : false;
        
        $this->_templateData['rows'] = $this->simplecheckout->getRows();

        $this->validate();

        unset($this->session->data['simple']['registered']);

        $this->_templateData['display_header']              = $this->simplecheckout->getSettingValue('displayHeader');
        $this->_templateData['display_you_will_registered'] = !$this->customer->isLogged() && $this->simplecheckout->getSettingValue('displayYouWillRegistered') && $this->simplecheckout->registerCustomer() && !$this->simplecheckout->isFieldUsed('customer', 'register');
        $this->_templateData['display_error']               = $this->simplecheckout->displayError();
        $this->_templateData['has_error']                   = $this->simplecheckout->hasError();
        $this->_templateData['hide']                        = $this->simplecheckout->isBlockHidden();
        
        $this->simplecheckout->resetCurrentBlock();

        $this->setOutputContent($this->renderPage('checkout/simplecheckout_customer.tpl', $this->_templateData));
    }

    public function update() {
        self::$updated = true;

        $this->init();

        if ($this->simplecheckout->isBlockHidden()) {
            $this->simplecheckout->resetCurrentBlock();
            return;
        }

        $this->simplecheckout->updateFields();

        $this->simplecheckout->resetCurrentBlock();
    }

    private function validate() {
        $error = false;

        if (!$this->simplecheckout->validateFields()) {
            $error = true;
        }
        
        if ($error) {
            $this->simplecheckout->addError();
        }

        self::$error = $error;
        
        return !$error;
    }
}
?>