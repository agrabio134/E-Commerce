
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>

<body>
<div class="main-header">
<h1>Products</h1>

<ul class="header-item">
<?php 
session_start();
//show customer information session
if (!isset($_SESSION['id'])) {
    echo '<li><a href="/customers/create">Sign Up <span class="badge"></span></a></li>';
    echo '<li><a href="/customers/login">Login <span class="badge"></span></a></li>';
  } 
    else {
        $welcome = 'Welcome ' . $_SESSION['customer_name'];
        echo '<li><a href="/customers/edit?id=' . $_SESSION['id'] . '">'. $welcome .'  <span class="badge"></span></a></li>';
        echo '<li><a href="/customers/logout">Log out <span class="badge"></span></a></li>';
    }
?>
<!-- <li><a href="/customers/create">Sign Up <span class="badge"></span></a></li> -->
<!-- <li><a href="/customers/login">Login <span class="badge"></span></a></li> -->

</ul>
</div>
<li><a href="/products/viewcart">View Cart <span class="badge"></span></a></li>

<!-- <a href="/products/create">Add Item <span class="badge"></span></a> -->
<div class="product-card">
    <?php foreach ($products as $product) : ?>
        <a href="/products/show_prod?id=<?php echo $product['id']; ?>">
            <div class="item-card">
                <div class="item-img-container">
                <img src="../../controllers/uploads/<?php echo $product['prod_image']; ?>" alt="<?php echo $product['prod_image']; ?>" class="item-img">
                </div>
                <div class="item-title">
                    <h3><?php echo $product['prod_name']; ?></h3>
                </div>
                <!-- <div class="item-description">
                    <p><i><?php
                    //  echo $product['prod_description']; 
                    ?></i></p>
                </div> -->
                <div class="item-price">
                    <?php echo $product['prod_price']; ?>
                </div>

            </div>
        </a>

    <?php endforeach; ?>
</div>

</body>

</html>
