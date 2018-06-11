<?php 
/*
@author Dmitriy Kubarev
@link   http://www.simpleopencart.com
@link   http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/ 

include_once(DIR_SYSTEM . 'library/simple/simple.php');

class SimpleRegister extends Simple {
    protected static $_instance;
    
    protected function __construct($registry) {
        $this->setPage('register');
        parent::__construct($registry);
    }    

    public static function getInstance($registry) {
        if (self::$_instance === null) {
            self::$_instance = new self($registry);  
        }

        return self::$_instance;
    }

    public function loadSimpleSession() {
        if ($this->request->server['REQUEST_METHOD'] == 'GET') {
            if (!empty($this->session->data['shipping_postcode']) && empty($this->session->data['simple']['register']['postcode'])) {
                $this->session->data['simple']['register']['postcode'] = $this->session->data['shipping_postcode'];
            }

            if (!empty($this->session->data['shipping_country_id']) && empty($this->session->data['simple']['register']['country_id'])) {
                $this->session->data['simple']['register']['country_id'] = $this->session->data['shipping_country_id'];
            }

            if (!empty($this->session->data['shipping_zone_id']) && empty($this->session->data['simple']['register']['zone_id'])) {
                $this->session->data['simple']['register']['zone_id'] = $this->session->data['shipping_zone_id'];
            }
        }
    }

    public function displayError() {
        return $this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['submitted']) ? true : false;
    }

    public function getCustomerInfo() {
        $randomPassword = '';

        $this->load->model('tool/simpleapimain');

        if (method_exists($this->model_tool_simpleapimain, 'getRandomPassword')) {
            $randomPassword = $this->model_tool_simpleapimain->getRandomPassword();
        }

        $fieldsInfo = array(
            'customer_id'       => 0,
            'firstname'         => $this->getFieldValue('firstname'),
            'lastname'          => $this->getFieldValue('lastname'),
            'email'             => $this->getFieldValue('email'),
            'telephone'         => $this->getFieldValue('telephone'),
            'fax'               => $this->getFieldValue('fax'),
            'password'          => $this->isFieldUsed($this->_page, 'password') ? $this->getFieldValue('password') : $randomPassword,
            'newsletter'        => $this->getFieldValue('newsletter'),
            'customer_group_id' => $this->getCustomerGroupId()            
        );

        $fieldsInfo['email'] = !empty($fieldsInfo['email']) ? $fieldsInfo['email'] : 'empty@localhost';

        $customInfo = $this->getCustomFields($this->_page, 'customer');

        return array_merge($customInfo, $fieldsInfo);
    }

    public function getAddress() {
        $zoneId = $this->getFieldValue('zone_id');
        $countryId = $this->getFieldValue('country_id');

        $fieldsInfo = $this->prepareAddress($zoneId, $countryId);

        $fieldsInfo['address_id'] = 0;
        $fieldsInfo['firstname']  = $this->getFieldValue('firstname');
        $fieldsInfo['lastname']   = $this->getFieldValue('lastname');
        $fieldsInfo['company']    = $this->getFieldValue('company');
        $fieldsInfo['company_id'] = $this->getFieldValue('company_id');
        $fieldsInfo['tax_id']     = $this->getFieldValue('tax_id');
        $fieldsInfo['address_1']  = $this->getFieldValue('address_1');
        $fieldsInfo['address_2']  = $this->getFieldValue('address_2');
        $fieldsInfo['postcode']   = $this->getFieldValue('postcode');
        $fieldsInfo['city']       = $this->getFieldValue('city');

        $customInfo = $this->getCustomFields($this->_page, 'address');

        return array_merge($customInfo, $fieldsInfo);
    }

    public function clearSimpleSession() {
        unset($this->session->data['simple']);
    }

}