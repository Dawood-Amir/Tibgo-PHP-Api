<?php
require_once "include/DbOperations.php";
require "include/vendor/autoload.php";
require_once 'include/DbConnect.php';
require_once 'include/Constants.php';


use \Firebase\JWT\JWT;


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


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
    
    /*
    Loop through array, if value is itself an array recursively call the
    function else add the value found to the output items array,
    and increment counter by 1 for each value found
    */
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
        
        // Access is granted. Add code of the operation here
        // need to find a way to get the user id something like this  $id =$decoded->id;
        //getThe data of user

        echo json_encode(array(
            "jwtMessage" => "Access granted:",
            "jwtError" => false,
            "JwtDecoded" => $jwtDecoded
        
        ));
        $arr =  json_decode(json_encode($jwtDecoded),true);

        $result = $arr['user'];
        $uId= $result['id'];

        
        
        echo json_encode($uId);


        
        //JsonDecode
        //get the orders from id 

    } catch (Exception $er) {

        http_response_code(401);
       

        echo json_encode(array(
            "jwtError" => true,
            "jwtMessage" => "Access denied  " . $er->getMessage()

        ));
    }
} else {
    echo json_encode(array(
        "message" => "Access denied: Problem in header",
        "error" => true,
    ));
}
