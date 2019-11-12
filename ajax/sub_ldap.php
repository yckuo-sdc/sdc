<?php
	header('Content-type: text/html; charset=utf-8');
	//alert message
	function phpAlert($msg) {
		echo '<script type="text/javascript">alert("' . $msg . '")</script>';
	}

	if(!empty($_GET['target'])){
		$target = $_GET['target'];
		// connect to AD server
		require("../ldap_config.inc.php");
		$host = "tainan.gov.tw";
		$ldapconn = ldap_connect($host) or die("Could not connect to LDAP server.");
		$set = ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

		$ldap_bd = ldap_bind($ldapconn,$account."@".$host,$password);

	    $ou = ["TainanLocalUser","TainanComputer"];
		//Search CN Object From LocalUser and Local Computer
		for($k=0;$k<count($ou);$k++){
			$result = @ldap_search($ldapconn,"ou=".$ou[$k].",dc=tainan,dc=gov,dc=tw","(CN=".$target.")") or die ("Error in query");
				$data = @ldap_get_entries($ldapconn,$result);
				echo $data["count"]. " entries returned from ".$ou[$k]."<br><br>\n\n";
				if($data["count"]!=0){
					for($i=0; $i<$data["count"];$i++){
						echo "<form id='form-ldap' class='ui form' action='javascript:void(0)'>";
						echo "<h4 class='ui dividing header'>Entry Information</h4>";
						echo "<div class='inline fields'>";
							echo "<div class='twelve wide field'>";
							echo "Setting";
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
						for ($j=0;$j<=$data[$i]["count"];$j++){
							if(@isset($data[$i][$j][0])) {
								if($data[$i][$j] == "displayname" || $data[$i][$j] == "title" || $data[$i][$j] == "description" || $data[$i][$j] == "telephonenumber" || $data[$i][$j] == "mail" || $data[$i][$j] == "physicaldeliveryofficename" ){
									echo "<li>".$data[$i][$j].": ";
									echo "<div class='ui input'>";
										echo "<input type='text' name='".$data[$i][$j]."' value='".$data[$i][$data[$i][$j]][0]."' placeholder='".$data[$i][$data[$i][$j]][0]."'>";
									echo "</div></li>";
								}elseif($data[$i][$j] == "distinguishedname"){
									echo "<li>".$data[$i][$j].": ".$data[$i][$data[$i][$j]][0]."</li>";
									echo "<input type='hidden' name='".$data[$i][$j]."' value='".$data[$i][$data[$i][$j]][0]."' placeholder='".$data[$i][$data[$i][$j]][0]."'>";
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
		ldap_close($ldapconn);

	}else{
		phpAlert("沒有輸入");
	}
	

?>
