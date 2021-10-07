<?php
$error = array(); 

// Sanitizes data and converts strings to UTF-8 (if available), according to the provided field whitelist
$whitelist = array("objectCategory", "target", "action"); 
$_GET = $gump->sanitize($_GET, $whitelist); 

// set validation rules
$validation_rules_array = array(
	'objectCategory' => 'required',
	'target' => 'required',
	'action' => 'required'
);
$gump->validation_rules($validation_rules_array);

// set filter rules
//$filter_rules_array = array(
//	'objectCategory' => 'trim|sanitize_string',
//	'target' => 'trim|sanitize_string',
//	'action' => 'trim|sanitize_string'
//);
//$gump->filter_rules($filter_rules_array);

$validated_data = $gump->run($_GET);

if($validated_data === false) {
	$error = $gump->get_readable_errors(false);
} else {
	foreach ($validated_data as $key => $val) {
		${$key} = $val; // transfer to local parameters
	}

    $controller_array = scandir('controller/ajax/ldap');
    $controller_array = array_change_key_case($controller_array, CASE_LOWER);

    if ($action === "search") {
        if (in_array($action . '_' . $objectCategory . '.php', $controller_array)) {
              require 'controller/ajax/ldap/' . $action . '_' . $objectCategory . '.php';
        }
    } else {
        if (in_array($action . '.php', $controller_array)) {
              require 'controller/ajax/ldap/' . $action . '.php';
        }
    }

}

if (!empty($error)) {
	foreach ($error as $e) {
        echo $e . "<br>";
	}
}
