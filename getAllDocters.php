<?php
// required headers
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "include/DoctorDbOperations.php";
require_once "include/Constants.php";

require "include/vendor/autoload.php";

use \Firebase\JWT\JWT;

$db = new DoctorDbOperations();

$id = isset($_POST['id']) ? $_POST['id'] : -1;

if($db->isValid(array('id'))){

    //TODO: need to get the id from token using it as URL Requested body

    $result = $db->getDoctors($id);

        if($result['error'] == false){
            $response = array();
            $response['error'] = false;
            $response['message'] = $result['message'];
            $response['doctor'] = $result['doctors'];
            echo json_encode($response);
        }else if($result['error'] == true){
            $response = array();
            $response['error'] = true;
            $response['message'] = $result['message'];
            echo json_encode($response);

        }else{
            $response = array();
            $response['error'] = true;
            $response['message'] = "something went wrong cant get doctors";
            echo json_encode($response);
        }

}else{
    $response = array();
    $response['error'] =true;
    $response['messge'] ="Empty params";
    echo json_encode($response);

}

