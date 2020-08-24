<?php
use gcb\api as gcb;
include("gcb_api.php");
// the api key of sdc-iss
$api_key = "u3mOZuf8lvZYps210BD5vA";
$token = gcb\get_access_token($api_key);
// '0' represent no limit 
$limit = 0;
gcb\get_client_list_insert2DB($token,$limit);
