<?php
/*** manual： https://172.16.254.209/php/rest/browse.php ***/
namespace paloalto\api;

Class PaloaltoAPI {
	private $host;
	private $apikey;
	//private $object_map = [ 0 => 'ApplicationGroups', 1 => 'Addresses'];
	//private $policy_map = [ 0 => 'SecurityRules' ];

	public function __construct($host, $username, $password){  // a public function (default)
		$this->host = $host;
		$this->apikey = $this->GetAPIKey("keygen", $username, $password);
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
		#echo $url;
		$res = $this->CustomCurlRequest($url);
		return $res;
	}

	public function GetXmlCmdResponse($type, $cmd){
		$host = $this->host;
		$apikey = $this->apikey;
		$url = "https://".$host."/api/?type=$type&cmd=$cmd&key=$apikey";
		$res = $this->CustomCurlRequest($url);
		return $res;
	}

	//Restful API
	public function GetObjectList($object_type, $name){
		$host = $this->host;
		$apikey = $this->apikey;
		$url = "https://$host/restapi/9.0/Objects/$object_type?name=$name&location=vsys&vsys=vsys1&output-format=json&key=".$apikey;
		$res = CustomCurlRequest($url);
		return $res;
	}

	public function GetPoliciesList($policy_type, $name){
		$host = $this->host;
		$apikey = $this->apikey;
		$url = "https://$host/restapi/9.0/Policies/$policy_type?name=$name&location=vsys&vsys=vsys1&output-format=json&key=".$apikey;
		$res = CustomCurlRequest($url);
		return $res;
	}

	//Private Function
	private function GetAPIKey($type, $username, $password){
		$host = $this->host;
		$url = "https://".$host."/api/?type=$type&user=$username&password=$password";
		$res = $this->CustomCurlRequest($url);
		$xml = simplexml_load_string($res) or die("Error: Cannot create object");
		$apikey = $xml->result->key;
		return $apikey;
	}
	
	//Curl Request with custom setting
	private function CustomCurlRequest($url){
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
?>

