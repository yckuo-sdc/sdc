<?php
require __DIR__ . '/vendor/autoload.php';
$ad = new ad\api\WebadAPI();
$res = $ad->editUser($cn = "yckuo", $new_password="", $displayname="郭育傑", $title="管理師8", $telephonenumber="2991111", $physicaldeliveryofficename="8280", $mail="yckuo@mail.tainan.gov.tw", $isActive=true);
echo $res;


