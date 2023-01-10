<!-- views/customers/show.php -->
<?php
  require_once 'config/database.php';

  $id = $_GET['id'];
  // $id = 1;

  $query = "SELECT * FROM customers WHERE id = :id";
  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
  $stmt->execute();

  $customer = $stmt->fetch();
?>






<h1><?php echo $customer['customer_name']; ?></h1>
<p>Email: <?php echo $customer['customer_email']; ?></p>

<h2>Orders</h2>
<table>
  <tr>
    <th>ID</th>
    <th>Date</th>
    <th>Total</th>
  </tr>
  <?php
    $query = "SELECT * FROM orders WHERE id = " . $customer['id'];
    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    $orders = $stmt->fetchAll();

    foreach ($orders as $order) {
      echo '<tr>';
      echo '<td>' . $order['id'] . '</td>';
      echo '<td>' . $order['date'] . '</td>';
      echo '<td>$' . $order['total'] . '</td>';
      echo '</tr>';
    }
  ?>
</table>
