<?php 
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

include_once(DIR_SYSTEM . 'library/simple/simple_controller.php');

class ControllerCheckoutSimpleCheckoutShipping extends SimpleController {
    static $updated = false;

    private $_templateData = array();

    private function init() {
        $this->load->library('simple/simplecheckout');
        
        $this->simplecheckout = SimpleCheckout::getInstance($this->registry);
        $this->simplecheckout->setCurrentBlock('shipping');

        $this->language->load('checkout/simplecheckout');
    }

    public function index() {
        if (!$this->cart->hasShipping()) {
            return;
        }

        if (!self::$updated) {
            $this->update();
        }

        $this->init();

        $address = $this->simplecheckout->getShippingAddress();

        $this->_templateData['address_empty'] = $this->simplecheckout->isShippingAddressEmpty();
        
        $quote_data = array();

        if ($stubs = $this->simplecheckout->getShippingStubs()) {
            foreach ($stubs as $stub) {
                $quote_data[$stub['code']] = $stub;
            }
        }

        if ($this->simplecheckout->getOpencartVersion() < 200) {
            $this->load->model('setting/extension');
        
            $results = $this->model_setting_extension->getExtensions('shipping');
        } else {
            $this->load->model('extension/extension');
        
            $results = $this->model_extension_extension->getExtensions('shipping');
        }
        
        foreach ($results as $result) {            
            $display = true;
            if ($this->_templateData['address_empty']) {
                $display = $this->simplecheckout->displayShippingMethodForEmptyAddress($result['code']);
            }

            if ($this->config->get($result['code'] . '_status') && $display) {
                $this->load->model('shipping/' . $result['code']);
                
                $quote = $this->{'model_shipping_' . $result['code']}->getQuote($address); 
    
                if ($quote) {
                    $this->simplecheckout->exportShippingMethods($quote);
                    $quote = $this->simplecheckout->prepareShippingMethods($quote);
                    if (!empty($quote)) {
                        $stubsInfo = !empty($quote_data[$result['code']]['quote']) ? $quote_data[$result['code']]['quote'] : array();
                        $realInfo = !empty($quote['quote']) ? $quote['quote'] : array();

                        $quote['quote'] = $stubsInfo;

                        foreach ($realInfo as $realId => $realInfo) {
                            $quote['quote'][$realId] = $realInfo;
                        }

                        $quote_data[$result['code']] = $quote;
                    }
                }
            }
        }

        $sort_order = array();
      
        foreach ($quote_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $quote_data);
        
        $this->_templateData['shipping_methods']   = $quote_data;
        $this->_templateData['shipping_method']    = null;
        $this->_templateData['error_shipping']     = $this->language->get('error_shipping');
        $this->_templateData['has_error_shipping'] = false;

        $this->_templateData['code'] = '';
        $this->_templateData['checked_code'] = '';

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && !empty($this->request->post['shipping_method_checked'])) {
            $shipping = explode('.', $this->request->post['shipping_method_checked']);
            
            if (isset($shipping[1]) && isset($this->_templateData['shipping_methods'][$shipping[0]]['quote'][$shipping[1]]) && empty($this->_templateData['shipping_methods'][$shipping[0]]['quote'][$shipping[1]]['dummy'])) {
                $this->_templateData['checked_code'] = $this->request->post['shipping_method_checked'];
            }
        }
        
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['shipping_method'])) {
            $shipping = explode('.', $this->request->post['shipping_method']);
            
            if (isset($shipping[1]) && isset($this->_templateData['shipping_methods'][$shipping[0]]['quote'][$shipping[1]]) && empty($this->_templateData['shipping_methods'][$shipping[0]]['quote'][$shipping[1]]['dummy'])) {
                $this->_templateData['shipping_method'] = $this->_templateData['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
               
                if (isset($this->request->post['shipping_method_current']) && $this->request->post['shipping_method_current'] != $this->request->post['shipping_method']) {
                    $this->_templateData['checked_code'] = $this->request->post['shipping_method'];
                }
            }
        }

        if ($this->request->server['REQUEST_METHOD'] == 'GET' && isset($this->session->data['shipping_method'])) {
            $user_checked = false;
            if (isset($this->session->data['shipping_method'])) {
                $shipping = explode('.', $this->session->data['shipping_method']['code']);
                $user_checked = true;
            }
            
            if (isset($shipping[1]) && isset($this->_templateData['shipping_methods'][$shipping[0]]['quote'][$shipping[1]]) && empty($this->_templateData['shipping_methods'][$shipping[0]]['quote'][$shipping[1]]['dummy'])) {
                $this->_templateData['shipping_method'] = $this->_templateData['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
                if ($user_checked) {
                    $this->_templateData['checked_code'] = $this->session->data['shipping_method']['code'];
                }
            }
        }

        $selectFirst = $this->simplecheckout->getSettingValue('selectFirst');
        $hide = $this->simplecheckout->isBlockHidden();
        
        if ($hide) {
            $selectFirst = true;
        }
        
        if (!empty($this->_templateData['shipping_methods']) && ($hide || ($selectFirst && $this->_templateData['checked_code'] == ''))) {
            $first = false;
            foreach ($this->_templateData['shipping_methods'] as $method) {
                if (!empty($method['quote'])) {
                    $first_method = reset($method['quote']);

                    if (!empty($first_method) && empty($first_method['dummy'])) {
                        $this->_templateData['shipping_method'] = $first_method;
                        break;
                    }
                }
            }
        }
        
        if ($this->validate()) {
            $this->simplecheckout->setShippingMethod($this->_templateData['shipping_method']);
            $this->_templateData['code'] = $this->_templateData['shipping_method']['code'];
        }
        
        $this->_templateData['rows'] = $this->simplecheckout->getRows();

        $this->validateFields();

        $this->saveToSession();

        $this->_templateData['display_header']        = $this->simplecheckout->getSettingValue('displayHeader');
        $this->_templateData['display_error']         = $this->simplecheckout->displayError();
        $this->_templateData['display_address_empty'] = $this->simplecheckout->getSettingValue('displayAddressEmpty');
        $this->_templateData['has_error']             = $this->simplecheckout->hasError();
        $this->_templateData['hide']                  = $this->simplecheckout->isBlockHidden();
        
        $this->_templateData['text_checkout_shipping_method'] = $this->language->get('text_checkout_shipping_method');
        $this->_templateData['text_shipping_address']         = $this->language->get('text_shipping_address');
        $this->_templateData['error_no_shipping']             = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
        $this->_templateData['display_type']                  = $this->simplecheckout->getShippingDisplayType();
        
        $this->simplecheckout->resetCurrentBlock();

        $this->setOutputContent($this->renderPage('checkout/simplecheckout_shipping.tpl', $this->_templateData));
    }

    public function update() {
        if (!$this->cart->hasShipping()) {
            return;
        }

        self::$updated = true;

        $this->init();

        $this->simplecheckout->updateFields();

        $this->simplecheckout->resetCurrentBlock();
    }
    
    private function saveToSession() {
        $this->session->data['shipping_methods'] = $this->_templateData['shipping_methods'];
        $this->session->data['shipping_method'] = $this->_templateData['shipping_method'];
        
        if (empty($this->session->data['shipping_methods'])) {
            unset($this->session->data['shipping_method']);
        }
    }
    
    private function validate() {
        $error = false;
        
        if (empty($this->_templateData['shipping_method']['code'])) {
            $this->_templateData['has_error_shipping'] = true;
            $error = true;
        } 
        
        if ($error) {
            $this->simplecheckout->addError();
        }
        
        return !$error;
    }

    private function validateFields() {
        $error = false;
        
        if (!$this->simplecheckout->validateFields()) {
            $error = true;
        }
        
        if ($error) {
            $this->simplecheckout->addError();
        }
        
        return !$error;
    }
}