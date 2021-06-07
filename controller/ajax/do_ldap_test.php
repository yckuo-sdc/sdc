<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$ad = new ad\api\WebadAPI();

$cn = "yckuo";
$new_password = "";
$confirm_password = "!!QAZ2wsx";
$displayname = "郭育傑";
$title = "管理師";
$telephonenumber = "2991111";
$physicaldeliveryofficename = "8280";
$mail = "yckuo@mail.tainan.gov.tw";
$isActive = "true";

$res = $ad->editUser($cn, $new_password, $confirm_password, $displayname, $title, $telephonenumber, $physicaldeliveryofficename, $mail, $isActive);

echo $res;

