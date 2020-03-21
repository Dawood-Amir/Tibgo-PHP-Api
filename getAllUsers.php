<?php
header("Content-Type: application/json; charset=UTF-8");


require_once "include/DbOperations.php";
require_once "include/Constants.php";
$db = new DbOperations();

$result = $db->getAllUsers();


if ($result['error']==true) {
    echo json_encode($result);
    http_response_code(500);
} else {
    http_response_code(200);
    echo json_encode($result);
}



