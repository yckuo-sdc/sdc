<?php
//fetch dom from url
function self_curl($url,$type){
	$ch = curl_init();
    $timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_REFERER, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	$data = curl_exec($ch);
	curl_close($ch);
	switch($type){
		case "js":
			$data = "<script type='text/javascript'>".$data."</script>";
			break;
		case "css":
			$data = "<style type='text/css'>".$data."</style>";
			break;
	}
	return $data;
}

//Get html content and link(css+img)
if (isset($_GET['url'])) {
	$url = $_GET['url'];
	$ch = curl_init();
    $timeout = 5;
	if($url=="https://sdc-mrbs.tainan.gov.tw"){   
		curl_setopt($ch, CURLOPT_URL, $url."/month.php?room=3");
	}else{
		curl_setopt($ch, CURLOPT_URL, $url);
	}
	curl_setopt($ch, CURLOPT_REFERER, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	$data = curl_exec($ch);
    curl_close($ch);
   	$data = str_replace('src="','src="'.$url.'/',$data);
	$data = str_replace('href="','href="'.$url.'/',$data);
	
	//fecth all html-links of css
	if(preg_match_all('/<link\s+href=["\']([^"\']+)["\']/i', $data, $links, PREG_PATTERN_ORDER)){
	   	$all_hrefs = array_unique($links[1]);
		foreach($all_hrefs as $href){
			$return_data = self_curl($href,"css");
			$data = str_replace("<head>","<head>".$return_data,$data);
		}
	}
	//remove all html-links of a-href
	if(preg_match_all('/<a\s+href=["\']([^"\']+)["\']/i', $data, $links, PREG_PATTERN_ORDER)){
	   	$all_hrefs = array_unique($links[1]);
		foreach($all_hrefs as $href){
			$data = str_replace($href,"javascript:void(0)",$data);
		}
	}
	/*
	//fetch source of javascript
	if(preg_match_all('/<script\s+src=["\']([^"\']+)["\']/i', $data, $links, PREG_PATTERN_ORDER)){
	   	$all_hrefs = array_unique($links[1]);
		foreach($all_hrefs as $href){
			//echo $href."<br>";
			$return_data = self_curl($href,"js");
			$data = str_replace("<head>","<head>".$return_data,$data);
		}
	}
	 */
	//fetch special link of css	
	if($url=="https://sdc-mrbs.tainan.gov.tw"){  
		$url = "https://sdc-mrbs.tainan.gov.tw/css/mrbs-print.css.php";
		$return_data = self_curl($url,"css");
		$data = str_replace("<head>","<head>".$return_data,$data);
		$url = "https://sdc-mrbs.tainan.gov.tw/css/mrbs.css.php";
		$return_data = self_curl($url,"css");
		$data = str_replace("<head>","<head>".$return_data,$data);
		$data = str_replace("<head>","<head><style>#month_main div.I{ border: 0px !important;}</style>",$data);
	}	
 	echo $data;
}
?>

