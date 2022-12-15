<?php
$ld = new MyLDAP();
$ad = new ad\api\WebadAPI();
$dist_ou_array = array(
'D51' => '395510000A', 'D51' => '395510000A', 'D52' => '395520000A', 'D53' => '395530000A', 'D54' => '395540000A', 'D55' => '395550000A', 'D56' => '395560000A', 'D57' => '395570000A', 'D58' => '395580000A', 'D59' => '395590000A', 'D60' => '395600000A', 'D61' => '395610000A', 'D62' => '395620000A', 'D63' => '395630000A', 'D64' => '395640000A', 'D65' => '395650000A', 'D66' => '395660000A', 'D67' => '395670000A', 'D68' => '395680000A', 'D69' => '395690000A', 'D70' => '395700000A', 'D71' => '395710000A', 'D72' => '395720000A', 'D73' => '395730000A', 'D74' => '395740000A', 'D75' => '395750000A', 'D76' => '395760000A', 'D77' => '395770000A', 'D78' => '395780000A', 'D79' => '395790000A', 'D80' => '395800000A', 'D81' => '395810000A', 'D82' => '395820000A', 'D83' => '395830000A', 'D84' => '395840000A', 'D85' => '395850000A', 'D86' => '395860000A', 'D87' => '395870000A',
);

$dist_cidr_array = array(
'10.11.4.0/24' => 'D51', '10.11.8.0/24' => 'D52', '10.11.12.0/24' => 'D53', '10.11.16.0/24' => 'D54', '10.11.20.0/24' => 'D55', '10.11.24.0/24' => 'D56', '10.12.4.0/24' => 'D57', '10.12.8.0/24' => 'D58', '10.12.12.0/24' => 'D59', '10.12.28.0/24' => 'D60', '10.12.48.0/24' => 'D61', '10.12.72.0/24' => 'D62', '10.12.76.0/24' => 'D63', '10.12.52.0/24' => 'D64', '10.12.16.0/24' => 'D65', '10.12.20.0/24' => 'D66', '10.12.24.0/24' => 'D67', '10.12.32.0/24' => 'D68', '10.12.36.0/24' => 'D69 ', '10.12.40.0/24' => 'D70', '10.12.44.0/24' => 'D71', '10.12.56.0/24' => 'D72', '10.12.60.0/24' => 'D73', '10.12.64.0/24' => 'D74', '10.12.68.0/24' => 'D75', '10.12.80.0/24' => 'D76', '10.12.84.0/24' => 'D77', '10.12.88.0/24' => 'D78', '10.12.92.0/24' => 'D79', '10.12.96.0/24' => 'D80', '10.12.100.0/24' => 'D81', '10.12.104.0/24' => 'D82', '10.12.108.0/24' => 'D83', '10.12.112.0/24' => 'D84', '10.12.116.0/24' => 'D85', '10.12.120.0/24' => 'D86', '10.12.124.0/24' => 'D87', 
);

$data_array = array();
$data_array['base'] = "cn=Computers, dc=tainan, dc=gov, dc=tw";
$data_array['filter'] = "(objectCategory=computer)";
$data_array['attributes'] = array("cn");
$list = $ld->getList($data_array);
$computer_array = array_column($list, 'cn');

$assigned_array = array();
foreach ($computer_array as $computer) {
    $found = false;
    //echo $computer . "<br>;";
    // for name, e.g. PC-D77-152 -> D77
	$sections = explode('-', $computer);
    if (!empty($sections[1])) {
        $ou_key = strtoupper($sections[1]);
        if (array_key_exists($ou_key, $dist_ou_array)) {
            $found = true;
        }
    }

    // for cidr, e.g. 10.11.4.0/24 -> D51
    if (!$found) {
        $ip = shell_exec("/usr/bin/dig +short " . $computer . ".tainan.gov.tw @172.16.0.251");
        if (ipMatch(trim($ip), array_keys($dist_cidr_array), $match)){
            $ou_key = $dist_cidr_array[$match];
            $found=true;
            //echo "ip=" . $ip . ", cidr=" . $match . " ou=$ou_key<br>";
        } else {
            //echo "ip=$ip no match <br>";
        }
    }

    if (!$found) {
        continue;
    }
    //echo $ou_key . PHP_EOL;

    $TopOU = "District";
    $ou = $dist_ou_array[$ou_key];
    $result = $ad->changeComputerOU($computer, $ou, $TopOU);

    if ($result == '"1."') {
        $assigned_array[] = "the computer: " . $computer . " has been assigned to ou: " . $ou;
    } else {
        $assigned_array[] = "the computer: " . $computer . " has not been assigned to ou: " . $ou;
    }
}


if (empty($assigned_array)) {
    echo "No dist computer has been assigned";
    return;
}

var_dump($assigned_array);
