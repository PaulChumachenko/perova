<?php

class PcAlternativeArticles
{
	const IMPORT_DELIMITER = '/';

	/**
	 * @param $originalArticle
	 * @param $csvArticles
	 */
	public function import($originalArticle, $csvArticles)
	{
		$altArticles = $this->getAlternativeArticles($csvArticles);
		if($altArticles){
			$this->save($originalArticle, $altArticles);
		}
	}

	/**
	 * Flush previous alternative articles
	 */
	public function flushPrevious()
	{
		$sql = "TRUNCATE TABLE PC_ALT_ARTICLES";
		mysql_query($sql);
		$affected = mysql_affected_rows();

		if (mysql_error() != "") {
			$this->renderError($sql);
			return false;
		}

		return true;
	}

	/**
	 * @param $article
	 * @return string
	 */
	public static function getAlternativeQuery($article)
	{
		return "
			UNION
			SELECT TDM_PRICES.* FROM TDM_PRICES
			  LEFT JOIN PC_ALT_ARTICLES ALT ON ALT.ORIGINAL = TDM_PRICES.AKEY
			WHERE ALT.ALTERNATIVE = '{$article}'
			GROUP BY TDM_PRICES.AKEY
		";
	}

	/**
	 * @param $csvArticles
	 * @return array
	 */
	protected function getAlternativeArticles($csvArticles)
	{
		return empty($csvArticles)? array() : explode(static::IMPORT_DELIMITER, $csvArticles);
	}

	/**
	 * @param $original
	 * @param $articles
	 */
	protected function save($original, $articles)
	{
		foreach ($articles as $article) {
			if(empty($article)) continue;
			
			$row["ORIGINAL"] = $original;
			$row["ALTERNATIVE"] = trim($article);

			$cols = array();
			$values = array();
			foreach ($row as $key => $value) {
				$cols[] = $key;
				$values[] = "'" . mysql_real_escape_string($value) . "'";
			}

			$duplicates = array();
			$tableCols = array("ORIGINAL", "ALTERNATIVE");
			foreach ($tableCols as $col) {
				$duplicates[] = $col . "='" . mysql_real_escape_string($row[$col]) . "'";
			}

			$cols = implode(",", $cols);
			$values = implode(",", $values);
			$duplicates = implode(",", $duplicates);

			$insert = "INSERT INTO PC_ALT_ARTICLES (" . $cols . ") VALUES (" . $values . ") ON DUPLICATE KEY UPDATE " . $duplicates;
			mysql_query($insert);

			if (mysql_error() != "") {
				$this->renderError($insert);
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
}