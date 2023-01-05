<?php

class ProductController
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
        // Get a list of all products from the database
        $stmt = $this->conn->prepare('SELECT * FROM products');
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Render the product list view
        require 'views/products/index.php';
    }

    public function show_prod()
    {
        // Get the product details from the database
        $id = $_GET['id'];
        $stmt = $this->conn->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // Render the product detail view
        require 'views/products/show.php';
    }

    public function addToCart()
    {
        // Get the product ID and quantity from the form submission
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $product_id = $_POST['id'];

        if (isset($_POST['quantity'])) {
            // The form submission is for adding to the cart
            $quantity = $_POST['quantity'];

            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id] = $quantity;
            } else {
                $_SESSION['cart'][$product_id] = $quantity;
            }
        } else {
            // The form submission is for updating the cart
            $quantity = $_POST[$product_id . '_quantity'];

            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id] = $quantity;
            } else {
                $_SESSION['cart'][$product_id] = $quantity;
            }
        }
    }



    public function cart()
    {

        $products = [];
        $total = 0;

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }


        foreach ($_SESSION['cart'] as $id => $quantity) {
            $stmt = $this->conn->prepare('SELECT * FROM products WHERE id = ?');
            $stmt->execute([$id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
          
            $product['quantity'] = $quantity;

            $products[] = $product;
            // echo  $quantity;

            $productPrices = array();

            foreach ($products as $product) {
                $productPrices[] = $product['prod_price'] * $product['quantity'];
              }

              $total = array_sum($productPrices);

            //   echo 'Total: $' . $totalPrice;





           

            // $totalQuantity = 0;
            // foreach ($products as $product) {
            //     $totalQuantity += $product['quantity'];
            // }
            // // echo $totalQuantity;
            // // // echo $quantity;
            // // echo "<br>";

            // $total = $product['prod_price'] * $totalQuantity;
            // echo $total;
            // echo "<br>";
            // echo var_dump($total);
            // echo $product['quantity'];
        }

        // Render the cart view
        require 'views/products/cart.php';
    }





    public function create()
    {
        // Render the create product form view
        require 'views/products/create.php';
    }

    public function store()
    {
        // process form data and store a new product
        // $txtimage = $_POST['image'];
        if (isset($_FILES['image'])) {
            $image = $_FILES['image']['name'];
            // image file directory 
            $target = "controllers/uploads/" . basename($image);


            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                echo "Image uploaded successfully";
                $stmt = $this->conn->prepare('INSERT INTO products (prod_name, prod_price, prod_description, prod_image) 
                VALUES (?, ?, ?, ?)');
                $stmt->execute([$_POST['name'], $_POST['price'], $_POST['description'], $image]);
            } else {
                echo "Failed to upload image";
            }
        } else {
            echo "Image not set";
        }

        // header('Location: /products/index');

    }

    public function edit()
    {
        // Get the product details from the database
        $id = $_GET['id'];
        $stmt = $this->conn->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // Render the edit product form view
        require 'views/products/edit.php';
    }

    public function update($id)
    {
        // Update the product in the database
        $stmt = $this->conn->prepare('UPDATE products SET prod_name = ?, prod_price = ?, prod_description = ? WHERE id = ?');
        $stmt->execute([$_POST['name'], $_POST['price'], $_POST['description'], $id]);

        // Redirect to the product detail page
        header('Location: /products/' . $id);
    }

    public function delete($id)
    {
        // Delete the product from the database
        $stmt = $this->conn->prepare('DELETE FROM products WHERE id = ?');
        $stmt->execute([$id]);

        // Redirect to the product list
        header('Location: /products');
    }
}
