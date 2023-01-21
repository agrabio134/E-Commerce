<!-- Edit customer form -->

<head>
    <link rel="stylesheet" type="text/css" href="../../public/css/customers/edit-profile.css">
</head>

<form action="/customers/update" method="post">
  <input type="hidden" name="customer_id" value="<?php echo $customer['id']; ?>">
  <label for="customer_name">Name:</label><br>
  <input type="text" id="customer_name" name="customer_name" value="<?php echo $customer['customer_name']; ?>"><br>
  <label for="customer_email">Email:</label><br>
  <input type="text" id="customer_email" name="customer_email" value="<?php echo $customer['customer_email']; ?>"><br>
  <label for="customer_password">New Password:</label><br>
  <input type="password" id="customer_password" name="customer_password"><br>
  <label for="customer_address">Address:</label><br>
  <input type="text" id="customer_address" name="customer_address" value="<?php echo $customer['customer_address']; ?>"><br>
  <input type="submit" value="Update">
</form>
