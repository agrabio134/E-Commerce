<?php

//database configuration file
require_once 'config/database.php';

//CustomerController file
require_once 'controllers/CustomerController.php';

//  request URI to determine the controller and action
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// default controller and action
$controller = "HomeController";
$action = "index";
$id = 1;

// Check the request URI and set the controller and action
if ($uri[1] == "products") {
  $controller = "ProductController";
  $action = $uri[2];
} elseif ($uri[1] == "customers") {
  $controller = "CustomerController";
  $action = $uri[2];
}

// Check if the controller and action exist, and kung meron, create a new instance of the controller and call the action
if (file_exists('controllers/' . $controller . '.php')) {
  require_once 'controllers/' . $controller . '.php';

  $customerController = new CustomerController();
  if ($action == 'show') {
    $customerController->show($id);
  }
  

 

  if (method_exists($controller, $action)) {
    $controller = new $controller();
    $controller->$action();
  } else {
    // Action not found
    header("HTTP/1.0 404 Not Found");
    exit();
  }
} else {
  // Controller not found
  header("HTTP/1.0 404 Not Found");
  exit();
}

?>
