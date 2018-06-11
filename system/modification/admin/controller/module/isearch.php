<?php
class ControllerModuleIsearch extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/isearch');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->document->addStyle('view/stylesheet/isearch.css');
		
        $this->init_search_term_db();

		$this->load->model('setting/setting');
				
		$data['error_warning'] = '';
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
			if (!$this->user->hasPermission('modify', 'module/isearch')) {
				$this->session->data['isearch_error'][] = $this->language->get('error_permission');
				$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			if (!empty($_POST['OaXRyb1BhY2sgLSBDb21'])) {
      	$this->request->post['iSearch']['LicensedOn'] = $_POST['OaXRyb1BhY2sgLSBDb21'];
      }
			
   		if (!empty($_POST['cHRpbWl6YXRpb24ef4fe'])) {
      	$this->request->post['iSearch']['License'] = json_decode(base64_decode($_POST['cHRpbWl6YXRpb24ef4fe']), true);
      }
			
			//{HOOK_CHECK_IF_CACHE_DISABLED}

                $cacheSettings = $this->config->get('isearch');
                $cacheEnable = (empty($cacheSettings['EnableCaching']) || $cacheSettings['EnableCaching'] == 'No') ? true : false;
            

			$this->editSetting('iSearch', $this->request->post);		
			
			$this->cache->delete('product');
			$this->cache->delete('productstandard');
			
			$this->session->data['isearch_success'][] = $this->language->get('text_success');
			
			//{HOOK_REFRESH_CACHE_ON_ENABLE}

                if ($this->request->post['iSearch']['EnableCaching'] == 'Yes' && $cacheEnable) {
                    $this->session->data['refreshInit'] = true;
                }
            
			
			$this->response->redirect($this->url->link('module/isearch', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['error_warning'] = '';
		$data['success_message'] = '';

                $data['token'] = $this->session->data['token'];

                if (!empty($this->session->data['refreshInit'])) {
                    $data['refreshInit'] = true;
                    unset($this->session->data['refreshInit']);
                    unset($this->session->data['iSearchCache']);
                } else {
                    $data['refreshInit'] = false;
                }

                $rows = $this->db->query("SHOW VARIABLES LIKE 'ft_min_word_len'");

                if ($rows->num_rows) {
                    $data['ft_min_word_len'] = $rows->row['Value'];
                } else {
                    $data['ft_min_word_len'] = 'Unknown';
                }

                $rows = $this->db->query("SHOW VARIABLES LIKE 'ft_max_word_len'");
                
                if ($rows->num_rows) {
                    $data['ft_max_word_len'] = $rows->row['Value'];
                } else {
                    $data['ft_max_word_len'] = 'Unknown';
                }

                $this->load->model('module/isearchcorporate');
                $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "isearch_cache` (
                  " . $this->model_module_isearchcorporate->generateCreateStatement() . "
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
                ");
            

        $data['href_clear_suggestions'] = $this->url->link('module/isearch/clear_search_suggestions', 'token=' . $this->session->data['token'], 'SSL');

		if (!empty($this->session->data['isearch_success'])) {
			$data['success_message'] = implode('<br />', $this->session->data['isearch_success']);
			unset($this->session->data['isearch_success']);
		}

		if (!empty($this->session->data['isearch_error'])) {
			$this->error = array_merge($this->error, $this->session->data['isearch_error']);
			unset($this->session->data['isearch_error']);
		}

		if (!empty($this->error)) {
			$data['error_warning'] = implode('<br />', $this->error);
		}
				
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_content_top'] = $this->language->get('text_content_top');
		$data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$data['text_column_left'] = $this->language->get('text_column_left');
		$data['text_column_right'] = $this->language->get('text_column_right');
		
		$data['entry_code'] = $this->language->get('entry_code');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add_module'] = $this->language->get('button_add_module');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['entry_layouts_active'] = $this->language->get('entry_layouts_active');
		$data['entry_highlightcolor'] = $this->language->get('entry_highlightcolor');

		$data['entry_layout_options'] = $this->language->get('entry_layout_options');
		$data['entry_position_options'] = $this->language->get('entry_position_options');
		$data['entry_action_options'] = $this->language->get('entry_action_options');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_position'] = $this->language->get('entry_position');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['text_column_right'] = $this->language->get('text_column_right');
		$data['text_column_left'] = $this->language->get('text_column_left');
		$data['text_content_bottom'] = $this->language->get('text_content_bottom');
		$data['text_content_top'] = $this->language->get('text_content_top');
		
		$data['moduleName'] = 'iSearch';
		$data['moduleNameSmall'] = 'isearch';
		$data['moduleData_module'] = 'isearch_module';
		$data['moduleModel'] = 'model_module_isearch';

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		$data['tabs'] = $this->getTabs();
		
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/isearch', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$data['action'] = $this->url->link('module/isearch', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['iSearch'])) {
			foreach ($this->request->post['iSearch'] as $key => $value) {
				$data['data']['iSearch'][$key] = $this->request->post['iSearch'][$key];
			}
		} else {
			$configValue = $this->config->get('iSearch');
			$data['data']['iSearch'] = $configValue;
		}
		
		$data['modules'] = array();
			
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
			
		$data['moduleData'] = $data['data']['iSearch'];	
		$this->load->model('design/layout');
		
		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');
		$data['column_left'] = $this->load->controller('common/column_left');

		$this->response->setOutput($this->load->view('module/isearch.tpl', $data));
	}
	
	//{HOOK_CACHE_BUILDING_FUNCTIONS}

                public function refreshprogress() {
                    $this->load->model('module/isearchcorporate');

                    $this->model_module_isearchcorporate->refreshprogress();
                }

                public function refreshcache() {
                    if (!$this->user->hasPermission('modify', 'module/isearch')) {
                        $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
                    }
                    $this->session->data['refreshInit'] = true;
                    $this->response->redirect($this->url->link('module/isearch', 'token=' . $this->session->data['token'] . '&tab=2', 'SSL'));
                }

                public function analytics_get() {
                    $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "isearch_analytics` (`id` int(10) unsigned NOT NULL AUTO_INCREMENT, `keyword` varchar(255) NOT NULL, `count` int(11) NOT NULL DEFAULT '1', PRIMARY KEY (`id`), UNIQUE KEY `keyword` (`keyword`), KEY `count` (`count`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");

                    $result = array(
                        'analytics' => array(),
                        'pagination' => ''
                    );
                    
                    $page = !empty($this->request->get['page']) && is_numeric($this->request->get['page']) ? (int)$this->request->get['page'] : 1;
                    
                    $analytics = $this->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM " . DB_PREFIX . "isearch_analytics WHERE 1 ORDER BY id DESC LIMIT " . ($page - 1) * $this->config->get('config_limit_admin') . ", " . $this->config->get('config_limit_admin'));
                    
                    $total = $this->db->query("SELECT FOUND_ROWS() as count");
                    if ($total->num_rows) {
                        $count = $total->row['count'];
                    } else {
                        $count = 0;
                    }
                    
                    if ($analytics->num_rows) {
                        $result['analytics'] = $analytics->rows;
                    }
                    
                    $pagination = new Pagination();
                    $pagination->total = $count;
                    $pagination->page = $page;
                    $pagination->limit = $this->config->get('config_limit_admin');
                    $pagination->text = $this->language->get('text_pagination');
                    $pagination->url = '{page}';
            
                    $result['pagination'] = $pagination->render();

                    $this->response->setOutput(json_encode($result));
                }
                
                public function analytics_delete() {
                    if (!empty($this->request->get['analytics_id']) && is_numeric($this->request->get['analytics_id']) && $this->user->hasPermission('modify', 'module/isearch')) {
                        $this->db->query("DELETE FROM " . DB_PREFIX . "isearch_analytics WHERE id='" . $this->db->escape($this->request->get['analytics_id']) . "'");
                    }
                }
            
	
	public function editSetting($group, $data, $store_id = 0) {
        $group_key = 'group';

        if (VERSION > '2.0.0.0') {
            $group_key = 'code';
        }

		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `" . $group_key . "` = '" . $this->db->escape($group) . "'");

		foreach ($data as $key => $value) {
			
			if (!is_array($value)) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `" . $group_key . "` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
			} else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `" . $group_key . "` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1'");
			}
			
		}
	}

    private function init_search_term_db() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "isearch_terms` (`id` int(11) NOT NULL AUTO_INCREMENT, `term` varchar(255) NOT NULL, `count` int(11) NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `term` (`term`), KEY `count` (`count`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");
    }

    public function clear_search_suggestions() {
        if ($this->validate()) {
            $this->init_search_term_db();
            $this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "isearch_terms`");
            $this->session->data['isearch_success'][] = 'Search terms have been cleared.';
        } else {
            $this->session->data['isearch_error'] = 'No modify permissions for module/isearch.';
        }

        $this->response->redirect($this->url->link('module/isearch', 'token=' . $this->session->data['token'], 'SSL'));
    }

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/isearch')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

	private function getTabs() {

		if (!function_exists('modification_vqmod')) {
	    function modification_vqmod($file) {
	      if (class_exists('VQMod')) {
	        return VQMod::modCheck(modification($file), $file);
	      } else {
	        return modification($file);
	      }
	    }
	  }
		
		$dir = 
			DIR_APPLICATION . 
			'view' . DIRECTORY_SEPARATOR . 
			'template' . DIRECTORY_SEPARATOR . 
			'module' . DIRECTORY_SEPARATOR . 
			'isearch' . DIRECTORY_SEPARATOR;

		$files = scandir($dir);
		$result = array();

		$name_map = array(
			'tab_control_panel.php' => array(
				'name' => 'Control Panel',
				'id' => 'control_panel'
			),
			'tab_improving_results.php' => array(
				'name' => 'Improving Results',
				'id' => 'improving_results'
			),
			'tab_support.php' => array(
				'name' => 'Support',
				'id' => 'support'
			),
		);


                $name_map['tab_analytics.php'] = array(
                    'name' => 'Analytics',
                    'id' => 'analytics'
                );

                $name_map['tab_isearch_corporate.php'] = array(
                    'name' => 'iSearchCorporate',
                    'id' => 'isearch_corporate'
                );
            
		foreach ($files as $file) {
			if (!in_array($file, array_keys($name_map))) continue;

			$result[] = array(
				'file' => modification_vqmod($dir . $file),
				'name' => $name_map[$file]['name'],
				'id' => $name_map[$file]['id']
			);
		}

		return $result;
	}
}
?>