<?php

if (!(defined("TDM_PROLOG_INCLUDED")) || TDM_PROLOG_INCLUDED !== true) {
	exit();
}
$SEARCH = TDMSingleKey($_REQUEST["article"]);
if (strlen($SEARCH) < 3) {
	TDMRedirect();
}
$TDMCore->DBSelect("TECDOC");
TDMSetTime();
if ($_POST["VIEW"] == "LIST") {
	$_SESSION["TDM_SEACH_DEFAULT_VIEW"] = 2;
}
if ($_POST["VIEW"] == "CARD") {
	$_SESSION["TDM_SEACH_DEFAULT_VIEW"] = 1;
}
if ($_SESSION["TDM_SEACH_DEFAULT_VIEW"] == 0) {
	$_SESSION["TDM_SEACH_DEFAULT_VIEW"] = $arComSets["DEFAULT_VIEW"];
}
if ($_SESSION["TDM_SEACH_DEFAULT_VIEW"] == 1) {
	$arResult["VIEW"] = "CARD";
}
else {
	$arResult["VIEW"] = "LIST";
}
$arResult["LIST_PRICES_LIMIT"] = $arComSets["LIST_PRICES_LIMIT"];
$arResult["ALLOW_ORDER"] = $arComSets["ALLOW_ORDER"];
if ($arResult["LIST_PRICES_LIMIT"] < 3) {
	$arResult["LIST_PRICES_LIMIT"] = 2;
}
if (1 < $TDMCore->UserGroup) {
	$arResult["GROUP_NAME"] = $TDMCore->arPriceType[$TDMCore->UserGroup];
	$arResult["GROUP_DISCOUNT"] = $TDMCore->arPriceDiscount[$TDMCore->UserGroup];
	$arResult["GROUP_VIEW"] = $TDMCore->arPriceView[$TDMCore->UserGroup];
}

$arPARTS_noP = array();
$arResult["ALL_BRANDS"] = array();
$arResult["ALL_BRANDS_LETTERS"] = array();

// PC: Look up for parts in TecDoc by `$SEARCH` article
$rsArts = TDSQL::LookupByNumber($SEARCH);
while ($arArts = $rsArts->Fetch()) {
	$BKEY = TDMSingleKey($arArts["BRAND"], true);
	$AKEY = TDMSingleKey($arArts["ARTICLE"]);
	$arPARTS_noP[$BKEY . $AKEY] = array("PKEY" => $BKEY . $AKEY, "BKEY" => $BKEY, "AKEY" => $AKEY, "AID" => $arArts["AID"], "ARTICLE" => $arArts["ARTICLE"], "BRAND" => $arArts["BRAND"], "TD_NAME" => $arArts["TD_NAME"], "NAME" => $arArts["TD_NAME"], "IMG_SRC" => "/" . TDM_ROOT_DIR . "/media/images/nopic.jpg");
	$arPAIDs_noP[] = $arArts["AID"];
}
TDMSetTime("LookupByNumber(SEARCH) ## Not sorted TecDoc result items count - <b>" . count($arPARTS_noP)) . "</b>";
$TDMCore->DBSelect("MODULE");
$WS = new TDMWebservers();
$arPARTS_noP[$SEARCH] = array("PKEY" => $SEARCH, "BKEY" => "", "AKEY" => $SEARCH, "ARTICLE" => $SEARCH, "BRAND" => "", "TD_NAME" => "", "NAME" => "", "IMG_SRC" => "/" . TDM_ROOT_DIR . "/media/images/nopic.jpg");
$WS->SearchPrices($arPARTS_noP, array(), array("CACHE_MODE" => true, "LINKS_TAKE" => "OFF", "PKEY" => $SEARCH));

// PC: Look up for parts in Prices by `$SEARCH` article
$rsDBPrices = new TDMQuery();
$rsDBPrices->SimpleSelect("SELECT * FROM TDM_PRICES WHERE AKEY=\"" . $SEARCH . "\" " . PcAlternativeArticles::getAlternativeQuery($SEARCH));
while ($arSArts = $rsDBPrices->Fetch()) {
	$arSArts["PKEY"] = $arSArts["BKEY"] . $arSArts["AKEY"];
	if (is_array($arPARTS_noP[$arSArts["PKEY"]])) {
		continue;
	}
	$arPARTS_noP[$arSArts["PKEY"]] = array("PKEY" => $arSArts["PKEY"], "BKEY" => $arSArts["BKEY"], "AKEY" => $arSArts["AKEY"], "ARTICLE" => $arSArts["ARTICLE"], "BRAND" => $arSArts["BRAND"], "TD_NAME" => $arSArts["ALT_NAME"], "NAME" => $arSArts["ALT_NAME"], "IMG_SRC" => "/" . TDM_ROOT_DIR . "/media/images/nopic.jpg");
	continue;
}
unset($arPARTS_noP[$SEARCH]);
if (0 < count($arPARTS_noP)) {
	$arPAIDs_noP_cnt = count($arPAIDs_noP);
	if ($_SESSION["TDM_SEACH_SORTING"] <= 0) {
		$_SESSION["TDM_SEACH_SORTING"] = $arComSets["ITEMS_SORT"];
	}
	if (0 < $_POST["SORT"]) {
		$arAvailSortModes = array(1, 2, 3, 4, 5);
		if (in_array($_POST["SORT"], $arAvailSortModes)) {
			$_SESSION["TDM_SEACH_SORTING"] = $_POST["SORT"];
		}
	}
	$arResult["SORT"] = $_SESSION["TDM_SEACH_SORTING"];
	$arResult["PRICES"] = array();
	$arMinPrices = array();
	$arMinDays = array();

	// PC: Decorate parts that found
	// PC: All selected items filter by existed price. If there are not any record in Prices table for part - skip it
	if (0 < count($arPARTS_noP)) {
		$rsDBPrices = new TDMQuery();
		if ($arResult["GROUP_VIEW"] == 1) {
			$GROUP_FILTER = " AND TYPE=" . $TDMCore->UserGroup;
		}
		foreach ($arPARTS_noP as $arTPart) {
			$PrcsSQL .= $PUnion . "SELECT * FROM TDM_PRICES WHERE BKEY=\"" . $arTPart["BKEY"] . "\" AND AKEY=\"" . $arTPart["AKEY"] . "\" " . $GROUP_FILTER;
			$PUnion = " UNION ";
		}
		switch ($_SESSION["TDM_SEACH_SORTING"]) {
			case 1:
				$PrSort = "PRICE ASC";
				break;

			case 2:
				$PrSort = "PRICE ASC";
				break;

			case 3:
				$PrSort = "PRICE ASC";
				break;

			case 4:
				$PrSort = "DAY ASC";
				break;

			case 5:
				$PrSort = "PRICE ASC";

		}
		$rsDBPrices->SimpleSelect($PrcsSQL . " ORDER BY " . $PrSort);
		$arNmC = array();
		$PrCnt = 0;
		while ($arPrice = $rsDBPrices->Fetch()) {
			if ($arComSets["HIDE_PRICES_NOAVAIL"] == 1 && $arPrice["AVAILABLE"] < 1) {
				continue;
			}
			++$PrCnt;
			$PrPKey = $arPrice["BKEY"] . $arPrice["AKEY"];
			if (trim($arPrice["ALT_NAME"]) != "") {
				if (!(in_array($PrPKey, $arNmC))) {
					$arPARTS_noP[$PrPKey]["NAME"] = "";
					$arNmC[] = $PrPKey;
				}
				if (strlen($arPARTS_noP[$PrPKey]["NAME"]) < strlen($arPrice["ALT_NAME"])) {
					$arPARTS_noP[$PrPKey]["NAME"] = $arPrice["ALT_NAME"];
				}
			}
			$arPrice = TDMFormatPrice($arPrice);
			$arResult["PRICES"][$PrPKey][] = $arPrice;
			++$arPARTS_noP[$PrPKey]["PRICES_COUNT"];
			if ($arMinPrices[$PrPKey] == 0 || $arPrice["PRICE_CONVERTED"] < $arMinPrices[$PrPKey]) {
				$arMinPrices[$PrPKey] = $arPrice["PRICE_CONVERTED"];
			}
			if ($arMinDays[$PrPKey] == "" || $arPrice["DAY"] < $arMinDays[$PrPKey]) {
				$arMinDays[$PrPKey] = $arPrice["DAY"] + 1;
			}
			if (!($arPrice["PRICE_CONVERTED"] < $arResult["AB_MIN_PRICE"][$arPrice["BKEY"]] || $arResult["AB_MIN_PRICE"][$arPrice["BKEY"]] == 0)) {
				continue;
			}
			$arResult["AB_MIN_PRICE"][$arPrice["BKEY"]] = $arPrice["PRICE_CONVERTED"];
			$arResult["AB_MIN_PRICE_F"][$arPrice["BKEY"]] = $arPrice["PRICE_CONVERTED"];
		}
		unset($arNmC);
		TDMSetTime("SelectPricesQuery(PARTS) ## For all selected " . count($arPARTS_noP) . " items  - returned prices count <b>" . $PrCnt . "</b>");
	}
	$TDMCore->DBSelect("TECDOC");
	TDMSetTime("DBSelect(TECDOC)");
	$arPImgAvail = array();
	if (0 < $arPAIDs_noP_cnt) {
		$arPImgAvail = TDSQL::ImagesAvialable($arPAIDs_noP);
		TDMSetTime("ImagesAvialable(arPAIDs_noP) ## All selected " . $arPAIDs_noP_cnt . " items  - returned rows count <b>" . count($arPImgAvail) . "</b>");
	}
	if (0 < $arPAIDs_noP_cnt && $arComSets["SHOW_ITEM_PROPS"] == 1 && $arResult["VIEW"] == "LIST") {
		$rsProps = TDSQL::GetPropertysUnion($arPAIDs_noP);
		TDMSetTime("GetPropertysUnion(PAIDs) ## For items count - " . $arPAIDs_noP_cnt);
		foreach ($arPARTS_noP as $PKey => $arTPart) {
			$ar_AID[$PKey] = $arTPart["AID"];
			$ar_PKEY[$arTPart["AID"]] = $PKey;
		}
		$arHiddenProps = array(1073);
		while ($arProp = $rsProps->Fetch()) {
			if (!($arProp["VALUE"] != "")) {
				continue;
			}
			if ($arProp["CRID"] == 836 || $arProp["CRID"] == 596) {
				$arProp["NAME"] = $arProp["VALUE"];
				$arProp["VALUE"] = "";
			}
			if (!(in_array($arProp["AID"], $ar_AID) && !(isset($arPARTS_noP[$ar_PKEY[$arProp["AID"]]]["PROPS"][$arProp["NAME"]])))) {
				continue;
			}
			++$arPARTS_noP[$ar_PKEY[$arProp["AID"]]]["PROPS_COUNT"];
			$arPARTS_noP[$ar_PKEY[$arProp["AID"]]]["PROPS"][$arProp["NAME"]] = $arProp["VALUE"];
		}
		TDMSetTime("GetPropertysUnion(PAIDs) ## Processing result");
	}
	foreach ($arPARTS_noP as $PKEY => $arTPart) {
		$SortNum = 999999999;
		if ($arResult["SORT"] == 1) {
			if (0 < $arTPart["PRICES_COUNT"]) {
				$SortNum = 999;
			}
		}
		else {
			if ($arResult["SORT"] == 2) {
				if (0 < $arTPart["PRICES_COUNT"]) {
					$SortNum = 999;
				}
				if (in_array($arTPart["AID"], $arPImgAvail)) {
					$SortNum = $SortNum - 100;
				}
				if (0 < $arPARTS_noP[$PKEY]["PROPS_COUNT"]) {
					$SortNum = $SortNum - $arPARTS_noP[$PKEY]["PROPS_COUNT"];
				}
			}
			else {
				if ($arResult["SORT"] == 3) {
					if (0 < $arMinPrices[$PKEY]) {
						$SortNum = $arMinPrices[$PKEY];
					}
				}
				else {
					if ($arResult["SORT"] == 4) {
						if (0 < $arMinDays[$PKEY]) {
							$SortNum = $arMinDays[$PKEY];
						}
					}
					else {
						if ($arResult["SORT"] == 5) {
							if (in_array($arTPart["AID"], $arPImgAvail)) {
								$SortNum = 1;
							}
						}
					}
				}
			}
		}
		$arSortKeys[] = $SortNum;
	}
	array_multisort($arSortKeys, $arPARTS_noP);
	$arPARTS = $arPARTS_noP;
	$arPAIDs = $arPAIDs_noP;
	$arPAIDs_cnt = count($arPAIDs);
	if ($arComSets["SHOW_ITEM_PROPS"] == 1 && $arResult["VIEW"] == "LIST") {
		foreach ($arPARTS as $PKey => $arTPart) {
			if (!(0 < count($arTPart["PROPS"]))) {
				continue;
			}
			$arCProps = $arTPart["PROPS"];
			$arPARTS[$PKey]["PROPS"] = array();
			foreach ($arCProps as $PName => $PValue) {
				$PName = str_replace("/\xd0\xbc\xd0\xbc?", "/\xd0\xbc\xd0\xbc\xc2\xb2", $PName);
				$PName = str_replace("? ", "\xc3\x98 ", $PName);
				if (0 < strpos($PName, "[")) {
					$Dim = substr($PName, strpos($PName, "["));
					$PName = str_replace(" " . $Dim, "", $PName);
					$Dim = str_replace("[", "", $Dim);
					$Dim = str_replace("]", "", $Dim);
					$PValue = $PValue . " " . $Dim;
				}
				$arPARTS[$PKey]["PROPS"][UWord($PName)] = $PValue;
			}
		}
	}
	$arResult["ART_LOGOS"] = array();
	if (0 < $arPAIDs_cnt) {
		$rsImages = TDSQL::GetImagesUnion($arPAIDs);
		TDMSetTime("GetImagesUnion(PAIDs) ## For items count - " . $arPAIDs_cnt);
		while ($arImage = $rsImages->Fetch()) {
			foreach ($arPARTS as $PKey => $arTPart) {
				if (!($arTPart["AID"] == $arImage["AID"] && !(strpos($arImage["PATH"], "0/0.jpg")))) {
					continue;
				}
				if ($arPARTS[$PKey]["IMG_ZOOM"] == "") {
					$arPARTS[$PKey]["IMG_SRC"] = "http://" . TECDOC_FILES_PREFIX . $arImage["PATH"];
					$arPARTS[$PKey]["IMG_ZOOM"] = "Y";
					$arPARTS[$PKey]["IMG_FROM"] = "TecDoc";
				}
				else {
					$arPARTS[$PKey]["IMG_ADDITIONAL"][] = "http://" . TECDOC_FILES_PREFIX . $arImage["PATH"];
				}
				break;
			}
		}
		$rsBLogos = TDSQL::GetArtsLogoUnion($arPAIDs);
		TDMSetTime("GetArtsLogoUnion(PAIDs) ## For items count - " . $arPAIDs_cnt);
		while ($arBLogos = $rsBLogos->Fetch()) {
			$arResult["ART_LOGOS"][$arBLogos["AID"]] = "http://" . TECDOC_FILES_PREFIX . $arBLogos["PATH"];
		}
	}
	foreach ($arPARTS as $arPart) {
		$SEO_PARTS_LIST .= $arPart["BRAND"] . " " . $arPart["ARTICLE"] . ", ";
	}
	$arResult["PARTS"] = array();
	$arResult["PARTS"] = $arPARTS;

	$arResult = (new PcPartsListProcessor($arResult, $TDMCore))->runPostProcessing()->flushPartsWithoutPrices()->getList();

	$arResult["ADDED_PHID"] = TDMPerocessAddToCart($arResult["PRICES"], $arResult["PARTS"]);
}
SetComMeta("SEARCHPARTS", array("PARTS_LIST" => $SEO_PARTS_LIST, "SEARCH_NUMBER" => $SEARCH));