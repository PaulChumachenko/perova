<?php

/*
 * CSV Price import/export 4 CLI - v1.0.0 (08/11/2017)
 * Require OpenCart 2.3.0.2
 *
 * Email: support@costaslabs.com
 * Website: http://www.costaslabs.com/
 *
 * Changes:
 * v1.0.0 - first release for OpenCart 2.3.0.2
 *
 */
define('CSVPRICE_PRO_DEBUG', '1');
//define('OPENCART_ADMIN_DIR', '/home/user/www/example.com/admin');
define('OPENCART_ADMIN_DIR', '/home/autopl05/autopartix.com/www/admin/');

$root_dir = realpath(str_replace(array('csvprice_pro_cli.php', 'cli'), array('', ''), dirname(__FILE__)));

// Admin directory
$admin_dir = '';
if (file_exists($root_dir . '/admin/config.php')) {
	$admin_dir = $root_dir . '/admin';
} else {
	foreach (new DirectoryIterator($root_dir) as $dir_info) {
		if (!$dir_info->isDot() && $dir_info->isDir()) {
			$path = $dir_info->getPathname();
			if (file_exists($path . '/config.php')) {
				$admin_dir = $path;
				break;
			}
		}
	}
}

if (!$admin_dir) {
	if (file_exists(OPENCART_ADMIN_DIR . '/config.php')) {
		$admin_dir = OPENCART_ADMIN_DIR;
	}
}
if (!$admin_dir) {
	die("ERROR: cli cannot access to config.php");
}

// Config file
require_once ($admin_dir . '/config.php');

// Get VERSION
$content = @file_get_contents($admin_dir . '/index.php');
preg_match("/define\('VERSION', '([0-9\.]+)/i", $content, $matches);
if (!isset($matches[1])) {
	die("ERROR: cli cannot get index.php");
} else {
	define('OPENCART_VERSION', $matches[1]);
}

if (version_compare(OPENCART_VERSION, '2.3.0.0') >= 0) {
	$fdir = '2302';
} elseif (version_compare(OPENCART_VERSION, '2.2.0.0') <= 0) {
	$fdir = '2000';
} else {
	die("ERROR: cli OpenCart version is not supported");
}
// Startup
if (file_exists($root_dir . '/cli/' . $fdir . '/cli_framework.php')) {
	require_once($root_dir . '/cli/' . $fdir . '/cli_framework.php');
} else {
	die("ERROR: cli error startup cli_framework");
}
?>