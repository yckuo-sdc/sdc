<?php
require 'PaloAltoAPI.php';

$host_map = ['yonghua1' => '172.16.254.209', 'yonghua2' => '172.16.254.208', 'minjhih' => '10.6.2.102', 'idc-fw' => '10.7.11.241', 'idc-pa' => '10.7.11.240', 'intrayonghua' => '172.16.254.205'];

foreach($host_map as $key => $host){
	$pa = new PaloAltoAPI($host);
	$xml_type = "op";
	$cmd = "<show><system><info></info></system></show>";
	$res = $pa->getXmlCmdResponse($xml_type, $cmd);
	print_r($res);
}
