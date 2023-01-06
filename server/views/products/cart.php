<h1>Cart</h1>

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
      <td>
        <form action="/products/update_cart" method="POST" data-price="<?php echo $product['prod_price']; ?>">
          <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
          <input type="number" id="<?php echo $product['product_id']; ?>_quantity" name="<?php echo $product['product_id']; ?>_quantity" value="<?php echo $product['quantity']; ?>">
          <button type="submit">Update</button>
        </form>
      </td>
      <td>Php <?php echo $product['prod_price'] * $product['quantity']; ?></td>
    </tr>
    <?php endif; ?>

  <?php endforeach; ?>
  
</table>
<p>Total: Php <?php echo $total; ?></p>
<a href="/products/checkout">Checkout</a>