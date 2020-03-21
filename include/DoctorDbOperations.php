<?php

//First make this a class which can have functions
//make a var of connection
//make a constructor
class DoctorDbOperations
{
    private $con;
    
    public function __construct()
    {
        require_once 'DbConnect.php';
        $db = new DbConnect();
        $this->con = $db->connect();
    }   


    public function getDoctors($id){
        $query = "SELECT u.id,u.email,u.name,u.phoneNumber,u.userType,u.ADT ,d.address, d.openingTime, d.closingTime,d.chargePerVisit,d.latLng,d.isSpecialist,d.specialistIn,d.docImgUrl,d.docType,d.d_id
                    FROM users As u, doctors AS d
                    WHERE u.id ='$id' AND d.u_id = '$id'";
        $result = mysqli_query($this->con, $query);

        if($result->num_rows > 0){
            $row = mysqli_fetch_assoc($result);
            $doctorsArray = [
                "id" => intval($row["id"]),
                "email" => $row["email"],
                "name" => $row["name"],
                "phoneNumber" => $row["phoneNumber"],
                "userType" => $row["userType"],
                "ADT" => $row["ADT"],
                "address" => $row["address"],
                "openingTime" => $row["openingTime"],
                "closingTime" => $row["closingTime"],
                "docImgUrl" => $row["docImgUrl"],
                "chargePerVisit" => intval($row["chargePerVisit"]),
                "latLng" => $row["latLng"],
                "isSpecialist" => intval($row["isSpecialist"]),
                "docType" => $row["docType"],
                "d_id" => intval($row["d_id"]),
            ]; 
            
            $response = array();
            $response['error'] = false;
            $response['message']= "Found doctor with that id";
            $response['doctors'] = $doctorsArray;
            return $response;
        }else{
            $response = array();
            $response['error'] = true;
            $response['message']= "cannot find any doctor with this id";
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
