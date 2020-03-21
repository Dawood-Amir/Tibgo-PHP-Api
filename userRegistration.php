<?php
// required headers
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


require_once "include/DbOperations.php";
require_once "include/Constants.php";

require "include/vendor/autoload.php";

use \Firebase\JWT\JWT;

$db = new DbOperations();


$email = isset($_POST['email']) ? $_POST['email'] : '';
//$password = sha1(isset($_POST['password']) ? $_POST['password'] : '');
$password = isset($_POST['password']) ? $_POST['password'] : '';
$phoneNumber = isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : '';
$userType = isset($_POST['userType']) ? $_POST['userType'] : '';
$ADT = isset($_POST['ADT']) ? $_POST['ADT'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';
/* For updating if exists
ALTER TABLE users ADD created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER userType ;
ALTER TABLE users ADD `lastUpdated` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER created_at ; */


if ($db->isValid(array('email', 'password', 'name', 'phoneNumber', 'userType', 'ADT'))) {

    $result = $db->registerUser($email, $password, $name, $phoneNumber, $userType, $ADT);

    if ($result['status'] == USER_CREATED) {
        
    
        $user = $result['user'];

        $token = array(
            "error" => false,
            "message" => 'User created successfully',
            "user" => $user
        );

        http_response_code(201);

        $jwt = JWT::encode($token, SECRET_KEY);
        echo json_encode(
            array(
                "error" => false,
                "message" => "User created successfully",
                "jwt" => $jwt
            )
        );
    } elseif ($result['status'] == USER_EXISTS) {
        $message = array();
        $message['error'] = true;
        $message['message'] = 'User already exists';
        http_response_code(226);
        echo json_encode($message);
    } elseif ($result['status'] == USER_FAILURE) {
        $message = array();
        $message['error'] = true;
        $message['message'] = 'Something went wrong user is not created';
        http_response_code(500);
        echo json_encode($message);
    }
} else {
    $message = array();
    $message['error'] = true;
    $message['message'] = 'Empty Params';
    http_response_code(203);
    echo json_encode($message);
}
