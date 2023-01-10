<h1>Order Confirmation</h1>

<p>Thank you for your order! Your order reference number is <strong><?php 
// Get the order ID
$stmt = $this->conn->prepare('SELECT unique_id FROM orders WHERE customer_id = ? ORDER BY id DESC LIMIT 1');
$stmt->execute([$customer_id]);
$unique_id = $stmt->fetch(PDO::FETCH_ASSOC)['unique_id'];
echo $unique_id;
?></strong>.</p>

<h2>Order Details</h2>

<table>
  <tr>
    <th>Product</th>
    <th>Quantity</th>
    <th>Price</th>
  </tr>
  <?php foreach ($order_items as $item) : ?>
    <tr>
      <td><?php echo $item['prod_name']; ?></td>
      <td><?php echo $item['quantity']; ?></td>
      <td>$<?php echo $item['price']; ?></td>
    </tr>
  <?php endforeach; ?>
  <tr>
    <td colspan="2">Shipping:</td>
    <td>$<?php echo $shipping_price; ?></td>
  </tr>
  <tr>
    <td colspan="2">Total:</td>
    <td>$<?php echo $total_price; ?></td>
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
