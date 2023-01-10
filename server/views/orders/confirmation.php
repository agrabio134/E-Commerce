<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Order Confirmation</title>
  <link rel="stylesheet" href="../../public/css/confirm-order.css">
  

<body>
<div class="confirmation-main">
<h1 class="order-confirmation-title">Order Confirmation</h1>
<div class="confirmation-content">
<p>Thank you for your order! Your order reference number is <strong><?php 
// Get the order ID
$stmt = $this->conn->prepare('SELECT unique_id FROM orders WHERE customer_id = ? ORDER BY id DESC LIMIT 1');
$stmt->execute([$customer_id]);
$unique_id = $stmt->fetch(PDO::FETCH_ASSOC)['unique_id'];
echo $unique_id;
?></strong>.</p>

<h2>Order Details</h2>
  
<p class="payment-method">Payment Method: <?php echo $payments['payment_method']; ?></p> 


<table>
  <tr>
    <th>Product</th>
    <th>Quantity</th>
    <th>Price</th> 
  </tr>
  <?php foreach ($order_items as $item) : ?>
    <?php if ($item['quantity'] > 0): ?>

    <tr>
      <td><?php echo $item['prod_name']; ?></td>
      <td><?php echo $item['quantity']; ?></td>
      <td>&#8369;<?php echo $item['price']; ?></td>
      </tr>
    <?php endif; ?>
  <?php endforeach; ?>
  <tr>
  
  <td colspan="2">Shipping Fee:</td>
  <td>&#8369;<?php echo $shipping_price; ?></td>
  
    </tr>

    <tr>
  
  <td colspan="2"><h3>Total: </h3></td>
  <td><h3>&#8369;<?php echo $total_price; ?></h3></td>
  
    </tr>
</table>

<p>Your order will be shipped to the following address:</p>

<address>
  <?php 
  // Get the customer's name and address
  $stmt = $this->conn->prepare('SELECT * FROM customers WHERE id = ?');
  $stmt->execute([$customer_id]);
  $customer = $stmt->fetch(PDO::FETCH_ASSOC);
  $customer_name = $customer['customer_name'];
  $customer_address = $customer['customer_address'];
  

  ?>
  <?php echo $customer_name; ?><br>
  <?php echo $customer_address; ?><br>

</address>
</div>
</div>

</body>