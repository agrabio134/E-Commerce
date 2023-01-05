<!-- views/products/show.php -->

<h1><?php echo $product['prod_name']; ?></h1>

<img src="../../controllers/uploads/<?php echo $product['prod_image']; ?>" alt="<?php echo $product['prod_image']; ?>">

<p>Php <?php echo $product['prod_price']; ?></p>

<p><?php echo $product['prod_description']; ?></p>


<!-- Add to cart form -->
<form action="/products/cart" method="POST">
  <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
  <label for="quantity">Quantity:</label>
  <input type="number" id="quantity" name="quantity" value="1">
  <button type="submit">Add to Cart</button>
</form>
