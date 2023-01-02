<?php

class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "db_ecomm";

    public function connect() {
        $conn = new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }

    //test connection

    // public function test() {
    //     try {
    //         $conn = $this->connect();
    //         echo "Connected successfully";
    //     } catch (PDOException $e) {
    //         echo "Connection failed: " . $e->getMessage();
    //     }
    // }


}