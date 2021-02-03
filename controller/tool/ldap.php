<?php 
$ld = new MyLDAP();
$target = "yckuo";

$data_array = array();
$data_array['base'] =  "ou=YongHua,ou=TainanComputer,dc=tainan,dc=gov,dc=tw";
$data_array['filter'] = "(objectClass=organizationalUnit)";
$data_array['attributes'] = array("name", "description");
$OUs = $ld->getData($data_array);

require 'view/header/default.php'; 
require 'view/body/tool/ldap.php';
require 'view/footer/default.php'; 
