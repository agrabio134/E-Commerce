<?php
// require_once '../config/database.php';

class CustomerController
{

  public $database;
  public $conn;


  public function __construct()
  {
    $this->database = new Database();
    $this->conn = $this->database->connect();
  }

  public function index()
  {
    // Get a list of all customers
    $query = "SELECT * FROM customers";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    $customers = $stmt->fetchAll();

    // Display the customer list view
    require_once 'views/customers/index.php';
  }


  public function show()
  {
    // Get the details of the customer with the specified ID
    $query = "SELECT * FROM customers WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // $customer = $stmt->fetch();


    // Display the customer detail view
    require_once 'views/customers/show.php';
  }
  public function create()
  {
    require_once 'views/customers/create.php';
    

    if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['address'])) {
      $name = $_POST['name'];
      $email = $_POST['email'];
      $password = md5($_POST['password']);
      $address = $_POST['address'];

      
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Please enter a valid email address";
        exit;
      }

      if(empty($name) || empty($email) || empty($password) || empty($address)) {
        echo "Please fill out all fields";
      } else {
        $query = "INSERT INTO customers (customer_name, customer_email, customer_password, customer_address) VALUES (:name, :email, :password, :address)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $stmt->execute();

        header('Location: /customers/index');
      }
    }
  }
   

  public function edit()
  {

    // Get the customer's details from the database
    $id = $_GET['id'];
    $stmt = $this->conn->prepare('SELECT * FROM customers WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $customer = $stmt->fetch();
    

    // Render the edit customer form
    require 'views/customers/edit.php';
  }

  public function update()
  {
    

     if (isset($_POST['customer_id']) && isset($_POST['customer_name']) && isset($_POST['customer_email']) && isset($_POST['customer_password']) && isset($_POST['customer_address'])) {
      {
        $customer_id = $_POST['customer_id'];
        $customer_name = $_POST['customer_name'];
        $customer_email = $_POST['customer_email'];
        $customer_password = md5($_POST['customer_password']);
        $customer_address = $_POST['customer_address'];

        if ($customer_name == "" || $customer_email == "" || $customer_password == "" || $customer_address == "") {
          // Return an error if any of the fields are empty
          echo 'Error: All fields are required';
          //add http response code
          http_response_code(400);
          exit();
        }elseif (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
          // Return an error if the email address is invalid
          echo 'Error: Invalid email address';
          http_response_code(400);
          exit();
        }

        // Get the customer's details from the database
        $stmt = $this->conn->prepare('SELECT * FROM customers WHERE id = :id');
        $stmt->execute(['id' => $customer_id]);
        $customer = $stmt->fetch(PDO::FETCH_OBJ);

        // Check if the email address already exists
        $stmt = $this->conn->prepare('SELECT * FROM customers WHERE customer_email = :customer_email AND id != :id');
        $stmt->execute(['customer_email' => $customer_email, 'id' => $customer_id]);
        $customer = $stmt->fetch(PDO::FETCH_OBJ);
        if ($customer) {
          // Return an error if the email address already exists
          return 'Error: Email address already exists';
        }

        // Update the customer record in the database

        $stmt = $this->conn->prepare('UPDATE customers SET customer_name = :name, customer_email = :email, customer_password = :password, customer_address = :address WHERE id = :id');
        $stmt->bindParam(':id', $customer_id);
        $stmt->bindParam(':name', $customer_name);
        $stmt->bindParam(':email', $customer_email);
        $stmt->bindParam(':password', $customer_password);
        $stmt->bindParam(':address', $customer_address);
        $stmt->execute();

        // Redirect to the customer show page
        header('Location: /customers/show?id=' . $customer_id);



      }
     }
  }

    
  
  

  public function delete()
  {
    // Delete a customer's account
    $id = $_GET['id'];
    $query = "DELETE FROM customers WHERE id = :id";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Redirect to the customer list page
    header('Location: /customers/index');



  }

  public function login()
  {
    // Display a form for logging in
    require_once 'views/customers/login.php';
  }

  public function authenticate()
  {
    //add session data to session
    // session_start();
    // Authenticate a customer
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $stmt = $this->conn->prepare('SELECT * FROM customers WHERE customer_email = ? AND customer_password = ?');
    $stmt->execute([$email, $password]);
    $customer = $stmt->fetch();

  
    if ($customer) {
      // Set the customer_id and customer_name session variables
      
      session_start();

      $_SESSION['id'] = $customer['id'];
      $_SESSION['customer_name'] = $customer['customer_name'];
      // echo 'Success: You are now logged in';
      header('Location: /customers/index');
      return true;
    } else {
      echo 'Error: Invalid email address or password';
      return false;
    }
    
  }

  public function logout()
  {
    // Destroy the session
    session_start();
    session_destroy();
    // Redirect to the login page
    header('Location: /customers/login');
    exit;
  }
}

$app = new CustomerController();

// $app->show(1);


// $app->index();
