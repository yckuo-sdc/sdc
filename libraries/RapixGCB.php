<?php
namespace gcb\api;

class RapixGCB {
  
	private $host;
	private $token;

    public function __construct() {
        $this->host = Rapix::HOST;
        $this->token = $this->getAccessToken(Rapix::APIKEY);
    }

    public function __destruct() {
        $this->token = null;
    }

    //get client_list
    public function getClientList($limit) {
        $url = $this->host . "/api/v1/client/list";
        $postField = json_encode(array(
               "kind" => "list&count",
               "page" => 1,
               "limit" => $limit,
               "incl_exc" => true,
               "sorts" => [[
                    "field" => "ID",
                    "type" => "ASC"
               ]],
               "select" => [
                   "ID",
                   "Name",
                   "UserName",
                   "AssocOwner",
                   "OrgName",
                   "OSEnvID",
                   "OSArch",
                   "IEEnvID",
                   "InternalIP",
                   "ExternalIP",
                   "IsOnline",
                   "GsID",
                   "GsSetDeployID",
                   "GsStat",
                   "GsExcTot",
                   "GsAll",
                   "GsUpdatedAt"
               ],
               "filter" => []
        ));
	    $response = $this->sendHttpRequest($url, $postField);
        return $response;
    }

    //get client_detail
    public function getClientDetail($client_id) {
        $url = $this->host . "/api/v1/client/detail/" . $client_id;
        $postField = json_encode(array("client_id" => $client_id));
	    $response = $this->sendHttpRequest($url, $postField);
        return $response;
    }

    //get gcb_scan result
    public function getGscanResult($gs_id) {
        $url = $this->host . "/api/v1/gscan/result/" . $gs_id;
        $postField = json_encode(array("gs_id" => $gs_id));
	    $response = $this->sendHttpRequest($url, $postField);
        return $response;
    }

    //get token
    private function getAccessToken($apikey) {
        $url = $this->host . "/api/v1/token";
        $httpHeader = array("Content-Type"=>"application/json");
        $postField = json_encode(array("key"=>$apikey));
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postField); 
        $response = curl_exec($ch);
        curl_close($ch);

        if(($data = json_decode($response,true)) == true) {
            $token = $data['token']; 
        }
        if(isset($token)) {
            return $token;
        }else {
            return false;
        }
   }

	//send curl request with bearer token 
	private function sendHttpRequest($url, $postField) {
        $httpHeader = array("Content-Type: application/json", "Authorization: Bearer ".$this->token);
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
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $postField,
          CURLOPT_HTTPHEADER => $httpHeader
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
