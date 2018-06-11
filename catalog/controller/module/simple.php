<?php  
/*
@author Dmitriy Kubarev
@link   http://www.simpleopencart.com
@link   http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

include_once(DIR_SYSTEM . 'library/simple/simple_controller.php');

class ControllerModuleSimple extends SimpleController {
    private $_templateData = array();

    protected function index($setting) {
        $route = '';

        if (!empty($setting['page'])) {
            if ($setting['page'] == 'checkout') {
                $route = 'checkout/simplecheckout';
            } elseif ($setting['page'] == 'register') {
                $route = 'account/simpleregister';
            }
        }

        if ($route && empty($setting['scripts'])) {
            $this->_templateData['simple_content'] = $this->getChildController($route, array('module' => true, 'group' => (!empty($setting['settingsId']) ? $setting['settingsId'] : 0)));
            
            $this->setOutputContent($this->renderPage('module/simple.tpl', $this->_templateData));
        } elseif (!empty($setting['scripts'])) {
            if ($route == 'checkout/simplecheckout') {
                $this->load->library('simple/simplecheckout');
                SimpleCheckout::getInstance($this->registry, (!empty($setting['settingsId']) ? $setting['settingsId'] : 0));
            } elseif ($route == 'account/simpleregister') {
                $this->load->library('simple/simpleregister');
                SimpleRegister::getInstance($this->registry);
            }
        }
    }
}
?>