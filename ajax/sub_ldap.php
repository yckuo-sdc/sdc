<?php
include("../login/function.php");
session_start(); 
verifyBySession_Cookie("account");
header('Content-type: text/html; charset=utf-8');

if(!empty($_GET['target']) && !empty($_GET['type'])){
	$target = $_GET['target'];
	$type = $_GET['type'];
	switch($type){
		case "search":
			// connect to AD server
			require_once("../ldap_admin_config.inc.php");
			require_once("../login/function.php");
			$ldapconn = ldap_connect($host_ip) or die("Could not connect to LDAP server.");
			$set = ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
			
			if($ldapconn){
				//bind user
				$ldap_bd = ldap_bind($ldapconn,$account."@".$host_dn,$password);
				$ou = ["TainanLocalUser","TainanComputer"];
				$keyword_type = ["CN","CN"];
				//Search CN Object From LocalUser and Local Computer
				for($k=0;$k<count($ou);$k++){
					$result = @ldap_search($ldapconn,"ou=".$ou[$k].",dc=tainan,dc=gov,dc=tw","(".$keyword_type[$k]."=".$target."*)") or die ("Error in query");
					$data = @ldap_get_entries($ldapconn,$result);
					echo $data["count"]. " entries returned from ".$ou[$k]."<br><br>\n\n";
					if($data["count"]!=0){
						for($i=0; $i<$data["count"];$i++){
							echo "<form id='form-ldap' class='ui form' action='javascript:void(0)'>";
							echo "<h4 class='ui dividing header'>Entry Information</h4>";
							echo "<div class='inline fields'>";
								echo "<div class='twelve wide field'>";
								echo "Setting(".$data[$i]['cn'][0].")";
								echo "<input type='hidden' name='type' value='search' >";
								echo "</div>";
								echo "<div class='two wide field'>";
									echo "<button class='ui button' onclick='ldap_clear()'>Cancel</button>";
								echo "</div>";
								echo "<div class='two wide field'>";
									echo "<button class='ui button' onclick='ldap_edit()' >Save</button>";
								echo "</div>";
							echo "</div>";
							echo "<div class='description'>";
								echo "<div class='inline fields'>";
									echo "<label for='fruit'>Change password:</label>";
									echo "<div class='field'>";
										echo "<div class='ui radio checkbox'>";
										echo  "<input type='radio' name='pwd_changed' value='no' onchange='uncheck('new_password')' tabindex='0' checked>";
										echo  "<label>No</label>";
										echo  "</div>";
									echo  "</div>";
									echo "<div class='field'>";
										echo "<div class='ui radio checkbox'>";
										echo  "<input type='radio' name='pwd_changed' value='yes' onchange='check('new_password')' tabindex='0'>";
										echo  "<label>Yes</label>";
										echo  "</div>";
									echo  "</div>";
								echo "</div>";	
								if($k==0){
									$labelArr = ['新密碼','確認密碼','姓名','職稱','mail','電話','分機'];
									$nameArr = ['new_password','confirm_password','displayname','title','mail','telephonenumber','physicaldeliveryofficename'];
									$rArr = ['','','required','required','required','',''];
									foreach($nameArr as $key => $val){
										echo "<div class='field'>";
											echo "<label>".$labelArr[$key].$r = ($rArr[$key]=='required') ?"<font color='red'>*</font>":""."</label>";
											if(isset($data[$i][$val])){
												echo "<input type='text' name='".$val."' value='".$data[$i][$val][0]."'>";
											}else{
												if($val == "new_password" || $val == "confirm_password"){
													echo "<input type='password' name='".$val."' value='' placeholder='".$labelArr[$key]."' >";
												}else{
													echo "<input type='text' name='".$val."' value='' placeholder='".$labelArr[$key]."' >";
												}
											}
										echo "</div>";
									}
								}
							echo "<ol>";
							for ($j=0;$j<$data[$i]["count"];$j++){
								if($data[$i][$j] == "cn"){
									echo "<li>".$data[$i][$j].": ";
									echo "<div class='ui input'>";
										echo "<input type='text' name='".$data[$i][$j]."' value='".$data[$i][$data[$i][$j]][0]."' readonly='readonly'>";
									echo "</div></li>";
								}elseif($data[$i][$j] == "distinguishedname"){
									echo "<li>".$data[$i][$j].": ".$data[$i][$data[$i][$j]][0]."</li>";
									$str_sec = explode(",",$data[$i][$data[$i][$j]][0]);
									for($o=0;$o<count($str_sec);$o++){
										if(substr_compare($str_sec[$o],"OU",0,2)==0){
											//echo $str_sec[$o]."(";
											$result_ou = ldap_search($ldapconn,"ou=".$ou[$k].",dc=tainan,dc=gov,dc=tw","(".$str_sec[$o].")") or die ("Error in query");
											$data_ou = ldap_get_entries($ldapconn,$result_ou);
											if(isset($data_ou[0]['description'][0]))	echo $data_ou[0]["description"][0]."/";
										}
									}
								}
								elseif(@isset($data[$i][$j][0])) {
									if($data[$i][$j] == "dnshostname"){
										echo "<li>".$data[$i][$j].": ".$data[$i][$data[$i][$j]][0]." | ";
										$output = shell_exec("/usr/bin/dig +short ".$data[$i][$data[$i][$j]][0]);
										echo "IP: ".$output."</li>";
									}elseif($data[$i][$j] == "pwdlastset" || $data[$i][$j] == "lastlogon" || $data[$i][$j] == "badpasswordtime"){
										echo "<li>".$data[$i][$j].": ".WindowsTime2DateTime($data[$i][$data[$i][$j]][0])."</li>";
									}else{
										echo "<li>".$data[$i][$j].": ".$data[$i][$data[$i][$j]][0]."</li>";
									}
								}
							}
							echo "</ol>";
							echo "</div>";
						echo "</form>";
						
						}	
					}
				}
			}
			ldap_close($ldapconn);
			break;
		case "newuser":
			require_once("../ldap_admin_config.inc.php");
			require_once("../login/function.php");
			$ldapconn = ldap_connect($host_ip) or die("Could not connect to LDAP server.");
			$set = ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
			if($ldapconn){
				//bind user
				$ldap_bd = ldap_bind($ldapconn,$account."@".$host_dn,$password);
				$keyword = "(objectClass=organizationalUnit)";
				$result = ldap_search($ldapconn,"OU=395000000A,OU=TainanLocalUser,dc=tainan,dc=gov,dc=tw",$keyword) or die ("Error in query");
				$data = ldap_get_entries($ldapconn,$result);
				echo "<form id='form-ldap' class='ui form' action='javascript:void(0)'>";
				echo "<h4 class='ui dividing header'>New User Information</h4>";
				echo "<div class='inline fields'>";
					echo "<div class='twelve wide field'>";
					echo "Setting";
					echo "<input type='hidden' name='type' value='newuser' >";
					echo "</div>";
					echo "<div class='two wide field'>";
						echo "<button class='ui button' onclick='ldap_clear()'>Cancel</button>";
					echo "</div>";
					echo "<div class='two wide field'>";
						echo "<button class='ui button' onclick='ldap_edit()' >Save</button>";
					echo "</div>";
				echo "</div>";
				echo "<div class='description'>";
				echo "<div class='field'>";
					echo "<label>單位<font color='red'>*</font></label>";
				
					echo "<input list='brow' name='organizationalUnit' placeholder='請選擇ou' >";
					echo "<datalist id='brow' name='organizationalUnit'>";
						if($data["count"]!=0){
							for($i=0; $i<$data["count"];$i++) {
								if(isset($data[$i]['description'][0])) {
									echo "<option value='".$data[$i]['name'][0]."(".$data[$i]['description'][0].")'>";
								}else{
									echo "<option value='".$data[$i]['name'][0]."'>";
								}	
							}
						}
					echo "</datalist>";
				echo "</div>";
				//create input with name and label
				$labelArr = ['帳號','新密碼','確認密碼','姓名','職稱','mail','電話','分機'];
				$nameArr = ['cn','new_password','confirm_password','displayname','title','mail','telephonenumber','physicaldeliveryofficename'];
				$rArr = ['required','required','required','required','required','required','',''];
				foreach($nameArr as $key => $val){
					echo "<div class='field'>";
						echo "<label>".$labelArr[$key].$r = ($rArr[$key]=='required') ?"<font color='red'>*</font>":""."</label>";
						echo "</label>";
						if($val == "new_password" || $val == "confirm_password"){
							echo "<input type='password' name='".$val."' value='' placeholder='".$labelArr[$key]."' >";
						}else{
							echo "<input type='text' name='".$val."' value='' placeholder='".$labelArr[$key]."' >";
						}
					echo "</div>";
				}
				echo "</div>";
				echo "</form>";
			}
			ldap_close($ldapconn);
			break;
	}

}else{
	phpAlert("沒有輸入");
}
	

?>
