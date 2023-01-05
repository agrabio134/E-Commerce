<h1>Cart</h1>

<table>
  <tr>
    <th>Product</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Total</th>
  </tr>
  <?php foreach ($products as $product): ?>
    <tr>
      <td><?php echo $product['prod_name']; ?></td>
      <td>$<?php echo $product['prod_price']; ?></td>
      <td>
      <form action="/products/cart" method="POST" data-price="<?php echo $product['prod_price']; ?>">
                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                <input type="number" id="<?php echo $product['id']; ?>_quantity" name="<?php echo $product['id']; ?>_quantity" value="<?php echo $product['quantity']; ?>" onchange="updateTotalPrice(this.form)">

          <button type="submit">Update</button>
        </form>
      </td>
      <td>$<?php echo $product['prod_price'] * $product['quantity']; ?></td>
    </tr>
  <?php endforeach; ?>
</table>

<p>Total: $<?php echo $total; ?></p>


<a href="/checkout">Checkout</a>

<script>

function updateTotalPrice() {
  var data = [];
  // Loop through all the products in the cart
  $('form').each(function() {
    var form = $(this);
    var price = form.data('price');
    var quantity = form.find('input[type=number]').val();
    data.push({
      id: form.find('input[name=id]').val(),
      quantity: quantity,
      price: price
    });
  });
  // Send an AJAX request to the server to update the cart
  $.ajax({
    url: '/products/update-cart',
    type: 'POST',
    data: {
      products: data
    },
    success: function(response) {
      // Update the total price display
      $('#total_price').text('Total: $' + response.totalPrice);
    }
  });
}
</script>
