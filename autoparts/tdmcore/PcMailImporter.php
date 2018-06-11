<?php

class PcMailImporter
{
	protected $host;
	protected $port;
	protected $crypt;
	protected $address;
	protected $password;
	protected $attachmentsDir;

	protected $jobId;
	protected $mailDateSent;
	protected $tdImportConfig;
	protected $tdImportColsConfig;

	protected $ignoredRows;

	/**
	 * @param string $attachmentsDir
	 */
	public function __construct($attachmentsDir)
	{
		// Mailbox settings
		$this->host = 'mail.ukraine.com.ua';
		$this->port = 993;
		$this->crypt = 'ssl'; // ssl or tls
		$this->address = 'import@autopartix.com';
		$this->password = 'xyS2xrT561DC';
		$this->attachmentsDir = $attachmentsDir;

		if (!is_dir($this->attachmentsDir)) mkdir($this->attachmentsDir, 0755, true);
	}

	/**
	 * @param $lastMailDate
	 * @return bool|string
	 */
	public function getLastCsv($lastMailDate)
	{
		$mailbox = new ImapMailbox("{{$this->host}:{$this->port}/service=imap/{$this->crypt}/novalidate-cert}INBOX", $this->address, $this->password, null, 'UTF-8');
		$mailIds = $mailbox->searchMailBox('UNSEEN');
		if(empty($mailIds)) return false;

		$fullPath = '';
		$stop = false;
		$lastMailDate = $lastMailDate ? (new DateTime($lastMailDate))->getTimestamp() : null;

		foreach($mailIds as $mailId){
			if($stop) break;
			$mail = $mailbox->getMail($mailId);
			$this->mailDateSent = $mail->date;
			$currentMailTs = $mail->date ? (new DateTime($mail->date))->getTimestamp() : -1;

			if($lastMailDate === null || $currentMailTs > $lastMailDate){
				$attachments = $mail->getAttachments();
				if($attachments){
					foreach ($attachments as $attach) {
						if($this->isCsvAttachment($attach)){
							$fullPath = $this->saveAttachment($mail, $attach);
							$stop = true;
						}
					}
				}
			}

			$mailbox->markMailAsRead($mailId);
		}

		return $fullPath;
	}

	/**
	 * @param IncomingMailAttachment $attachment
	 * @return bool
	 */
	protected function isCsvAttachment($attachment)
	{
		if(empty($attachment->name)) return false;
		$ext = trim(mb_strtolower(pathinfo($attachment->name, PATHINFO_EXTENSION)));

		return empty($ext) ? false : ($ext === 'csv');
	}

	/**
	 * @param IncomingMail $mail
	 * @param IncomingMailAttachment $attachment
	 * @return string
	 */
	protected function saveAttachment($mail, $attachment)
	{
		$replace = array(
			'/\s/' => '_',
			'/[^0-9a-zA-Z_\.]/' => '',
			'/_+/' => '_',
			'/(^_)|(_$)/' => '',
		);
		//$filename = preg_replace('~[\\\\/]~', '', $mail->id . '_' . $attachment->id . '_' . preg_replace(array_keys($replace), $replace, $attachment->name));
		$filename = 'inet.csv';
		$fullPath = $this->attachmentsDir . DIRECTORY_SEPARATOR . $filename;
		file_put_contents($fullPath, $attachment->contents);

		return $fullPath;
	}

	/**
	 * @return mixed
	 */
	public function getMailDateSent()
	{
		return $this->mailDateSent;
	}

	public function importToTecDoc($csvFilePath, $tdImSuppliersId = 2)
	{
		if(!$this->getTecDocConfig($tdImSuppliersId))
			throw new \Exception("[Mail Importer] Could not load TecDoc Import Config");

		$altArticles = new PcAlternativeArticles();
		$altArticles->flushPrevious();
		mysql_query("TRUNCATE TABLE TDM_PRICES");

		$PriceTime = TDMSetPriceDate();
		$arLogicOps = array("USED", "RESTORED", "DAMAGED", "NORETURN", "COPY", "HOT");
		$arIntvOps = array("SET", "WEIGHT", "PERCENTGIVE", "MINIMUM");
		$arLngs = array("ARTICLE" => 32, "ALT_NAME" => 128, "BRAND" => 32, "SUPPLIER" => 32, "STOCK" => 32, "OPTIONS" => 64, "CODE" => 32);

		foreach ($this->rowGenerator($csvFilePath, $this->tdImportConfig["COLUMN_SEP"]) as $Line => $arCSVrow){
			try {
				$arFields = array();
				foreach ($this->tdImportColsConfig as $FIELD => $NUM) {
					$arFields[$FIELD] = trim($arCSVrow[$NUM]);
				}
				if ($this->tdImportConfig["ARTBRA_SEP"] != "" && $arFields["ARTICLE_BRAND"] != "") {
					$arAB = explode($this->tdImportConfig["ARTBRA_SEP"], $arFields["ARTICLE_BRAND"]);
					if (1 < count($arAB)) {
						if ($this->tdImportConfig["ARTBRA_SIDE"] == 1) {
							$arFields["ARTICLE"] = $arAB[0];
							list(, $arFields["BRAND"]) = $arAB;
						}
						else {
							if ($this->tdImportConfig["ARTBRA_SIDE"] == 2) {
								list(, $arFields["ARTICLE"]) = $arAB;
								$arFields["BRAND"] = $arAB[0];
							}
						}
					}
					unset($arFields["ARTICLE_BRAND"]);
				}
				if ($this->tdImportConfig["ENCODE"] != "UTF-8") {
					if ($arFields["ALT_NAME"] != "") {
						$arFields["ALT_NAME"] = iconv($this->tdImportConfig["ENCODE"], "UTF-8//TRANSLIT", $arFields["ALT_NAME"]);
					}
					if ($arFields["BRAND"] != "") {
						$arFields["BRAND"] = iconv($this->tdImportConfig["ENCODE"], "UTF-8//TRANSLIT", $arFields["BRAND"]);
					}
					if ($arFields["ARTICLE"] != "") {
						$arFields["ARTICLE"] = iconv($this->tdImportConfig["ENCODE"], "UTF-8//TRANSLIT", $arFields["ARTICLE"]);
					}
					if ($arFields["DAY"] != "") {
						$arFields["DAY"] = iconv($this->tdImportConfig["ENCODE"], "UTF-8//TRANSLIT", $arFields["DAY"]);
					}
					if ($arFields["AVAILABLE"] != "") {
						$arFields["AVAILABLE"] = iconv($this->tdImportConfig["ENCODE"], "UTF-8//TRANSLIT", $arFields["AVAILABLE"]);
					}
					if ($arFields["STOCK"] != "") {
						$arFields["STOCK"] = iconv($this->tdImportConfig["ENCODE"], "UTF-8//TRANSLIT", $arFields["STOCK"]);
					}
				}
				$arFields["SUPPLIER"] = $this->tdImportConfig["NAME"];
				$arFields["CODE"] = $this->tdImportConfig["CODE"];
				$arFields["DATE"] = $PriceTime;
				$arFields["TYPE"] = $this->tdImportConfig["PRICE_TYPE"];
				$arFields["PRICE"] = str_replace(" ", "", $arFields["PRICE"]);
				$arFields["PRICE"] = str_replace(",", ".", $arFields["PRICE"]);
				$arFields["PRICE"] = floatval($arFields["PRICE"]);
				if ($arFields["CURRENCY"] == "") {
					$arFields["CURRENCY"] = $this->tdImportConfig["DEF_CURRENCY"];
				}
				if ($arFields["AVAILABLE"] == "") {
					$arFields["AVAILABLE"] = $this->tdImportConfig["DEF_AVAILABLE"];
				}
				if ($arFields["STOCK"] == "") {
					$arFields["STOCK"] = $this->tdImportConfig["DEF_STOCK"];
				}
				if ($arFields["BRAND"] == "") {
					$arFields["BRAND"] = $this->tdImportConfig["DEF_BRAND"];
				}
				foreach ($arLngs as $LField => $Lng) {
					if (!($Lng < mb_strlen($arFields[$LField], "UTF-8"))) {
						continue;
					}
					$arFields[$LField] = mb_substr(trim($arFields[$LField]), 0, $Lng, "UTF-8");
				}

				// PC: Generate new keys if not exist
				$arFields["ARTICLE"] = $arFields["ARTICLE"] ? $arFields["ARTICLE"] : PcHelper::getCustomKey($arCSVrow[7], $this->tdImportConfig);
				$arFields["BRAND"] = $arFields["BRAND"] ? $arFields["BRAND"] : PcHelper::getCustomKey($arCSVrow[9], $this->tdImportConfig);

				$arFields["BKEY"] = TDMSingleKey($arFields["BRAND"], true);
				$arFields["AKEY"] = TDMSingleKey($arFields["ARTICLE"]);
				$arFields["ALT_NAME"] = TDMClearName($arFields["ALT_NAME"]);

				$arOps = array();
				if (isset($arFields["LITERS"])) {
					if ($arFields["LITERS"] != "") {
						$arOps["LITERS"] = floatval($arFields["LITERS"]);
					}
					unset($arFields["LITERS"]);
				}
				foreach ($arIntvOps as $LOp) {
					if (!(isset($arFields[$LOp]))) {
						continue;
					}
					if ($arFields[$LOp] != "") {
						$arOps[$LOp] = intval($arFields[$LOp]);
					}
					unset($arFields[$LOp]);
				}
				foreach ($arLogicOps as $LOp) {
					if (!(isset($arFields[$LOp]))) {
						continue;
					}
					if ($arFields[$LOp] != "") {
						$arOps[$LOp] = 1;
					}
					unset($arFields[$LOp]);
				}

				$arFields["OPTIONS"] = TDMOptionsImplode($arOps, $arFields);
				$arFields["DAY"] = TDMOnlyNumbers($arFields["DAY"]);
				$arFields["AVAILABLE"] = TDMOnlyNumbers($arFields["AVAILABLE"]);
				if (9999 < $arFields["AVAILABLE"]) {
					$arFields["AVAILABLE"] = 9999;
				}
				if ($this->tdImportConfig["DAY_ADD"] != 0) {
					$arFields["DAY"] = $arFields["DAY"] + $this->tdImportConfig["DAY_ADD"];
				}
				if (9999 < $arFields["DAY"]) {
					$arFields["DAY"] = 9999;
				}
				if (0 < $this->tdImportConfig["MIN_AVAIL"] && $arFields["AVAILABLE"] < $this->tdImportConfig["MIN_AVAIL"]) {
					$this->addIgnoredRow($arCSVrow, $Line, 'Товара меньше, чем минимально допустимо');
					continue;
				}
				if (0 < $this->tdImportConfig["MAX_DAY"] && $this->tdImportConfig["MAX_DAY"] < $arFields["DAY"]) {
					$this->addIgnoredRow($arCSVrow, $Line, 'Превышен максимальный срок доставки');
					continue;
				}
				if ($this->tdImportConfig["CONSIDER_HOT"] != 1 || $arOps["HOT"] != 1) {
					if ($this->tdImportConfig["PRICE_EXTRA"] != 0) {
						$arFields["PRICE"] = $arFields["PRICE"] + $arFields["PRICE"] / 100 * $this->tdImportConfig["PRICE_EXTRA"];
					}
				}
				$arFields["PRICE"] = round($arFields["PRICE"], 2);
				if ($_GET["TEST"] == "Y" && 1 < strpos($arFields["PRICE"], ".")) {
					$arFields["PRICE"] = substr($arFields["PRICE"], 0, strpos($arFields["PRICE"], ".", 0) + 3);
				}
				if (0 < $this->tdImportConfig["PRICE_ADD"]) {
					$arFields["PRICE"] = $arFields["PRICE"] + $this->tdImportConfig["PRICE_ADD"];
				}
				$arFields["PRICE"] = str_replace(",", ".", $arFields["PRICE"]);
				$arFields["ARTICLE"] = str_replace("\"", "", $arFields["ARTICLE"]);
				$arFields["ARTICLE"] = str_replace("'", "", $arFields["ARTICLE"]);

				// PC: Save extra data
				$arFields["PC_MODEL"] = trim($arCSVrow[7]);
				$arFields["PC_SKU"] = trim($arCSVrow[8]);
				$arFields["PC_MANUFACTURER"] = trim($arCSVrow[9]);

				if ($arFields["BKEY"] != "" && $arFields["AKEY"] != "" && 0 < $arFields["PRICE"]) {
					$arUKeys = array();
					$arUValue = array();
					foreach ($arFields as $key => $value) {
						$arUKeys[] = $key;
						$arUValue[] = "'" . mysql_real_escape_string($value) . "'";
					}
					$qKeys = implode(",", $arUKeys);
					$qValues = implode(",", $arUValue);

					$SQL = "INSERT INTO TDM_PRICES \n(" . $qKeys . ") \nVALUES \n(" . $qValues . ") \n";

					mysql_query($SQL);
					if (mysql_error() != "") {
						$this->addIgnoredRow($arCSVrow, $Line, "Дубликат");
						continue;
					}

					// Import Alternative Articles for current Prices Row
					$altArticles->import($arFields["AKEY"], trim($arCSVrow[10]));
				} else {
					$this->addIgnoredRow($arCSVrow, $Line, 'Нет цены');
				}
				if(!($Line % 500)) echo "[Info] Processed lines: {$Line}\n";
			} catch(\Exception $ex) {
				echo "[Warning] Bad csv-row. Skipping. Line: {$Line} :: {$ex->getMessage()}\n";
				$this->addIgnoredRow($arCSVrow, $Line, "Ошибка скрипта импорта");
				continue;
			}
		}

		if(!empty($Line)) echo "[Info] Processed lines: " . ($Line + 1) . "\n";

		echo "[Info] Start crossing process\n";
		(new PcCrossGenerator())->run();
		echo "[Info] Crosses generated successfully\n";
	}

	/**
	 * @param $importConfigId
	 * @return bool
	 */
	protected function getTecDocConfig($importConfigId)
	{
		$query = new TDMQuery();
		$query->Select("TDM_IM_SUPPLIERS", array(), array("ID" => $importConfigId));
		if (!($this->tdImportConfig = $query->Fetch())) return false;

		$query->Select("TDM_IM_COLUMNS", array(), array("SUPID" => $this->tdImportConfig["ID"]));
		while ($arCol = $query->Fetch()) {
			$this->tdImportColsConfig[$arCol["FIELD"]] = $arCol["NUM"] - 1;
		}

		return true;
	}

	/**
	 * @param string $csvFilePath
	 * @param string $delimiter
	 * @return \Generator
	 */
	public function rowGenerator($csvFilePath, $delimiter = ';')
	{
		if ($handle = fopen($csvFilePath, "r")) {
			while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
				yield $row;
			}
		}
	}

	/**
	 * @param $csvRow
	 * @param int $line
	 * @param string $reason
	 * @return $this
	 */
	protected function addIgnoredRow($csvRow, $line, $reason = '')
	{
		if(!is_array($this->ignoredRows)) $this->ignoredRows = [];
		$this->ignoredRows[] = array_merge([
			'Line' => !$line ? 'Строка' : $line,
			'Reason' => !$line ? 'Причина' : $reason,
		], $csvRow);

		return $this;
	}

	/**
	 * @param string $to - Recipient's email
	 * @return bool
	 */
	public function sendIgnoredRowsReport($to)
	{
		echo "[Info] Ignored Rows: " . count($this->ignoredRows) . "\n";
		if(empty($this->ignoredRows)) return false;
		if(count($this->ignoredRows) < 2)

		$headers = "From: Partix-Auto-Importer\r\n";
		$headers .= "Reply-To: support@autopartix.com\r\n";
		$headers .= "Return-Path: support@autopartix.com\r\n";
		$headers .= "X-Mailer: PHP5\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=utf-8\r\n";

		$subject = "[Отчёт] Некорректные импортируемые данные";
		$body = $this->getIgnoredRowReportBody();

		return mail($to, $subject, $body, $headers);
	}

	/**
	 * @return string
	 */
	protected function getIgnoredRowReportBody()
	{
		$html = '<table style="width: 100%; border: 1px solid black; border-collapse: collapse; font-size: 10px;">';
		foreach($this->ignoredRows as $row){
			$html .= "<tr>";
			$tagOpen = $row === reset($this->ignoredRows) ?
				'<th style="text-align: center; background: #ccc; padding: 5px; border: 1px solid black; color: #000;">' :
				'<td style="padding: 5px; border: 1px solid black; color: #000;">';
			$tagClose = $row === reset($this->ignoredRows) ? '</th>' : '</td>';

			foreach($row as $index => $cell){
				if($index > 1) $cell = iconv($this->tdImportConfig["ENCODE"], "UTF-8//TRANSLIT", $cell);
				$html .= $tagOpen . $cell . $tagClose;
			}

			$html .= "</tr>";
		}
		$html .= "</table>";

		return $html;
	}
}