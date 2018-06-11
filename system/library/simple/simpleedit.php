<?php
/*
@author Dmitriy Kubarev
@link   http://www.simpleopencart.com
@link   http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

include_once(DIR_SYSTEM . 'library/simple/simple.php');

class SimpleEdit extends Simple {
    protected static $_instance;

    protected function __construct($registry) {
        $this->setPage('edit');
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

    public function getCustomerInfo() {
        $this->load->model('account/customer');

        $fullInfo = $this->model_account_customer->getCustomer($this->customer->getId());

        if (!is_array($fullInfo)) {
            $fullInfo = array();
        }

        $fieldsInfo = array(
            'customer_id'       => $this->customer->getId(),
            'firstname'         => $this->getFieldValue('firstname'),
            'lastname'          => $this->getFieldValue('lastname'),
            'email'             => $this->getFieldValue('email'),
            'telephone'         => $this->getFieldValue('telephone'),
            'fax'               => $this->getFieldValue('fax'),
            'newsletter'        => $this->getFieldValue('newsletter'),
            'customer_group_id' => $this->getCustomerGroupId()
        );

        $customInfo = $this->getCustomFields($this->_page, 'customer');

        $customInfo['custom_field'] = isset($customInfo['custom_field']['account']) ? $customInfo['custom_field']['account'] : array();

        $fullInfo = array_merge($fullInfo, $customInfo, $fieldsInfo);

        // fix for mijoshop
        unset($fullInfo['password']);

        return $fullInfo;
    }

    public function getPassword() {
        return $this->getFieldValue('password');
    }

    public function isNewsletterUsed() {
        return $this->isFieldUsed('edit', 'newsletter');
    }

    public function isNewsletterOn() {
        return $this->getFieldValue('newsletter');
    }

    public function editCustomerGroupId() {
        if (!empty($this->_displayedRows[$this->_page]) && is_array($this->_displayedRows[$this->_page]) && array_key_exists('field_customer_group_id', $this->_displayedRows[$this->_page])) {
            $customerGroupId = $this->getCustomerGroupId();
            $this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customerGroupId . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
        }
    }
}