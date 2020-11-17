<?php
/*** manual： https://172.16.254.209/php/rest/browse.php ***/
if(!defined("SITE_ROOT"))	define("SITE_ROOT", "/var/www/html/sdc");
require SITE_ROOT.'/config/PaloAlto.php';

Class PaloAltoAPI {
	const HOSTMAP = ['yonghua' => '172.16.254.209', 'minjhih' => '10.6.2.102', 'idc' => '10.7.11.241', 'intrayonghua' => '172.16.254.205'];
	private $host;
	private $apikey;

	public function __construct($host = null){  // a public function (default)
		if($host == null){	
			$this->host = PaloAlto::ADDRESS;
		}else{
			$this->host = $host;
		}
		$this->apikey = $this->GetAPIKey("keygen", PaloAlto::USERNAME, PaloAlto::PASSWORD);
	}

	public function GetProperty(){  // a public function (default)
		$item['host'] = $this->host;
		$item['apikey'] = $this->apikey;
		return $item;
	}
	
	public function SetProperty($host,$apikey){  // a public function (default)
		$this->host = $host;
		$this->apikey = $apikey;
	}
	
	//XML API
	public function GetLogList($log_type, $dir, $nlogs, $skip, $query){
		$host = $this->host;
		$apikey = $this->apikey;
		$args = array('type' => 'log', 'log-type' => $log_type, 'dir' => $dir, 'nlogs' => $nlogs, 'skip' => $skip, 'query' => $query, 'async' => 'yes', 'uniq' => 'yes');
		$url = "https://$host/api/?".http_build_query($args)."&key=$apikey";
		$res = $this->CurlRequest($url);

        $xml = simplexml_load_string($res) or die("Error: Cannot create object");
        $job_id = $xml->result->job;
        usleep(500000);
        $res = $this->RetriveLogs($job_id);
        $xml = simplexml_load_string($res) or die("Error: Cannot create object");
        if($xml['status'] != 'success'){
            return false;
        }
        $data['log_count'] = $xml->result->log->logs['count'];
		$data['logs'] = $xml->result->log->logs->entry;
        return $data;
	}

	public function GetReportList($report_type, $report_name){
		$host = $this->host;
		$apikey = $this->apikey;
		$args = array('type' => 'report', 'reporttype' => $report_type, 'reportname' => $report_name, 'async' => 'yes', 'uniq' => 'yes');
		$url = "https://$host/api/?".http_build_query($args)."&key=$apikey";
		$res = $this->CurlRequest($url);
		return $res;
	}

	public function RetriveLogs($job_id, $type="log", $action="get"){
		$host = $this->host;
		$apikey = $this->apikey;
		$args = array('type' => $type, 'action' => $action, );
		$url = "https://$host/api/?".http_build_query($args)."&job-id=$job_id&key=$apikey";
		$res = $this->CurlRequest($url);
		return $res;
	}

	public function GetXmlCmdResponse($type, $cmd){
		$host = $this->host;
		$apikey = $this->apikey;
		$url = "https://".$host."/api/?type=$type&cmd=$cmd&key=$apikey";
		$res = $this->CurlRequest($url);
		return $res;
	}

	//Restful API
	public function GetObjectList($object_type, $name){
		$host = $this->host;
		$apikey = $this->apikey;
		$url = "https://$host/restapi/9.0/Objects/$object_type?name=$name&location=vsys&vsys=vsys1&output-format=json&key=".$apikey;
		$res = $this->CurlRequest($url);
		return $res;
	}

	public function GetPoliciesList($policy_type, $name){
		$host = $this->host;
		$apikey = $this->apikey;
		$url = "https://$host/restapi/9.0/Policies/$policy_type?name=$name&location=vsys&vsys=vsys1&output-format=json&key=".$apikey;
		$res = $this->CurlRequest($url);
		return $res;
	}

	//Private Function
	private function GetAPIKey($type, $username, $password){
		$host = $this->host;
		$url = "https://".$host."/api/?type=$type&user=$username&password=$password";
		$res = $this->CurlRequest($url);
		$xml = simplexml_load_string($res) or die("Error: Cannot create object");
		$apikey = $xml->result->key;
		return $apikey;
	}
	
	//Curl Request with options
	private function CurlRequest($url){
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
		curl_close($curl);
		return $res;
	}

}

