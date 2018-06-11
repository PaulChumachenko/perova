<?if(!defined("TDM_PROLOG_INCLUDED") || TDM_PROLOG_INCLUDED!==true)die();
$arComSets = Array(
	"TEMPLATE" => "ss",
	"DEFAULT_VIEW" => 0,
	"LIST_PRICES_LIMIT" => 3,
	"HIDE_NOPRICES" => 0,
	"HIDE_PRICES_NOAVAIL" => 0,
	"ALLOW_ORDER" => 0,
	"ITEMS_SORT" => 1,
	"SHOW_ITEM_PROPS" => 0,

);
?>

<?if($_SESSION['TDM_CMS_USER_GROUP']===7)$arComSets = Array(
	"TEMPLATE" => "ss",
	"DEFAULT_VIEW" => 0,
	"LIST_PRICES_LIMIT" => 3,
	"HIDE_NOPRICES" => 0,
	"HIDE_PRICES_NOAVAIL" => 0,
	"ALLOW_ORDER" => 0,
	"ITEMS_SORT" => 2,
	"SHOW_ITEM_PROPS" => 1,

);
?>