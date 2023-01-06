<h1>Checkout</h1>
<?php
//get customers from session
$customers_name = $_SESSION['customer_name'];
echo '<div class="alert alert-success" role="alert">Customer Name: ' . $customers_name . '</div>';

//get customer address from database
$customers_id = $_SESSION['id'];
$stmt = $this->conn->prepare('SELECT * FROM customers WHERE id = ?');
$stmt->execute([$customers_id]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);
echo '<div class="alert alert-success" role="alert">Customer Address: ' . $customer['customer_address'] . '</div>';
?>

<br>


<table>
  <tr>
    <th>Product</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Total</th>
  </tr>
  <?php foreach ($cart as $product) : ?>
    <?php if ($product['quantity'] > 0): ?>
    <tr>
      <td><?php echo $product['prod_name']; ?></td>
      <td>Php <?php echo $product['prod_price']; ?></td>
      <td><?php echo $product['quantity']; ?></td>
      <td>Php <?php echo $product['prod_price'] * $product['quantity']; ?></td>
    </tr>
    <?php endif; ?>
  <?php endforeach; ?>
</table>

<p>Total: Php <?php echo $total; ?></p>

<form action="/order/confirm" method="POST">
  <input type="hidden" name="total_price" value="<?php echo $total; ?>">

  <label for="shipping_option">Shipping Option:</label>
  <select name="shipping_option" id="shipping_option">
    <option value="standard">Standard (Free)</option>
    <option value="express">Express (Php 50.00)</option>
    <option value="COD">Cash on Delivery (Php 60.00)</option>
  </select>
  <br/>

  <input type="submit" value="Confirm" onclick="
  if (confirm(`Are you sure you want to Confirm Checkout?`)) {
    alert(`Checkout Confirmed!`);
  } else {
    // alert(`Checkout Cancelled!`);
    event.preventDefault();
    }
  ">
</form>