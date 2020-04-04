<?php

//First make this a class which can have functions
//make a var $con of connection
//make a constructor
//make a var $db of connection give it a new instaancy of DbConnect
//connect the var $con in constructor with  $db

class PharmacyDbOperations
{
    private $con;
    
    public function __construct()
    {
        require_once 'DbConnect.php';
        $db = new DbConnect();
        $this->con = $db->connect();
    }
    
    public function getPharmacies()
    {
        $query ="SELECT u.id,
                u.email,
                u.name,
                u.phoneNumber,
                u.userType,
                u.ADT,
                p.address,
                p.openingTime,
                p.closingTime,
                p.chargePerVisit,
                p.cardHashedClr,
                p.notAvailableExceptionally,
                p.latLng,
                p.pharmacyId
                FROM users As u, pharmacies AS p
                WHERE u.id = p.uId";

        $result = mysqli_query($this->con, $query);
        $resultArray = array(); 
        if ($result->num_rows > 0) {

            while ($row = mysqli_fetch_assoc($result)) { 
                $pharmacyArray= [
                    "id" => intval($row["id"]),
                    "pharmacyId" => intval($row["pharmacyId"]),
                    "email" => $row["email"],
                    "name" => $row["name"],
                    "phoneNumber" => $row["phoneNumber"],
                    "userType" => $row["userType"],
                    "ADT" => $row["ADT"],
                    "address" => $row["address"],
                    "openingTime" => $row["openingTime"],
                    "closingTime" => $row["closingTime"],
                    "chargePerVisit" => intval($row["chargePerVisit"]),
                    "cardHashedClr" => $row["cardHashedClr"],
                    "notAvailableExceptionally" => intval($row["notAvailableExceptionally"]),
                    "latLng" => $row["latLng"]
                ];

                $resultArray[] =$pharmacyArray;
            }
        
            $response = array();
            $response['error'] = false;
            $response['message']= "Found pharmacy";
            $response['pharmacies'] = $resultArray;
            return $response;
        } else {
            $response = array();
            $response['error'] = true;
            $response['message']= "Cannot find any pharmacy";
            return $response;
        }
    }

    public function isValid($params)
    {
        foreach ($params as $param) {
            //if the paramter is not available or empty
            if (isset($_POST[$param])) {
                if (empty($_POST[$param])) {
                    return false;
                }
            } else {
                return false;
            }
        }
        //return true if every param is available and not empty
        return true;
    }
}
