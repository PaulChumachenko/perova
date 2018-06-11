<?php

class ControllerModulesortslimits extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('module/sortslimits');

        $this->document->setTitle('SORTS+');
        
        $this->load->model('setting/setting');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
        	$this->model_setting_setting->editSetting('sortslimits', $this->request->post);
        	$this->session->data['success'] = $this->language->get('text_success');
        	$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        }
        
        
        $text_strings = array(
            'heading_title',
            'text_edit',
        	'text_no',
        	'text_yes',
        	'entry_google',
        	'help_google',
			'entry_yandex',
        	'help_yandex',
        	'button_save',
        	'button_cancel',
			'text_nofollow',
			'text_follow',			
			'entry_follow',
        	'help_follow',
			'entry_description',
        	'help_description',
			'entry_page',
        	'help_page',			
			'header_1',
        	'header_2',
			'entry_pageh1',
        	'help_pageh1',
			'entry_sortslimits_default',
			'entry_sortslimits_default2',
        	'asc',	
			'desc',
        	'name',	
			'price',
        	'rating',			
        	'model',			
			'quantity',
        	'date_added',			
        	'sort_order',		
         );
		 
        foreach ($text_strings as $text) {
            $data[$text] = $this->language->get($text);
        }
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

        $data['token'] = $this->session->data['token'];

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_module'),
            'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('module/sortslimits', 'token=' . $this->session->data['token'], 'SSL'),
        );

		$data['action'] = $this->url->link('module/sortslimits', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        
    	if (isset($this->request->post['sortslimits_default'])) {
			$data['sortslimits_default'] = $this->request->post['sortslimits_default'];
		} else if($this->config->get('sortslimits_default') !== null) {
			$data['sortslimits_default'] = $this->config->get('sortslimits_default');
		}
        else {
        	$data['sortslimits_default'] = 'sort_order';
        }
        
    	if (isset($this->request->post['sortslimits_default2'])) {
			$data['sortslimits_default2'] = $this->request->post['sortslimits_default2'];
		} else if($this->config->get('sortslimits_default2') !== null) {
			$data['sortslimits_default2'] = $this->config->get('sortslimits_default2');
		}
        else {
        	$data['sortslimits_default2'] = 'asc';
        }
		
		if (isset($this->request->post['sortslimits_order_ASC'])) {
			$data['sortslimits_order_ASC'] = $this->request->post['sortslimits_order_ASC'];
		} else if($this->config->get('sortslimits_order_ASC') !== null) {
			$data['sortslimits_order_ASC'] = $this->config->get('sortslimits_order_ASC');
		}
        else {
        	$data['sortslimits_order_ASC'] = 1;
        }
		
		if (isset($this->request->post['sortslimits_order_DESC'])) {
			$data['sortslimits_order_ASC'] = $this->request->post['sortslimits_order_DESC'];
		} else if($this->config->get('sortslimits_order_DESC') !== null) {
			$data['sortslimits_order_DESC'] = $this->config->get('sortslimits_order_DESC');
		}
        else {
        	$data['sortslimits_order_DESC'] = 1;
        }

		if (isset($this->request->post['sortslimits_name_ASC'])) {
			$data['sortslimits_name_ASC'] = $this->request->post['sortslimits_name_ASC'];
		} else if($this->config->get('sortslimits_name_ASC') !== null) {
			$data['sortslimits_name_ASC'] = $this->config->get('sortslimits_name_ASC');
		}
        else {
        	$data['sortslimits_name_ASC'] = 1;
        }
		
		if (isset($this->request->post['sortslimits_name_DESC'])) {
			$data['sortslimits_name_DESC'] = $this->request->post['sortslimits_name_DESC'];
		} else if($this->config->get('sortslimits_name_DESC') !== null) {
			$data['sortslimits_name_DESC'] = $this->config->get('sortslimits_name_DESC');
		}
        else {
        	$data['sortslimits_name_DESC'] = 1;
        }
		
		if (isset($this->request->post['sortslimits_price_ASC'])) {
			$data['sortslimits_price_ASC'] = $this->request->post['sortslimits_price_ASC'];
		} else if($this->config->get('sortslimits_price_ASC') !== null) {
			$data['sortslimits_price_ASC'] = $this->config->get('sortslimits_price_ASC');
		}
        else {
        	$data['sortslimits_price_ASC'] = 1;
        }

		if (isset($this->request->post['sortslimits_price_DESC'])) {
			$data['sortslimits_price_DESC'] = $this->request->post['sortslimits_price_DESC'];
		} else if($this->config->get('sortslimits_price_DESC') !== null) {
			$data['sortslimits_price_DESC'] = $this->config->get('sortslimits_price_DESC');
		}
        else {
        	$data['sortslimits_price_DESC'] = 1;
        }		
		
		if (isset($this->request->post['sortslimits_rating_DESC'])) {
			$data['sortslimits_rating_DESC'] = $this->request->post['sortslimits_rating_DESC'];
		} else if($this->config->get('sortslimits_rating_DESC') !== null) {
			$data['sortslimits_rating_DESC'] = $this->config->get('sortslimits_rating_DESC');
		}
        else {
        	$data['sortslimits_rating_DESC'] = 1;
        }
		
		if (isset($this->request->post['sortslimits_rating_ASC'])) {
			$data['sortslimits_rating_ASC'] = $this->request->post['sortslimits_rating_ASC'];
		} else if($this->config->get('sortslimits_rating_ASC') !== null) {
			$data['sortslimits_rating_ASC'] = $this->config->get('sortslimits_rating_ASC');
		}
        else {
        	$data['sortslimits_rating_ASC'] = 1;
		}
		
		if (isset($this->request->post['sortslimits_model_ASC'])) {
			$data['sortslimits_model_ASC'] = $this->request->post['sortslimits_model_ASC'];
		} else if($this->config->get('sortslimits_model_ASC') !== null) {
			$data['sortslimits_model_ASC'] = $this->config->get('sortslimits_model_ASC');
		}
        else {
        	$data['sortslimits_model_ASC'] = 1;
		}
		
		if (isset($this->request->post['sortslimits_model_DESC'])) {
			$data['sortslimits_model_DESC'] = $this->request->post['sortslimits_model_DESC'];
		} else if($this->config->get('sortslimits_model_DESC') !== null) {
			$data['sortslimits_model_DESC'] = $this->config->get('sortslimits_model_DESC');
		}
        else {
        	$data['sortslimits_model_DESC'] = 1;
		}
		
		if (isset($this->request->post['sortslimits_quantity_ASC'])) {
			$data['sortslimits_quantity_ASC'] = $this->request->post['sortslimits_quantity_ASC'];
		} else if($this->config->get('sortslimits_quantity_ASC') !== null) {
			$data['sortslimits_quantity_ASC'] = $this->config->get('sortslimits_quantity_ASC');
		}
        else {
        	$data['sortslimits_quantity_ASC'] = 1;
        }	
		
		if (isset($this->request->post['sortslimits_quantity_DESC'])) {
			$data['sortslimits_quantity_DESC'] = $this->request->post['sortslimits_quantity_DESC'];
		} else if($this->config->get('sortslimits_quantity_DESC') !== null) {
			$data['sortslimits_quantity_DESC'] = $this->config->get('sortslimits_quantity_DESC');
		}
        else {
        	$data['sortslimits_quantity_DESC'] = 1;
        }	
		
		if (isset($this->request->post['sortslimits_date_added_ASC'])) {
			$data['sortslimits_date_added_ASC'] = $this->request->post['sortslimits_date_added_ASC'];
		} else if($this->config->get('sortslimits_date_added_ASC') !== null) {
			$data['sortslimits_date_added_ASC'] = $this->config->get('sortslimits_date_added_ASC');
		}
        else {
        	$data['sortslimits_date_added_ASC'] = 1;
        }	
		
		if (isset($this->request->post['sortslimits_date_added_DESC'])) {
			$data['sortslimits_date_added_DESC'] = $this->request->post['sortslimits_date_added_DESC'];
		} else if($this->config->get('sortslimits_date_added_DESC') !== null) {
			$data['sortslimits_date_added_DESC'] = $this->config->get('sortslimits_date_added_DESC');
		}
        else {
        	$data['sortslimits_date_added_DESC'] = 1;
        }	
		
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('module/sortslimits.tpl', $data));
    }
    

    protected function validate() {
    	if (!$this->user->hasPermission('modify', 'module/sortslimits')) {
    		$this->error['warning'] = $this->language->get('error_permission');
    	}
    
    	return !$this->error;
    }

    public function uninstall() {
    	$this->load->model('extension/event');
    	$this->model_extension_event->deleteEvent('sortslimits');
    	 
    }
    
}