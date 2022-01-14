<?php 
// above PAN-OS 9.0
$pa_hosts = ['yonghua', 'minjhih', 'idc'];
$host_policy_results = array();

foreach($pa_hosts as $host) {
    $pa = new PaloAltoAPI($host);
    $policy_types = ['SecurityRules', 'NatRules'];
    $policy_results = array();

    foreach($policy_types as $policy_type) {
        $response = $pa->getPoliciesList($policy_type, $name = "");

        if (empty($response['result']['@total-count'])) {
            $data_array = array();
            $data_array['status'] = "error";
            $data_array['total_count'] = 0;
            $data_array['apps'] = array();
        } else {
            $data_array = array();
            $data_array['status'] = $response['@status'];
            $data_array['total_count'] = $response['result']['@total-count'];
            $data_array['apps'] = $response['result']['entry'];
        }

        $policy_results[$policy_type] = $data_array;
    }
    $host_policy_results[$host] = $policy_results;
}

// PAN-OS 8.0
$pa_hosts = ['intrayonghua'];

foreach($pa_hosts as $host) {
    $pa = new PaloAltoAPI($host);
    $policy_types = ['SecurityRules', 'NatRules'];
    $policy_results = array();

    foreach($policy_types as $policy_type) {

        if($policy_type == 'NatRules') {
            $data_array = array();
            $data_array['status'] = "error";
            $data_array['total_count'] = 0;
            $data_array['apps'] = array();
            $policy_results[$policy_type] = $data_array;
            continue;
        }

        $response = $pa->getXmlSecurityRules();

        if (empty($response['@attributes']['status'])) {
            $data_array = array();
            $data_array['status'] = "error";
            $data_array['total_count'] = 0;
            $data_array['apps'] = array();
        } else {
            $data_array = array();
            $data_array['status'] = $response['@attributes']['status'];
            $data_array['total_count'] = count($response['result']['security']['rules']['entry']);
            $data_array['apps'] = $response['result']['security']['rules']['entry'];
        }

        $policy_results[$policy_type] = $data_array;
    }
    $host_policy_results[$host] = $policy_results;
}

require 'view/header/default.php'; 
require 'view/body/network/policy.php';
require 'view/footer/default.php'; 
