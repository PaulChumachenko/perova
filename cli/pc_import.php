<?php

define("TDM_PROLOG_INCLUDED", true);
define("PC_CLI_DEBUG_MODE", false);
define("PC_REPORT_IGNORED_ROWS_RECIPIENT", 'vit@kolodka.kiev.ua');

if(PC_CLI_DEBUG_MODE){
	// Developing
	define('TDM_CLI_ROOT_DIR', 'C:\OpenServer\domains\autopartix.loc\autoparts\\');
	$tdmInit = TDM_CLI_ROOT_DIR . 'tdmcore\init.php';
} else {
	// Prod
	define('TDM_CLI_ROOT_DIR', '/home/autopl05/autopartix.com/www/autoparts/');
	$tdmInit = TDM_CLI_ROOT_DIR . 'tdmcore/init.php';
}

if ( file_exists($tdmInit) ) {
	require_once ($tdmInit);
} else {
	die("[Pc Import] Cannot access to init.php");
}

//$tdmConfig = TDM_CLI_ROOT_DIR . 'config.php';
//PcCliHelper::connectToTecDocDb($tdmConfig);

$attachmentsDir = TDM_CLI_ROOT_DIR . 'admin/import/downloads/cli';
$mi = new PcMailImporter($attachmentsDir);
$mic = new PcMailImporterCrontab();

$jobId = $mic->startNewJob();
$csvFilePath = $mi->getLastCsv($mic->getLastMailDateSent());

if($csvFilePath){
	echo $csvFilePath . "\n";
	try {
		$mi->importToTecDoc($csvFilePath, 2);
		$mic->markJobAsDone($csvFilePath, $mi->getMailDateSent());
		$mi->sendIgnoredRowsReport(PC_REPORT_IGNORED_ROWS_RECIPIENT);
	} catch(\Exception $ex) {
		$mic->markJobAsFailed($ex->getMessage());
	}
} else {
	$mic->markJobAsIdle(); // Nothing to import
	echo "Nothing to import\n";
}

return 0;