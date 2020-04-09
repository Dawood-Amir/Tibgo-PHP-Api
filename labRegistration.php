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
$name = isset($_POST['name']) ? $_POST['name'] : '';
$phoneNumber = isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : '';
$userType = isset($_POST['userType']) ? $_POST['userType'] : '';
$ADT = isset($_POST['ADT']) ? $_POST['ADT'] : '';
$address = isset($_POST['address']) ? $_POST['address'] : '';
$openingTime = isset($_POST['openingTime']) ? $_POST['openingTime'] : '';
$closingTime = isset($_POST['closingTime']) ? $_POST['closingTime'] : '';
$chargePerVisit = isset($_POST['chargePerVisit']) ? $_POST['chargePerVisit'] : '';
$labImgUrl = isset($_POST['labImgUrl']) ? $_POST['labImgUrl'] : '';
$notAvailableExceptionally = isset($_POST['notAvailableExceptionally']) ? $_POST['notAvailableExceptionally'] : 0;
$latLng = isset($_POST['latLng']) ? $_POST['latLng'] : '';

if ($db->isValid(array(
    'email',
    'password',
    'name',
    'phoneNumber',
    'userType',
    'ADT',
    'address',
    'openingTime',
    'closingTime',
    'chargePerVisit',
    'labImgUrl',
    'latLng'))) {
    $result = $db->registerUser($email, $password, $name, $phoneNumber, $userType, $ADT);
    if ($result['status'] == USER_CREATED) {
        $uid = $result['id'];
        
        $rLabs = $db->addToLabs(
            $email,
            $address,
            $openingTime,
            $closingTime,
            $chargePerVisit,
            $labImgUrl,
            $notAvailableExceptionally,
            $latLng,
            $uid
        );

        $token = array(
            "error" => false,
            "message" => 'Lab created successfully',
            "lab" => $rLabs
        ) ;

        http_response_code(201);

        $jwt = JWT::encode($token, SECRET_KEY);
        echo json_encode(
            array(
                "error" => false,
                "message" => "lab created successfully",
                "jwt" => $jwt
            )
        );
    } elseif ($result['status'] == USER_FAILURE) {
        $message = array();
        $message['error'] = true;
        $message['message'] = 'Something went wrong user is not created';
        $message['id'] = $result['id'];
        http_response_code(500);
        echo json_encode($message);
    } elseif ($result['status'] == USER_EXISTS) {
        $message = array();
        $message['error'] = true;
        $message['message'] = 'Already have an account with this email';
        $message['id'] = $result['id'];
        http_response_code(226);
        echo json_encode($message);
    }
} else {
    $message = array();
    $message['error'] = true;
    $message['message'] = 'Empty params';
    http_response_code(203);
    echo json_encode($message);
}
