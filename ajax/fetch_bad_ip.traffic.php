<?php
$path = "/var/www/html/utility/google-api-php-client-2.2.2/";
require $path.'vendor/autoload.php';  // include your composer dependencies(google api library) 
require '../libraries/DatabasePDO.php';
require '../libraries/PaloAltoAPI.php';

$host_map = ['yonghua' => '172.16.254.209', 'minjhih' => '10.6.2.102', 'idc' => '10.7.11.241', 'intrayonghua' => '172.16.254.205'];
$host ='idc';

$db = Database::get();
$pa = new PaloAltoAPI($host_map[$host]);

$client = new Google_Client();
$client->setApplicationName('mytest');
$client->useApplicationDefaultCredentials();

//使用 google sheets apii
$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
$client->setAccessType('offline');
$client->setAuthConfig($path.'My Project for google sheet-3d2d6667b843.json');

//以下是建立存取 google sheets 的範

$sheets = new \Google_Service_Sheets($client);
$data = [];
$currentRow = 2;
								
// 填入要操作的試算表的 id (當然我為了保密刪掉了一小段)
$spreadsheetId = '1bb9zyNHfuwQanSdutcyh2fj1JsddsXmG9JCUC5NwPNE';
$range = 'nccst_bad_ip!A2:D';
$rows = $sheets->spreadsheets_values->get($spreadsheetId, $range, ['majorDimension' => 'ROWS']);

$time = date("Y-m-d H:i:s");
echo "IP test on $time \n";

if (!isset($rows['values'])) {
	return;
}

//ping test
foreach ($rows['values'] as $row){
	
	if (empty($row[0])){
		continue;						
	}

	echo $row[0]." ".$row[1]."\n"; 
	$ip = $row[1];
	$output = shell_exec("/bin/ping -c 1 -w 1 $ip");
	echo "ping done\n";	
	
}

$nlogs = sizeof($rows['values']);
$dir = 'backward';
$skip = 0;
$log_type = 'traffic';
$query = "(addr.src in 10.7.100.100) and (app eq ping) and (rule eq Block_ISAC_EDL_IN_OUT)"; 
$res = $pa->GetLogList($log_type, $dir, $nlogs, $skip, $query);
$xml = simplexml_load_string($res) or die("Error: Cannot create object");
$job = $xml->result->job;
$xml_type = "op";
$cmd = "<show><query><result><id>".$job."</id></result></query></show>";
$res = $pa->GetXmlCmdResponse($xml_type, $cmd);
$xml = simplexml_load_string($res) or die("Error: Cannot create object");

if($xml['status'] == 'error' || $xml->result->log->logs['count'] == 0 ){
	echo "query falied\n";
	return;
}
   

foreach($xml->result->log->logs->entry as $log){
	echo $log->dst."\n";
}

