<?php
include 'db_con.php';

$html_options = '';

if(isset($_POST["submit"])) {
  $target_dir = "images/products/";
  $target_file = $target_dir . basename($_FILES["imageToUpload"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  // Check if image file is a actual image or fake image
  $imgCheck = getimagesize($_FILES["imageToUpload"]["tmp_name"]);
  if($imgCheck !== false) {
    echo "File is an image - " . $imgCheck["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }

  // Check if file already exists
  if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
  }

  // Check file size
  if ($_FILES["imageToUpload"]["size"] > 5000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
  }

  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
  }

  // Check if properties are set and in bounds
  if(!isset($_POST["title"]) || strlen($_POST["title"]) < 1) { 
    echo "No product title set.";
    $uploadOk = 0;
  }

  if(!isset($_POST["category"]) || strlen($_POST["category"]) < 1) { 
    echo "No category set.";
    $uploadOk = 0;
  }

  if(!isset($_POST["description"]) || strlen($_POST["description"]) < 1) { 
    echo "No description set.";
    $uploadOk = 0;
  }

  if(!isset($_POST["stock"]) || $_POST["stock"] < 0 || $_POST["stock"] > 9999) { 
    echo "No stock quantity set or quantity is too low/high.";
    $uploadOk = 0;
  }

  if(!isset($_POST["price"]) || $_POST["price"] < 0 || $_POST["stock"] > 999999) { 
    echo "No price set or price is too low/high.";
    $uploadOk = 0;
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    echo "Sorry, your item was not added to the database.";
  // if everything is ok, try to upload file
  } else {
    // echo (gettype($_POST["title"]) . gettype($_POST["stock"]) . gettype($_POST["price"]) . gettype($_POST["description"]));
    // $sql = "INSERT INTO `products` (name, quantity, price, description) VALUES ({$_POST["title"]}, {$_POST["stock"]}, {$_POST["price"]}, {$_POST["description"]})";
    $stmt = $conn->prepare("INSERT INTO products (name, category, quantity, price, description, img_file_type) VALUES (?, ?, ?, ?, ?, ?);");
    $stmt->bind_param("siidss", trim($_POST["title"]), $_POST["category"], $_POST["stock"], $_POST["price"], trim($_POST["description"]), $imageFileType);
    if($stmt->execute()) {
      if (rename($_FILES["imageToUpload"]["tmp_name"], ($target_dir . "{$stmt->insert_id}." . $imageFileType))) {
        echo "The file ". htmlspecialchars( basename( $_FILES["imageToUpload"]["name"])). " has been uploaded.";
      } else {
        echo "Sorry, there was an error uploading your file.";
      }
    }
  }
}

$query = $conn->query("SELECT * FROM category;");

if($query->num_rows > 0) {
  $results = array();
  while($row = $query->fetch_assoc()) {
    $results[] = $row;
  }

  foreach($results as $category) {
    $html_options .= "<option value=\"{$category["id"]}\">{$category["category"]}</option>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item - Group Project</title>
</head>
<body>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="imageToUpload">Image: </label>
        <input type="file" name="imageToUpload" id="imageToUpload"><br>
        <label for="title">Product Title: </label>
        <input type="text" name="title" id="title"><br>
        <label for="category">Category: </label>
        <select name="category" id="category">
          <?php echo $html_options;?>
        </select>
        <br>
        <label for="description">Product Description: </label>
        <input type="text" name="description" id="description"><br>
        <label for="stock">Items in Stock: </label>
        <input type="number" name="stock" id="stock" min="0" max="9999"><br>
        <label for="price">Price: </label>
        <input type="number" name="price" id="price" min="0.00" max="999999" step=".01"><br><br>
        <input type="submit" value="Submit" name="submit">
    </form>
</body>
</html>