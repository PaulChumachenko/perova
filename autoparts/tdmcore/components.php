<?php

function TDMGetComponentsAvail() {
	$SERVER = "gparts.lv";
	$SERVER_NAME = str_replace("www.", "", $_SERVER["SERVER_NAME"]);
	if ($SERVER_NAME != $SERVER) {
		echo("<link rel=\"stylesheet\" href=\"/" . TDM_ROOT_DIR . "/styles.css\" type=\"text/css\">");
		echo("<div style=\"width:980px; margin:0px auto 0px auto;\"><div class=\"tderror\">Error! Online domain authentication is false</div></div>");
		exit();
		return null;
	}
	return 874564;
}

function ErShow() {
	global $TDMCore;
	$TDMCore->ShowErrors();
}

function ErCheck() {
	global $TDMCore;
	if (0 < count($TDMCore->arErrors)) {
		return false;
	}
	return true;
}

function ErAdd($Mes, $Type = 0) {
	global $TDMCore;
	if ($Type == 1) {
		$AddType = Lng("Warning") . "! ";
	}
	else {
		if ($Type == 2) {
			$AddType = Lng("Error") . "! ";
		}
	}
	$TDMCore->arErrors[] = $AddType . $Mes;
}

function NtAdd($Mes) {
	global $TDMCore;
	$TDMCore->arNotes[] = $Mes;
}

function Lng($Code, $Case = 0, $HLight = 1) {
	global $TDMCore;
	$Mes = trim($TDMCore->arLangValues[$Code]);
	if ($Mes == "") {
		if ($HLight == 1) {
			if ($_SESSION["TDM_ISADMIN"] == "Y") {
				return "<a href=\"/" . TDM_ROOT_DIR . "/admin/langs.php?l=" . TDM_LANG . "#" . $Code . "\" class=\"hlight\" title=\"No translation to " . UWord(TDM_LANG) . "! Click to add\">" . $Code . "</a>";
			}
			return "<span class=\"hlight\">" . $Code . "</span>";
		}
		return $Code;
	}
	if ($Case == 1) {
		$Mes = UWord($Mes);
	}
	if ($Case == 2) {
		$Mes = LWord($Mes);
	}
	return $Mes;
}

function Tip($Code, $HLight = 1) {
	global $TDMCore;
	$DTip = $TDMCore->arDescTips[$Code][TDM_LANG];
	if ($DTip == "") {
		$DTip = $TDMCore->arDescTips[$Code]["en"];
	}
	if ($DTip == "" && $HLight == 1) {
		$DTip = "Error! No tip with Code: " . $Code;
	}
	else {
		if ($DTip == "") {
			$DTip = $Code;
		}
	}
	return $DTip;
}

function UWord($W) {
	return mb_strtoupper(mb_substr($W, 0, 1, "UTF-8"), "UTF-8") . mb_substr($W, 1, mb_strlen($W), "UTF-8");
}

function LWord($W) {
	return mb_strtolower(mb_substr($W, 0, 1, "UTF-8"), "UTF-8") . mb_substr($W, 1, mb_strlen($W), "UTF-8");
}

if (!(defined("TDM_PROLOG_INCLUDED")) || TDM_PROLOG_INCLUDED !== true) {
	exit();
}

