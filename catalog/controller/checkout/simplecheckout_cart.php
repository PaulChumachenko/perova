<?php
/*
@author    Dmitriy Kubarev
@link    http://www.simpleopencart.com
@link    http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

include_once(DIR_SYSTEM . 'library/simple/simple_controller.php');

class ControllerCheckoutSimpleCheckoutCart extends SimpleController {
    static $error = array();
    static $updated = false;

    private $_templateData = array();

    private function init() {
        $this->load->library('simple/simplecheckout');

        $this->simplecheckout = SimpleCheckout::getInstance($this->registry);
        $this->simplecheckout->setCurrentBlock('cart');

        $this->language->load('checkout/cart');
        $this->language->load('checkout/simplecheckout');
    }

    public function index() {
        if (!self::$updated) {
            $this->update();
        }

        $this->init();

        $version = $this->simplecheckout->getOpencartVersion();

        // stupid hack for opencart > 2.0
        if ($version >= 200) {
            $this->tax = new Tax($this->registry);
            $this->cart = new Cart($this->registry);
        }
        // end

        $this->_templateData['attention'] = '';

        if ($this->config->get('config_customer_price') && !$this->customer->isLogged()) {
            $this->_templateData['attention'] = sprintf($this->language->get('text_login'), $this->url->link('account/login'), $this->url->link('account/simpleregister'));
            $this->simplecheckout->addError();
            $this->simplecheckout->blockOrder();
        }

        $this->_templateData['error_warning'] = '';

        if (isset(self::$error['warning'])) {
            $this->_templateData['error_warning'] = self::$error['warning'];
        }

        if (!$this->cart->hasStock()) {
            if ($this->config->get('config_stock_warning')) {
                $this->_templateData['error_warning'] = $this->language->get('error_stock');
            }
            if (!$this->config->get('config_stock_checkout')) {
                $this->_templateData['error_warning'] = $this->language->get('error_stock');
                $this->simplecheckout->addError();
                $this->simplecheckout->blockOrder();
            }
        }

        $customerGroupId = $this->simplecheckout->getCustomerGroupId();

        $useTotal    = $this->simplecheckout->getSettingValue('useTotal');

        $tmp = $this->simplecheckout->getSettingValue('minAmount');
        $minAmount = !empty($tmp[$customerGroupId]) ? $tmp[$customerGroupId] : 0;

        $tmp = $this->simplecheckout->getSettingValue('maxAmount');
        $maxAmount = !empty($tmp[$customerGroupId]) ? $tmp[$customerGroupId] : 0;

        $tmp = $this->simplecheckout->getSettingValue('minQuantity');
        $minQuantity = !empty($tmp[$customerGroupId]) ? $tmp[$customerGroupId] : 0;

        $tmp = $this->simplecheckout->getSettingValue('maxQuantity');
        $maxQuantity = !empty($tmp[$customerGroupId]) ? $tmp[$customerGroupId] : 0;

        $tmp = $this->simplecheckout->getSettingValue('minWeight');
        $minWeight = !empty($tmp[$customerGroupId]) ? $tmp[$customerGroupId] : 0;

        $tmp = $this->simplecheckout->getSettingValue('maxWeight');
        $maxWeight = !empty($tmp[$customerGroupId]) ? $tmp[$customerGroupId] : 0;

        $cartSubtotal = 0;

        if (!empty($minAmount) || !empty($maxAmount)) {
            if ($useTotal) {
                $cartSubtotal = $this->cart->getTotal();
            } else {
                $cartSubtotal = $this->cart->getSubTotal();
            }
        }

        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $key => $voucher) {
                $cartSubtotal += $voucher['amount'];
            }
        }

        $cartQuantity = $this->cart->countProducts();
        $cartWeight = $this->cart->getWeight();

        $this->_templateData['quantity'] = $cartQuantity;

        if (!empty($minAmount) && $minAmount > $cartSubtotal) {
            $this->simplecheckout->addError();
            $this->simplecheckout->blockOrder();
            $this->_templateData['error_warning'] = sprintf($this->language->get('error_min_amount'),$this->currency->format($minAmount));
        }

        if (!empty($maxAmount) && $maxAmount < $cartSubtotal) {
            $this->simplecheckout->blockOrder();
            $this->simplecheckout->addError();
            $this->_templateData['error_warning'] = sprintf($this->language->get('error_max_amount'),$this->currency->format($maxAmount));
        }

        if (!empty($minQuantity) && $minQuantity > $cartQuantity) {
            $this->simplecheckout->blockOrder();
            $this->simplecheckout->addError();
            $this->_templateData['error_warning'] = sprintf($this->language->get('error_min_quantity'), $minQuantity);
        }

        if (!empty($maxQuantity) && $maxQuantity < $cartQuantity) {
            $this->simplecheckout->addError();
            $this->simplecheckout->blockOrder();
            $this->_templateData['error_warning'] = sprintf($this->language->get('error_max_quantity'), $maxQuantity);
        }

        if (!empty($minWeight) && !empty($cartWeight) && $minWeight > $cartWeight) {
            $this->simplecheckout->blockOrder();
            $this->simplecheckout->addError();
            $this->_templateData['error_warning'] = sprintf($this->language->get('error_min_weight'), $minWeight);
        }

        if (!empty($maxWeight) && !empty($cartWeight) && $maxWeight < $cartWeight) {
            $this->simplecheckout->addError();
            $this->simplecheckout->blockOrder();
            $this->_templateData['error_warning'] = sprintf($this->language->get('error_max_weight'), $maxWeight);
        }

        $this->load->model('tool/image');

        if ($version >= 200) {
            $this->load->model('tool/upload');
        }

        $this->load->library('encryption');

        $this->_templateData['column_image']         = $this->language->get('column_image');
        $this->_templateData['column_name']          = $this->language->get('column_name');
        $this->_templateData['column_model']         = $this->language->get('column_model');
		$this->_templateData['column_manufacturer']  = $this->language->get('column_manufacturer');
        $this->_templateData['column_quantity']      = $this->language->get('column_quantity');
        $this->_templateData['column_price']         = $this->language->get('column_price');
        $this->_templateData['column_total']         = $this->language->get('column_total');
        $this->_templateData['text_until_cancelled'] = $this->language->get('text_until_cancelled');
        $this->_templateData['text_freq_day']        = $this->language->get('text_freq_day');
        $this->_templateData['text_freq_week']       = $this->language->get('text_freq_week');
        $this->_templateData['text_freq_month']      = $this->language->get('text_freq_month');
        $this->_templateData['text_freq_bi_month']   = $this->language->get('text_freq_bi_month');
        $this->_templateData['text_freq_year']       = $this->language->get('text_freq_year');
        $this->_templateData['text_trial']           = $this->language->get('text_trial');
        $this->_templateData['text_recurring']       = $this->language->get('text_recurring');
        $this->_templateData['text_length']          = $this->language->get('text_length');
        $this->_templateData['text_recurring_item']  = $this->language->get('text_recurring_item');
        $this->_templateData['text_payment_profile'] = $this->language->get('text_payment_profile');
        $this->_templateData['text_cart']            = $this->language->get('text_cart');

        $this->_templateData['button_update'] = $this->language->get('button_update');
        $this->_templateData['button_remove'] = $this->language->get('button_remove');

        $this->_templateData['products'] = array();

        $this->_templateData['config_stock_warning'] = $this->config->get('config_stock_warning');
        $this->_templateData['config_stock_checkout'] = $this->config->get('config_stock_checkout');

        $products = $this->cart->getProducts();

        $points_total = 0;

        foreach ($products as $product) {

            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['minimum'] > $product_total) {
                $this->_templateData['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
                $this->simplecheckout->addError();
                $this->simplecheckout->blockOrder();
            }

            $option_data = array();

            foreach ($product['option'] as $option) {
                if ($version >= 200) {
                    if ($option['type'] != 'file') {
                        $value = $option['value'];
                    } else {
                        $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                        if ($upload_info) {
                            $value = $upload_info['name'];
                        } else {
                            $value = '';
                        }
                    }
                } else {
                    if ($option['type'] != 'file') {
                        $value = $option['option_value'];
                    } else {
                        $encryption = new Encryption($this->config->get('config_encryption'));
                        $option_value = $encryption->decrypt($option['option_value']);
                        $filename = substr($option_value, 0, strrpos($option_value, '.'));
                        $value = $filename;
                    }
                }

                $option_data[] = array(
                    'name'  => $option['name'],
                    'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                );
            }

            if ($product['image']) {
                $image_cart_width = $this->config->get('config_image_cart_width');
                $image_cart_width = $image_cart_width ? $image_cart_width : 40;
                $image_cart_height = $this->config->get('config_image_cart_height');
                $image_cart_height = $image_cart_height ? $image_cart_height : 40;
                $image = $this->model_tool_image->resize($product['image'], $image_cart_width, $image_cart_height);
            } else {
                $image = '';
            }

            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $price = false;
            }

            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
            } else {
                $total = false;
            }

            if ($version >= 200) {
                $recurring = '';

                if ($product['recurring']) {
                    $frequencies = array(
                        'day'        => $this->language->get('text_day'),
                        'week'       => $this->language->get('text_week'),
                        'semi_month' => $this->language->get('text_semi_month'),
                        'month'      => $this->language->get('text_month'),
                        'year'       => $this->language->get('text_year'),
                    );

                    if ($product['recurring']['trial']) {
                        $recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
                    }

                    if ($product['recurring']['duration']) {
                        $recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                    } else {
                        $recurring .= sprintf($this->language->get('text_payment_until_canceled_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                    }
                }

                $this->_templateData['products'][] = array(
                    'key'       => $product['key'],
                    'thumb'     => $image,
                    'name'      => $product['name'],
                    'model'     => $product['model'],
					'manufacturer'     => $product['manufacturer'],
                    'option'    => $option_data,
                    'recurring' => $recurring,
                    'quantity'  => $product['quantity'],
                    'stock'     => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
                    'reward'    => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
                    'price'     => $price,
                    'total'     => $total,
                    'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id'])
                );
            } elseif ($version >= 156) {
                $profile_description = '';

                if ($product['recurring']) {
                    $frequencies = array(
                        'day'        => $this->language->get('text_day'),
                        'week'       => $this->language->get('text_week'),
                        'semi_month' => $this->language->get('text_semi_month'),
                        'month'      => $this->language->get('text_month'),
                        'year'       => $this->language->get('text_year'),
                    );

                    if ($product['recurring_trial']) {
                        $recurring_price = $this->currency->format($this->tax->calculate($product['recurring_trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')));
                        $profile_description = sprintf($this->language->get('text_trial_description'), $recurring_price, $product['recurring_trial_cycle'], $frequencies[$product['recurring_trial_frequency']], $product['recurring_trial_duration']) . ' ';
                    }

                    $recurring_price = $this->currency->format($this->tax->calculate($product['recurring_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')));

                    if ($product['recurring_duration']) {
                        $profile_description .= sprintf($this->language->get('text_payment_description'), $recurring_price, $product['recurring_cycle'], $frequencies[$product['recurring_frequency']], $product['recurring_duration']);
                    } else {
                        $profile_description .= sprintf($this->language->get('text_payment_until_canceled_description'), $recurring_price, $product['recurring_cycle'], $frequencies[$product['recurring_frequency']], $product['recurring_duration']);
                    }
                }

                $this->_templateData['products'][] = array(
                    'key'                 => $product['key'],
                    'thumb'               => $image,
                    'name'                => $product['name'],
                    'model'               => $product['model'],
					'manufacturer'        => $product['manufacturer'],
                    'option'              => $option_data,
                    'quantity'            => $product['quantity'],
                    'stock'               => $product['stock'],
                    'reward'              => ($product['reward'] ? sprintf($this->language->get('text_reward'), $product['reward']) : ''),
                    'price'               => $price,
                    'total'               => $total,
                    'href'                => $this->url->link('product/product', 'product_id=' . $product['product_id']),
                    'recurring'           => $product['recurring'],
                    'profile_name'        => isset($product['profile_name']) ? $product['profile_name'] : '',
                    'profile_description' => $profile_description,
                );
            } else {
                $this->_templateData['products'][] = array(
                    'key'      => $product['key'],
                    'thumb'    => $image,
                    'name'     => $product['name'],
                    'model'    => $product['model'],
					'manufacturer'    => $product['manufacturer'],
                    'option'   => $option_data,
                    'quantity' => $product['quantity'],
                    'stock'    => $product['stock'],
                    'reward'   => ($product['reward'] ? sprintf($this->language->get('text_reward'), $product['reward']) : ''),
                    'price'    => $price,
                    'total'    => $total,
                    'href'     => $this->url->link('product/product', 'product_id=' . $product['product_id'])
                );
            }

            if ($product['points']) {
                $points_total += $product['points'];
            }
        }

        // Gift Voucher
        $this->_templateData['vouchers'] = array();

        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $key => $voucher) {
                $this->_templateData['vouchers'][] = array(
                    'key'         => $key,
                    'description' => $voucher['description'],
                    'amount'      => $this->currency->format($voucher['amount'])
                );
            }
        }

        $total_data = array();
        $total = 0;
        $taxes = $this->cart->getTaxes();

        $this->_templateData['modules'] = array();

        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
            $sort_order = array();

            if ($version < 200) {
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

                    $this->_templateData['modules'][$result['code']] = true;
                }
            }

            $sort_order = array();

            foreach ($total_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];

                if (!isset($value['text'])) {
                    $total_data[$key]['text'] = $this->currency->format($value['value']);
                }
            }

            array_multisort($sort_order, SORT_ASC, $total_data);
        }

        $this->_templateData['totals'] = $total_data;

        $this->_templateData['entry_coupon'] = $this->language->get('entry_coupon');
        $this->_templateData['entry_voucher'] = $this->language->get('entry_voucher');

        $points = $this->customer->getRewardPoints();
        $points_to_use = $points > $points_total ? $points_total : $points;
        $this->_templateData['points'] = $points_to_use;

        $this->_templateData['entry_reward'] = sprintf($this->language->get('entry_reward'), $points_to_use);

        $this->_templateData['reward']  = isset($this->session->data['reward']) ? $this->session->data['reward'] : '';
        $this->_templateData['voucher'] = isset($this->session->data['voucher']) ? $this->session->data['voucher'] : '';
        $this->_templateData['coupon']  = isset($this->session->data['coupon']) ? $this->session->data['coupon'] : '';

        $this->_templateData['display_weight'] = $this->simplecheckout->displayWeight();

        if ($this->_templateData['display_weight']) {
            $this->_templateData['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
        }

        $this->_templateData['additional_path'] = $this->simplecheckout->getAdditionalPath();
        $this->_templateData['hide'] = $this->simplecheckout->isBlockHidden();

        $currentTheme = $this->config->get('config_template');

        if ($currentTheme == 'shoppica' || $currentTheme == 'shoppica2') {
            $this->_templateData['cart_total'] = $this->currency->format($total);
        } else {
            $this->_templateData['cart_total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
        }

        $this->_templateData['display_header'] = $this->simplecheckout->getSettingValue('displayHeader');
        $this->_templateData['display_model']  = $this->simplecheckout->getSettingValue('displayModel');
        $this->_templateData['display_error']  = $this->simplecheckout->displayError();
        $this->_templateData['has_error']      = $this->simplecheckout->hasError();

        $this->simplecheckout->resetCurrentBlock();

        $this->setOutputContent($this->renderPage('checkout/simplecheckout_cart.tpl', $this->_templateData));
    }

    public function update() {
        self::$updated = true;

        $this->init();

        if (!isset($this->session->data['vouchers'])) {
            $this->session->data['vouchers'] = array();
        }

        // Update
        if (!empty($this->request->post['quantity'])) {
            $keys =  isset($this->session->data['cart']) ? $this->session->data['cart'] : array();
            foreach ($this->request->post['quantity'] as $key => $value) {
                if (!empty($keys) && array_key_exists($key, $keys)) {
                    $this->cart->update($key, $value);
                }
            }
        }

        // Remove
        if (!empty($this->request->post['remove'])) {
            $this->cart->remove($this->request->post['remove']);
            unset($this->session->data['vouchers'][$this->request->post['remove']]);
        }

        // Coupon
        if (isset($this->request->post['coupon']) && $this->validateCoupon()) {
            $this->session->data['coupon'] = trim($this->request->post['coupon']);
            if ($this->session->data['coupon'] == '') {
                unset($this->session->data['coupon']);
            }
        }

        // Voucher
        if (isset($this->request->post['voucher']) && $this->validateVoucher()) {
            $this->session->data['voucher'] = trim($this->request->post['voucher']);
            if ($this->session->data['voucher'] == '') {
                unset($this->session->data['voucher']);
            }
        }

        if (!empty($this->request->post['quantity']) || !empty($this->request->post['remove']) || !empty($this->request->post['voucher'])) {
            unset($this->session->data['reward']);
        }

        // Reward
        if (isset($this->request->post['reward']) && $this->validateReward()) {
            $this->session->data['reward'] = $this->request->post['reward'];
        }

        if (!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) {
            if (!$this->simplecheckout->isAjaxRequest()) {
                $this->simplecheckout->redirect($this->url->link('checkout/simplecheckout', '', 'SSL'));
            } else {
               $this->simplecheckout->setRedirectUrl($this->url->link('checkout/simplecheckout', '', 'SSL'));
            }
        }

        $this->simplecheckout->resetCurrentBlock();
    }

    private function validateCoupon() {
        $this->load->model('checkout/coupon');

        $error = false;

        if (!empty($this->request->post['coupon'])) {
            $coupon_info = $this->model_checkout_coupon->getCoupon($this->request->post['coupon']);

            if (!$coupon_info) {
                self::$error['warning'] = $this->language->get('error_coupon');
                $error = true;
            }
        }

        return !$error;
    }

    private function validateVoucher() {
        $this->load->model('checkout/voucher');

        $error = false;

        if (!empty($this->request->post['voucher'])) {
            $voucher_info = $this->model_checkout_voucher->getVoucher($this->request->post['voucher']);

            if (!$voucher_info) {
                self::$error['warning'] = $this->language->get('error_voucher');
                $error = true;
            }
        }

        return !$error;
    }

    private function validateReward() {
        $error = false;

        if (!empty($this->request->post['reward'])) {
            $points = $this->customer->getRewardPoints();

            $points_total = 0;

            foreach ($this->cart->getProducts() as $product) {
                if ($product['points']) {
                    $points_total += $product['points'];
                }
            }

            if ($this->request->post['reward'] > $points) {
                self::$error['warning'] = sprintf($this->language->get('error_points'), $this->request->post['reward']);
                $error = true;
            }

            if ($this->request->post['reward'] > $points_total) {
                self::$error['warning'] = sprintf($this->language->get('error_maximum'), $points_total);
                $error = true;
            }
        } else {
            $error = true;
        }

        return !$error;
    }
}
?>