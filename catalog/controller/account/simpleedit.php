<?php
/*
@author	Dmitriy Kubarev
@link	http://www.simpleopencart.com
@link	http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

include_once(DIR_SYSTEM . 'library/simple/simple_controller.php');

class ControllerAccountSimpleEdit extends SimpleController {
    private $_templateData = array();

    public function index($args = null) {

        $this->load->library('simple/simpleedit');

        $this->simpleedit = SimpleEdit::getInstance($this->registry);

        if (!$this->customer->isLogged()) {
            $this->simpleedit->redirect($this->url->link('account/login','','SSL'));
        }

        $this->language->load('account/edit');

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
            'href'      => $this->url->link('account/simpleedit', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->_templateData['action'] = 'index.php?'.$this->simpleedit->getAdditionalParams().'route=account/simpleedit';

        $this->_templateData['heading_title']   = $this->language->get('heading_title');
        $this->_templateData['button_continue'] = $this->language->get('button_continue');

        $this->_templateData['error_warning'] = '';

        $this->simpleedit->updateFields();

        $this->_templateData['rows'] = $this->simpleedit->getRows();

        $this->_templateData['redirect'] = '';

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['submitted']) && $this->validate()) {
            $this->load->model('account/customer');

            $customerInfo = $this->simpleedit->getCustomerInfo();

            $customInfo = $this->simpleedit->getCustomFields('edit', 'customer');
            $customerInfo = array_merge($customInfo, $customerInfo);

            $this->model_account_customer->editCustomer($customerInfo);

            $this->simpleedit->editCustomerGroupId();
            $this->simpleedit->saveCustomFields('edit', 'customer', $this->customer->getId());

            $password = $this->simpleedit->getPassword();

            if ($password) {
                $this->model_account_customer->editPassword($this->customer->getEmail(), $password);
            }

            if ($this->simpleedit->isNewsletterUsed()) {
                $this->model_account_customer->editNewsletter($this->simpleedit->isNewsletterOn());
            }

            $this->session->data['success'] = $this->language->get('text_success');

            if ($this->simpleedit->isAjaxRequest()) {
                $this->_templateData['redirect'] = $this->url->link('account/account', '', 'SSL');
            } else {
                $this->simpleedit->redirect($this->url->link('account/account','','SSL'));
            }
        }

        $this->_templateData['ajax']                = $this->simpleedit->isAjaxRequest();
        $this->_templateData['additional_path']     = $this->simpleedit->getAdditionalPath();
        $this->_templateData['additional_params']   = $this->simpleedit->getAdditionalParams();
        $this->_templateData['scroll_to_error']     = $this->simpleedit->getSettingValue('scrollToError');

        $this->_templateData['javascript_callback'] = $this->simpleedit->getJavascriptCallback();

        $this->_templateData['display_error']       = $this->simpleedit->displayError();

        $this->_templateData['popup']     = !empty($args['popup']) ? true : (isset($this->request->get['popup']) ? true : false);
        $this->_templateData['as_module'] = !empty($args['module']) ? true : (isset($this->request->get['module']) ? true : false);

        $childrens = array();

        if (!$this->simpleedit->isAjaxRequest() && !$this->_templateData['popup'] && !$this->_templateData['as_module']) {
            $childrens = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header',
            );

            $this->_templateData['simple_header'] = $this->simpleedit->getLinkToHeaderTpl();
            $this->_templateData['simple_footer'] = $this->simpleedit->getLinkToFooterTpl();
        }

        $this->setOutputContent($this->renderPage('account/simpleedit.tpl', $this->_templateData, $childrens));
    }

    private function validate() {
        $error = false;

        if (!$this->simpleedit->validateFields()) {
            $error = true;
        }

        return !$error;
    }
}
