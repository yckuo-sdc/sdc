<?php
namespace gcb\api;

//get token from API
function get_access_token($url,$key){
	$header_request = array("Content-Type"=>"application/json");
	$body_request = json_encode(array("key"=>$key));
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
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header_request);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $body_request); 
	$response = curl_exec($ch);
	curl_close($ch);
	if(($data = json_decode($response,true)) == true){
		$token = $data['token']; 
	}
	if(isset($token)) return $token;
	else			  return false;
}
//get client_list from API
function get_client_list($url,$token,$limit){
	require("../mysql_connect.inc.php");
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
	  CURLOPT_POSTFIELDS =>"{\r\n\"kind\": \"list&count\",\r\n\"page\": 1,\r\n\"limit\": ".$limit.",\r\n\"incl_exc\": true,\r\n\"sorts\": [\r\n{\r\n\"field\": \"ID\",\r\n\"type\": \"ASC\"\r\n}\r\n],\r\n\"select\": [\r\n\"ID\",\r\n\"Name\",\r\n\"UserName\",\r\n\"Owner\",\r\n\"OrgName\",\r\n\"OSEnvID\",\r\n\"IEEnvID\",\r\n\"InternalIP\",\r\n\"ExternalIP\",\r\n\"IsOnline\",\r\n\"GsID\",\r\n\"GsSetDeployID\",\r\n\"GsStat\",\r\n\"GsExcTot\",\r\n\"GsAll\",\r\n\"GsUpdatedAt\"\r\n],\r\n\"filter\": [\r\n]\r\n}",
	  CURLOPT_HTTPHEADER => array(
		"Content-Type: application/json",
		"Authorization: Bearer ".$token
	  ),
	));
	$response = curl_exec($curl);
	curl_close($curl);

	//Decode 4 Dimensions of array	
	if(($data = json_decode($response,true)) == true){
		foreach($data as $key1 => $value1) {
			//echo $key1."\n";
			if(is_array($value1)){
				$count = 0;
				foreach ($value1 as $key2 => $value2) {
					$gcb_client_list['ExternalIP']= mysqli_real_escape_string($conn,$value2['ExternalIP']);
					$gcb_client_list['GsAll_0']= mysqli_real_escape_string($conn,$value2['GsAll'][0]);
					$gcb_client_list['GsAll_1']= mysqli_real_escape_string($conn,$value2['GsAll'][1]);
					$gcb_client_list['GsAll_2']= mysqli_real_escape_string($conn,$value2['GsAll'][2]);
					$gcb_client_list['GsExcTot']= mysqli_real_escape_string($conn,$value2['GsExcTot']);
					$gcb_client_list['GsID']= mysqli_real_escape_string($conn,$value2['GsID']);
					$gcb_client_list['GsSetDeployID']= mysqli_real_escape_string($conn,$value2['GsSetDeployID']);
					$gcb_client_list['GsStat']= mysqli_real_escape_string($conn,$value2['GsStat']);
					$gcb_client_list['GsUpdatedAt']= mysqli_real_escape_string($conn,$value2['GsUpdatedAt']);
					$gcb_client_list['ID']= mysqli_real_escape_string($conn,$value2['ID']);
					$gcb_client_list['IEEnvID']= mysqli_real_escape_string($conn,$value2['IEEnvID']);
					$gcb_client_list['InternalIP']= mysqli_real_escape_string($conn,$value2['InternalIP']);
					$gcb_client_list['IsOnline']= mysqli_real_escape_string($conn,$value2['IsOnline']);
					$gcb_client_list['Name']= mysqli_real_escape_string($conn,$value2['Name']);
					$gcb_client_list['OSEnvID']= mysqli_real_escape_string($conn,$value2['OSEnvID']);
					$gcb_client_list['OrgName']= mysqli_real_escape_string($conn,$value2['OrgName']);
					$gcb_client_list['Owner']= mysqli_real_escape_string($conn,$value2['Owner']);
					$gcb_client_list['UserName']= mysqli_real_escape_string($conn,$value2['UserName']);

					//echo long2ip($gcb_client_list['ExternalIP'])."\n\r";	
					// INSERT to table ON DUPLICATE KEY UPDATE data
					$sql = "insert into gcb_client_list(ExternalIP,GsAll_0,GsAll_1,GsAll_2,GsExcTot,GsID,GsSetDeployID,GsStat,GsUpdatedAt,ID,IEEnvID,InternalIP,IsOnline,Name,OSEnvID,OrgName,Owner,UserName ) values(".$gcb_client_list['ExternalIP'].",'".$gcb_client_list['GsAll_0']."','".$gcb_client_list['GsAll_1']."','".$gcb_client_list['GsAll_2']."','".$gcb_client_list['GsExcTot']."','".$gcb_client_list['GsID']."',".$gcb_client_list['GsSetDeployID'].",'".$gcb_client_list['GsStat']."','".$gcb_client_list['GsUpdatedAt']."',".$gcb_client_list['ID'].",'".$gcb_client_list['IEEnvID']."',".$gcb_client_list['InternalIP'].",'".$gcb_client_list['IsOnline']."','".$gcb_client_list['Name']."','".$gcb_client_list['OSEnvID']."','".$gcb_client_list['OrgName']."','".$gcb_client_list['Owner']."','".$gcb_client_list['UserName']."')
					ON DUPLICATE KEY UPDATE ExternalIP = ".$gcb_client_list['ExternalIP'].",GsAll_0 = '".$gcb_client_list['GsAll_0']."',GsAll_1 = '".$gcb_client_list['GsAll_1']."',GsAll_2 = '".$gcb_client_list['GsAll_2']."',GsExcTot = '".$gcb_client_list['GsExcTot']."',GsID = '".$gcb_client_list['GsID']."',GsSetDeployID = ".$gcb_client_list['GsSetDeployID'].",GsStat = '".$gcb_client_list['GsStat']."',GsUpdatedAt = '".$gcb_client_list['GsUpdatedAt']."',IEEnvID = '".$gcb_client_list['IEEnvID']."',InternalIP = ".$gcb_client_list['InternalIP'].",IsOnline = '".$gcb_client_list['IsOnline']."',Name = '".$gcb_client_list['Name']."',OSEnvID = '".$gcb_client_list['OSEnvID']."',OrgName = '".$gcb_client_list['OrgName']."',Owner = '".$gcb_client_list['Owner']."',UserName = '".$gcb_client_list['UserName']."' ";
					if ($conn->query($sql) == TRUE) {
						//echo "此筆資料已被上傳成功\n\r";		
						$count = $count + 1;							
					} else {
						echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
					}
				}
				date_default_timezone_set("Asia/Taipei");
				$nowTime 	= date("Y-m-d H:i:s");
				echo "The ".$count." records have been inserted or updated into the gcb_clinet_list on ".$nowTime."\n\r<br>";
			}else{
				echo "=>".$value1."\n";
			}
		}
		$status = 200;
	
	}else{
		echo "No target-data \n\r<br>";
		$status = 400;
	}
	$sql = "SELECT * FROM api_list WHERE class LIKE 'GCB' and name LIKE '用戶端清單' ";
	$result = mysqli_query($conn,$sql);
	$row = mysqli_fetch_assoc($result);
	$sql = "INSERT INTO api_status(api_id,url,status,data_number,last_update) VALUES(".$row['id'].",'".$url."',".$status.",".$count.",'".$nowTime."')";
	if ($conn->query($sql) == TRUE){
	}else {
		echo "Error: " . $sql . "<br>" . $conn->error."<p>\n\r";
	}
	$conn->close();
}

$url = "https://gcb.tainan.gov.tw/api/v1/token";
$key = "u3mOZuf8lvZYps210BD5vA";
$token = get_access_token($url,$key);
$url = "https://gcb.tainan.gov.tw/api/v1/client/list";
// '0' represent no limit 
$list_limit = 0;
get_client_list($url,$token,$list_limit);

?>
