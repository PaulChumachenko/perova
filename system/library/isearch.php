<?php
$admin_folder_name = 'admin';

if (empty($argc) || $argc !== 1) exit;
$_GET['route'] = 'module/isearch/refreshprogress';
$_SERVER['REQUEST_METHOD'] = 'GET';
$folder = dirname(dirname(dirname($argv[0])));
chdir($folder . DIRECTORY_SEPARATOR . $admin_folder_name);
ini_set('session.use_cookies', 0);
session_cache_limiter('');
ini_set('max_execution_time', 900);
session_start();
require_once('index.php');
?>