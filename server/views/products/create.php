<h1>Create a new product</h1>

<form action="/products/store" method="POST" enctype="multipart/form-data">
  <label for="name">Name:</label><br>
  <input type="text" id="name" name="name"><br>
  <label for="image">Image:</label><br>
  <input type="file" id="image" name="image"><br>
  <br>
  <label for="price">Price:</label><br>
  <input type="number" id="price" name="price"><br>
  <br>
  <label for="description">Description:</label><br>
  <textarea id="description" name="description"></textarea><br>
  <br>
  <input type="submit" value="Submit">
</form>
