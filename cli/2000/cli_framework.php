<?php

/*
 * CSV Price import/export OC2 CLI framework - v1.0.0 (08/11/2017)
 * Require OpenCart 2.0.0.0
 *
 * Email: support@costaslabs.com
 * Website: http://www.costaslabs.com/
 *
 * Changes:
 * v1.0.0 - first release for OpenCart 2.0.0.0
 *
 */

if (!isset($_SERVER['SERVER_PORT'])) {
	$_SERVER['SERVER_PORT'] = 80;
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Config
$config = new Config();
$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);


// Settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");

foreach ($query->rows as $setting) {
	if (!$setting['serialized']) {
		$config->set($setting['key'], $setting['value']);
	} else {
		if (version_compare(OPENCART_VERSION, '2.1.0.0') >= 0) {
			$config->set($setting['key'], json_decode($setting['value'], true));
		} else {
			$config->set($setting['key'], unserialize($setting['value']));
		}
	}
}

// Url
$url = new Url(HTTP_SERVER, $config->get('config_secure') ? HTTPS_SERVER : HTTP_SERVER);
$registry->set('url', $url);

// log File
$log = new Log(date("Y-m-d") . '_csvprice_cli.log');
$registry->set('log', $log);

set_error_handler(function($code, $message, $file, $line) use($log, $config) {
	// error suppressed with @
	if (error_reporting() === 0) {
		return false;
	}

	switch ($code) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$error = 'Notice';
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$error = 'Warning';
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$error = 'Fatal Error';
			break;
		default:
			$error = 'Unknown';
			break;
	}

	if ($config->get('error_display')) {
		echo '<b>' . $error . '</b>: ' . $message . ' in <b>' . $file . '</b> on line <b>' . $line . '</b>';
	}

	if ($config->get('error_log')) {
		$log->write('PHP ' . $error . ':  ' . $message . ' in ' . $file . ' on line ' . $line);
	}

	return true;
});

// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$registry->set('response', $response);

// Cache
$registry->set('cache', new Cache('file'));

// Language
$languages = array();

$query = $db->query("SELECT * FROM `" . DB_PREFIX . "language`");

foreach ($query->rows as $result) {
	$languages[$result['code']] = $result;
}

$config->set('config_language_id', $languages[$config->get('config_admin_language')]['language_id']);

// Language
$language = new Language($languages[$config->get('config_admin_language')]['directory']);
$language->load($languages[$config->get('config_admin_language')]['directory']);
$registry->set('language', $language);

// Default Store
$config->set('config_store_id', 0);

// Event
$event = new Event($registry);
$registry->set('event', $event);

$query = $db->query("SELECT * FROM " . DB_PREFIX . "event");

foreach ($query->rows as $result) {
	$event->register($result['trigger'], $result['action']);
}

// Front Controller
$controller = new Front($registry);

// PC: Flush previous records before new import
$db->query("TRUNCATE TABLE " . DB_PREFIX . "product");

// Router
$action = new Action('csvprice_pro/app_cli');

// Dispatch
$controller->dispatch($action, new Action('error/not_found'));

// Output
$response->output();
?>