<?php
/*
@author Dmitriy Kubarev
@link   http://www.simpleopencart.com
@link   http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

include_once(DIR_SYSTEM . 'library/simple/simple.php');

class SimpleAddress extends Simple {
    protected static $_instance;

    protected function __construct($registry) {
        $this->setPage('address');
        parent::__construct($registry);
    }

    public static function getInstance($registry) {
        if (self::$_instance === null) {
            self::$_instance = new self($registry);
        }

        return self::$_instance;
    }

    public function displayError() {
        return $this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['submitted']) ? true : false;
    }

    public function getAddress() {
        $addressId = $this->getFieldValue('address_id');

        $fullInfo = array();
        if ($addressId) {
            $this->load->model('account/address');
            $fullInfo = $this->model_account_address->getAddress($addressId);
            if (!is_array($fullInfo)) {
                $fullInfo = array();
            }
        }

        $zoneId = $this->getFieldValue('zone_id');
        $countryId = $this->getFieldValue('country_id');

        $fieldsInfo = $this->prepareAddress($zoneId, $countryId);

        $fieldsInfo['address_id'] = $addressId;
        $fieldsInfo['firstname']  = $this->getFieldValue('firstname');
        $fieldsInfo['lastname']   = $this->getFieldValue('lastname');
        $fieldsInfo['company']    = $this->getFieldValue('company');
        $fieldsInfo['company_id'] = $this->getFieldValue('company_id');
        $fieldsInfo['tax_id']     = $this->getFieldValue('tax_id');
        $fieldsInfo['address_1']  = $this->getFieldValue('address_1');
        $fieldsInfo['address_2']  = $this->getFieldValue('address_2');
        $fieldsInfo['postcode']   = $this->getFieldValue('postcode');
        $fieldsInfo['city']       = $this->getFieldValue('city');
        $fieldsInfo['default']    = $this->getFieldValue('default');

        $customInfo = $this->getCustomFields($this->_page, 'address');

        $customInfo['custom_field'] = isset($customInfo['custom_field']['address']) ? $customInfo['custom_field']['address'] : array();

        return array_merge($fullInfo, $customInfo, $fieldsInfo);
    }
}