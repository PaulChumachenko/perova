<?php
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

include_once(DIR_SYSTEM . 'library/simple/simple_controller.php');

class ControllerAccountSimpleaddress extends SimpleController {
    private $_templateData = array();

    public function insert($args = null) {

        $this->load->library('simple/simpleaddress');

        $this->simpleaddress = Simpleaddress::getInstance($this->registry);

        if (!$this->customer->isLogged()) {
            $this->simpleaddress->redirect($this->url->link('account/login','','SSL'));
        }

        $this->language->load('account/address');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->_templateData['breadcrumbs'] = array();

        $this->_templateData['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' => false
        );

        $this->_templateData['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_account'),
            'href'      => $this->url->link('account/account', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->_templateData['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('account/address', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->_templateData['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_edit_address'),
            'href'      => $this->url->link('account/simpleaddress/insert', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->_templateData['action'] = 'index.php?'.$this->simpleaddress->getAdditionalParams().'route=account/simpleaddress/insert';

        $this->_templateData['heading_title']   = $this->language->get('heading_title');
        $this->_templateData['button_continue'] = $this->language->get('button_continue');

        $this->_templateData['error_warning'] = '';

        $this->request->get['address_id'] = 0;

        $this->simpleaddress->updateFields();

        $this->_templateData['rows'] = $this->simpleaddress->getRows();
        $this->_templateData['hidden_rows'] = $this->simpleaddress->getHiddenAddressRows();

        $this->_templateData['redirect'] = '';

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['submitted']) && $this->validate()) {
            $this->load->model('account/address');

            $addressInfo = $this->simpleaddress->getAddress();

            $customInfo = $this->simpleaddress->getCustomFields('address', 'address');
            $addressInfo = array_merge($customInfo, $addressInfo);

            $addressId = $this->model_account_address->addAddress($addressInfo);

            $this->simpleaddress->saveCustomFields('address', 'address', $addressId);

            $this->session->data['success'] = $this->language->get('text_insert');

            if ($this->simpleaddress->isAjaxRequest()) {
                $this->_templateData['redirect'] = $this->url->link('account/address', '', 'SSL');
            } else {
                $this->simpleaddress->redirect($this->url->link('account/address','','SSL'));
            }
        }

        $this->_templateData['ajax']                = $this->simpleaddress->isAjaxRequest();
        $this->_templateData['additional_path']     = $this->simpleaddress->getAdditionalPath();
        $this->_templateData['additional_params']   = $this->simpleaddress->getAdditionalParams();
        $this->_templateData['use_autocomplete']    = $this->simpleaddress->getSettingValue('useAutocomplete');
        $this->_templateData['use_google_api']      = $this->simpleaddress->getSettingValue('useGoogleApi');
        $this->_templateData['scroll_to_error']     = $this->simpleaddress->getSettingValue('scrollToError');

        $this->_templateData['javascript_callback'] = $this->simpleaddress->getJavascriptCallback();

        $this->_templateData['display_error']       = $this->simpleaddress->displayError();

        $this->_templateData['popup']     = !empty($args['popup']) ? true : (isset($this->request->get['popup']) ? true : false);
        $this->_templateData['as_module'] = !empty($args['module']) ? true : (isset($this->request->get['module']) ? true : false);

        $childrens = array();

        if (!$this->simpleaddress->isAjaxRequest() && !$this->_templateData['popup'] && !$this->_templateData['as_module']) {
            $childrens = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header',
            );

            $this->_templateData['simple_header'] = $this->simpleaddress->getLinkToHeaderTpl();
            $this->_templateData['simple_footer'] = $this->simpleaddress->getLinkToFooterTpl();
        }

        $this->setOutputContent($this->renderPage('account/simpleaddress.tpl', $this->_templateData, $childrens));
    }

    public function update($args = null) {

        $this->load->library('simple/simpleaddress');

        $this->simpleaddress = Simpleaddress::getInstance($this->registry);

        if (!$this->customer->isLogged()) {
            $this->simpleaddress->redirect($this->url->link('account/login','','SSL'));
        }

        $this->language->load('account/address');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->_templateData['breadcrumbs'] = array();

        $this->_templateData['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' => false
        );

        $this->_templateData['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_account'),
            'href'      => $this->url->link('account/account', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->_templateData['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('account/address', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->_templateData['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_edit_address'),
            'href'      => $this->url->link('account/simpleaddress/update', 'address_id=' . $this->request->get['address_id'], 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->_templateData['action'] = 'index.php?'.$this->simpleaddress->getAdditionalParams().'route=account/simpleaddress/update&address_id=' . $this->request->get['address_id'];

        $this->_templateData['heading_title']   = $this->language->get('heading_title');
        $this->_templateData['button_continue'] = $this->language->get('button_continue');

        $this->_templateData['error_warning'] = '';

        $this->simpleaddress->updateFields();

        $this->_templateData['rows'] = $this->simpleaddress->getRows();
        $this->_templateData['hidden_rows'] = $this->simpleaddress->getHiddenAddressRows();

        $this->_templateData['redirect'] = '';

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['submitted']) && $this->validate()) {
            $this->load->model('account/address');

            $addressInfo = $this->simpleaddress->getAddress();

            $customInfo = $this->simpleaddress->getCustomFields('address', 'address');
            $addressInfo = array_merge($customInfo, $addressInfo);

            $this->model_account_address->editAddress($addressInfo['address_id'], $addressInfo);

            $this->simpleaddress->saveCustomFields('address', 'address', $addressInfo['address_id']);

            // Default Shipping Address
            if (isset($this->session->data['shipping_address_id']) && ($this->request->get['address_id'] == $this->session->data['shipping_address_id'])) {
                $this->session->data['shipping_country_id'] = $addressInfo['country_id'];
                $this->session->data['shipping_zone_id'] = $addressInfo['zone_id'];
                $this->session->data['shipping_postcode'] = $addressInfo['postcode'];

                unset($this->session->data['shipping_method']);
                unset($this->session->data['shipping_methods']);
            }

            // Default Payment Address
            if (isset($this->session->data['payment_address_id']) && ($this->request->get['address_id'] == $this->session->data['payment_address_id'])) {
                $this->session->data['payment_country_id'] = $addressInfo['country_id'];
                $this->session->data['payment_zone_id'] = $addressInfo['zone_id'];

                unset($this->session->data['payment_method']);
                unset($this->session->data['payment_methods']);
            }

            $this->session->data['success'] = $this->language->get('text_update');

            if ($this->simpleaddress->isAjaxRequest()) {
               $this->_templateData['redirect'] = $this->url->link('account/address', '', 'SSL');
            } else {
                $this->simpleaddress->redirect($this->url->link('account/address','','SSL'));
            }
        }

        $this->_templateData['ajax']                = $this->simpleaddress->isAjaxRequest();
        $this->_templateData['additional_path']     = $this->simpleaddress->getAdditionalPath();
        $this->_templateData['additional_params']   = $this->simpleaddress->getAdditionalParams();
        $this->_templateData['use_autocomplete']    = $this->simpleaddress->getSettingValue('useAutocomplete');
        $this->_templateData['use_google_api']      = $this->simpleaddress->getSettingValue('useGoogleApi');
        $this->_templateData['scroll_to_error']     = $this->simpleaddress->getSettingValue('scrollToError');

        $this->_templateData['javascript_callback'] = $this->simpleaddress->getJavascriptCallback();

        $this->_templateData['display_error']       = $this->simpleaddress->displayError();

        $this->_templateData['popup']     = !empty($args['popup']) ? true : (isset($this->request->get['popup']) ? true : false);
        $this->_templateData['as_module'] = !empty($args['module']) ? true : (isset($this->request->get['module']) ? true : false);

        $childrens = array();

        if (!$this->simpleaddress->isAjaxRequest() && !$this->_templateData['popup'] && !$this->_templateData['as_module']) {
            $childrens = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header',
            );

            $this->_templateData['simple_header'] = $this->simpleaddress->getLinkToHeaderTpl();
            $this->_templateData['simple_footer'] = $this->simpleaddress->getLinkToFooterTpl();
        }

        $this->setOutputContent($this->renderPage('account/simpleaddress.tpl', $this->_templateData, $childrens));
    }

    private function validate() {
        $error = false;

        if (!$this->simpleaddress->validateFields()) {
            $error = true;
        }

        return !$error;
    }
}
