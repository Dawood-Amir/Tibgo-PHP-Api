<?php

class DbConnect
{
    private $con;

    public function connect()
    {

        require_once 'include/Constants.php';

        $this->con = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        return $this->con;

        // try{
        //     $this->con = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        // }catch(PDOException $exception){
        //     echo "Connection failed: " . $exception->getMessage();
        // }
        // return $this->con;

    }


}


?>

