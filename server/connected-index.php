<?php
    require_once 'config/database.php';

    class Application {
        public $database;
        public $conn;
    
        public function __construct() {
            $this->database = new Database();
            $this->conn = $this->database->connect();
        }

        public function TestConnection() {
            try {
                $conn = $this->conn;
                echo "Connected successfully";
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }       

    }
    
    $app = new Application();
    $app->TestConnection();    
?>

