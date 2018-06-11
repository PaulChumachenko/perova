<?php

class PcHelper
{
	/**
	 * @param $configPath
	 * @return bool
	 * @throws Exception
	 */
	public static function connectToTecDocDb($configPath)
	{
		if (!(file_exists($configPath)))
			throw new \Exception("[Mail Importer Crontab] Could not load DB config file");

		require_once($configPath);
		$config =& $arTDMConfig;
		
		$S = $config["MODULE_DB_SERVER"];
		$L = $config["MODULE_DB_LOGIN"];
		$P = $config["MODULE_DB_PASS"];
		$DB = $config["MODULE_DB_NAME"];

		$rsSQL = DB_PCONN ? @mysql_pconnect($S, $L, $P) : @mysql_connect($S, $L, $P);
		$Charset = "utf8";

		if ($rsSQL) {
			if (mysql_select_db($DB)) {
				mysql_set_charset($Charset);
				mysql_query("SET NAMES '" . $Charset . "'");
				mysql_query("set character_set_connection=" . $Charset . ";");
				mysql_query("set character_set_database=" . $Charset . ";");
				mysql_query("set character_set_results=" . $Charset . ";");
				mysql_query("set character_set_client=" . $Charset . ";");

				return true;
			}
			throw new \Exception("[Mail Importer Crontab] DB '{$DB}' not exist");
		}
		throw new \Exception("[Mail Importer Crontab] No connection to '{$S}'");
	}

	/**
	 * @param $input
	 * @param $settings
	 * @return string
	 */
	public static function getCustomKey($input, $settings)
	{
		$singleKey = trim($input);
		$singleKey = $singleKey ? $singleKey : 'PC' . time();
		if ($settings["ENCODE"] != "UTF-8") {
			$singleKey = iconv($settings["ENCODE"], "UTF-8//TRANSLIT", $singleKey);
		}
		
		return $singleKey;
	}

	/**
	 * @param $var
	 * @param int $varDump
	 */
	public static function dump($var, $varDump = 0)
	{
		if($varDump)
			var_dump($var);
		else
			echo "<div class=\"imlog\">PC Var Dump:</div><pre>" . $var . "</pre>";
	}
}