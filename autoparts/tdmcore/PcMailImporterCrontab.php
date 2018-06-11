<?php

class PcMailImporterCrontab
{
	const STATUS_PROCESSING = 1;
	const STATUS_DONE = 2;
	const STATUS_FAILED = 3;
	const STATUS_IDLE = 4;

	private $jobId;

	/**
	 * @return int
	 * @throws Exception
	 */
	public function startNewJob()
	{
		$row = array(
			array('col' => 'STATUS', 'val' => static::STATUS_PROCESSING),
			array('col' => 'TIME_START', 'val' => 'NOW()'),
		);
		list($cols, $values) = $this->prepareInsert($row);
		$insert = "INSERT INTO PC_MAIL_IMPORTER_CRONTAB (" . $cols . ") VALUES (" . $values . ")";
		$result = mysql_query($insert);

		if($result){
			$this->jobId = mysql_insert_id();
			return $this->jobId;
		} else {
			throw new \Exception("[Mail Importer Crontab] Could not start new job. " . mysql_error());
		}
	}

	/**
	 * @param $csvPath
	 * @param $mailDateSent
	 * @return resource
	 */
	public function markJobAsDone($csvPath, $mailDateSent)
	{
		$csvPath =  $this->prepareSqlString($csvPath);
		$mailDateSent =  $this->prepareSqlString($mailDateSent);
		$sql = "UPDATE PC_MAIL_IMPORTER_CRONTAB
				SET STATUS = " . static::STATUS_DONE . "
					,TIME_END = NOW()
					,CSV_PATH = {$csvPath}
					,MAIL_DATE_SENT = {$mailDateSent}
				WHERE ID = {$this->jobId}";
		$result = mysql_query($sql);

		return $result;
	}

	/**
	 * @param $reason
	 * @return resource
	 */
	public function markJobAsFailed($reason)
	{
		$reason =  $this->prepareSqlString($reason);
		$sql = "UPDATE PC_MAIL_IMPORTER_CRONTAB
				SET STATUS = " . static::STATUS_FAILED . "
					,TIME_END = NOW()
					,REASON = {$reason}
				WHERE ID = {$this->jobId}";
		$result = mysql_query($sql);

		return $result;
	}

	/**
	 * @return resource
	 */
	public function markJobAsIdle()
	{
		$sql = "UPDATE PC_MAIL_IMPORTER_CRONTAB
				SET STATUS = " . static::STATUS_IDLE . "
					,TIME_END = NOW()
				WHERE ID = {$this->jobId}";
		$result = mysql_query($sql);

		return $result;
	}

	/**
	 * @return mixed
	 */
	public function getLastMailDateSent()
	{
		$query = new TDMQuery();
		$query->SimpleSelect("SELECT MAX(MAIL_DATE_SENT) AS last_date FROM PC_MAIL_IMPORTER_CRONTAB WHERE STATUS = " . static::STATUS_DONE . " GROUP BY ID");
		$row = $query->Fetch();

		return $row['last_date'];
	}

	/**
	 * @param $str
	 * @return string
	 */
	protected function prepareSqlString($str)
	{
		return "'" . mysql_real_escape_string($str) . "'";
	}

	/**
	 * @param array $row
	 * @return array
	 */
	protected function prepareInsert($row)
	{
		$cols = array();
		$values = array();
		foreach ($row as $data) {
			$cols[] = $data['col'];
			$values[] = !empty($data['escape']) ? $this->prepareSqlString($data['val']) : $data['val'];
		}

		$cols = implode(",", $cols);
		$values = implode(",", $values);

		return array($cols, $values);
	}
}