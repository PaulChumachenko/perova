<?php
/*
@author Dmitriy Kubarev
@link   http://www.simpleopencart.com
@link   http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

include_once(DIR_SYSTEM . 'library/simple/simple_controller.php');

class ControllerCheckoutSimpleCheckoutSummary extends SimpleController {
    private $_templateData = array();

    public function index() {

        $this->load->library('simple/simplecheckout');

        $this->simplecheckout = SimpleCheckout::getInstance($this->registry);
        $this->simplecheckout->setCurrentBlock('summary');

        $version = $this->simplecheckout->getOpencartVersion();

        $this->language->load('checkout/cart');
        $this->language->load('checkout/simplecheckout');

        $this->load->model('tool/image');

        if ($version >= 200) {
            $this->load->model('tool/upload');
        }

        $this->load->library('encryption');

        $this->_templateData['column_image']                  = $this->language->get('column_image');
        $this->_templateData['column_name']                   = $this->language->get('column_name');
        $this->_templateData['column_model']                  = $this->language->get('column_model');
        $this->_templateData['column_quantity']               = $this->language->get('column_quantity');
        $this->_templateData['column_price']                  = $this->language->get('column_price');
        $this->_templateData['column_total']                  = $this->language->get('column_total');
        $this->_templateData['text_summary']                  = $this->language->get('text_summary');
        $this->_templateData['text_summary_comment']          = $this->language->get('text_summary_comment');
        $this->_templateData['text_summary_shipping_address'] = $this->language->get('text_summary_shipping_address');
        $this->_templateData['text_summary_payment_address']  = $this->language->get('text_summary_payment_address');
        $this->_templateData['text_until_cancelled']          = $this->language->get('text_until_cancelled');
        $this->_templateData['text_freq_day']                 = $this->language->get('text_freq_day');
        $this->_templateData['text_freq_week']                = $this->language->get('text_freq_week');
        $this->_templateData['text_freq_month']               = $this->language->get('text_freq_month');
        $this->_templateData['text_freq_bi_month']            = $this->language->get('text_freq_bi_month');
        $this->_templateData['text_freq_year']                = $this->language->get('text_freq_year');
        $this->_templateData['text_trial']                    = $this->language->get('text_trial');
        $this->_templateData['text_recurring']                = $this->language->get('text_recurring');
        $this->_templateData['text_length']                   = $this->language->get('text_length');
        $this->_templateData['text_recurring_item']           = $this->language->get('text_recurring_item');
        $this->_templateData['text_payment_profile']          = $this->language->get('text_payment_profile');

        $this->_templateData['button_update'] = $this->language->get('button_update');

        $this->_templateData['products'] = array();

        $products = $this->cart->getProducts();

        $points_total = 0;

        foreach ($products as $product) {

            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
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
                    'option'              => $option_data,
                    'quantity'            => $product['quantity'],
                    'stock'               => $product['stock'],
                    'reward'              => ($product['reward'] ? sprintf($this->language->get('text_reward'), $product['reward']) : ''),
                    'price'               => $price,
                    'total'               => $total,
                    'href'                => $this->url->link('product/product', 'product_id=' . $product['product_id']),
                    'recurring'           => $product['recurring'],
                    'profile_name'        => $product['profile_name'],
                    'profile_description' => $profile_description,
                );
            } else {
                $this->_templateData['products'][] = array(
                    'key'      => $product['key'],
                    'thumb'    => $image,
                    'name'     => $product['name'],
                    'model'    => $product['model'],
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

        $points = $this->customer->getRewardPoints();
        $points_to_use = $points > $points_total ? $points_total : $points;
        $this->_templateData['points'] = $points_to_use;

        $this->_templateData['reward']  = isset($this->session->data['reward']) ? $this->session->data['reward'] : '';
        $this->_templateData['voucher'] = isset($this->session->data['voucher']) ? $this->session->data['voucher'] : '';
        $this->_templateData['coupon']  = isset($this->session->data['coupon']) ? $this->session->data['coupon'] : '';

        $payment_address = $this->simplecheckout->getPaymentAddress();

        $payment_address_format = $payment_address['address_format'];

        if (!empty($payment_address_format)) {
            $format = $payment_address_format;
        } else {
            $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
        }

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
            '{country}',
            '{company_id}',
            '{tax_id}'
        );

        $replace = array(
            'firstname'  => $payment_address['firstname'],
            'lastname'   => $payment_address['lastname'],
            'company'    => $payment_address['company'],
            'address_1'  => $payment_address['address_1'],
            'address_2'  => $payment_address['address_2'],
            'city'       => $payment_address['city'],
            'postcode'   => $payment_address['postcode'],
            'zone'       => $payment_address['zone'],
            'zone_code'  => $payment_address['zone_code'],
            'country'    => $payment_address['country'],
            'company_id' => isset($payment_address['company_id']) ? $payment_address['company_id'] : '',
            'tax_id'     => isset($payment_address['tax_id']) ? $payment_address['tax_id'] : ''
        );

        foreach($payment_address as $id => $value) {
            if (isset($replace[$id]) || $id == 'address_format' || $id == 'custom_field') {
                continue;
            }

            if (strpos($id, 'payment_') === 0) {
                $id = str_replace('payment_', '', $id);
                $find[] = '{'.$id.'}';
                $replace[$id] = $value;
            } elseif (strpos($id, 'shipping_') === false) {
                $find[] = '{'.$id.'}';
                $replace[$id] = $value;
            }
        }

        $this->_templateData['summary_payment_address'] = trim(str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format)))));

        $this->_templateData['summary_shipping_address'] = '';

        if ($this->cart->hasShipping()) {
            $shipping_address = $this->simplecheckout->getShippingAddress();

            $shipping_address_format = $shipping_address['address_format'];

            if (!empty($shipping_address_format)) {
                $format = $shipping_address_format;
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
            }

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
                '{country}',
                '{company_id}',
                '{tax_id}'
            );

            $replace = array(
                'firstname'  => $shipping_address['firstname'],
                'lastname'   => $shipping_address['lastname'],
                'company'    => $shipping_address['company'],
                'address_1'  => $shipping_address['address_1'],
                'address_2'  => $shipping_address['address_2'],
                'city'       => $shipping_address['city'],
                'postcode'   => $shipping_address['postcode'],
                'zone'       => $shipping_address['zone'],
                'zone_code'  => $shipping_address['zone_code'],
                'country'    => $shipping_address['country'],
                'company_id' => isset($shipping_address['company_id']) ? $shipping_address['company_id'] : '',
                'tax_id'     => isset($shipping_address['tax_id']) ? $shipping_address['tax_id'] : ''
            );

            foreach($shipping_address as $id => $value) {
                if (isset($replace[$id]) || $id == 'address_format' || $id == 'custom_field') {
                    continue;
                }

                if (strpos($id, 'shipping_') === 0) {
                    $id = str_replace('shipping_', '', $id);
                    $find[] = '{'.$id.'}';
                    $replace[$id] = $value;
                } elseif (strpos($id, 'payment_') === false) {
                    $find[] = '{'.$id.'}';
                    $replace[$id] = $value;
                }
            }

            $this->_templateData['summary_shipping_address'] = trim(str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format)))));
        }

        $this->_templateData['display_header'] = $this->simplecheckout->getSettingValue('displayHeader');
        $this->_templateData['summary_comment'] = $this->simplecheckout->getComment();

        $this->simplecheckout->resetCurrentBlock();

        $this->setOutputContent($this->renderPage('checkout/simplecheckout_summary.tpl', $this->_templateData));
    }
}
