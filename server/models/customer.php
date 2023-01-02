<?php
class Customer {
  private $id;
  private $name;
  private $email;
  private $password;
  private $address;

  public function __construct($id, $name, $email, $password, $address) {
    $this->id = $id;
    $this->name = $name;
    $this->email = $email;
    $this->password = $password;
    $this->address = $address;
  }

  public function getId() {
    return $this->id;
  }

  public function getName() {
    return $this->name;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function getEmail() {
    return $this->email;
  }

  public function setEmail($email) {
    $this->email = $email;
  }

  public function getPassword() {
    return $this->password;
  }

  public function setPassword($password) {
    $this->password = $password;
  }

  public function getAddress() {
    return $this->address;
  }

  public function setAddress($address) {
    $this->address = $address;
  }
}

// Path: server\models\customer.php
// Compare this snippet from server\controllers\CustomerController.php:
?>
