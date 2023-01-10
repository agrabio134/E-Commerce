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

        $product_id = $_POST['prod_id'];


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
        // insert to database
        $customer_id = $_SESSION['id'];

        //if data is not present, insert data into database else update the data
        $stmt = $this->conn->prepare('SELECT * FROM cart WHERE customers_id = ? AND product_id = ?');
        $stmt->execute([$customer_id, $product_id]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cart) {
            // To insert a new row
            $stmt = $this->conn->prepare('INSERT INTO cart (customers_id, product_id, quantity) VALUES (?, ?, ?)');
            $stmt->execute([$customer_id, $product_id, $quantity]);
        } else {
            // To update an existing row

            //not really update but it should add the current quantity to the existing quantity
            $quantity = $cart['quantity'] + $quantity;

            $stmt = $this->conn->prepare('UPDATE cart SET quantity = ? WHERE customers_id = ? AND product_id = ?');
            $stmt->execute([$quantity, $customer_id, $product_id]);
        }
    }

    public function update_cart()
    {
        session_start();
        if (isset($_SESSION['id'])) {
            // The user is logged in, so save the cart to the 'cart' table in the database
            $customer_id = $_SESSION['id'];

            $product_id = $_POST['product_id'];
            $quantity = $_POST[$product_id . '_quantity'];

            $qty = $quantity;


            $stmt = $this->conn->prepare('UPDATE cart SET quantity = ? WHERE customers_id = ? AND product_id = ?');
            $stmt->execute([$qty, $customer_id, $product_id]);


            header('Location: /products/viewcart');
        } else {
            header('Location: /customers/login');
        }

        echo "<br>";
        echo "cust id " . $customer_id;
        echo "<br>";
        echo "prod id " . $product_id;
    }



    public function cart()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['id'])) {
            header('Location: /customers/login');
            exit;
        }


        echo 'Welcome ' . $_SESSION['customer_name'];

        $products = [];
        $customer_id = $_SESSION['id'];
        $total = 0;



        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }


        foreach ($_SESSION['cart'] as $id => $quantity) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            $stmt = $this->conn->prepare('SELECT * FROM products WHERE id = ?');
            $stmt->execute([$id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            $product['quantity'] = $quantity;


            $products[] = $product;
            // echo  $quantity;

            $productPrices = array();

            //select data from table cart and table product to get the total price
            $stmt = $this->conn->prepare(
                'SELECT c.id, c.customers_id, c.product_id, c.quantity, p.prod_name, p.prod_price
             FROM cart c
             INNER JOIN products p ON c.product_id = p.id
             WHERE c.customers_id = ?'
            );
            $stmt->execute([$customer_id]);
            $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($cart as $product) {
                $productPrices[] = $product['quantity'] * $product['prod_price'];
            }
            $total = array_sum($productPrices);
            // echo $productPrices;

        }



        $stmt = $this->conn->prepare(
            'SELECT c.id, c.customers_id, c.product_id, c.quantity, p.prod_name, p.prod_price
             FROM cart c
             INNER JOIN products p ON c.product_id = p.id
             WHERE c.customers_id = ?'
        );
        $stmt->execute([$customer_id]);
        $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->execute([$customer_id]);
        // $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);



        // Render the cart view
        header('Location: /products/index');
    }
    public function viewCart()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['id'])) {
            header('Location: /customers/login');
            exit;
        }


        echo 'Welcome ' . $_SESSION['customer_name'];

        $products = [];
        $customer_id = $_SESSION['id'];
        $total = 0;



        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }


        foreach ($_SESSION['cart'] as $id => $quantity) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            $stmt = $this->conn->prepare('SELECT * FROM products WHERE id = ?');
            $stmt->execute([$id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            $product['quantity'] = $quantity;


            $products[] = $product;
            // echo  $quantity;

            $productPrices = array();

            //select data from table cart and table product to get the total price
            $stmt = $this->conn->prepare(
                'SELECT c.id, c.customers_id, c.product_id, c.quantity, p.prod_name, p.prod_price
             FROM cart c
             INNER JOIN products p ON c.product_id = p.id
             WHERE c.customers_id = ?'
            );
            $stmt->execute([$customer_id]);
            $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($cart as $product) {
                $productPrices[] = $product['quantity'] * $product['prod_price'];
            }
            $total = array_sum($productPrices);
            // echo $productPrices;

        }



        $stmt = $this->conn->prepare(
            'SELECT c.id, c.customers_id, c.product_id, c.quantity, p.prod_name, p.prod_price
             FROM cart c
             INNER JOIN products p ON c.product_id = p.id
             WHERE c.customers_id = ?'
        );
        $stmt->execute([$customer_id]);
        $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->execute([$customer_id]);
        // $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require 'views/products/cart.php';

    }
    public function checkout()
    {
        // Get the current user's cart from the database
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['id'])) {
            header('Location: /customers/login');
            exit;
        }

        $customer_id = $_SESSION['id'];

        $stmt = $this->conn->prepare(
            'SELECT c.id, c.customers_id, c.product_id, c.quantity, p.prod_name, p.prod_price
             FROM cart c
             INNER JOIN products p ON c.product_id = p.id
             WHERE c.customers_id = ?'
        );
        $stmt->execute([$customer_id]);
        $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get the customer details from the database
        $stmt = $this->conn->prepare('SELECT * FROM customers WHERE id = ?');
        $stmt->execute([$customer_id]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        //get total price
        $productPrices = array();
        foreach ($cart as $product) {
            $productPrices[] = $product['quantity'] * $product['prod_price'];
        }
        $total = array_sum($productPrices);



        // Render the checkout view

        // require 'views/products/checkout.php';
        require 'views/checkout/checkout.php';
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
