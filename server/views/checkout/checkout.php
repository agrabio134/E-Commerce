<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="../../public/css/products/checkout.css">
</head>


<body>


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

<form action="/orders/confirm" method="POST">

  <table>
    <tr>
      <th>Product</th>
      <th>Price</th>
      <th>Quantity</th>
      <th>Total</th>
    </tr>
    <?php foreach ($cart as $product) : ?>
      <?php if ($product['quantity'] > 0) : ?>
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

  <input type="hidden" name="total_price" value="<?php echo $total; ?>">
  <input type="hidden" name="customer_address" value="<?php echo $customer['customer_address']; ?>">

  <label for="shipping_option">Shipping Option:</label>
  <select name="shipping_option" id="shipping_option">
    <option value="standard">Standard (Free)</option>
    <option value="express">Express (Php 50.00)</option>
    <option value="COD">Cash on Delivery (Php 60.00)</option>
  </select>
  <br />
  <!-- payment method -->
  <label for="pay_method">Payment Method: </label>
  <select name="pay_method" id="pay_method">
    <option value="GCASH">GCASH</option>
    <option value="CASH">CASH </option>
  </select>


  <br />
  <input type="submit" value="Checkout" onclick="
  if (confirm(`Are you sure you want to Confirm Checkout?`)) {
    alert(`Checkout Confirmed!`);
  } else {
    // alert(`Checkout Cancelled!`);
    event.preventDefault();
    }
  ">
</form>

</body>

  <!-- when shipping option is COD, payment method should be in cash by default use javascript-->
  <script>
    const shippingOption = document.getElementById('shipping_option');
    const paymentMethod = document.getElementById('pay_method');
    shippingOption.addEventListener('change', (event) => {
      if (event.target.value === 'COD') {
        paymentMethod.value = 'CASH';
        paymentMethod.disabled="";

      } else {
        paymentMethod.value = 'GCASH';
        paymentMethod.disabled="";

      }
    });
  </script>