<?php
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

include_once(DIR_SYSTEM . 'library/simple/simple_controller.php');

class ControllerCheckoutSimpleCheckoutPayment extends SimpleController {
    static $updated = false;

    private $_templateData = array();

    private function init() {
        $this->load->library('simple/simplecheckout');

        $this->simplecheckout = SimpleCheckout::getInstance($this->registry);
        $this->simplecheckout->setCurrentBlock('payment');

        $this->language->load('checkout/simplecheckout');
    }

    public function index() {
        if (!self::$updated) {
            $this->update();
        }

        $this->init();

        $address = $this->simplecheckout->getPaymentAddress();

        $this->_templateData['address_empty'] = $this->simplecheckout->isPaymentAddressEmpty();

        $total_data = array();
        $total = 0;
        $taxes = $this->cart->getTaxes();

        $sort_order = array();

        if ($this->simplecheckout->getOpencartVersion() < 200) {
            $this->load->model('setting/extension');

            $results = $this->model_setting_extension->getExtensions('total');
        } else {
            $this->load->model('extension/extension');

            $results = $this->model_extension_extension->getExtensions('total');
        }

        foreach ($results as $key => $value) {
            $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
        }

        array_multisort($sort_order, SORT_ASC, $results);

        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                $this->load->model('total/' . $result['code']);
                $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
            }
        }

        $method_data = array();

        if ($stubs = $this->simplecheckout->getPaymentStubs()) {
            foreach ($stubs as $stub) {
                $method_data[$stub['code']] = $stub;
            }
        }

        $version = $this->simplecheckout->simplecheckout->getOpencartVersion();

        $cartHasReccuringProducts = 0;

        if ($version >= 156) {
            $cartHasReccuringProducts = $this->cart->hasRecurringProducts();
        }

        if ($this->simplecheckout->getOpencartVersion() < 200) {
            $this->load->model('setting/extension');

            $results = $this->model_setting_extension->getExtensions('payment');
        } else {
            $this->load->model('extension/extension');

            $results = $this->model_extension_extension->getExtensions('payment');
        }

        foreach ($results as $result) {
            $display = true;
            if ($this->_templateData['address_empty']) {
                $display = $this->simplecheckout->displayPaymentMethodForEmptyAddress($result['code']);
            }

            if ($this->config->get($result['code'] . '_status') && $display) {
                $this->load->model('payment/' . $result['code']);

                $method = $this->{'model_payment_' . $result['code']}->getMethod($address, $total);

                if ($method) {
                    if (!$cartHasReccuringProducts || ($cartHasReccuringProducts > 0 && method_exists($this->{'model_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_payment_' . $result['code']}->recurringPayments() == true)) {
                        if (!empty($method['quote']) && is_array($method['quote'])) {
                            foreach ($method['quote'] as $quote) {
                                $this->simplecheckout->exportPaymentMethod($quote);
                                $quote = $this->simplecheckout->preparePaymentMethod($quote);
                                if (!empty($quote)) {
                                    $method_data[$quote['code']] = $quote;
                                }
                            }
                        } else {
                            $this->simplecheckout->exportPaymentMethod($method);
                            $method = $this->simplecheckout->preparePaymentMethod($method);
                            if (!empty($method)) {
                                $method_data[$result['code']] = $method;
                            }
                        }
                    }
                }
            }
        }

        $sort_order = array();

        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $method_data);

        $this->_templateData['payment_methods']   = $method_data;
        $this->_templateData['payment_method']    = null;
        $this->_templateData['error_payment']     = $this->language->get('error_payment');
        $this->_templateData['has_error_payment'] = false;

        $this->_templateData['code'] = '';
        $this->_templateData['checked_code'] = '';

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && !empty($this->request->post['payment_method_checked']) && !empty($this->_templateData['payment_methods'][$this->request->post['payment_method_checked']]) && empty($this->_templateData['payment_methods'][$this->request->post['payment_method_checked']]['dummy'])) {
            $this->_templateData['checked_code'] = $this->request->post['payment_method_checked'];
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['payment_method']) && !empty($this->_templateData['payment_methods'][$this->request->post['payment_method']]) && empty($this->_templateData['payment_methods'][$this->request->post['payment_method_checked']]['dummy'])) {
            $this->_templateData['payment_method'] = $this->_templateData['payment_methods'][$this->request->post['payment_method']];

            if (isset($this->request->post['payment_method_current']) && $this->request->post['payment_method_current'] != $this->request->post['payment_method']) {
                $this->_templateData['checked_code'] = $this->request->post['payment_method'];
            }
        }

        if ($this->request->server['REQUEST_METHOD'] == 'GET' && isset($this->session->data['payment_method'])) {
            $user_checked = false;
            if (!empty($this->session->data['payment_method']['code'])) {
                $payment_code = $this->session->data['payment_method']['code'];
                $user_checked = true;
            }

            if (isset($this->_templateData['payment_methods'][$payment_code]) && empty($this->_templateData['payment_methods'][$payment_code]['dummy'])) {
                $this->_templateData['payment_method'] = $this->_templateData['payment_methods'][$payment_code];
                if ($user_checked) {
                    $this->_templateData['checked_code'] = $this->session->data['payment_method']['code'];
                }
            }
        }

        $selectFirst = $this->simplecheckout->getSettingValue('selectFirst');
        $hide = $this->simplecheckout->isBlockHidden();

        if ($hide) {
            $selectFirst = true;
        }

        if (!empty($this->_templateData['payment_methods']) && ($hide || ($selectFirst && $this->_templateData['checked_code'] == ''))) {
            foreach ($this->_templateData['payment_methods'] as $method) {
                if (empty($method['dummy'])) {
                    $this->_templateData['payment_method'] = $method;
                    break;
                }
            }

        }

        if ($this->validate()) {
            $this->simplecheckout->setPaymentMethod($this->_templateData['payment_method']);
            $this->_templateData['code'] = $this->_templateData['payment_method']['code'];
        }

        $this->_templateData['rows'] = $this->simplecheckout->getRows();

        $this->validateFields();

        $this->saveToSession();

        $this->_templateData['display_header']        = $this->simplecheckout->getSettingValue('displayHeader');
        $this->_templateData['display_error']         = $this->simplecheckout->displayError();
        $this->_templateData['display_address_empty'] = $this->simplecheckout->getSettingValue('displayAddressEmpty');
        $this->_templateData['has_error']             = $this->simplecheckout->hasError();
        $this->_templateData['hide']                  = $this->simplecheckout->isBlockHidden();

        $this->_templateData['text_checkout_payment_method'] = $this->language->get('text_checkout_payment_method');
        $this->_templateData['text_payment_address']         = $this->language->get('text_payment_address');
        $this->_templateData['error_no_payment']             = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact'));
        $this->_templateData['display_type']                 = $this->simplecheckout->getPaymentDisplayType();

        $this->simplecheckout->resetCurrentBlock();

        $this->setOutputContent($this->renderPage('checkout/simplecheckout_payment.tpl', $this->_templateData));
    }

    public function update() {
        self::$updated = true;

        $this->init();

        $this->simplecheckout->updateFields();

        $this->simplecheckout->resetCurrentBlock();
    }

    private function saveToSession() {
        $this->session->data['payment_methods'] = $this->_templateData['payment_methods'];
        $this->session->data['payment_method'] = $this->_templateData['payment_method'];

        if (empty($this->session->data['payment_methods'])) {
            unset($this->session->data['payment_method']);
        }
    }

    private function validate() {
        $error = false;

        if (empty($this->_templateData['payment_method']['code'])) {
            $this->_templateData['has_error_payment'] = true;
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
