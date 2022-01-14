<?php 
$pa = new PaloAltoAPI();
$object_type = 'ApplicationGroups'; 
$res = $pa->getObjectList($object_type, $name="");
$status = $res['@status'];
$apps = $res['result']['entry'];

require 'view/header/default.php'; 
require 'view/body/network/allowlist.php';
require 'view/footer/default.php'; 
