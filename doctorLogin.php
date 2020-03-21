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

    if ($db->isValid(array('email', 'password'))) {
        $result = $db->docLogin($email, $password);
        $token = $result;
        http_response_code(201);

        $jwt = JWT::encode($token, SECRET_KEY);
        echo json_encode(
            array(
                "error" => false,
                "message" => $result['message'],
                "jwt" => $jwt
            )
        );
    } else {
        $message = array();
        $message['error'] = true;
        $message['message'] = "# ParamsEmpty";
        http_response_code(401);
        echo json_encode($message);
    }
