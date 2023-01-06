<h1>Products</h1>

<a href="/products/cart">View Cart <span class="badge"></span></a>


<table>
    <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Name</th>
        <th>Description</th>
        <th>Price</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($products as $product) : ?>
        <tr>
            <td><?php echo $product['id']; ?></td>
            <td><img src="../../controllers/uploads/<?php echo $product['prod_image']; ?>" alt="<?php echo $product['prod_image']; ?>">
            </td>
            <td><?php echo $product['prod_name']; ?></td>
            <td><?php echo $product['prod_description']; ?></td>
            <td><?php echo $product['prod_price']; ?></td>
            <td>
                <a href="/products/show_prod?id=<?php echo $product['id']; ?>">Show</a> |
                <!-- <a href="/products/delete?id=<?php echo $product['id']; ?>" 
            onclick="confirm('Are you sure you want to delete this record?')">Delete</a> -->
        </tr>
    <?php endforeach; ?>
</table>