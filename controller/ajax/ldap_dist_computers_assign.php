<?php
$ld = new MyLDAP();
$ad = new ad\api\WebadAPI();
$dist_ou_array = array(
'D51' => '395510000A', 'D51' => '395510000A', 'D52' => '395520000A', 'D53' => '395530000A', 'D54' => '395540000A', 'D55' => '395550000A', 'D56' => '395560000A', 'D57' => '395570000A', 'D58' => '395580000A', 'D59' => '395590000A', 'D60' => '395600000A', 'D61' => '395610000A', 'D62' => '395620000A', 'D63' => '395630000A', 'D64' => '395640000A', 'D65' => '395650000A', 'D66' => '395660000A', 'D67' => '395670000A', 'D68' => '395680000A', 'D69' => '395690000A', 'D70' => '395700000A', 'D71' => '395710000A', 'D72' => '395720000A', 'D73' => '395730000A', 'D74' => '395740000A', 'D75' => '395750000A', 'D76' => '395760000A', 'D77' => '395770000A', 'D78' => '395780000A', 'D79' => '395790000A', 'D80' => '395800000A', 'D81' => '395810000A', 'D82' => '395820000A', 'D83' => '395830000A', 'D84' => '395840000A', 'D85' => '395850000A', 'D86' => '395860000A', 'D87' => '395870000A',
);

$data_array = array();
$data_array['base'] = "cn=Computers, dc=tainan, dc=gov, dc=tw";
$data_array['filter'] = "(objectCategory=computer)";
$data_array['attributes'] = array("cn");
$list = $ld->getList($data_array);
$computer_array = array_column($list, 'cn');

$assigned_array = array();
foreach ($computer_array as $computer) {
    // PC-D77-152 -> D77
	$sections = explode('-', $computer);
    if (empty($sections[1])) {
        continue;
    }

    $key = strtoupper($sections[1]);
    if (!array_key_exists($key, $dist_ou_array)) {
        continue;
    }

    $TopOU = "District";
    $ou = $dist_ou_array[$key];
    $ad->changeComputerOU($computer, $ou, $TopOU);
    $assigned_array[] = "The computer: " . $computer . " has been assigned to ou: " . $ou;
}

if (empty($assigned_array)) {
    echo "No dist computer has been assigned";
    return;
}

var_dump($assigned_array);
