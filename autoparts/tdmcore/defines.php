<?
if(!defined("TDM_PROLOG_INCLUDED") || TDM_PROLOG_INCLUDED!==true)die();

define('TDM_ROOT_DIR',"autoparts");

if(!defined("TDM_CLI_ROOT_DIR")){
	define('TDM_PATH', $_SERVER['DOCUMENT_ROOT'].'/'.TDM_ROOT_DIR); // Web behaviour
} else {
	define('TDM_PATH', TDM_CLI_ROOT_DIR); // Cli behaviour
}

//define('TDM_UPDATES_SERVER',"http://tecdoc-module.com/");
?>