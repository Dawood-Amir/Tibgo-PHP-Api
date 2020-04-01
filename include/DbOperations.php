<?php

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

class DbOperations
{
    private $con;

    public function __construct()
    {
        require_once 'DbConnect.php';
        $db = new DbConnect();
        $this->con = $db->connect();
    }

    public function registerUser($email, $password, $name, $phoneNumber, $userType, $ADT)
    {
        /*
        Check Constants for Querries
         */
        if (!$this->isEmailExists($email)) {
            $query = "INSERT INTO users (email,password,name,phoneNumber,userType,ADT) VALUES ('$email','$password','$name','$phoneNumber','$userType','$ADT')";
            $result = mysqli_query($this->con, $query);
            if ($result) {
                $user = $this->getUserData($email);
                $param = array();
                $param['status'] = USER_CREATED;
                $param['user'] = $user;
                $param['id'] = $user['id'];
                return $param;
            } else {
                $param['status'] = USER_FAILURE;
                $param['id'] = -1;
                return $param;
            }
        } else {
            $param['status'] = USER_EXISTS;
            $param['id'] = -2;
            return $param;
        }
    }

    public function addToDoctors($email, $address, $openingTime, $closingTime, $chargePerVisit, $latLng, $isSpecialist, $specialistIn, $u_id, $docType, $docImgUrl)
    {
        if ($this->isEmailExists($email)) {
            if ($isSpecialist) {
                $isSpecInt = 1;
            } else {
                $isSpecInt = 0;
            }
            $query = "INSERT INTO doctors
            (address,
            openingTime,
            closingTime,
            chargePerVisit,
            latLng,
            isSpecialist,
            specialistIn,
            u_id,
            docType,
            docImgUrl
            ) VALUES ('$address',
            '$openingTime',
            '$closingTime',
            '$chargePerVisit',
            '$latLng',
            '$isSpecInt',
            '$specialistIn',
            '$u_id',
            '$docType',
            '$docImgUrl')";

            $result = mysqli_query($this->con, $query);
            $id = mysqli_insert_id($this->con);

            if ($result) {
                $gotPrams = $this->getDocUserData($email, $u_id);
                if ($gotPrams['error'] == false) {
                    $param = array();
                    $param['error'] = false;
                    $param['message'] = "inserted Successfully in docs";
                    $param['user'] = $gotPrams['row'];
                    return $param;
                }
            } else {
                $param = array();
                $param['error'] = true;
                $param['message'] = "Couldn't insert Data";
                return $param;
            }
        }
        $param = array();
        $param['error'] = true;
        $param['message'] = "Couldn't insert else Data";
        return $param;
    }
    /*
        `~!@#$%^&*()-=+[{]}\|;:'",.<>/? */
    public function addToPharmacy(
        $email,
        $address,
        $openingTime,
        $closingTime,
        $chargePerVisit,
        $cardHashedClr,
        $notAvailableExceptionally,
        $latLng,
        $uId
    ) {
        if ($this->isEmailExists($email)) {
            if ($notAvailableExceptionally) {
                $notAvailableExceptionalInt = 1;
            } else {
                $notAvailableExceptionalInt = 0;
            }

            $query = "INSERT INTO pharmacies
            (address,
            openingTime,
            closingTime,
            chargePerVisit,
            cardHashedClr,
            notAvailableExceptionally,
            latLng,
            uId)
             VALUES ('$address',
            '$openingTime',
            '$closingTime',
            '$chargePerVisit',
            '$cardHashedClr',
            '$notAvailableExceptionalInt',
            '$latLng',
            '$uId')";

            $result = mysqli_query($this->con, $query);
            $id = mysqli_insert_id($this->con);

            if ($result) {
                $gotPrams = $this->getPharmacyData($email, $uId);
                if ($gotPrams['error'] == false) {
                    $param = array();
                    $param['error'] = false;
                    $param['message'] = "inserted Successfully in pharmacies";
                    $param['pharmacy'] = $gotPrams['row'];
                    return $param;
                }
            } else {
                $param = array();
                $param['error'] = true;
                $param['message'] = "Couldn't insert Data";
                return $param;
            }
        } else {
            $param = array();
            $param['error'] = true;
            $param['message'] = "Couldn't insert Data";
            return $param;
        }
    }


    public function getUserData($email)
    {
        if ($this->isEmailExists($email)) {
            $query = "SELECT id,email,name,phoneNumber,userType,ADT FROM users WHERE email='$email'";
            $result = mysqli_query($this->con, $query);
            if ($result->num_rows > 0) {
                $row = mysqli_fetch_assoc($result);

                $user = [
                    "id" => intval($row["id"]),
                    "email" => $row["email"],
                    "name" => $row["name"],
                    "phoneNumber" => $row["phoneNumber"],
                    "userType" => $row["userType"],
                    "ADT" => $row["ADT"],
                ];

                return $user;
            } else {
                return $result;
            }
        } else {
            $param['status'] = USER_FAILURE;
            return $param;
        }
    }

    private function getDocUserData($email, $id)
    {
        if ($this->isEmailExists($email)) {
            $query = "SELECT u.id,u.email,u.name,u.phoneNumber,u.userType,u.ADT,d.address,d.openingTime,d.closingTime,d.chargePerVisit,d.latLng,d.isSpecialist,d.specialistIn,d.docType,d.docImgUrl,d.d_id
                    FROM users As u, doctors AS d
                    WHERE u.id ='$id' AND d.u_id = '$id'";

            $result = mysqli_query($this->con, $query);
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                $param = array();
                $param['error'] = false;
                // $boolean = $mysql_data ? true : false;
                $userArray = [
                    "id" => intval($row["id"]),
                    "email" => $row["email"],
                    "name" => $row["name"],
                    "phoneNumber" => $row["phoneNumber"],
                    "userType" => $row["userType"],
                    "ADT" => $row["ADT"],
                    "address" => $row["address"],
                    "openingTime" => $row["openingTime"],
                    "closingTime" => $row["closingTime"],
                    "chargePerVisit" => intval($row["chargePerVisit"]),
                    "latLng" => $row["latLng"],
                    "isSpecialist" => intval($row["isSpecialist"]),
                    "specialistIn" => $row["specialistIn"],
                    "docType" => $row["docType"],
                    "docImgUrl" => $row["docImgUrl"],
                    "d_id" => intval($row["d_id"])
                ];
                $param['row'] = $userArray;
                return $param;
            } else {
                $param = array();
                $param['error'] = true;
                $param['message'] = "Couldn't get the data from doctors";
                return $param;
            }
        } else {
            $param = array();
            $param['error'] = true;
            $param['message'] = "Couldn't find the given $email";
            return $param;
        }
    }

    private function getPharmacyData($email, $id)
    {
        if ($this->isEmailExists($email)) {
            $query = "SELECT u.id,u.email,u.name,
            u.phoneNumber,u.userType,u.ADT,
            p.address,p.openingTime,p.closingTime,
            p.chargePerVisit,p.cardHashedClr,p.notAvailableExceptionally,
            p.latLng,
            p.pharmacyId
            FROM users As u, pharmacies AS p
            WHERE u.id ='$id' AND p.uId = '$id'";

            $result = mysqli_query($this->con, $query);
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                // $boolean = $mysql_data ? true : false;
                $pharmacyArray = [
                    "id" => intval($row["id"]),
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
                    "latLng" => $row["latLng"],
                    "pharmacyId" => intval($row["pharmacyId"])
                ];
                $param = array();
                $param['error'] = false;
                $param['row'] = $pharmacyArray;
                return $param;
            } else {
                $param = array();
                $param['error'] = true;
                $param['message'] = "Couldn't get the data from Phamacies";
                return $param;
            }
        } else {
            $param = array();
            $param['error'] = true;
            $param['message'] = "Couldn't find the given $email";
            return $param;
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

    private function isEmailExists($email)
    {
        $query = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($this->con, $query);
        $num = $result->num_rows;

        if ($num > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getAllUsers()
    {
        /*
        By this method well get response like
        [
        {
        "id": "1",
        "email": "dawoodamir115@gmail.com",
        "name": "Dawood Amir"
        },
        {
        "id": "2",
        "email": "fairwaysolutions786@gmail.com",
        "name": "Junaid"
        }
        ]
         */

        $query = "SELECT id,email,name,phoneNumber,userType,ADT FROM users";
        $result = mysqli_query($this->con, $query);
        $origResult = array();
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $origResult[] = $row;
            }
            $response = array();
            $response['error'] = false;
            $response['users'] = $origResult;

            return $response;
        } else {
            $response = array();
            $response['error'] = true;
            return $response;
        }
    }

    public function docLogin($email, $password)
    {
        //TODO : i should check if there is any email exists
        $isCorrect = $this->isPasswordCorrect($email, $password);
        $id = $isCorrect['id'];

        if ($isCorrect['isCorrect']) {
            if ($isCorrect['userType'] == "doctor") {
                $query = "SELECT u.id,u.email,u.name,u.phoneNumber,u.userType,u.ADT ,d.address, d.workingHours,d.chargePerVisit,d.latLng,d.isSpecialist,d.specialistIn,d.docType,d.d_id
                    FROM users As u, doctors AS d
                    WHERE u.id ='$id' AND d.u_id = '$id'";
                $result = mysqli_query($this->con, $query);
                $row = mysqli_fetch_assoc($result);
                $userArray = [
                    "id" => intval($row["id"]),
                    "email" => $row["email"],
                    "name" => $row["name"],
                    "phoneNumber" => $row["phoneNumber"],
                    "userType" => $row["userType"],
                    "ADT" => $row["ADT"],
                    "address" => $row["address"],
                    "openingTime" => $row["openingTime"],
                    "closingTime" => $row["openingTime"],
                    "docImgUrl" => $row["docImgUrl"],
                    "chargePerVisit" => intval($row["chargePerVisit"]),
                    "latLng" => $row["latLng"],
                    "isSpecialist" => intval($row["isSpecialist"]),
                    "docType" => $row["docType"],
                    "d_id" => intval($row["d_id"]),
                ];
                $message = array();
                $message['error'] = false;
                $message['message'] = "User login successfully";
                $message['user'] = $userArray;
                return $message;
            } else {
                $message = array();
                $message['error'] = true;
                $message['message'] = "Looks like this email is using on wrong app if this happens 3 times your account will be blocked permanently";
                return $message;
            }
        } else {
            $message = array();
            $message['error'] = true;
            $message['message'] = "No user found with this email and password check email,password and try again";
            return $message;
        }
    }

    public function login($email, $password)
    {
        //TODO : i should check if there is any email exists
        $isCorrect = $this->isPasswordCorrect($email, $password);

        $id = $isCorrect['id'];
        if ($isCorrect['isCorrect']) {
            if ($isCorrect['userType'] == "user") {
                $query = "SELECT id, name ,email, phoneNumber, userType,ADT FROM users WHERE id ='$id'";
                $result = mysqli_query($this->con, $query);
                $row = mysqli_fetch_assoc($result);
                $userArray = [
                    "id" => intval($row["id"]),
                    "email" => $row["email"],
                    "name" => $row["name"],
                    "phoneNumber" => $row["phoneNumber"],
                    "userType" => $row["userType"],
                    "ADT" => $row["ADT"],
                ];
                $message = array();
                $message['error'] = false;
                $message['message'] = "User login successfully";
                $message['user'] = $userArray;
                http_response_code(201);

                return $message;
            } else {
                $message = array();
                $message['error'] = true;
                $message['message'] = "Looks like this email is using on wrong app if this happens 3 times your account will be blocked permanently";
                http_response_code(401);

                return $message;
            }
        } else {
            $message = array();
            $message['error'] = true;
            $message['message'] = "No user found with this email and password check email,password and try again";
            http_response_code(401);

            return $message;
        }
    }

    public function isPasswordCorrect($email, $password)
    {
        $query = "SELECT id, userType FROM users WHERE email ='$email' AND password ='$password'";
        $result = mysqli_query($this->con, $query);
        $id = -1;

        if ($result->num_rows > 0) {
            $row = mysqli_fetch_assoc($result);
            $message = array();
            $message['isCorrect'] = true;
            $message['id'] = $row['id'];
            $message['userType'] = $row['userType'];
        } else {
            $message = array();
            $message['isCorrect'] = false;
            $message['id'] = $id;
            $message['userType'] = "";
        }

        return $message;
    }

    public function updateADT($ADT, $id)
    {
        //Should check if the ADT value is same as before then it will not work so first check that and then update the ADT
        $query = "UPDATE users SET ADT ='$ADT' WHERE id ='$id'";

        $result = mysqli_query($this->con, $query);

        if (mysqli_affected_rows($this->con) > 0) {
            $message = array();
            $message['error'] = false;
            $message['message'] = "ADT updated successfully";
            return $message;
        } else {
            $message = array();
            $message['error'] = true;
            $message['message'] = "ADT is not updated";
            return $message;
        }
    }
}
