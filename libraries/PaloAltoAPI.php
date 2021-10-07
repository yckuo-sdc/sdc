<?php
/*** manual： https://172.16.254.209/php/rest/browse.php ***/
Class PaloAltoAPI {

	private $host;
	private $apikey;

	public function __construct($host = null) {  // a public function (default)
		if($host == null){	
			$this->host = PaloAlto::HOST_ADDRESS['yonghua'];
		}else{
			$this->host = PaloAlto::HOST_ADDRESS[$host];
		}
		$this->apikey = $this->getAPIKey("keygen", PaloAlto::USERNAME, PaloAlto::PASSWORD);
	}

	public function getProperty() {  // a public function (default)
		$item['host'] = $this->host;
		$item['apikey'] = $this->apikey;
		return $item;
	}
	
	public function setProperty($host,$apikey) {  // a public function (default)
		$this->host = $host;
		$this->apikey = $apikey;
	}
	
	// XML API
	public function getXmlSecurityRules() {
		$host = $this->host;
		$apikey = $this->apikey;
		$args = array('type' => 'config', 'action' => 'show', 'xpath' => '/config/devices/entry/vsys/entry/rulebase/security');
		$url = "https://" . $host . "/api/?" . http_build_query($args) . "&key=" . $apikey;
		$response = $this->sendHttpRequest($url);
		$xmlObject = simplexml_load_string($response) or die("Error: Cannot create object");
		$data_array = xml2array($xmlObject);        
        return $data_array;
    }

	public function getLogList($log_type, $dir, $nlogs, $skip, $query) {
		$host = $this->host;
		$apikey = $this->apikey;
		$args = array('type' => 'log', 'log-type' => $log_type, 'dir' => $dir, 'nlogs' => $nlogs, 'skip' => $skip, 'query' => $query, 'async' => 'yes', 'uniq' => 'yes');
		$url = "https://$host/api/?".http_build_query($args)."&key=$apikey";
		$res = $this->sendHttpRequest($url);

        $xml = simplexml_load_string($res) or die("Error: Cannot create object");
        $job_id = $xml->result->job;

        $loop_count = 0;
        $flag = 1;
        do {
            $res = $this->retrieveLogs($job_id);
            $loop_count = $loop_count + 1;
            $xml = simplexml_load_string($res) or die("Error: Cannot create object");
            if ($xml['status'] == 'success') {
                echo $xml->result->job->status . " "; 
                if ($xml->result->job->status == 'FIN') {
                    $flag = 0;
                }
            }
        } while ($flag & $loop_count < 30);
        echo "<br>";
        if ($flag) {
            echo "Timeout<br>";
        }

        if($xml['status'] != 'success'){
            $data['log_count'] = 0;
            $data['logs'] = array();
            return $data;
        }
        $data['log_count'] = $xml->result->log->logs['count'];
		$data['logs'] = $xml->result->log->logs->entry;
        return $data;
	}

	public function getAsyncReport($report_type, $report_name) {
		$host = $this->host;
		$apikey = $this->apikey;
		$args = array('type' => 'report', 'reporttype' => $report_type, 'reportname' => $report_name, 'async' => 'yes', 'uniq' => 'yes');
		$url = "https://$host/api/?".http_build_query($args)."&key=$apikey";
		$res = $this->sendHttpRequest($url);
        $xml = simplexml_load_string($res) or die("Error: Cannot create object");
        $job_id = $xml->result->job;

        $log_count = 0; 
        $logs = array();
        if(!isset($job_id)){
            echo "no job_id";
            $data['log_count'] = $log_count;
            $data['logs'] = $logs;
            return $data;
        }else{
            $res = $this->retrieveLogs($job_id, $type="report", $action="get");
            $xml = simplexml_load_string($res) or die("Error: Cannot create object");
            foreach($xml->result->report->entry as $entry){
                foreach($entry as $key => $val){
                    $logs[$log_count][$key] = $val;
                }
                $log_count = $log_count + 1;
            }
            $data['log_count'] = $log_count;
            $data['logs'] = $logs;
        }
		return $data;
	}

	public function getSyncReport($report_type, $report_name) {
		$host = $this->host;
		$apikey = $this->apikey;
		$args = array('type' => 'report', 'reporttype' => $report_type, 'reportname' => $report_name, 'async' => 'yes', 'uniq' => 'yes');
		$url = "https://$host/api/?".http_build_query($args)."&key=$apikey";
		$res = $this->sendHttpRequest($url);
        $xml = simplexml_load_string($res) or die("Error: Cannot create object");
        
        $log_count = 0; 
        $logs = array();
        foreach($xml->result->entry as $entry){
            foreach($entry as $key => $val){
                $logs[$log_count][$key] = $val;
           }
            $log_count = $log_count + 1;
        }
       
        $data['log_count'] = $log_count;
		$data['logs'] = $logs;
        return $data;
	}

	public function retrieveLogs($job_id, $type="log", $action="get") {
		$host = $this->host;
		$apikey = $this->apikey;
		$args = array('type' => $type, 'action' => $action, );
		$url = "https://$host/api/?".http_build_query($args)."&job-id=$job_id&key=$apikey";
		$res = $this->sendHttpRequest($url);
		return $res;
	}

	public function getXmlCmdResponse($type, $cmd) {
		$host = $this->host;
		$apikey = $this->apikey;
		$url = "https://".$host."/api/?type=$type&cmd=$cmd&key=$apikey";
		$res = $this->sendHttpRequest($url);
		return $res;
	}

	//Restful API
	public function getObjectList($object_type, $name){
		$host = $this->host;
		$apikey = $this->apikey;
		$url = "https://$host/restapi/9.0/Objects/$object_type?name=$name&location=vsys&vsys=vsys1&output-format=json&key=".$apikey;
		$res = $this->sendHttpRequest($url);
		return $res;
	}

	public function getPoliciesList($policy_type, $name) {
		$host = $this->host;
		$apikey = $this->apikey;
		$url = "https://$host/restapi/9.0/Policies/$policy_type?name=$name&location=vsys&vsys=vsys1&output-format=json&key=".$apikey;
		$res = $this->sendHttpRequest($url);
		return $res;
	}

	//Private Function
	private function getAPIKey($type, $username, $password) {
		$host = $this->host;
		$url = "https://".$host."/api/?type=$type&user=$username&password=$password";
		$res = $this->sendHttpRequest($url);
		$xml = simplexml_load_string($res) or die("Error: Cannot create object");
		$apikey = $xml->result->key;
		return $apikey;
	}
	
	//Curl Request with options
	private function sendHttpRequest($url) {
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_SSL_VERIFYPEER => false,
		  CURLOPT_SSL_VERIFYHOST => false,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_HTTPHEADER => array("Content-Type: application/json")
		));
		$res = curl_exec($curl);

        // Check if any error occurred
        if (curl_errno($curl)) {
            echo 'Curl error: ' . curl_error($curl);
        }

		curl_close($curl);
		return $res;
	}

}

/** Usage
require 'PaloAltoAPI.php';

$hosts = ['yonghua', 'minjhih', 'idc', 'intrayonghua'];

foreach($hosts as $key => $host){
	$pa = new PaloAltoAPI($host);
	$xml_type = "op";
	$cmd = "<show><system><info></info></system></show>";
	$res = $pa->getXmlCmdResponse($xml_type, $cmd);
	print_r($res);
}

**/
