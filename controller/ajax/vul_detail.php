<?php
if(empty($_GET['url'])){
    return;
}

$url = $_GET["url"];

if (!filter_var($url, FILTER_VALIDATE_URL)) {
    return;
}

$parse = parse_url($url);
$domain =  $parse['host'];
$domains = array('tainan-vsms.chtsecurity.com');

if (!in_array($domain, $domains)) {
    return;
}

$cookie = __DIR__ . "/tmp/cookies.txt";

// Get cookie from login page
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, ChtSecurity::LOGIN_URL);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 忽略SSL
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 自動跳轉

$response = curl_exec($ch);
if (curl_errno($ch)) die(curl_error($ch));

// Get csrfToken from login page
$dom = new DomDocument();
@$dom->loadHTML($response);
$xpath = new DOMXpath($dom);
$nodes = $xpath->query("//input[@name='csrfToken']");
$node = $nodes->item(0);
$token = $node->getAttribute('value');

$postinfo = "username=" . ChtSecurity::USERNAME . "&userpass=" . ChtSecurity::PASSWORD . "&csrfToken=" . $token . "&search2=確認";

// Login action 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 忽略SSL
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 自動跳轉

$html = curl_exec($ch);
if (curl_errno($ch)) print curl_error($ch);

// Get http response body 
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 忽略SSL
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 自動跳轉

$html2 = curl_exec($ch);
if (curl_errno($ch)) print curl_error($ch);
print $html2;

curl_close($ch);
