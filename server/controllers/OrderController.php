<?php

class OrderController
{
    public $database;
    public $conn;


    public function __construct()
    {
        $this->database = new Database();
        $this->conn = $this->database->connect();
    }


    public function confirm()
    {

        session_start();


        // Get the customer ID from the session
        $customer_id = $_SESSION['id'];

        // Get the shipping option from the form submission
        $shipping_option = $_POST['shipping_option'];

        // Calculate the shipping price based on the selected option
        $shipping_price = 0;
        switch ($shipping_option) {
            case 'standard':
                $shipping_price = 0;
                break;
            case 'express':
                $shipping_price = 50;
                break;
            case 'COD':
                $shipping_price = 60;
                break;
        }
        //when shipping is COD payment method should be in cash by default
        


        // Get the total price of the order from the form submission
        $total_price = $_POST['total_price'];
        echo $total_price;
        $customer = $_POST['customer_address'];

        // Calculate the final price of the order (including shipping)
        $final_price = $total_price + $shipping_price;


        $unique_id = "ORD" . uniqid();

        // Insert the order into the database
        $stmt = $this->conn->prepare('INSERT INTO orders (unique_id, customer_id, total_price, shipping_option, customer_address, final_price) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$unique_id, $customer_id, $total_price, $shipping_option, $customer, $final_price]);
        // Get the ID of the inserted order
        $order_id = $this->conn->lastInsertId();

        // Get the cart data from the database
        $stmt = $this->conn->prepare('SELECT * FROM cart WHERE customers_id = ?');
        $stmt->execute([$customer_id]);
        $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //get the quantity * price of each product

        foreach ($cart as $item) {
            $stmt = $this->conn->prepare('SELECT * FROM products WHERE id = ?');
            $stmt->execute([$item['product_id']]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            $price = $product['prod_price'] * $item['quantity'];
            // Insert the order items into the database
            $stmt = $this->conn->prepare('INSERT INTO order_items (orders_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
            $stmt->execute([$order_id, $item['product_id'], $item['quantity'],  $price]);

            // } 
        }

        // Clear the cart
        $stmt = $this->conn->prepare('DELETE FROM cart WHERE customers_id = ?');
        $stmt->execute([$customer_id]);

        // Redirect the user to the order confirmation page
        if ($shipping_option == 'COD') {
            header('Location: /orders/confirmation/');
        } else {
            header('Location: /orders/payment');
        }
    }
    // header('Location: /orders/payment');


    public function payment()
    {

        require_once('../vendor/autoload.php');

        $client = new \GuzzleHttp\Client();

        //get lastid and final price from orders table
        $stmt = $this->conn->prepare('SELECT * FROM orders ORDER BY id DESC LIMIT 1');
        $stmt->execute();
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        $amount = $order['final_price'];
        //remove dot from final price
        $amount = str_replace('.', '', $amount);

        // $final_price

        echo $amount;

        $response = $client->request('POST', 'https://api.paymongo.com/v1/sources', [
            'body' => '{"data":{"attributes":{"amount":' . $amount . ',"redirect":{"success":"http://localhost:8080/orders/confirmation","failed":"http://localhost:8080/orders/failed"},"type":"gcash","currency":"PHP"}}}',
            'headers' => [
                'accept' => 'application/json',
                'authorization' => 'Basic c2tfdGVzdF8yR2lRQzlKVEdrNDZwWXFNNW5Hc0xDSmI6c2tfdGVzdF8yR2lRQzlKVEdrNDZwWXFNNW5Hc0xDSmI=',
                'content-type' => 'application/json',
            ],
        ]);

        echo $response->getBody();
        // I want to get checkout url from response body and redirect to it 
        $response = json_decode($response->getBody(), true);
        $checkout_url = $response['data']['attributes']['redirect']['checkout_url'];

        header('Location: ' . $checkout_url);

       

    }
    


    // public function get_payment()
    // {
    //     require_once('../vendor/autoload.php');

    //     $client = new \GuzzleHttp\Client();

    //     $response = $client->request('POST', 'https://api.paymongo.com/v1/webhooks', [
    //         'body' => '{"data":{"attributes":{"events":["source.chargeable"],"url":"https://cnfragrance.000webhostapp.com/"}}}',
    //         'headers' => [
    //             'accept' => 'application/json',
    //             'authorization' => 'Basic c2tfdGVzdF8yR2lRQzlKVEdrNDZwWXFNNW5Hc0xDSmI6',
    //             'content-type' => 'application/json',
    //         ],
    //     ]);

    //     echo $response->getBody();
    // }

    public function confirmation()
    {
        // Get the current user's order from the database
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $customer_id = $_SESSION['id'];
        $stmt = $this->conn->prepare('SELECT * FROM orders WHERE customer_id = ? ORDER BY id DESC LIMIT 1');
        $stmt->execute([$customer_id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        // Get the order items from the database order_items and products table.
        $stmt = $this->conn->prepare('SELECT * FROM order_items INNER JOIN products ON order_items.product_id = products.id WHERE orders_id = ?');
        $stmt->execute([$order['id']]);
        $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get the shipping price from the order
        $shipping_price = 0;
        switch ($order['shipping_option']) {
            case 'standard':
                $shipping_price = 0;
                break;
            case 'express':
                $shipping_price = 50;
                break;
            case 'COD':
                $shipping_price = 60;
                break;
        }

        // Get the total price of the order plus shipping fee
        $total_price = $order['total_price'] + $shipping_price;

        // Get the customer details from the database
        $stmt = $this->conn->prepare('SELECT * FROM customers WHERE id = ?');
        $stmt->execute([$customer_id]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);


        // Render the order confirmation view
        require 'views/orders/confirmation.php';
    }
    public function failed()
    {
        // Render the order failed view
        echo "failed";
    }
}
