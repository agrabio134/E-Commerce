<?php
  // Check if the user is already logged in
  if (isset($_SESSION['customer_id'])) {
    header('Location: ./products/index.php');
    exit;
  }
?>


<h1>Log in</h1>

<?php if (isset($error)): ?>
  <p class="error"><?php echo $error; ?></p>
<?php endif; ?>

<form action="/customers/authenticate" method="post">
  <label for="email">Email:</label><br>
  <input type="email" name="email" id="email"><br>
  <label for="password">Password:</label><br>
  <input type="password" name="password" id="password"><br><br>
  <input type="submit" value="Log in">
</form>