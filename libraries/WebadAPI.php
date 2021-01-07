<?php
namespace ad\api;

class WebadAPI {
  
	private $host;

    public function __construct() {
        $this->host = Webad::HOST;
    }

    // edit domain user
    public function editUser($cn, $newpass, $confirmpass, $displayname, $title, $telephonenumber, $physicaldeliveryofficename, $mail, $isActive){
        $url = $this->host . "/api/EditUser";
        $postField = json_encode(array(
            "Username" => $cn,
            "Password" => $newpass,
            "NewPassword" => $confirmpass,
            "Name" => $displayname,
            "JobTitle" => $title,
            "Tel" => $telephonenumber,
            "Tel_Extension" => $physicaldeliveryofficename,
            "Email" => $mail,
            "IsActive" => $isActive
        ));
	    $response = $this->sendCurlRequest($url, $postField);
        return $response;
    }

    // insert domain user
    public function insertUser($cn, $newpass, $confirmpass, $displayname, $title, $telephonenumber, $physicaldeliveryofficename, $mail, $ou){
        $url = $this->host . "/api/NewUser";
        $postField = json_encode(array(
            "Username" => $cn,
            "Password" => $newpass,
            "NewPassword" => $confirmpass,
            "Name" => $displayname,
            "JobTitle" => $title,
            "Tel" => $telephonenumber,
            "Tel_Extension" => $physicaldeliveryofficename,
            "Email" => $mail,
            "OU" => $ou
        ));
	    $response = $this->sendCurlRequest($url, $postField);
        return $response;
    }

    // change user's or computer's state
    public function changeState($cn, $PasswordChangeNextTime, $isActive, $isLocked){
        $url = $this->host . "/api/ChangeUserState";
        $postField = json_encode(array(
            "Username" => $cn,
            "ComputerName" => $cn,
            "PasswordChangeNextTime" => $PasswordChangeNextTime,
            "isActive" => $isActive,
            "isLocked" => $isLocked
        ));
	    $response = $this->sendCurlRequest($url, $postField);
        return $response;
    }

    // change computer's OU
    public function changeComputerOU($cn, $ou, $isYonghua){
        $url = $this->host . "/api/ChangeComputerOU";
        $postField = json_encode(array(
            "Username" => $cn,
            "ComputerName" => $cn,
            "UpperOU" => $ou,
            "IsYongHua" => $isYonghua
        ));
	    $response = $this->sendCurlRequest($url, $postField);
        return $response;
    }

    //change ou
    public function changeUserOU($cn, $ou){
        $url = $this->host . "/api/ChangeOU";
        $postField = json_encode(array(
            "Username" => $cn,
            "OU" => $ou
        ));
	    $response = $this->sendCurlRequest($url, $postField);
        return $response;
    }

	//send curl request with bearer token 
	private function sendCurlRequest($url, $postField) {
        $httpHeader = array("Content-Type: application/json");
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
		curl_close($curl);
		return $res;
	}

}
