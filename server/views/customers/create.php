
<!-- signup form for customer -->
<!-- Create customer form -->
<form action="/customers/create" method="POST">
  <label for="customer_name">Name:</label><br>
  <input type="text" id="customer_name" name="name"><br>
  <label for="customer_email">Email:</label><br>
  <input type="text" id="customer_email" name="email"><br>
  <label for="customer_password">Password:</label><br>
  <input type="password" id="customer_password" name="password"><br>
  <label for="customer_address">Address:</label><br>
  
  <input type="text" id="customer_address" name="address"><br>
  <input type="submit" value="Create">
</form>
