<!-- views/customers/index.php -->

<?php 
  // Check if the user is logged in
  session_start();
  if (!isset($_SESSION['id'])) {
    header('Location: /customers/login');
    exit;
  }

  echo 'Welcome ' . $_SESSION['customer_name'];
  // echo 'Welcome ' . $_SESSION['email'];
  // echo 'Welcome ' . $_SESSION['id'];
?>

<h1>Customers</h1>

<table>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Address</th>
    <th>Actions</th>
  </tr>
  <?php foreach ($customers as $customer): ?>
    <tr>
      <td><?php echo $customer['id']; ?></td>
      <td><?php echo $customer['customer_name']; ?></td>
      <td><?php echo $customer['customer_email']; ?></td>
      <td><?php echo $customer['customer_address']; ?></td>
      <td>
        <a href="/customers/edit?id=<?php echo $customer['id']; ?>">Edit</a> |
        <a href="/customers/delete?id=<?php echo $customer['id']; ?>" 
        onclick="confirm('Are you sure you want to delete this record?')">Delete</a>
      </td>
    </tr>
  <?php endforeach; ?>
  <a href="/customers/logout">Log out</a>

</table>
