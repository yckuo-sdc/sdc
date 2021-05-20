<?php 
$pa_hosts = ['yonghua', 'minjhih', 'idc'];
$host_policy_results = array();

foreach($pa_hosts as $host) {
    $pa = new PaloAltoAPI($host);
    $policy_types = ['SecurityRules', 'NatRules'];
    $policy_results = array();

    foreach($policy_types as $policy_type) {
        $res = $pa->getPoliciesList($policy_type, $name = "");
        $res = json_decode($res, true);

        $data_array = array();
        $data_array['status'] = $res['@status'];

        if (empty($res['result']['@total-count'])) {
            $data_array['status'] = "error";
            $data_array['total_count'] = 0;
        } else {
            $data_array['total_count'] = $res['result']['@total-count'];
            $data_array['apps'] = $res['result']['entry'];
        }

        $policy_results[$policy_type] = $data_array;
    }
    $host_policy_results[$host] = $policy_results;
}

require 'view/header/default.php'; 
require 'view/body/network/policy.php';
require 'view/footer/default.php'; 
