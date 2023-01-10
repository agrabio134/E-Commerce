<?php

//database configuration file
require_once 'config/database.php';

//CustomerController file
require_once 'controllers/CustomerController.php';
require_once 'controllers/ProductController.php';
require_once 'controllers/OrderController.php';

require_once 'Router/Router.php';

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
  // set default $uri[2] to index
  if (empty($uri[2])) {
    $action = "index";
  } else {
    $action = $uri[2];
  }
} elseif ($uri[1] == "customers") {
  $controller = "CustomerController";
  if (empty($uri[2])) {
    $action = "index";
  } else {
    $action = $uri[2];
  }
} elseif ($uri[1] == "orders") {
  $controller = "OrderController";
  if (empty($uri[2])) {
    $action = "index";
  } else {
    $action = $uri[2];
  }
} 




// instantiate the controller and call the action
$productController = new ProductController();
$customerController = new CustomerController();
$orderController = new OrderController();



$router->post('/products/cart', function() use ($productController) {
  $productController->addToCart();
});

$router->get('/checkout', function() {
  require __DIR__ . '/../views/checkout.php';
});

//order controller
$router->post('/orders/payment', function() use ($orderController) {
  $orderController->payment();
});







// Check if the controller and action exist, and kung meron, create a new instance of the controller and call the action
if (file_exists('controllers/' . $controller . '.php')) {
  require_once 'controllers/' . $controller . '.php';

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