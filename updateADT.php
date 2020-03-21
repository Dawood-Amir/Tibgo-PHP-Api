<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "include/DbOperations.php";
require "include/vendor/autoload.php";
require_once 'include/DbConnect.php';
require_once 'include/Constants.php';
use \Firebase\JWT\JWT;


$ADT = isset($_POST['ADT']) ? $_POST['ADT'] : '';


$db = new DbOperations();

//$authHeader = $_SERVER['HTTP_AUTHORIZATION'];
$header = apache_request_headers();

$headerArr = array();

try {
    $hear = isset($header[HEADER_AUTHORIZATION]) ? $header[HEADER_AUTHORIZATION] : false;
} catch (Exception $e) {
    $hear = false;
}
foreach ($header as $headers => $value) {
    $headerArr[$headers] = $value;
}
function printValues($arr) {
    global $count;
    global $values;
    
    // Check input is an array
    if(!is_array($arr)){
        die("ERROR: Input is not an array");
    }
    
    foreach($arr as $key=>$value){
        if(is_array($value)){
            printValues($value);
        } else{
            $values[] = $value;
            $count++;
        }
    }
    
    // Return total count and values found in array
    return array('total' => $count, 'values' => $values);
}

if ($headerArr != null && $hear != false) {

    try {

        $jwtDecoded = JWT::decode($hear, SECRET_KEY, array('HS256'));
    
        $arr =  json_decode(json_encode($jwtDecoded),true);

        $result = $arr['user'];
        $uId= $result['id'];
        if ($db->isValid(array('ADT'))) {
            $result = $db->updateADT($ADT, $uId);
            http_response_code(200);
            echo json_encode($result);

        } else {
            http_response_code(400);

            $message = array();
            $message['error'] = true;
            $message['message'] = "# ParamsEmpty";
            echo json_encode($message);
        }
        
        
      //  echo json_encode($uId);


        
        //JsonDecode
        //get the orders from id 

    } catch (Exception $er) {

        http_response_code(401);

        echo json_encode(array(

            "error" => true,
            "message" => "Access denied  " . $er->getMessage()

        ));
    }
} else {
    http_response_code(400);

    echo json_encode(array(

        "message" => "Access denied: Problem in header",
        "error" => true
    ));
}
