<?php
	header('Content-type: text/html; charset=utf-8');

	if(!empty($_GET['target'])){
		$target = $_GET['target'];
		// connect to AD server
		require_once("../ldap_admin_config.inc.php");
		require_once("../login/function.php");
		$ldapconn = ldap_connect($host_ip) or die("Could not connect to LDAP server.");
		$set = ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
	    
		if($ldapconn){
			//bind user
			$ldap_bd = ldap_bind($ldapconn,$account."@".$host_dn,$password);
			$ou = ["TainanLocalUser","TainanComputer","TainanComputer"];
			$keyword_type = ["CN","CN","objectClass"];
			//Search CN Object From LocalUser and Local Computer
			for($k=0;$k<count($ou);$k++){
				$result = @ldap_search($ldapconn,"ou=".$ou[$k].",dc=tainan,dc=gov,dc=tw","(".$keyword_type[$k]."=".$target."*)") or die ("Error in query");
				$data = @ldap_get_entries($ldapconn,$result);
				echo $data["count"]. " entries returned from ".$ou[$k]."<br><br>\n\n";
				if($data["count"]!=0){
					if($k==2){
						echo "<div class='description'>";
							echo "<ol>";
					}
					for($i=0; $i<$data["count"];$i++){
						if($k==2){
							if(@isset($data[$i]['cn'][0]))	echo "<li>".$data[$i]['cn'][0];
							if(@isset($data[$i]['description'][0]))	echo " | ".$data[$i]['description'][0]."</li>";
						}else{
						echo "<form id='form-ldap' class='ui form' action='javascript:void(0)'>";
						echo "<h4 class='ui dividing header'>Entry Information</h4>";
						echo "<div class='inline fields'>";
							echo "<div class='twelve wide field'>";
							echo "Setting(".$data[$i]['cn'][0].")";
							echo "</div>";
							echo "<div class='two wide field'>";
								echo "<button class='ui button' onclick='ldap_clear()'>Cancel</button>";
							echo "</div>";
							echo "<div class='two wide field'>";
								echo "<button class='ui button' onclick='ldap_edit()' >Save</button>";
							echo "</div>";
						echo "</div>";
						echo "<div class='description'>";
						echo "<ol>";
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
							echo "<li>New Password: ";
								echo "<div class='ui input'>";
									echo "<input type='text' id='new_password' name='new_password' value='' placeholder='new password' disabled>";
								echo "</div>";
							echo "</li>";
							echo "<li>New Password: ";
								echo "<div class='ui input'>";
									echo "<input type='text' id='confirm_password' name='confirm_password' value='' placeholder='confirm_password' disabled>";
								echo "</div></li>";
							echo "</li>";
						for ($j=0;$j<$data[$i]["count"];$j++){
							if(@isset($data[$i][$j][0])) {
								if($data[$i][$j] == "displayname" || $data[$i][$j] == "title" || $data[$i][$j] == "description" || $data[$i][$j] == "telephonenumber" || $data[$i][$j] == "mail" || $data[$i][$j] == "physicaldeliveryofficename" ){
									echo "<li>".$data[$i][$j].": ";
									echo "<div class='ui input'>";
										echo "<input type='text' name='".$data[$i][$j]."' value='".$data[$i][$data[$i][$j]][0]."' placeholder='".$data[$i][$data[$i][$j]][0]."'>";
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
									echo "<input type='hidden' name='".$data[$i][$j]."' value='".$data[$i][$data[$i][$j]][0]."' placeholder='".$data[$i][$data[$i][$j]][0]."'>";
								}elseif($data[$i][$j] == "dnshostname"){
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
					if($k==2){
						echo "</ol></div>";
					}
				}
			}
		}
		ldap_close($ldapconn);

	}else{
		phpAlert("沒有輸入");
	}
	

?>
