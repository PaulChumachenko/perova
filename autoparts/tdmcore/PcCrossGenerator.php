<?php

class PcCrossGenerator
{
	private $ignored;
	private $inserted;
	private $executionTime;

	public function run()
	{
		$startTime = microtime(true);

		$crosses = $this->getCrosses();
		$flushed = $this->flushPrevious();
		if($crosses && $flushed){
			$this->insertToDb($crosses);
		}

		$this->executionTime = round((microtime(true) - $startTime), 2);
	}

	/**
	 * @return array
	 */
	protected function getCrosses()
	{
		$sql = "SELECT org.AKEY AS AKEY1, org.BKEY AS BKEY1, crs.AKEY AS AKEY2, crs.BKEY AS BKEY2
				FROM TDM_PRICES org
				  INNER JOIN TDM_PRICES crs ON crs.PC_MODEL = org.PC_MODEL AND (crs.AKEY <> org.AKEY OR crs.BKEY <> org.BKEY)
				WHERE org.AKEY <> '' AND org.AKEY IS NOT NULL
				      AND org.BKEY <> '' AND org.BKEY IS NOT NULL
				      AND crs.AKEY <> '' AND crs.AKEY IS NOT NULL
				      AND crs.BKEY <> '' AND crs.BKEY IS NOT NULL";
		$query = new TDMQuery();
		$query->SimpleSelect($sql);

		$data = array();
		while ($cross = $query->Fetch()) {
			$data[] = $cross;
		}

		$crosses = array();
		$index = array();
		foreach ($data as $row){
			$originalKey = $row['AKEY1'] . $row['BKEY1'];
			$crossKey = $row['AKEY2'] . $row['BKEY2'];
			if(empty($index[$crossKey][$originalKey])){
				$index[$originalKey][$crossKey] = true;
				$crosses[] = $row;
			}
		}

		return $crosses;
	}

	/**
	 * Flush previous crosses
	 */
	protected function flushPrevious()
	{
		$sql = "TRUNCATE TABLE TDM_LINKS";
		mysql_query($sql);
		$affected = mysql_affected_rows();

		if (mysql_error() != "") {
			$this->renderError($sql);
			return false;
		}

		return true;
	}

	/**
	 * @param $crosses
	 */
	protected function insertToDb($crosses)
	{
		foreach ($crosses as $cross) {
			$cross["BKEY1"] = TDMSingleKey($cross["BKEY1"], true);
			$cross["AKEY1"] = TDMSingleKey($cross["AKEY1"]);
			$cross["PKEY1"] = $cross["BKEY1"] . $cross["AKEY1"];
			$cross["BKEY2"] = TDMSingleKey($cross["BKEY2"], true);
			$cross["AKEY2"] = TDMSingleKey($cross["AKEY2"]);
			$cross["PKEY2"] = $cross["BKEY2"] . $cross["AKEY2"];
			$cross["SIDE"] = 0; // Cross align; 0 - each other, 1 - left, 2 - right
			$cross["CODE"] = 'main';

			if (empty($cross["BKEY1"]) || empty($cross["AKEY1"]) || empty($cross["BKEY2"]) || empty($cross["AKEY2"])) {
				$this->ignored++;
				continue;
			}

			$arUKeys = array();
			$arUValue = array();
			foreach ($cross as $key => $value) {
				$arUKeys[] = $key;
				$arUValue[] = "'" . mysql_real_escape_string($value) . "'";
			}

			$duplicates = array();
			$tableCols = array("PKEY1", "BKEY1", "AKEY1", "PKEY2", "BKEY2", "AKEY2", "SIDE", "CODE");
			foreach ($tableCols as $col) {
				$duplicates[] = $col . "='" . mysql_real_escape_string($cross[$col]) . "'";
			}

			$qKeys = implode(",", $arUKeys);
			$qValues = implode(",", $arUValue);
			$qDuplicates = implode(",", $duplicates);

			$insert = "INSERT INTO TDM_LINKS (" . $qKeys . ") VALUES (" . $qValues . ") ON DUPLICATE KEY UPDATE " . $qDuplicates;
			mysql_query($insert);

			if (mysql_error() != "") {
				$this->renderError($insert);
			} else {
				$this->inserted++;
			}
		}
	}

	/**
	 * @param $sql
	 */
	protected function renderError($sql)
	{
		echo("<div class=\"imlog\">Query was executed<pre>" . $sql . "</pre>MySQL Error<pre>" . mysql_error() . "</pre></div>");
	}

	public function getExecutionTime()
	{
		return $this->executionTime;
	}
}