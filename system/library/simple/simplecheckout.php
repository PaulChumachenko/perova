<?php
/*
@author Dmitriy Kubarev
@link   http://www.simpleopencart.com
@link   http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

include_once(DIR_SYSTEM . 'library/simple/simple.php');

class SimpleCheckout extends Simple {
    protected static $_instance;

    private $_errors = array();
    private $_blocked = false;
    private $_redirectUrl = '';
    private $_reservedBlockNames = array(
        '{three_column}',
        '{/three_column}',
        '{left_column}',
        '{/left_column}',
        '{right_column}',
        '{/right_column}',
        '{step}',
        '{/step}',
        '{customer}',
        '{payment_address}',
        '{shipping_address}',
        '{cart}',
        '{shipping}',
        '{payment}',
        '{agreement}',
        '{help}',
        '{summary}',
        '{comment}',
        '{payment_form}'
    );

    protected function __construct($registry, $settingsId = 0) {
        $this->setPage('checkout');
        $this->_settingsId = $settingsId;
        parent::__construct($registry);
    }

    public static function getInstance($registry, $settingsId = 0) {
        if (self::$_instance === null) {
            self::$_instance = new self($registry, $settingsId);
        }

        return self::$_instance;
    }

    public function setRedirectUrl($url) {
        $this->_redirectUrl = $url;
    }

    public function getRedirectUrl() {
        return $this->_redirectUrl;
    }

    public function isGuestCheckoutDisabled() {
        return $this->getSettingValueDirectly('checkout', '', 'guestCheckoutDisabled');
    }

    public function clearPreventDeleteFlag() {
        if ($this->request->server['REQUEST_METHOD'] == 'GET' && !isset($this->session->data['order_id']) && isset($this->session->data['prevent_delete'])) {
            unset($this->session->data['prevent_delete']);
        }
    }

    public function loadSimpleSession() {
        $customerFields = array('firstname', 'lastname', 'email', 'telephone', 'fax', 'customer_group_id');
        $addressFields = array('firstname', 'lastname', 'company_id', 'tax_id', 'address_1', 'address_2', 'postcode', 'city', 'country_id', 'zone_id');
        $specialFields = array('postcode', 'country_id', 'zone_id');

        if ($this->request->server['REQUEST_METHOD'] == 'GET') {
            foreach ($customerFields as $field) {
                if (!empty($this->session->data['guest'][$field])) { // && empty($this->session->data['simple']['customer'][$field])) {
                    $this->session->data['simple']['customer'][$field] = $this->session->data['guest'][$field];
                }
            }

            foreach ($addressFields as $field) {
                if (!empty($this->session->data['guest']['payment'][$field])) { //  && empty($this->session->data['simple']['payment_address'][$field])) {
                    $this->session->data['simple']['payment_address'][$field] = $this->session->data['guest']['payment'][$field];
                }
            }

            foreach ($addressFields as $field) {
                if (!empty($this->session->data['guest']['shipping'][$field])) { //  && empty($this->session->data['simple']['shipping_address'][$field])) {
                    $this->session->data['simple']['shipping_address'][$field] = $this->session->data['guest']['shipping'][$field];
                }
            }

            foreach ($specialFields as $field) {
                if (!empty($this->session->data['shipping_'.$field])) { //  && empty($this->session->data['simple']['shipping_address'][$field])) {
                    $this->session->data['simple']['shipping_address'][$field] = $this->session->data['shipping_'.$field];
                }
            }

            foreach ($specialFields as $field) {
                if (!empty($this->session->data['payment_'.$field])) { //  && empty($this->session->data['simple']['payment_address'][$field])) {
                    $this->session->data['simple']['payment_address'][$field] = $this->session->data['payment_'.$field];
                }
            }
        }
    }

    public function loadCookies() {
        if ($this->getSettingValueDirectly('checkout', '', 'useCookies') && $this->request->server['REQUEST_METHOD'] == 'GET' && isset($this->request->cookie['simple'])) {
            $simple = @unserialize(@base64_decode($this->request->cookie['simple']));

            if (is_array($simple)) {
                foreach ($simple as $block => $info) {
                    if (is_array($info)) {
                        foreach ($info as $key => $value) {
                            if (empty($this->session->data['simple'][$block][$key])) {
                                $this->session->data['simple'][$block][$key] = $value;
                            }
                        }
                    }
                }
            }
        }
    }

    public function saveCookies() {
        if ($this->getSettingValueDirectly('checkout', '', 'useCookies') && isset($this->session->data['simple'])) {
            setcookie('simple', base64_encode(serialize($this->session->data['simple'])), time() + 60 * 60 * 24 * 30);
        }
    }

    public function clearSimpleSession() {
        if ($this->request->server['REQUEST_METHOD'] == 'GET' && empty($this->session->data['guest']) && isset($this->session->data['simple'])) {
            unset($this->session->data['simple']);
        }
    }

    public function setPreventDeleteFlag() {
        $order_id = isset($this->session->data['order_id']) ? $this->session->data['order_id'] : 0;
        $this->session->data['prevent_delete'][$order_id] = true;
    }

    public function isCustomerCombinedWithShippingAddress() {
        return $this->getSettingValueDirectly('checkout', 'shipping_address', 'combined') && !$this->getSettingValueDirectly('checkout', 'payment_address', 'combined');
    }

    public function isCustomerCombinedWithPaymentAddress() {
        return !$this->getSettingValueDirectly('checkout', 'shipping_address', 'combined') && $this->getSettingValueDirectly('checkout', 'payment_address', 'combined');
    }

    public function clearOrder() {
        if (isset($this->session->data['order_id']) && !isset($this->session->data['prevent_delete'][$this->session->data['order_id']]) && !isset($this->session->data['prevent_delete'][0])) {
            $order_id = $this->session->data['order_id'];
            $version = $this->getOpencartVersion();

            $order_pending = $this->cache->get('order_pending');

            if (!isset($order_pending)) {
                $query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "order_pending'");
                $order_pending = $query->rows ? true : false;
                $this->cache->set('order_pending', $order_pending);
            }

            $this->db->query("SET SQL_BIG_SELECTS=1");
            $this->db->query("DELETE
                                    `" . DB_PREFIX . "order`,
                                    " . DB_PREFIX . "order_product,
                                    " . DB_PREFIX . "order_history,
                                    " . DB_PREFIX . "order_option,
                                    " . DB_PREFIX . "affiliate_transaction,
                                    " . ($version < 200 ? (DB_PREFIX . "order_download,") : "") . "
                                    " . DB_PREFIX . "order_total"
                                    . ($version >= 152 ? "," . DB_PREFIX . "order_voucher" : "") .
                                    ($version >= 152 && $version < 203 ? "," . DB_PREFIX . "order_fraud" : "") .
                            " FROM
                                `" . DB_PREFIX . "order`
                            LEFT JOIN
                                " . DB_PREFIX . "order_product
                            ON
                                " . DB_PREFIX . "order_product.order_id = `" . DB_PREFIX . "order`.order_id
                            LEFT JOIN
                                " . DB_PREFIX . "order_history
                            ON
                                " . DB_PREFIX . "order_history.order_id = `" . DB_PREFIX . "order`.order_id
                            LEFT JOIN
                                " . DB_PREFIX . "affiliate_transaction
                            ON
                                " . DB_PREFIX . "affiliate_transaction.order_id = `" . DB_PREFIX . "order`.order_id
                            LEFT JOIN
                                " . DB_PREFIX . "order_option
                            ON
                                " . DB_PREFIX . "order_option.order_id = `" . DB_PREFIX . "order`.order_id"
                            . ($version < 200 ? "
                            LEFT JOIN
                                " . DB_PREFIX . "order_download
                            ON
                                " . DB_PREFIX . "order_download.order_id = `" . DB_PREFIX . "order`.order_id" : "")."
                            LEFT JOIN
                                " . DB_PREFIX . "order_total
                            ON
                                " . DB_PREFIX . "order_total.order_id = `" . DB_PREFIX . "order`.order_id "
                            . ($version >= 152 ? "
                            LEFT JOIN
                                " . DB_PREFIX . "order_voucher
                            ON
                                " . DB_PREFIX . "order_voucher.order_id = `" . DB_PREFIX . "order`.order_id" : "")
                            . ($version >= 152 && $version < 203 ? "
                            LEFT JOIN
                                " . DB_PREFIX . "order_fraud
                            ON
                                " . DB_PREFIX . "order_fraud.order_id = `" . DB_PREFIX . "order`.order_id" : "")
                            . ($order_pending ? " LEFT JOIN
                                " . DB_PREFIX . "order_pending
                            ON
                                " . DB_PREFIX . "order_pending.order_id = `" . DB_PREFIX . "order`.order_id" : "") .
                            " WHERE
                                `" . DB_PREFIX . "order`.order_id = '" . (int)$order_id . "'
                            AND
                                `" . DB_PREFIX . "order`.order_status_id = 0");

            if ($this->db->countAffected() > 0) {
                $this->db->query("SET insert_id = " . (int)$order_id);
            }

            unset($this->session->data['order_id']);
        }
    }

    public function getPaymentDisplayType() {
        $type = $this->getSettingValueDirectly('checkout', 'payment', 'displayType');
        return in_array($type, array(1,2)) ? $type : 1;
    }

    public function getShippingDisplayType() {
        $type = $this->getSettingValueDirectly('checkout', 'shipping', 'displayType');
        return in_array($type, array(1,2)) ? $type : 1;
    }

    public function isPaymentBeforeShipping() {
        return $this->getSettingValueDirectly('checkout', '', 'paymentBeforeShipping');
    }

    public function getModules() {
        $replaces = array();
        foreach ($this->_reservedBlockNames as $name) {
            $replaces[$name] = '';
        }

        $template = trim(str_replace($this->_reservedBlockNames, $replaces, $this->getTemplate()), '{}');

        return explode('}{', $template);
    }

    public function isOrderBlocked() {
        return $this->_blocked;
    }

    public function getErrors() {
        return $this->_errors;
    }

    private function getSteps() {
        $search = array('{three_column}',
                        '{/three_column}',
                        '{left_column}',
                        '{/left_column}',
                        '{right_column}',
                        '{/right_column}',
                        '{step}',
                        '{/step}');

        $replace = array('{three_column}' => '',
                        '{/three_column}' => '',
                        '{left_column}' => '',
                        '{/left_column}' => '',
                        '{right_column}' => '',
                        '{/right_column}' => '',
                        '{step}' => '',
                        '{/step}' => '');

        $steps = $this->getSettingValueDirectly('checkout', '', 'steps');

        if (!empty($steps) && is_array($steps)) {
            $result = array();
            foreach ($steps as $key => $info) {
                $countOfBlocks = 0;
                $countOfHiddenBlocks = 0;

                if (!empty($info['template'])) {
                    $template = str_replace($search, $replace, $info['template']);

                    $tmp = explode('{', $template);



                    foreach ($tmp as $block) {
                        if (!$block) {
                            continue;
                        }

                        $countOfBlocks++;

                        $block = trim($block, '{}');

                        if ($this->isBlockHidden($block)) {
                            $countOfHiddenBlocks++;
                        }
                    }
                }

                if ($countOfBlocks > $countOfHiddenBlocks) {
                    $result[$key] = $info;
                }
            }

            $steps = $result;
        }

        return $steps;
    }

    public function getTemplate($full = false) {
        $steps = $full ? $this->getSettingValueDirectly('checkout', '', 'steps') : $this->getSteps();
        $template = '';

        if (!empty($steps) && is_array($steps)) {
            foreach ($steps as $key => $info) {
                if (!empty($info['template'])) {
                    $template .= '{step}'.$info['template'].'{/step}';
                }
            }
        }

        return $template;
    }

    public function hasBlock($name = '') {
        if (!$name) {
            $name = $this->_block;
        }

        return strpos($this->getTemplate(true), '{'.$name.'}') !== false ? true : false;
    }

    public function isBlockHidden($block = '') {
        if (!$block) {
            $block = $this->_block;
        }

        if ($this->hasBlock($block)) {
            $hidden = $this->customer->isLogged() ? $this->getSettingValueDirectly('checkout', $block, 'hideForLogged') : $this->getSettingValueDirectly('checkout', $block, 'hideForGuest');

            if (!$hidden) {
                if ($block == 'shipping_address') {
                    $hideForMethods = $this->getSettingValueDirectly('checkout', $block, 'hideForMethods');

                    if (!empty($hideForMethods) && is_array($hideForMethods)) {
                        $shippingMethod = $this->getShippingMethod();

                        if (!empty($shippingMethod) && !empty($shippingMethod['code'])) {
                            $tmp = explode('.', $shippingMethod['code']);
                            $all = $tmp[0].'.*';

                            if (!empty($hideForMethods[$shippingMethod['code']]) || !empty($hideForMethods[$all])) {
                                $hidden = true;
                            }
                        }
                    }
                } else if ($block == 'payment_address') {
                    $hideForMethods = $this->getSettingValueDirectly('checkout', $block, 'hideForMethods');

                    if (!empty($hideForMethods) && is_array($hideForMethods)) {
                        $paymentMethod = $this->getPaymentMethod();

                        if (!empty($paymentMethod) && !empty($paymentMethod['code']) && !empty($hideForMethods[$paymentMethod['code']])) {
                            $hidden = true;
                        }
                    }
                }
            }
        } else {
            $hidden = true;
        }

        return $hidden ? true : false;
    }

    public function setCurrentBlock($block) {
        $this->_page = 'checkout';
        $this->_block = $block;
    }

    public function resetCurrentBlock() {
        $this->_block = 'common';
    }

    public function addError() {
        if ($this->_block) {
            $this->_errors[] = $this->_block;
        } else {
            $this->_errors[] = 'common';
        }
    }

    public function blockOrder() {
        $this->_blocked = true;
    }

    public function hasError() {
        return in_array($this->_block, $this->_errors) ? true : false;
    }

    public function getBlockStepNumber() {
        $stepsCount = $this->getStepsCount();

        if ($stepsCount == 1) {
            return 1;
        }

        if (!$this->_block || $this->_block == 'common') {
            return $stepsCount-1;
        }

        $steps = $this->getSteps();

        $step = 1;

        if (!empty($steps) && is_array($steps)) {
            foreach ($steps as $key => $info) {
                if (!empty($info['template']) && strpos($info['template'], '{'.$this->_block.'}') !== false) {
                    return $step;
                }
                $step++;
            }
        }

        return $step;
    }

    public function displayError() {
        return (isset($this->request->post['create_order']) || (isset($this->request->post['next_step']) && $this->request->post['next_step'] > $this->getBlockStepNumber())) ? true : false;
    }

    public function canCreateOrder() {
        $asap = false;
        if ($this->getStepsCount() == 1) {
            $asap = $this->customer->isLogged() ? $this->getSettingValueDirectly('checkout', '', 'asapForLogged') : $this->getSettingValueDirectly('checkout', '', 'asapForGuests');
        }

        return $asap || (!$asap && (isset($this->request->post['create_order']) || ($this->getStepsCount() > 1 && isset($this->request->post['next_step']) && $this->request->post['next_step'] == $this->getStepsCount())) ? true : false);
    }

    public function getStepsCount() {
        $steps = $this->getSteps();

        if (!empty($steps) && is_array($steps)) {
            return count($steps);
        }

        return 1;
    }

    public function getStepsNames() {
        $steps = $this->getSteps();

        $result = array();
        $lc = $this->getCurrentLanguageCode();

        if (!empty($steps) && is_array($steps)) {
            foreach ($steps as $key => $info) {
                $label = $key;
                if (!empty($info['label'][$lc])) {
                    $label = $info['label'][$lc];
                }
                $result[] = $label;
            }
        }

        return $result;
    }

    public function setComment($value) {
        $this->_data['comment'] = $value;
    }

    public function getComment() {
        $result = array();

        if (!empty($this->_data['comment'])) {
            $result[] = $this->_data['comment'];
        }

        foreach ($this->_fields as $block => $fields) {
            if (!empty($fields) && is_array($fields)) {
                foreach ($fields as $id => $fieldInfo) {
                    if (empty($fieldInfo['custom']) || !$this->isFieldUsed($block, $fieldInfo['id'])) {
                        continue;
                    }

                    if (!empty($fieldInfo['saveToComment'])) {
                        $value = '';
                        if (in_array($fieldInfo['type'], array('radio','select','checkbox')) && is_array($fieldInfo['values']) && !empty($fieldInfo['values'])) {
                            if (is_array($fieldInfo['value'])) {
                                $tmp = array();
                                foreach ($fieldInfo['values'] as $info) {
                                    if (array_key_exists($info['id'], $fieldInfo['value'])) {
                                        $tmp[] = $info['text'];
                                    }
                                }
                                $value = implode(', ', $tmp);
                            } else {
                                foreach ($fieldInfo['values'] as $info) {
                                    if ($fieldInfo['value'] == $info['id']) {
                                        $value = $info['text'];
                                        break;
                                    }
                                }
                            }
                        } else {
                            $value = $fieldInfo['value'];
                        }
                        if (!empty($value)) {
                            if (!empty($fieldInfo['label'])) {
                                $value = $fieldInfo['label'].': '.$value;
                            }
                            $result[] = $value;
                        }
                    }
                }
            }
        }

        return implode(', ', $result);
    }

    public function getShippingStubs() {
        $methods = $this->getSettingValueDirectly('checkout', 'shipping', 'methods');
        $displayTitles = $this->getSettingValueDirectly('checkout', 'shipping', 'displayTitles');

        $lc = $this->getCurrentLanguageCode();
        $result = array();

        if (empty($methods)) {
            return array();
        }

        foreach($methods as $method) {
            $use = false;
            $result[$method['code']] = array(
                'code'       => $method['code'],
                'title'      => $displayTitles ? (!empty($method['title'][$lc]) ? $method['title'][$lc] : $method['code']) : '',
                'dummy'      => true,
                'sort_order' => isset($method['sortOrder']) ? $method['sortOrder'] : $this->config->get($method['code'].'_sort_order'),
                'quote'      => array()
            );

            if (!empty($method['methods']) && is_array($method['methods'])) {
                foreach ($method['methods'] as $submethod) {
                    if (strpos($submethod['code'], '.*')) {
                        continue;
                    }

                    if (!empty($submethod['display'])) {
                        $use = true;
                        $tmp = explode('.', $submethod['code']);
                        $result[$method['code']]['quote'][$tmp[1]] = array(
                            'code'        => $submethod['code'],
                            'dummy'       => true,
                            'title'       => !empty($submethod['title'][$lc]) ? $submethod['title'][$lc] : $submethod['code'],
                            'description' => !empty($submethod['description'][$lc]) ? $submethod['description'][$lc] : '',
                            'sort_order'  => isset($submethod['sortOrder']) ? $submethod['sortOrder'] : -1,
                            'text'        => ''
                        );
                    }
                }
            }

            if (!$use) {
                unset($result[$method['code']]);
            }
        }

        return $result;
    }

    public function displayShippingMethodForEmptyAddress($code) {
        $methods = $this->getSettingValueDirectly('checkout', 'shipping', 'methods');

        if (!empty($methods) && is_array($methods) && !empty($methods[$code]['wait'])) {
            return false;
        }

        return true;
    }

    public function prepareShippingMethods($quote) {
        $methods       = $this->getSettingValueDirectly('checkout', 'shipping', 'methods');
        $checkedMethod = isset($quote['code']) && !empty($methods[$quote['code']]) ? $methods[$quote['code']] : array();
        $lc            = $this->getCurrentLanguageCode();
        $result        = $quote;
        $displayTitles = $this->getSettingValueDirectly('checkout', 'shipping', 'displayTitles');

        $groupId = $this->getCustomerGroupId();

        $all = '';
        if (!empty($methods) && isset($quote['code'])) {
            $tmp = explode('.', $quote['code']);
            $all = $tmp[0].'.*';

            $checkedMethodForAll = !empty($methods[$quote['code']]['methods'][$all]) ? $methods[$quote['code']]['methods'][$all] : array();

            if (!empty($checkedMethodForAll)) {
                if (empty($checkedMethodForAll['forAllGroups']) && !empty($checkedMethodForAll['forGroups']) && empty($checkedMethodForAll['forGroups'][$groupId])) {
                    return array();
                }

                if (!empty($checkedMethodForAll['hideForStatuses']['guest']) && !$this->customer->isLogged()) {
                    return array();
                }

                if (!empty($checkedMethodForAll['hideForStatuses']['logged']) && $this->customer->isLogged()) {
                    return array();
                }

                if ($this->isPaymentBeforeShipping() && empty($checkedMethodForAll['forAllMethods']) && !empty($checkedMethodForAll['forMethods'])) {
                    $paymentMethod = $this->getPaymentMethod();
                    if (!empty($paymentMethod['code']) && empty($checkedMethodForAll['forMethods'][$paymentMethod['code']])) {
                        return array();
                    }
                }
            }
        }

        if (empty($checkedMethod)) {
            if (!$displayTitles) {
                unset($result['title']);
            }

            return $result;
        }

        if (!empty($checkedMethod['useTitle']) && !empty($checkedMethod['title'][$lc])) {
            $result['title'] = $checkedMethod['title'][$lc];
        }

        if (!is_array($quote['quote'])) {
            return $quote;
        }

        foreach ($quote['quote'] as $code => $info) {
            $checkedSubmethod = !empty($checkedMethod['methods'][$info['code']]) ? $checkedMethod['methods'][$info['code']] : array();

            $tmp = explode('.', $info['code']);
            $all = $tmp[0].'.*';

            if (empty($checkedSubmethod)) {
                continue;
            }

            if (!empty($checkedSubmethod['useTitle']) && !empty($checkedSubmethod['title'][$lc])) {
                $result['quote'][$code]['title'] = $checkedSubmethod['title'][$lc];
            }

            if (!empty($checkedSubmethod['useDescription']) && !empty($checkedSubmethod['description'][$lc])) {
                $result['quote'][$code]['description'] = $checkedSubmethod['description'][$lc];
            }

            if (empty($checkedSubmethod['forAllGroups']) && !empty($checkedSubmethod['forGroups']) && empty($checkedSubmethod['forGroups'][$groupId])) {
                unset($result['quote'][$code]);
            }

            if (!empty($checkedSubmethod['hideForStatuses']['guest']) && !$this->customer->isLogged()) {
                unset($result['quote'][$code]);
            }

            if (!empty($checkedSubmethod['hideForStatuses']['logged']) && $this->customer->isLogged()) {
                unset($result['quote'][$code]);
            }

            if ($this->isPaymentBeforeShipping() && empty($checkedSubmethod['forAllMethods']) && !empty($checkedSubmethod['forMethods'])) {
                $paymentMethod = $this->getPaymentMethod();
                if (!empty($paymentMethod['code']) && empty($checkedSubmethod['forMethods'][$paymentMethod['code']])) {
                    unset($result['quote'][$code]);
                }
            }
        }

        if (empty($result['quote'])) {
            return array();
        }

        if (!$displayTitles) {
            unset($result['title']);
        }

        return $result;
    }

    public function setShippingMethod($method) {
        $this->_data['shipping_method'] = $method;
    }

    public function getShippingMethod() {
        return !empty($this->_data['shipping_method']) ? $this->_data['shipping_method'] : array();
    }

    public function getPaymentStubs() {
        $methods = $this->getSettingValueDirectly('checkout', 'payment', 'methods');
        $lc = $this->getCurrentLanguageCode();
        $result = array();

        if (empty($methods)) {
            return array();
        }

        foreach($methods as $method) {
            if (!empty($method['display'])) {
                $result[$method['code']] = array(
                    'code'        => $method['code'],
                    'title'       => !empty($method['title'][$lc]) ? $method['title'][$lc] : $method['code'],
                    'description' => !empty($method['description'][$lc]) ? $method['description'][$lc] : '',
                    'dummy'       => true,
                    'sort_order'  => isset($method['sortOrder']) ? $method['sortOrder'] : $this->config->get($method['code'].'_sort_order')
                );
            }
        }

        return $result;
    }

    public function displayPaymentMethodForEmptyAddress($code) {
        $methods = $this->getSettingValueDirectly('checkout', 'payment', 'methods');

        if (!empty($methods) && is_array($methods) && !empty($methods[$code]['wait'])) {
            return false;
        }

        return true;
    }

    public function preparePaymentMethod($method) {
        $methods       = $this->getSettingValueDirectly('checkout', 'payment', 'methods');
        $checkedMethod = !empty($methods[$method['code']]) ? $methods[$method['code']] : array();
        $lc            = $this->getCurrentLanguageCode();
        $result        = $method;

        if (empty($checkedMethod)) {
            return $result;
        }

        if (!empty($checkedMethod['useTitle']) && !empty($checkedMethod['title'][$lc])) {
            $result['title'] = $checkedMethod['title'][$lc];
        }

        $groupId = $this->getCustomerGroupId();

        if (!empty($checkedMethod['useDescription']) && !empty($checkedMethod['description'][$lc])) {
            $result['description'] = $checkedMethod['description'][$lc];
        }

        if (empty($checkedMethod['forAllGroups']) && !empty($checkedMethod['forGroups']) && empty($checkedMethod['forGroups'][$groupId])) {
            return array();
        }

        if (!empty($checkedMethod['hideForStatuses']['guest']) && !$this->customer->isLogged()) {
            return array();
        }

        if (!empty($checkedMethod['hideForStatuses']['logged']) && $this->customer->isLogged()) {
            return array();
        }

        if (!$this->isPaymentBeforeShipping() && $this->cart->hasShipping() && empty($checkedMethod['forAllMethods']) && !empty($checkedMethod['forMethods'])) {
            $shippingMethod = $this->getShippingMethod();

            if (!empty($shippingMethod['code'])) {
                $tmp = explode('.', $shippingMethod['code']);
                $all = $tmp[0].'.*';

                if (empty($checkedMethod['forMethods'][$shippingMethod['code']]) && empty($checkedMethod['forMethods'][$all])) {
                    return array();
                }
            }
        }

        return $result;
    }

    public function setPaymentMethod($method) {
        $this->_data['payment_method'] = $method;
    }

    public function getPaymentMethod() {
        return !empty($this->_data['payment_method']) ? $this->_data['payment_method'] : array();
    }

    public function displayWeight() {
        return $this->getSettingValueDirectly('checkout', '', 'displayWeight');
    }

    public function displayAddressSame() {
        return $this->cart->hasShipping() && !$this->isBlockHidden('shipping_address') && !$this->isBlockHidden('payment_address') && $this->getSettingValueDirectly('checkout', 'payment_address', 'displayAddressSame');
    }

    public function isAddressSame() {
        if ($this->displayAddressSame()) {
            if ($this->request->server['REQUEST_METHOD'] == 'POST') {
                return !empty($this->request->post['address_same']) ? true : false;
            } else {
                return $this->getSettingValueDirectly('checkout', 'payment_address', 'addressSameInit');
            }
        } else {
            if ($this->isBlockHidden('shipping_address')) {
                return true;
            } else {
                return false;
            }
        }

        return true;
    }

    public function getCustomerInfo() {
        $block = $this->_block;
        $this->_block = 'customer';

        $this->initCustomerInfoFromDatabase();

        $customerId = $this->customer->isLogged() ? $this->customer->getId() : 0;

        $fullInfo = array();
        if ($customerId) {
            $this->load->model('account/customer');
            $fullInfo = $this->model_account_customer->getCustomer($customerId);
            if (!is_array($fullInfo)) {
                $fullInfo = array();
            }
        }

        $randomPassword = '';

        $this->load->model('tool/simpleapimain');

        if (method_exists($this->model_tool_simpleapimain, 'getRandomPassword')) {
            $randomPassword = $this->model_tool_simpleapimain->getRandomPassword();
        }

        $firstname = $this->getFieldValue('firstname');
        if (!$firstname && !$this->isFieldUsed('customer', 'firstname')) {
            if (!$this->isBlockHidden('payment_address') && $this->isFieldUsed('payment_address', 'firstname')) {
                $tmp = $this->_block;
                $this->_block = 'payment_address';
                $firstname = $this->getFieldValue('firstname');
                $this->_block = $tmp;
            } elseif (!$this->isBlockHidden('shipping_address') && $this->isFieldUsed('shipping_address', 'firstname')) {
                $tmp = $this->_block;
                $this->_block = 'shipping_address';
                $firstname = $this->getFieldValue('firstname');
                $this->_block = $tmp;
            }
        }

        $lastname = $this->getFieldValue('lastname');
        if (!$lastname && !$this->isFieldUsed('customer', 'lastname')) {
            if (!$this->isBlockHidden('payment_address') && $this->isFieldUsed('payment_address', 'lastname')) {
                $tmp = $this->_block;
                $this->_block = 'payment_address';
                $lastname = $this->getFieldValue('lastname');
                $this->_block = $tmp;
            } elseif (!$this->isBlockHidden('shipping_address') && $this->isFieldUsed('shipping_address', 'lastname')) {
                $tmp = $this->_block;
                $this->_block = 'shipping_address';
                $lastname = $this->getFieldValue('lastname');
                $this->_block = $tmp;
            }
        }

        $fieldsInfo = array(
            'customer_id'       => $customerId,
            'firstname'         => $firstname,
            'lastname'          => $lastname,
            'email'             => $this->getFieldValue('email'),
            'telephone'         => $this->getFieldValue('telephone'),
            'fax'               => $this->getFieldValue('fax'),
            'password'          => $this->isFieldUsed('customer', 'password') ? $this->getFieldValue('password') : $randomPassword,
            'newsletter'        => $this->getFieldValue('newsletter'),
            'customer_group_id' => $this->getCustomerGroupId()
        );

        $fieldsInfo['email'] = !empty($fieldsInfo['email']) ? $fieldsInfo['email'] : 'empty'.time().'@localhost';

        $customInfo = $this->getCustomFields(array('customer', 'shipping', 'payment'), 'customer');

        $customInfo['custom_field'] = isset($customInfo['custom_field']['account']) ? $customInfo['custom_field']['account'] : array();

        $fullInfo = array_merge($fullInfo, $customInfo, $fieldsInfo);

        // fix for mijoshop
        if ($this->customer->isLogged()) {
            unset($fullInfo['password']);
        }

        $this->_block = $block;

        return $fullInfo;
    }

    public function setCustomerId($id) {
        $this->replacePostValue('customer', 'customer_id', $id);
    }

    public function getPaymentAddress() {
        if ($this->isBlockHidden('payment_address') && !$this->isBlockHidden('shipping_address')) {
            return $this->getShippingAddress();
        }

        $block = $this->_block;
        $this->_block = 'payment_address';

        $this->initAddressInfoFromDatabase();

        $addressId = $this->getFieldValue('address_id');

        $fullInfo = array();
        if ($addressId) {
            $this->load->model('account/address');
            $fullInfo = $this->model_account_address->getAddress($addressId);
            if (!is_array($fullInfo)) {
                $fullInfo = array();
            }
        }

        $customerInfo = array();
        if (!$this->isFieldUsed('payment_address', 'firstname') || !$this->isFieldUsed('payment_address', 'lastname')) {
            $customerInfo = $this->getCustomerInfo();
        }

        $firstname = $this->getFieldValue('firstname');
        if (/*!$firstname && */!$this->isFieldUsed('payment_address', 'firstname') && !empty($customerInfo['firstname'])) {
            $firstname = $customerInfo['firstname'];
        }

        $lastname = $this->getFieldValue('lastname');
        if (/*!$lastname && */!$this->isFieldUsed('payment_address', 'lastname') && !empty($customerInfo['lastname'])) {
            $lastname = $customerInfo['lastname'];
        }

        $zoneId = $this->getFieldValue('zone_id');
        $countryId = $this->getFieldValue('country_id');

        $fieldsInfo = $this->prepareAddress($zoneId, $countryId);

        $fieldsInfo['address_id'] = $addressId;
        $fieldsInfo['firstname']  = $firstname;
        $fieldsInfo['lastname']   = $lastname;
        $fieldsInfo['company']    = $this->getFieldValue('company');
        $fieldsInfo['company_id'] = $this->getFieldValue('company_id');
        $fieldsInfo['tax_id']     = $this->getFieldValue('tax_id');
        $fieldsInfo['address_1']  = $this->getFieldValue('address_1');
        $fieldsInfo['address_2']  = $this->getFieldValue('address_2');
        $fieldsInfo['postcode']   = $this->getFieldValue('postcode');
        $fieldsInfo['city']       = $this->getFieldValue('city');
        $fieldsInfo['default']    = $this->getFieldValue('default');

        $customInfo = $this->getCustomFields(array('payment_address', 'payment'), 'address');

        $customInfo['custom_field'] = isset($customInfo['custom_field']['address']) ? $customInfo['custom_field']['address'] : array();

        $this->_block = $block;

        return array_merge($fullInfo, $customInfo, $fieldsInfo);
    }

    public function setPaymentAddressId($id) {
        $this->replacePostValue('payment_address', 'address_id', $id);
    }

    public function getShippingAddress() {
        if ($this->simplecheckout->isAddressSame()) {
            return $this->getPaymentAddress();
        }

        $block = $this->_block;
        $this->_block = 'shipping_address';

        $this->initAddressInfoFromDatabase();

        $addressId = $this->getFieldValue('address_id');

        $fullInfo = array();
        if ($addressId) {
            $this->load->model('account/address');
            $fullInfo = $this->model_account_address->getAddress($addressId);
            if (!is_array($fullInfo)) {
                $fullInfo = array();
            }
        }

        $customerInfo = array();
        if (!$this->isFieldUsed('shipping_address', 'firstname') || !$this->isFieldUsed('shipping_address', 'lastname')) {
            $customerInfo = $this->getCustomerInfo();
        }

        $firstname = $this->getFieldValue('firstname');
        if (/*!$firstname && */!$this->isFieldUsed('shipping_address', 'firstname') && !empty($customerInfo['firstname'])) {
            $firstname = $customerInfo['firstname'];
        }

        $lastname = $this->getFieldValue('lastname');
        if (/*!$lastname && */!$this->isFieldUsed('shipping_address', 'lastname') && !empty($customerInfo['lastname'])) {
            $lastname = $customerInfo['lastname'];
        }

        $zoneId = $this->getFieldValue('zone_id');
        $countryId = $this->getFieldValue('country_id');

        $fieldsInfo = $this->prepareAddress($zoneId, $countryId);

        $fieldsInfo['address_id'] = $addressId;
        $fieldsInfo['firstname']  = $firstname;
        $fieldsInfo['lastname']   = $lastname;
        $fieldsInfo['company']    = $this->getFieldValue('company');
        $fieldsInfo['company_id'] = $this->getFieldValue('company_id');
        $fieldsInfo['tax_id']     = $this->getFieldValue('tax_id');
        $fieldsInfo['address_1']  = $this->getFieldValue('address_1');
        $fieldsInfo['address_2']  = $this->getFieldValue('address_2');
        $fieldsInfo['postcode']   = $this->getFieldValue('postcode');
        $fieldsInfo['city']       = $this->getFieldValue('city');
        $fieldsInfo['default']    = $this->getFieldValue('default');

        $customInfo = $this->getCustomFields(array('shipping_address', 'shipping'), 'address');

        $customInfo['custom_field'] = isset($customInfo['custom_field']['address']) ? $customInfo['custom_field']['address'] : array();

        $this->_block = $block;

        return array_merge($fullInfo, $customInfo, $fieldsInfo);
    }

    public function setShippingAddressId($id) {
        $this->replacePostValue('shipping_address', 'address_id', $id);
    }

    public function isPaymentAddressEmpty() {
        return $this->isBlockHidden('payment_address') ? false : $this->_paymentAddressEmpty;
    }

    public function isShippingAddressEmpty() {
        return $this->isAddressSame() ? $this->isPaymentAddressEmpty() : ($this->isBlockHidden('shipping_address') ? false : $this->_shippingAddressEmpty);
    }

    public function exportShippingMethods($quote) {
        //if (!empty($this->session->data['user_id'])) {
            if (empty($quote['code']) || empty($quote['quote'])) {
                return;
            }

            $exported = $this->cache->get('simple_shipping_methods');

            if (empty($exported)) {
                $exported = array();
            }

            if (empty($exported[$quote['code']])) {
                $exported[$quote['code']] = $quote;
            } else {
                foreach ($quote['quote'] as $code => $info) {
                    if (empty($exported[$quote['code']]['quote'][$code])) {
                        $exported[$quote['code']]['quote'][$code] = $info;
                    }
                }
            }

            $this->cache->set('simple_shipping_methods', $exported);
        //}
    }

    public function exportPaymentMethod($method) {
        //if (!empty($this->session->data['user_id'])) {
            if (empty($method['code'])) {
                return;
            }

            $exported = $this->cache->get('simple_payment_methods');

            if (empty($exported)) {
                $exported = array();
            }

            if (empty($exported[$method['code']])) {
                $exported[$method['code']] = $method;
            }

            $this->cache->set('simple_payment_methods', $exported);
        //}
    }

    public function registerCustomer() {
        $block = $this->_block;
        $this->_block = 'customer';
        $value = $this->getFieldValue('register');
        $result = false;
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                if ($v) {
                    $result = true;
                }
            }
        } elseif (!empty($value)) {
            $result = true;
        }
        $this->_block = $block;

        return $result;
    }
}