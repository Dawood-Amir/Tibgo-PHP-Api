<?php
// required headers
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "include/LabDbOperations.php";
require_once "include/Constants.php";

require "include/vendor/autoload.php";

use \Firebase\JWT\JWT;

$db = new LabDbOperations();

$result = $db->getLabs();

if ($result['error'] == false) {
    $response = array();
    $response['error'] = false;
    $response['message']=$result['message'];
    $response['labs'] = $result['labs'];
    echo json_encode($response);

} elseif ($result['error'] == true) {
    $response = array();
    $response['error'] = true;
    $response['message'] = $result['message'];
    echo json_encode($response);

} else {
    $response = array();
    $response['error'] = true;
    $response['message'] = "something went wrong cant get doctors";
    echo json_encode($response);
}
