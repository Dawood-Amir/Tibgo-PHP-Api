<?php
require_once "include/DbOperations.php";
require "include/vendor/autoload.php";

use \Firebase\JWT\JWT;

$db = new DbOperations();

$email = isset($_POST['email']) ? $_POST['email'] : '';
$request_type = isset($_POST['request_type']) ? $_POST['request_type'] : '';
//$password = sha1(isset($_POST['password']) ? $_POST['password'] : '');
$password = isset($_POST['password']) ? $_POST['password'] : '';
$phoneNumber = isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : '';
$userType = isset($_POST['userType']) ? $_POST['userType'] : '';
$ADT = isset($_POST['ADT']) ? $_POST['ADT'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';
$address = isset($_POST['address']) ? $_POST['address'] : '';
$workingHours = isset($_POST['workingHours']) ? $_POST['workingHours'] : '';
$chargePerVisit = isset($_POST['chargePerVisit']) ? $_POST['chargePerVisit'] : '';
$latLng = isset($_POST['latLng']) ? $_POST['latLng'] : '';
$isSpecialist = isset($_POST['isSpecialist']) ? $_POST['isSpecialist'] : 0;
$specialistIn = isset($_POST['specialistIn']) ? $_POST['specialistIn'] : '';
$u_id = isset($_POST['u_id']) ? $_POST['u_id'] : '';
$id = isset($_POST['id']) ? $_POST['id'] : -1;
$docType =      isset($_POST['docType']) ? $_POST['docType'] : '';

// required headers
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// database connection will be here
//header('Content-type: application/json');

switch ($request_type) {

    case "signUp":
    {

        if ($userType == "doctor") {
            if ($db->isValid(array('email', 'password', 'name', 'phoneNumber', 'userType', 'ADT', 'address', 'workingHours', 'chargePerVisit', 'latLng'))) {
                $result = $db->registerUser($email, $password, $name, $phoneNumber, $userType, $ADT);
                if ($result['status'] == USER_CREATED) {

                    $uid = $result['id'];
                    $rInsert = $db->addToDoctors($email, $address, $workingHours, $chargePerVisit, $latLng, $isSpecialist, $specialistIn, $uid ,$docType);
                    header('Content-type: application/json');
                    echo json_encode($rInsert);


                } elseif ($result['status'] == USER_FAILURE) {
                    $message = array();
                    $message['error'] = true;
                    $message['message'] = 'Something went wrong user is not created';
                    $message['id'] = $result['id'];
                    header('Content-type: application/json');
                    echo json_encode($message);
                } elseif ($result['status'] == USER_EXISTS) {
                    $message = array();
                    $message['error'] = true;
                    $message['message'] = 'Already have an account with this email';
                    $message['id'] = $result['id'];
                    header('Content-type: application/json');
                    echo json_encode($message);
                }
            } else {
                $message = array();
                $message['error'] = true;
                $message['message'] = 'Empty params';
                echo json_encode($message);
            }

        }
        
        else if ($userType == "user") {

            if ($db->isValid(array('email', 'password', 'name', 'phoneNumber', 'userType', 'ADT'))) {

                $result = $db->registerUser($email, $password, $name, $phoneNumber, $userType, $ADT);

                /*    $message = array();
                    $message['error'] = false;
                    $message['message'] = 'User created successfully';
                    $message['user'] = $result['user'];
                    echo json_encode($message);*/


                if ($result['status'] == USER_CREATED) {
                    $secret_key = "PASSWORD_IS_HASHED";
                    $issuer_claim = "LOCAL_HOST"; // this can be the servername
                    $issuedat_claim = time(); // issued at
                    $notbefore_claim = $issuedat_claim + 10; //when the toke will work
                    $user = $result['user'];

                    /* $token = array(
                        "iss" => $issuer_claim,
                        "iat" => $issuedat_claim,
                        "nbf" => $notbefore_claim,
                        "response" => [
                            "error" => false,
                            "message" => 'User created successfully'],
                        "user" => $user
                    ); */
                    $token = array(
                            "error" => false,
                            "message" => 'User created successfully',
                        "user" => $user
                    );

                    http_response_code(200);

                    $jwt = JWT::encode($token, $secret_key);
                    echo json_encode(
                        array(
                            "error"=>false,
                            "message" => "User created successfully",
                            "jwt" => $jwt
                        ));
                } elseif ($result['status'] == USER_EXISTS) {
                    $message = array();
                    $message['error'] = true;
                    $message['message'] = 'User already exists';
                    $message['id'] = $result['id'];
                    echo json_encode($message);
                } elseif($result['status'] == USER_FAILURE) {
                    $message = array();
                    $message['error'] = true;
                    $message['message'] = 'Something went wrong user is not created';
                    $message['id'] = $result['id'];
                    echo json_encode($message);
                }
            } else {
                $message = array();
                $message['error'] = true;
                $message['message'] = 'Something went user is not created # ParamsEmpty';
                $message['id'] = -1;
                echo json_encode($message);
            }

        }


        break;
    }
    case "getUsers":
    {
        $result = $db->getAllUsers();
        if ($result == -1) {
            echo json_encode($result);
        } else {
            echo json_encode($result);

        }
        break;
    }
    case "login":
    {

        if ($userType == "doctor") {
            if ($db->isValid(array('email', 'password', 'userType'))) {
                $result = $db->docLogin($email, $password);
                echo json_encode($result);
            } else {
                $message = array();
                $message['error'] = true;
                $message['message'] = "# ParamsEmpty";
                echo json_encode($message);
            }
        } else {
            if ($db->isValid(array('email', 'password'))) {
                $result = $db->login($email, $password);
                echo json_encode($result);
            } else {
                $message = array();
                $message['error'] = true;
                $message['message'] = "# ParamsEmpty";
                echo json_encode($message);
            }
        }


        break;
    }
    case "updateADT":
    {
        if ($db->isValid(array('ADT', 'id'))) {
            $result = $db->updateADT($ADT, $id);
            echo json_encode($result);

        } else {
            $message = array();
            $message['error'] = true;
            $message['message'] = "# ParamsEmpty";
            echo json_encode($message);
        }
        break;
    }

}
