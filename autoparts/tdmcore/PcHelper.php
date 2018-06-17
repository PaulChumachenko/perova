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
		if (TDM_ISADMIN){
			echo "<div class=\"imlog\">PC Var Dump:</div>";
			echo "<pre>" . ($varDump ? var_export($var, true) : $var) . "</pre>";
		}
	}

	/**
	 * @return array
	 */
	public static function getTestPartsList()
	{
		return [
			'PRICES' => [
				'ABS8871_LPR 04100ROULUNDS'     => [
					[
						'ID'                 => '429',
						'BKEY'               => 'ABS',
						'AKEY'               => '8871',
						'ARTICLE'            => '8871',
						'ALT_NAME'           => 'Барабанные тормозные колодки',
						'BRAND'              => 'A.B.S.',
						'PRICE'              => '352.00',
						'TYPE'               => '1',
						'CURRENCY'           => 'UAH',
						'DAY'                => '0',
						'AVAILABLE'          => '1',
						'PC_MODEL'           => 'LPR 06830/LPR 04100/LPR 05730',
						'PC_SKU'             => 'LPR 04100',
						'PC_MANUFACTURER'    => 'ROULUNDS',
						'PRICE_CONVERTED'    => 352,
						'CURRENCY_CONVERTED' => 'UAH',
						'PRICE_FORMATED'     => '352',
						'EDIT_LINK'          => '/autoparts/admin/dbedit_price.php?BKEY=ABS&AKEY=8871&TYPE=1&DAY=0&SUPPLIER=inet&STOCK=',
						'PHID'               => '48c82089692006e5b1e33b0775d19c2a',
						'AVAILABLE_NUM'      => 1,
					],
				],
				'FEBIBILSTEIN07013_FE07013FEBI' => [
					[
						'ID'                 => '427',
						'BKEY'               => 'FEBIBILSTEIN',
						'AKEY'               => '07013',
						'ARTICLE'            => '07013',
						'ALT_NAME'           => 'Барабанные тормозные колодки',
						'BRAND'              => 'FEBI BILSTEIN',
						'PRICE'              => '576.00',
						'TYPE'               => '1',
						'CURRENCY'           => 'UAH',
						'DAY'                => '0',
						'AVAILABLE'          => '1',
						'SUPPLIER'           => 'inet',
						'PC_MODEL'           => 'LPR 06830/LPR 04100/LPR 05730',
						'PC_SKU'             => 'FE07013',
						'PC_MANUFACTURER'    => 'FEBI',
						'PRICE_CONVERTED'    => 576,
						'CURRENCY_CONVERTED' => 'UAH',
						'PRICE_FORMATED'     => '576',
						'EDIT_LINK'          => '/autoparts/admin/dbedit_price.php?BKEY=FEBIBILSTEIN&AKEY=07013&TYPE=1&DAY=0&SUPPLIER=inet&STOCK=',
						'PHID'               => '1faaaf2d96e20577c8dbe6d5b1952439',
						'AVAILABLE_NUM'      => 1,
					],
				],
				'LPR06830_C0W009GP'             => [
					[
						'ID'                 => '426',
						'BKEY'               => 'LPR',
						'AKEY'               => '06830',
						'ARTICLE'            => '06830',
						'ALT_NAME'           => 'Барабанные тормозные колодки',
						'BRAND'              => 'LPR',
						'PRICE'              => '352.00',
						'TYPE'               => '1',
						'CURRENCY'           => 'UAH',
						'DAY'                => '0',
						'AVAILABLE'          => '4',
						'SUPPLIER'           => 'inet',
						'PC_MODEL'           => 'LPR 06830/LPR 04100/LPR 05730',
						'PC_SKU'             => 'C0W009',
						'PC_MANUFACTURER'    => 'GP',
						'PRICE_CONVERTED'    => 352,
						'CURRENCY_CONVERTED' => 'UAH',
						'PRICE_FORMATED'     => '352',
						'EDIT_LINK'          => '/autoparts/admin/dbedit_price.php?BKEY=LPR&AKEY=06830&TYPE=1&DAY=0&SUPPLIER=inet&STOCK=',
						'PHID'               => 'd7435cc01f95694652df4fedc9cf708b',
						'AVAILABLE_NUM'      => 0,//4
					],
				],
				'MEYLE1140420601_GS8526MEYLE'   => [
					[
						'ID'                 => '428',
						'BKEY'               => 'MEYLE',
						'AKEY'               => '1140420601',
						'ARTICLE'            => '114 042 0601',
						'ALT_NAME'           => 'Барабанные тормозные колодки',
						'BRAND'              => 'MEYLE',
						'PRICE'              => '429.00',
						'TYPE'               => '1',
						'CURRENCY'           => 'UAH',
						'DAY'                => '0',
						'AVAILABLE'          => '2',
						'SUPPLIER'           => 'inet',
						'PC_MODEL'           => 'LPR 06830/LPR 04100/LPR 05730',
						'PC_SKU'             => 'GS8526',
						'PC_MANUFACTURER'    => 'MEYLE',
						'PRICE_CONVERTED'    => 429,
						'CURRENCY_CONVERTED' => 'UAH',
						'PRICE_FORMATED'     => '429',
						'EDIT_LINK'          => '/autoparts/admin/dbedit_price.php?BKEY=MEYLE&AKEY=1140420601&TYPE=1&DAY=0&SUPPLIER=inet&STOCK=',
						'PHID'               => 'd710bb43572b374ec83aa40180d212c6',
						'AVAILABLE_NUM'      => 0,//2
					],
				],
				'FERODOFSB4182_LPR 06830FRICO'  => [
					[
						'ID'                 => '430',
						'BKEY'               => 'FERODO',
						'AKEY'               => 'FSB4182',
						'ARTICLE'            => 'FSB4182',
						'ALT_NAME'           => 'Барабанные тормозные колодки',
						'BRAND'              => 'FERODO',
						'PRICE'              => '320.00',
						'TYPE'               => '1',
						'CURRENCY'           => 'UAH',
						'DAY'                => '0',
						'AVAILABLE'          => '1',
						'SUPPLIER'           => 'inet',
						'PC_MODEL'           => 'LPR 06830/LPR 04100/LPR 05730',
						'PC_SKU'             => 'LPR 06830',
						'PC_MANUFACTURER'    => 'FRICO',
						'PRICE_CONVERTED'    => 320,
						'CURRENCY_CONVERTED' => 'UAH',
						'PRICE_FORMATED'     => '320',
						'EDIT_LINK'          => '/autoparts/admin/dbedit_price.php?BKEY=FERODO&AKEY=FSB4182&TYPE=1&DAY=0&SUPPLIER=inet&STOCK=',
						'PHID'               => 'a9669119e98a71666b4f01b2f6be54b3',
						'AVAILABLE_NUM'      => 1,
					],
				],
				'LPR05730_RD.2638.GS8092RIDER'  => [
					[
						'ID'                 => '431',
						'BKEY'               => 'LPR',
						'AKEY'               => '05730',
						'ARTICLE'            => '05730',
						'ALT_NAME'           => 'Барабанные тормозные колодки',
						'BRAND'              => 'LPR',
						'PRICE'              => '342.00',
						'TYPE'               => '1',
						'CURRENCY'           => 'UAH',
						'DAY'                => '2',
						'AVAILABLE'          => '3',
						'PC_MODEL'           => 'LPR 06830/LPR 04100/LPR 05730',
						'PC_SKU'             => 'RD.2638.GS8092',
						'PC_MANUFACTURER'    => 'RIDER',
						'PRICE_CONVERTED'    => 342,
						'CURRENCY_CONVERTED' => 'UAH',
						'PRICE_FORMATED'     => '342',
						'EDIT_LINK'          => '/autoparts/admin/dbedit_price.php?BKEY=LPR&AKEY=05730&TYPE=1&DAY=2&SUPPLIER=inet&STOCK=',
						'PHID'               => '4e82d2f10a1b5e6ff99b7655d32e939f',
						'AVAILABLE_NUM'      => 3,
					],
				],
			],
			'PARTS' => [
				[
					'PKEY' => 'ABS8871_LPR 04100ROULUNDS',
					'BKEY' => 'ABS',
					'BRAND' => 'A.B.S.',
					'AKEY' => '8871',
					'ARTICLE' => '8871',
					'LINK_SIDE' => '0',
					'LINK_CODE' => 'main',
					'LINK_INFO' => 'ABS 8871 ↔ FERODO FSB4182',
					'IMG_SRC' => 'http://offliner.gq/TecDoc_2Q2016/images/35/212042.jpg',
					'NAME' => 'Барабанные тормозные колодки',
					'PRICES_COUNT' => 1,
					'AID' => '1347338',
					'TD_NAME' => 'Комплект тормозных колодок',
					'IMG_ZOOM' => 'Y',
					'IMG_FROM' => 'TecDoc',
					'PC_SKU' => 'LPR 04100',
					'PC_MANUFACTURER' => 'ROULUNDS',
				],
				[
					'PKEY' => 'FEBIBILSTEIN07013_FE07013FEBI',
					'BKEY' => 'FEBIBILSTEIN',
					'BRAND' => 'FEBI BILSTEIN',
					'AKEY' => '07013',
					'ARTICLE' => '07013',
					'LINK_SIDE' => '0',
					'LINK_CODE' => 'main',
					'LINK_INFO' => 'FEBIBILSTEIN 07013 ↔ FERODO FSB4182',
					'IMG_SRC' => 'http://offliner.gq/TecDoc_2Q2016/images/38/125950.jpg',
					'NAME' => 'Барабанные тормозные колодки',
					'PRICES_COUNT' => 1,
					'AID' => '1219782',
					'TD_NAME' => 'Комплект тормозных колодок',
					'IMG_ZOOM' => 'Y',
					'IMG_FROM' => 'TecDoc',
					'PC_SKU' => 'FE07013',
					'PC_MANUFACTURER' => 'FEBI',
				],
				[
					'PKEY' => 'LPR06830_C0W009GP',
					'BKEY' => 'LPR',
					'BRAND' => 'LPR',
					'AKEY' => '06830',
					'ARTICLE' => '06830',
					'LINK_SIDE' => '0',
					'LINK_CODE' => 'main',
					'LINK_INFO' => 'LPR 06830 ↔ FERODO FSB4182',
					'IMG_SRC' => 'http://offliner.gq/TecDoc_2Q2016/images/62/339645.jpg',
					'NAME' => 'Барабанные тормозные колодки',
					'PRICES_COUNT' => 1,
					'AID' => '1457353',
					'TD_NAME' => 'Комплект тормозных колодок',
					'IMG_ZOOM' => 'Y',
					'IMG_FROM' => 'TecDoc',
					'IMG_ADDITIONAL' =>
						array (
							0 => 'http://offliner.gq/TecDoc_2Q2016/images/58/339646.jpg',
							1 => 'http://offliner.gq/TecDoc_2Q2016/images/66/339647.jpg',
							2 => 'http://offliner.gq/TecDoc_2Q2016/images/59/339648.jpg',
							3 => 'http://offliner.gq/TecDoc_2Q2016/images/66/339649.jpg',
							4 => 'http://offliner.gq/TecDoc_2Q2016/images/13/294342.jpg',
						),
					'PC_SKU' => 'C0W009',
					'PC_MANUFACTURER' => 'GP',
				],
				[
					'PKEY' => 'MEYLE1140420601_GS8526MEYLE',
					'BKEY' => 'MEYLE',
					'BRAND' => 'MEYLE',
					'AKEY' => '1140420601',
					'ARTICLE' => '1140420601',
					'LINK_SIDE' => '0',
					'LINK_CODE' => 'main',
					'LINK_INFO' => 'MEYLE 1140420601 ↔ FERODO FSB4182',
					'IMG_SRC' => 'http://offliner.gq/TecDoc_2Q2016/images/3/444271.jpg',
					'NAME' => 'Барабанные тормозные колодки',
					'PRICES_COUNT' => 1,
					'AID' => '1720096',
					'TD_NAME' => 'Комплект тормозных колодок',
					'IMG_ZOOM' => 'Y',
					'IMG_FROM' => 'TecDoc',
					'PC_SKU' => 'GS8526',
					'PC_MANUFACTURER' => 'MEYLE',
				],
				[
					'PKEY' => 'FERODOFSB4182_LPR 06830FRICO',
					'BKEY' => 'FERODO',
					'AKEY' => 'FSB4182',
					'ARTICLE' => 'FSB4182',
					'BRAND' => 'FERODO',
					'TD_NAME' => 'Барабанные тормозные колодки',
					'NAME' => 'Барабанные тормозные колодки',
					'IMG_SRC' => '/autoparts/media/images/nopic.jpg',
					'PRICES_COUNT' => 1,
					'PC_SKU' => 'LPR 06830',
					'PC_MANUFACTURER' => 'FRICO',
				],
				[
					'PKEY' => 'LPR05730_RD.2638.GS8092RIDER',
					'BKEY' => 'LPR',
					'BRAND' => 'LPR',
					'AKEY' => '05730',
					'ARTICLE' => '05730',
					'LINK_SIDE' => '0',
					'LINK_CODE' => 'main',
					'LINK_INFO' => 'LPR 05730 ↔ FERODO FSB4182',
					'IMG_SRC' => 'http://offliner.gq/TecDoc_2Q2016/images/62/339645.jpg',
					'NAME' => 'Барабанные тормозные колодки',
					'PRICES_COUNT' => 1,
					'AID' => '1457353',
					'TD_NAME' => 'Комплект тормозных колодок',
					'IMG_ZOOM' => 'Y',
					'IMG_FROM' => 'TecDoc',
					'IMG_ADDITIONAL' => [
						0 => 'http://offliner.gq/TecDoc_2Q2016/images/58/339646.jpg',
						1 => 'http://offliner.gq/TecDoc_2Q2016/images/66/339647.jpg',
						2 => 'http://offliner.gq/TecDoc_2Q2016/images/59/339648.jpg',
						3 => 'http://offliner.gq/TecDoc_2Q2016/images/66/339649.jpg',
						4 => 'http://offliner.gq/TecDoc_2Q2016/images/13/294342.jpg',
					],
					'PC_SKU' => 'RD.2638.GS8092',
					'PC_MANUFACTURER' => 'RIDER',
				],
			]
		];
	}
}