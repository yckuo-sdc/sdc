<?php
require '../vendor/autoload.php';

// input validation
$v1 = 0;
$v2 = 0;
foreach($_GET as $getkey => $val){
	$$getkey = $val;
	if($getkey == "jsonObj" && $val == "[]"){
		$v1 = 1;	
	}elseif($getkey != "jsonObj" && $val == ""){
		$v2 = 1;	
	}
}

if($v1 && $v2){
	echo "沒有輸入";
	return 0;
}

$db = Database::get();

$page = isset($page) ? $page : 1;
$ap = isset($ap) ? $ap : 'html';

$arr_jsonObj = json_decode($jsonObj,true);

//table switch
switch(true){
	case ($type == 'security_event'):
		$condition_table = "security_event";
		$table = "security_event";
		$order_by = "EventID DESC, OccurrenceTime DESC";	
		break;
	case ($type == 'ncert'):
		$condition_table = "tainangov_security_Incident";
		$table = "tainangov_security_Incident";
		$order_by = "IncidentID DESC, OccurrenceTime DESC";	
		break;
	case ($type == 'security_contact'):
		$condition_table = "security_contact";
		$table = "(select * from security_contact union select * from security_contact_extra)a";
		$table = "(SELECT a.*, b.rank FROM( SELECT * FROM security_contact UNION SELECT * FROM security_contact_extra ORDER by OID asc,person_type asc )a LEFT JOIN security_rank AS b ON a.OID = b.OID)v";
		$order_by = "oid, person_type";	
		break;
	case ($type == 'gcb_client_list'):
		$condition_table = "gcb_client_list";
		$table = "(SELECT a.*,b.name as os_name,c.name as ie_name FROM gcb_client_list as a LEFT JOIN gcb_os as b ON a.OSEnvID = b.id LEFT JOIN gcb_ie as c ON a.IEEnvID = c.id)A";
		if($keyword == 'ExternalIP' or $keyword == 'InternalIP') $key = ip2long($key);
		$order_by = "ID";	
		break;
	case ($type == 'wsus_client_list'):
		$condition_table = "wsus_computer_status";
		$table = "wsus_computer_status";
		$order_by = "TargetID";	
		break;
	case ($type == 'antivirus_client_list'):
		$condition_table = "antivirus_client_list";
		$table = "antivirus_client_list";
		$order_by = "GUID";	
		break;
	case ($type == 'drip_client_list'):
		$condition_table = "drip_client_list";
		$table = "drip_client_list";
		$order_by = "DetectorName ,IP";	
		break;
}

//retrieve condition
$data_array = [];
if( count($arr_jsonObj) !=0 ){
	$condition = "";
	foreach($arr_jsonObj as $val){
		if($val['keyword'] == "all"){
			$res = $db->getFullTextSearchCondition($condition_table, $val['key']);
			$one_condition = "(".$res['condition'].") "; 
			$data_array = array_merge($data_array, $res['data']);
		}else{
			$one_condition = $val['keyword']." LIKE ?";
			$data_array[] = "%".$val['key']."%"; 
		}
		$condition = $condition." AND ".$one_condition;
	}
	$condition = substr($condition,4);
}else{
	if($keyword == "all"){
		$res = $db->getFullTextSearchCondition($condition_table, $key);
		$condition = "(".$res['condition'].") "; 
		$data_array = $res['data'];
	}else{
		$condition = $keyword." LIKE ?";
		$data_array[] = "%".$key."%"; 
	}
}

//echo $condition."<br>";
$table = $table; // 設定你想查詢資料的資料表
$total_entries = $db->query($table, $condition, $order_by, $fields = "*", $limit = "", $data_array);
$last_num_rows = $db->getLastNumRows();

if($ap=='html'){
	if ($last_num_rows == 0){
		echo "很抱歉，該分類目前沒有資料！";
	}else{
		echo "該分類共搜尋到".$last_num_rows."筆資料！";
		$pageParm = getPaginationParameter($page, $last_num_rows);
		$limit = "limit ".($start = $pageParm['start']).",".($offset = $pageParm['offset']);
		$entries = $db->query($table, $condition, $order_by, $fields = "*", $limit, $data_array);
		
		switch($type){
			case "security_event": 
			echo "<div class='ui relaxed divided list'>";
				echo "<div class='item'>";
					echo "<div class='content'>";
						echo "<a class='header'>";
						echo "<a>";
					echo "</div>";
				echo "</div>";
					foreach($entries as $entry) {
						echo "<div class='item'>";
						echo "<div class='content'>";
							echo "<a>";
							if($entry['Status']=="已結案")echo "<i class='check circle icon' style='color:green'></i>";
							else echo "<i class='exclamation circle icon'></i>";
							echo date_format(new DateTime($entry['OccurrenceTime']),'Y-m-d')."&nbsp&nbsp";
							echo $entry['Status']."&nbsp&nbsp";
							echo "<span style='background:#fde087'>".$entry['EventTypeName']."</span>&nbsp&nbsp";
							echo $entry['Location']."&nbsp&nbsp";
							echo "<span style='background:#DDDDDD'>".$entry['IP']."</span>&nbsp&nbsp";
							echo $entry['DeviceOwnerName']."&nbsp&nbsp";
							echo $entry['DeviceOwnerPhone']."&nbsp&nbsp";
							echo "<i class='angle down icon'></i>";
							echo "</a>";
							echo "<div class='description'>";
								echo "<ol>";
								echo "<li>序號:".$entry['EventID']."</li>";
								echo "<li>結案狀態:".$entry['Status']."</li>";
								echo "<li>發現日期:".date_format(new DateTime($entry['OccurrenceTime']),'Y-m-d')."</li>";
								echo "<li>資安事件類型:".$entry['EventTypeName']."</li>";
								echo "<li>位置:".$entry['Location']."</li>";
								echo "<li>電腦IP:".$entry['IP']."</li>";
								echo "<li>封鎖原因:".$entry['BlockReason']."</li>";
								echo "<li>設備類型:".$entry['DeviceTypeName']."</li>";
								echo "<li>電腦所有人姓名:".$entry['DeviceOwnerName']."</li>";
								echo "<li>電腦所有人分機:".$entry['DeviceOwnerPhone']."</li>";
								echo "<li>機關:".$entry['AgencyName']."</li>";
								echo "<li>單位:".$entry['UnitName']."</li>";
								echo "<li>處理日期(國眾):".$entry['NetworkProcessContent']."</li>";
								echo "<li>處理日期(三佑科技):".$entry['MaintainProcessContent']."</li>";
								echo "<li>處理日期(京稘或中華SOC):".$entry['AntivirusProcessContent']."</li>";
								echo "<li>未能處理之原因及因應方式:".$entry['UnprocessedReason']."</li>";
								echo "<li>備註:".$entry['Remarks']."</li>";
								echo "</ol>";
							echo "</div>";
							echo "</div>";
						echo "</div>";

					}
				echo "</div>";
				break;
			case "ncert": 
				echo "<div class='ui relaxed divided list'>";
					echo "<div class='item'>";
						echo "<div class='content'>";
							echo "<a class='header'>";
							echo "</a>";
						echo "</div>";
					echo "</div>";

					foreach($entries as $entry) {
						echo "<div class='item'>";
						echo "<div class='content'>";
							echo "<a>";
							echo date_format(new DateTime($entry['OccurrenceTime']),'Y-m-d')."&nbsp&nbsp";
							echo $entry['Status']."&nbsp&nbsp";
							echo "<span style='background:#DDDDDD'>".$entry['ImpactLevel']."</span>&nbsp&nbsp";
							echo $entry['Classification']."&nbsp&nbsp";
							echo "<span style='background:#fde087'>".$entry['PublicIP']."</span>&nbsp&nbsp";
							echo $entry['OrganizationName'];
							echo "<i class='angle down icon'></i>";
							echo "</a>";
							
							echo "<div class='description'>";
								echo "<ol>";
								echo "<li>編號:".$entry['IncidentID']."</li>";
								echo "<li>結案狀態:".$entry['Status']."</li>";
								echo "<li>事件編號:".$entry['NccstID']."</li>";	
								echo "<li>行政院攻防演練:".$entry['NccstPT']."</li>";
								echo "<li>攻防演練衝擊性:".$entry['NccstPTImpact']."</li>";
								echo "<li>機關名稱:".$entry['OrganizationName']."</li>";
								echo "<li>聯絡人:".$entry['ContactPerson']."</li>";
								echo "<li>電話:".$entry['Tel']."</li>";
								echo "<li>電子郵件:".$entry['Email']."</li>";
								echo "<li>資安維護廠商:".$entry['SponsorName']."</li>";
								echo "<li>對外IP或網址:".$entry['PublicIP']."</li>";
								echo "<li>使用用途:".$entry['DeviceUsage']."</li>";
								echo "<li>作業系統:".$entry['OperatingSystem']."</li>";
								echo "<li>入侵網址:".$entry['IntrusionURL']."</li>";
								echo "<li>影響等級:".$entry['ImpactLevel']."</li>";
								echo "<li>事故分類:".$entry['Classification']."</li>";
								echo "<li>事故說明:".$entry['Explaination']."</li>";
								echo "<li>影響評估:".$entry['Evaluation']."</li>";
								echo "<li>應變措施:".$entry['Response']."</li>";
								echo "<li>解決辦法/結報內容:".$entry['Solution']."</li>";
								echo "<li>發生時間:".$entry['OccurrenceTime']."</li>";
								echo "<li>通報時間:".$entry['InformTime']."</li>";	
								echo "<li>修復時間:".$entry['RepairTime']."</li>";
								echo "<li>審核機關審核時間:".$entry['TainanGovVerificationTime']."</li>";
								echo "<li>技服中心審核時間:".$entry['NccstVerificationTime']."</li>";
								echo "<li>通報結報時間:".$entry['FinishTime']."</li>";	
								echo "<li>通報執行時間(時:分):".$entry['InformExecutionTime']."</li>";
								echo "<li>結案執行時間(時:分):".$entry['FinishExecutionTime']."</li>";
								echo "<li>中華SOC複測結果:".$entry['SOCConfirmation']."</li>";
								echo "<li>改善計畫提報日期:".$entry['ImprovementPlanTime']."</li>";	
								echo "</ol>";
							echo "</div>";
							echo "</div>";
						echo "</div>";
					}
				echo "</div>";
				break;
			case "security_contact":
			$condition = $condition." GROUP BY OID";
			$fields = "OID";
			$db->query($table, $condition, $order_by = "1", $fields, $limit = "", $data_array);
			$oid_num = $db->getLastNumRows();
		
			echo "該分類共搜尋到".$oid_num."個機關！";
			echo "<div class='ui relaxed divided list'>";
				echo "<div class='item'>";
					echo "<div class='content'>";
						echo "<a class='header'>";
						echo "<a>";
					echo "</div>";
				echo "</div>";

				foreach($entries as $entry) {
					echo "<div class='item'>";
					echo "<div class='content'>";
						echo "<a>";
						echo $entry['organization']."&nbsp&nbsp";
						if( !empty($entry['rank'] )) echo "<span style='color:#f80000'>".$entry['rank']."</span>&nbsp&nbsp";
						echo $entry['person_name']."&nbsp&nbsp";
						echo "<span style='background:#fde087'>".$entry['person_type']."</span>&nbsp&nbsp";
						echo $entry['email']."&nbsp&nbsp";
						echo "<span style='background:#DDDDDD'>".$entry['tel']."#".$entry['ext']."</span>&nbsp&nbsp";
						echo "<i class='angle down icon'></i>";
						echo "</a>";
						echo "<div class='description'>";
							echo "<ol>";
							echo "<li>序號:".$entry['CID']."</li>";
							echo "<li>OID:".$entry['OID']."</li>";
							echo "<li>資安責任等級:".$entry['rank']."</li>";
							echo "<li>機關名稱:".$entry['organization']."</li>";
							echo "<li>姓名:".$entry['person_name']."</li>";
							echo "<li>單位名稱:".$entry['unit']."</li>";
							echo "<li>職稱:".$entry['position']."</li>";
							echo "<li>資安聯絡人類型:".$entry['person_type']."</li>";
							echo "<li>地址:".$entry['address']."</li>";
							echo "<li>電話:".$entry['tel']."</li>";
							echo "<li>分機:".$entry['ext']."</li>";
							echo "<li>傳真:".$entry['fax']."</li>";
							echo "<li>email:".$entry['email']."</li>";
							echo "</ol>";
						echo "</div>";
					echo "</div>";
					echo "</div>";
				}
			echo "</div>";
			break;
			case "gcb_client_list":
				echo "<div class='ui relaxed divided list'>";
					echo "<div class='item'>";
						echo "<div class='content'>";
							echo "<a class='header'>";
							echo "電腦名稱&nbsp&nbsp";
							echo "單位名稱&nbsp&nbsp";
							echo "使用者帳號&nbsp&nbsp";
							echo "內網IP&nbsp&nbsp";
							echo "作業系統&nbsp&nbsp";
							echo "<a>";
						echo "</div>";
					echo "</div>";
			foreach($entries as $entry) {
				echo "<div class='item'>";
				echo "<div class='content'>";
					echo "<a>";
					if($entry['IsOnline'] == "1")		echo "<i class='circle green icon'></i>";
					else							echo "<i class='circle outline icon'></i>";
					switch($entry['GsStat']){
						case '0':
							$GsStat_str = "未套用";
							break;
						case '1':
							$GsStat_str = "已套用";
							break;
						case '-1':
							$GsStat_str = "套用失敗";
							break;
						case '2':
							$GsStat_str = "還原成功";
							break;
						case '-2':
							$GsStat_str = "未套用";
							break;
					}
					
					echo $entry['Name']."&nbsp&nbsp";
					echo "<span style='background:#fde087'>".$entry['OrgName']."</span>&nbsp&nbsp";
					echo $entry['UserName']."&nbsp&nbsp";
					echo $entry['Owner']."&nbsp&nbsp";
					echo "<span style='background:#DDDDDD'>".long2ip($entry['InternalIP'])."</span>&nbsp&nbsp";
					echo $entry['os_name']."&nbsp&nbsp";
					echo "<i class='angle down icon'></i>";
					echo "</a>";
					echo "<div class='description'>";
						echo "<ol>";
						echo "<li><a href='ajax/gcb_detail.php?action=detail&id=".$entry['ID']."' target='_blank'>序號:".$entry['ID']."&nbsp<i class='external alternate icon'></i></a></li>";
						echo "<li>外部IP:".long2ip($entry['ExternalIP'])."</li>";
						echo "<li>內部IP:".long2ip($entry['InternalIP'])."</li>";
						echo "<li>電腦名稱:".$entry['Name']."</li>";
						echo "<li>單位名稱:".$entry['OrgName']."</li>";
						echo "<li>使用者帳號:".$entry['UserName']."</li>";
						echo "<li>使用者名稱:".$entry['Owner']."</li>";
						echo "<li>OS:".$entry['os_name']."</li>";
						echo "<li>IE:".$entry['ie_name']."</li>";
						echo "<li>是否上線:".$entry['IsOnline']."</li>";
						echo "<li>Gcb總通過數[未包含例外]:".$entry['GsAll_0']."</li>";
						echo "<li>Gcb總通過數[包含例外]:".$entry['GsAll_1']."</li>";
						echo "<li>Gcb總通過數[總數]:".$entry['GsAll_2']."</li>";
						echo "<li>Gcb例外數量:".$entry['GsExcTot']."</li>";
						echo "<li><a href='ajax/gcb_detail.php?action=gscan&id=".$entry['GsID']."' target='_blank'>Gcb掃描編號:".$entry['GsID']."&nbsp<i class='external alternate icon'></i></a></li>";
						echo "<li>Gcb派送編號:".$entry['GsSetDeployID']."</li>";
						echo "<li>Gcb狀態:".$GsStat_str."</li>";
						echo "<li>Gcb回報時間:".$entry['GsUpdatedAt']."</li>";
						echo "</ol>";
					echo "</div>";
					echo "</div>";
				echo "</div>";
			}
			
			echo "</div>";
			break;
			case "wsus_client_list":
				echo "<div class='ui relaxed divided list'>";
			foreach($entries as $entry) {
				$table = "wsus_computer_updatestatus_kbid";
				$order_by = "ID";
				$condition = "TargetID = :TargetID AND UpdateState = :UpdateState";
				$data_array = [':TargetID'=>$entry['TargetID'],':UpdateState'=>'NotInstalled'];
				$notinstalled_kb = $db->query($table, $condition, $order_by, $fields = "*", $limit = "",$data_array);
				$data_array = [':TargetID'=>$entry['TargetID'],':UpdateState'=>'Failed'];
				$failed_kb = $db->query($table, $condition, $order_by, $fields = "*", $limit = "", $data_array);
				echo "<div class='item'>";
				echo "<div class='content'>";
					echo "<a>";
					echo strtoupper(str_replace(".tainan.gov.tw","",$entry['FullDomainName']))."&nbsp&nbsp";
					echo "<span style='background:#fde087'>".$entry['IPAddress']."</span>&nbsp&nbsp";
					echo "<span style='background:#DDDDDD'>".$entry['NotInstalled']."</span>&nbsp&nbsp";
					echo "<span style='background:#fbc5c5'>".$entry['Failed']."</span>&nbsp&nbsp";
					echo $entry['OSDescription'];
					echo "<i class='angle down icon'></i>";
					echo "</a>";
					echo "<div class='description'>";
						echo "<ol>";
						echo "<li>序號:".$entry['TargetID']."</li>";
						echo "<li>電腦名稱:".strtoupper(str_replace(".tainan.gov.tw","",$entry['FullDomainName']))."</li>";
						echo "<li>內網IP:".$entry['IPAddress']."</li>";
						echo "<li>未知更新數量:".$entry['Unknown']."</li>";
						echo "<li>未安裝更新數量:".$entry['NotInstalled']."</li>";
						foreach($notinstalled_kb as $kb) {
							echo "<strong>KB".$kb['KBArticleID']."</strong> ";	
						}	
						echo "<li>已下載更新數量:".$entry['Downloaded']."</li>";
						echo "<li>已安裝更新數量:".$entry['Installed']."</li>";
						echo "<li>安裝失敗更新數量:".$entry['Failed']."</li>";
						foreach($failed_kb as $kb) {
							echo "<strong>KB".$kb['KBArticleID']."</strong> ";	
						}	
						echo "<li>已安裝待重開機更新數量:".$entry['InstalledPendingReboot']."</li>";
						echo "<li>上次狀態回報日期:".dateConvert($entry['LastReportedStatusTime'])."</li>";
						echo "<li>上次更新重開機日期:".dateConvert($entry['LastReportedRebootTime'])."</li>";
						echo "<li>上次可用更新日期:".dateConvert($entry['EffectiveLastDetectionTime'])."</li>";
						echo "<li>上次同步日期:".dateConvert($entry['LastSyncTime'])."</li>";
						echo "<li>上次修改日期:".dateConvert($entry['LastChangeTime'])."</li>";
						echo "<li>上次同步結果:".$entry['LastSyncResult']."</li>";
						echo "<li>製造商:".$entry['ComputerMake']."</li>";
						echo "<li>型號:".$entry['ComputerModel']."</li>";
						echo "<li>作業系統:".$entry['OSDescription']."</li>";
						echo "</ol>";
					echo "</div>";
					echo "</div>";
				echo "</div>";
			}
			echo "</div>";
			break;
			case "antivirus_client_list":
			echo "<div class='ui relaxed divided list'>";
			foreach($entries as $entry) {
				echo "<div class='item'>";
				echo "<div class='content'>";
					echo "<a>";
					if($entry['ConnectionState'] == "線上")	echo "<i class='circle green icon'></i>";
					else									echo "<i class='circle outline icon'></i>";
					echo $entry['ClientName']."&nbsp&nbsp";
					echo "<span style='background:#fde087'>".$entry['IP']."</span>&nbsp&nbsp";
					echo "<span style='background:#fbc5c5'>".$entry['OS']."</span>&nbsp&nbsp";
					echo $entry['VirusNum']."&nbsp&nbsp";
					echo $entry['SpywareNum']."&nbsp&nbsp";
					echo "<span style='background:#DDDDDD'>".$entry['VirusPatternVersion']."</span>&nbsp&nbsp";
					echo $entry['LogonUser']."&nbsp&nbsp";
					echo "<i class='angle down icon'></i>";
					echo "</a>";
				echo "<div class='description'>";
					echo "<ol>";
					echo "<li>設備名稱:".$entry['ClientName']."</li>";
					echo "<li>內網IP:".$entry['IP']."</li>";
					echo "<li>網域階層:".$entry['DomainLevel']."</li>";
					echo "<li>連線狀態:".$entry['ConnectionState']."</li>";
					echo "<li>GUID:".$entry['GUID']."</li>";
					echo "<li>掃描方式:".$entry['ScanMethod']."</li>";
					echo "<li>DLP狀態:".$entry['DLPState']."</li>";
					echo "<li>病毒數量:".$entry['VirusNum']."</li>";
					echo "<li>間諜程式數量:".$entry['SpywareNum']."</li>";
					echo "<li>作業系統:".$entry['OS']."</li>";
					echo "<li>位元版本:".$entry['BitVersion']."</li>";
					echo "<li>MAC位址:".$entry['MAC']."</li>";
					echo "<li>設備版本:".$entry['ClientVersion']."</li>";
					echo "<li>病毒碼版本:".$entry['VirusPatternVersion']."</li>";
					echo "<li>登入使用者:".$entry['LogonUser']."</li>";
					echo "</ol>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
			}		
			echo "</div>";
			break;
			case "drip_client_list":
			echo "<div class='ui relaxed divided list'>";
			foreach($entries as $entry) {
				echo "<div class='item'>";
				echo "<div class='content'>";
					echo "<a>";
					if($entry['ad']==1) echo "<i class='circle yellow icon'></i>";
					else echo "<i class='circle outline icon'></i>";
					if($entry['gcb']==1) echo "<i class='circle green icon'></i>";
					else echo "<i class='circle outline icon'></i>";
					if($entry['wsus']==1) echo "<i class='circle red icon'></i>";
					else echo "<i class='circle outline icon'></i>";
					if($entry['antivirus']==1) echo "<i class='circle blue icon'></i>";
					else echo "<i class='circle outline icon'></i>";
					echo $entry['DetectorName']."&nbsp&nbsp";
					echo "<span style='background:#fde087'>".$entry['IP']."</span>&nbsp&nbsp";
					echo $entry['ClientName']."&nbsp&nbsp";
					echo "<span style='background:#fbc5c5'>".$entry['OrgName']."</span>&nbsp&nbsp";
					echo $entry['Owner']."&nbsp&nbsp";
					echo $entry['UserName']."&nbsp&nbsp";
					echo "<i class='angle down icon'></i>";
					echo "</a>";
				echo "<div class='description'>";
					echo "<ol>";
					echo "<li>內網IP:".$entry['IP']."</li>";
					echo "<li>MAC位址:".$entry['MAC']."</li>";
					echo "<li>設備名稱:".$entry['ClientName']."</li>";
					echo "<li>群組名稱:".$entry['GroupName']."</li>";
					echo "<li>網卡製造商:".$entry['NICProductor']."</li>";
					echo "<li>偵測器名稱:".$entry['DetectorName']."</li>";
					echo "<li>偵測器IP:".$entry['DetectorIP']."</li>";
					echo "<li>偵測器群組:".$entry['DetectorGroup']."</li>";
					echo "<li>交換器名稱:".$entry['SwitchName']."</li>";
					echo "<li>連接埠名稱:".$entry['PortName']."</li>";
					echo "<li>最後上線時間:".$entry['LastOnlineTime']."</li>";
					echo "<li>最後下線時間:".$entry['LastOfflineTime']."</li>";
					echo "<li>IP封鎖原因:".$entry['IP_BlockReason']."</li>";
					echo "<li>MAC封鎖原因:".$entry['MAC_BlockReason']."</li>";
					echo "<li>備註ByIP:".$entry['MemoByIP']."</li>";
					echo "<li>備註ByMac:".$entry['MemoByMAC']."</li>";
					echo "<li>ad安裝:".$entry['ad']."</li>";
					echo "<li>gcb安裝:".$entry['gcb']."</li>";
					echo "<li>wsus安裝:".$entry['wsus']."</li>";
					echo "<li>antivirus安裝:".$entry['antivirus']."</li>";
					echo "<li>OrgName:".$entry['OrgName']."</li>";
					echo "<li>Owner:".$entry['Owner']."</li>";
					echo "<li>UserName:".$entry['UserName']."</li>";
					echo "</ol>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
			}		
			echo "</div>";
			break;
		}
		
		$pageAttr['key'] = $key;	
		$pageAttr['keyword'] = $keyword;	
		$pageAttr['type'] = $type;	
		$pageAttr['jsonObj'] = $jsonObj;	
		echo createPaginationElement($pageParm, $page, $pageAttr);
	}
}elseif($ap='csv'){
	$arrs=array();
	switch($type){
		case 'security_event': 
			break;
		case 'ncert': 
			break;
		case 'security_contact': 
			break;
		case 'gcb_client_list': 
			break;
		case 'wsus_client_list': 
			break;
		case 'antivirus_client_list': 
			break;
		case 'drip_client_list': 
			foreach($total_entries as $entry) {
				foreach($entry as $key => $val){
					$arr[$key] = $val;
				}
				array_push($arrs,$arr);	
			}
			array_to_csv_download($arrs,"export.csv",";"); 	
			break;
	}
}
