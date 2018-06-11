<?php
/*
@author Dmitriy Kubarev
@link   http://www.simpleopencart.com
@link   http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

class ModelToolSimpleApiMain extends Model {
    static $data = array();

    public function getCustomerGroups($filter = '') {
        $values = array();

        $version = explode('.', VERSION);
        $version = floatval($version[0].$version[1].$version[2].'.'.(isset($version[3]) ? $version[3] : 0));

        $requiredGroupId = 0;

        if ($this->customer->isLogged()) {
            if ($version < 200) {
                $requiredGroupId = $this->customer->getCustomerGroupId();
            } else {
                $requiredGroupId = $this->customer->getGroupId();
            }
        }

        if (file_exists(DIR_APPLICATION . 'model/account/customer_group.php') && $version >= 153) {
            $this->load->model('account/customer_group');

            if (method_exists($this->model_account_customer_group,'getCustomerGroups')) {
                $customerGroups = $this->model_account_customer_group->getCustomerGroups();

                $displayedGroups = $this->config->get('config_customer_group_display');

                if (!empty($displayedGroups) && is_array($displayedGroups)) {
                    foreach ($customerGroups as $customerGroup) {
                        if (in_array($customerGroup['customer_group_id'], $displayedGroups) || $customerGroup['customer_group_id'] == $requiredGroupId) {
                            $values[] = array(
                                'id'   => $customerGroup['customer_group_id'],
                                'text' => $customerGroup['name']
                            );
                        }
                    }
                } else {
                    foreach ($customerGroups as $customerGroup) {
                        $values[] = array(
                            'id'   => $customerGroup['customer_group_id'],
                            'text' => $customerGroup['name']
                        );
                    }
                }
            }
        } else {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group");

            $displayedGroups = $this->config->get('simple_customer_group_display');

            if (!empty($displayedGroups) && is_array($displayedGroups)) {
                foreach ($query->rows as $row) {
                    if (in_array($row['customer_group_id'], $displayedGroups) || $row['customer_group_id'] == $requiredGroupId) {
                        $values[] = array(
                            'id'   => $row['customer_group_id'],
                            'text' => $row['name']
                        );
                    }
                }
            } else {
                foreach ($query->rows as $row) {
                    $values[] = array(
                        'id'   => $row['customer_group_id'],
                        'text' => $row['name']
                    );
                }
            }
        }

        return $values;
    }

    public function getCountries($filter = '') {
        $values = array(
            array(
                'id'   => '', 
                'text' => $this->language->get('text_select')
            )
        );
        
        $this->load->model('localisation/country');

        $results = $this->model_localisation_country->getCountries();

        foreach ($results as $result) {
            $values[] =  array(
                'id'   => $result['country_id'], 
                'text' => $result['name']
            );
        }

        if (!$results) {
            $values[] = array(
                'id'   => 0, 
                'text' => $this->language->get('text_none')
            );
        }

        return $values;
    }

    public function getZones($countryId) {
        $values = array(
            array(
                'id'   => '', 
                'text' => $this->language->get('text_select')
            )
        );
        
        $this->load->model('localisation/zone');

        $results = $this->model_localisation_zone->getZonesByCountryId($countryId);

        foreach ($results as $result) {
            $values[] = array(
                'id'   => $result['zone_id'], 
                'text' => $result['name']
            );
        }

        if (!$results) {
            $values[] = array(
                'id'   => 0, 
                'text' => $this->language->get('text_none')
            );
        }

        return $values;
    }

    public function getCities($zoneId) {
        $values = array(
            array(
                'id'   => '', 
                'text' => $this->language->get('text_select')
            )
        );
        
        $this->load->model('localisation/city');

        $results = $this->model_localisation_city->getCitiesByZoneId($zoneId);

        foreach ($results as $result) {
            $values[] = array(
                'id'   => $result['city_id'], 
                'text' => $result['name']
            );
        }

        if (!$results) {
            $values[] = array(
                'id'   => 0, 
                'text' => $this->language->get('text_none')
            );
        }

        return $values;
    }

    public function getYesNo($filter = '') {
        return array(
            array(
                'id'   => '1', 
                'text' => $this->language->get('text_yes')
            ),
            array(
                'id'   => '0', 
                'text' => $this->language->get('text_no')
            )
        );
    }

    public function checkTelephoneForUniqueness($telephone, $register) {
        $telephone = trim($telephone);

        if ($telephone && (!$this->customer->isLogged() || ($this->customer->isLogged() && $telephone != $this->customer->getTelephone()))) {
            $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE telephone = '" . $this->db->escape($telephone) . "'");

            return $query->row['total'] > 0 ? false : true;
        }

        return true;
    }

    public function checkEmailForUniqueness($email, $register) {
        $email = trim($email);

        if ((!$this->customer->isLogged() && $register && $email) || ($this->customer->isLogged() && $email != $this->customer->getEmail())) {
            $this->load->model('account/customer');
            return $this->model_account_customer->getTotalCustomersByEmail($email) > 0 ? false : true;
        }

        return true;
    }

    public function checkCaptcha($value, $filter) {
        if (isset($this->session->data['captcha']) && $this->session->data['captcha'] != $value) {
            return false;
        }

        return true;
    }

    public function getDefaultGroup() {
        if ($this->customer->isLogged()) {
            $version = explode('.', VERSION);
            $version = floatval($version[0].$version[1].$version[2].'.'.(isset($version[3]) ? $version[3] : 0));

            if ($version < 200) {
                return $this->customer->getCustomerGroupId();
            } else {
                return $this->customer->getGroupId();
            }
        } else {
            return $this->config->get('config_customer_group_id');
        }
    }

    public function getRandomPassword() {
        if (!empty(self::$data['password'])) {
            return self::$data['password'];
        }

        $eng = "qwertyuiopasdfghjklzxcvbnm1234567890";
        $length = 6;
        $password = '';

        while ($length) {
            $password .= $eng[rand(0, 35)];
            $length--;
        }

        self::$data['password'] = $password;

        return $password;
    }

    public function getAddresses() {
        if ($this->customer->isLogged()) {
            $this->load->language('checkout/simplecheckout');
            $this->load->model('account/address');

            $result = array();

            $addresses = $this->model_account_address->getAddresses();
            $format = $this->config->get('simple_address_format');

            $find = array(
                '{firstname}',
                '{lastname}',
                '{company}',
                '{address_1}',
                '{address_2}',
                '{city}',
                '{postcode}',
                '{zone}',
                '{zone_code}',
                '{country}'
            );

            $result[] = array(
                'id'   => 0,
                'text' => $this->language->get('text_add_new')
            );
        
            foreach ($addresses as $address) {
                $replace = array(
                    'firstname' => $address['firstname'],
                    'lastname'  => $address['lastname'],
                    'company'   => $address['company'],
                    'address_1' => $address['address_1'],
                    'address_2' => $address['address_2'],
                    'city'      => $address['city'],
                    'postcode'  => $address['postcode'],
                    'zone'      => $address['zone'],
                    'zone_code' => $address['zone_code'],
                    'country'   => $address['country']  
                );

                $result[] = array(
                    'id'   => $address['address_id'],
                    'text' => str_replace($find, $replace, $format)
                );
            }

            return $result;
        }

        return array();
    }

    public function getDefaultAddressId($filter) {
        if ($this->customer->isLogged()) {
            if ($this->request->server['REQUEST_METHOD'] == 'GET' && isset($this->request->get['address_id'])) {
                return $this->request->get['address_id'];
            }

            return $this->customer->getAddressId();
        }

        return 0;
    }

    public function isDefaultAddress($addressId) {
        if ($this->customer->isLogged()) {
            return $this->customer->getAddressId() == $addressId;
        }

        return false;
    }

    public function getDefaultCountry() {
        return '';
    }

    public function getDefaultZone() {
        return '';
    }

    public function getDefaultCity() {
        return '';
    }

    public function getDefaultPostcode() {
        return '';
    }

    // example of code for getting a mask of field
    public function getTelephoneMask($country) {
        return '99999';
    }
}