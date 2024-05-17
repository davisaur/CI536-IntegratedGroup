<?php
  include 'db_con.php';
  require 'session.php';

  $html_options = '';
  $error_message = "";


  if(isset($_POST["submit"])) {
    $target_dir = "images/products/";
    $target_file = $target_dir . basename($_FILES["imageToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $imgCheck = getimagesize($_FILES["imageToUpload"]["tmp_name"]);
    if($imgCheck !== false) {
      $uploadOk = 1;
    } else {
      $error_message = "File is not an image.";
      $uploadOk = 0;
    }

    if(!isset($_SESSION['loggedin'])) {
      $error_message = "You're not logged in!";
      $uploadOk = 0;
    }

    if(isset($_SESSION['user_type']) && !($_SESSION['user_type'] === "admin")) {
      $error_message = "You don't have permission to sell products.";
      $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
      $error_message = "Sorry, this file already exists.";
      $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["imageToUpload"]["size"] > 5000000) {
      $error_message = "Sorry, your file is too large!";
      $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
      $error_message = "Sorry, only JPG, JPEG, & PNG files are allowed.";
      $uploadOk = 0;
    }

    // Check if properties are set and in bounds
    if(!isset($_POST["title"]) || strlen($_POST["title"]) < 1) { 
      $error_message = "No product title set.";
      $uploadOk = 0;
    }

    if(!isset($_POST["category"]) || strlen($_POST["category"]) < 1) { 
      $error_message = "No category set.";
      $uploadOk = 0;
    }

    if(!isset($_POST["description"]) || strlen($_POST["description"]) < 1) { 
      $error_message = "No description set.";
      $uploadOk = 0;
    }

    if(!isset($_POST["stock"]) || $_POST["stock"] < 0 || $_POST["stock"] > 9999) { 
      $error_message = "No stock quantity set or quantity is too low/high.";
      $uploadOk = 0;
    }

    if(!isset($_POST["price"]) || $_POST["price"] < 0 || $_POST["stock"] > 999999) { 
      $error_message = "No price set or price is too low/high.";
      $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk === 0) {
    // if everything is ok, try to upload file
    } else {
      // echo (gettype($_POST["title"]) . gettype($_POST["stock"]) . gettype($_POST["price"]) . gettype($_POST["description"]));
      // $sql = "INSERT INTO `products` (name, quantity, price, description) VALUES ({$_POST["title"]}, {$_POST["stock"]}, {$_POST["price"]}, {$_POST["description"]})";
      $stmt = $conn->prepare("INSERT INTO products (name, category, quantity, price, description, img_file_type) VALUES (?, ?, ?, ?, ?, ?);");
      $stmt->bind_param("siidss", trim($_POST["title"]), $_POST["category"], $_POST["stock"], $_POST["price"], trim($_POST["description"]), $imageFileType);
      if($stmt->execute()) {
        if (rename($_FILES["imageToUpload"]["tmp_name"], ($target_dir . "{$stmt->insert_id}." . $imageFileType))) {
          chmod(($target_dir . "{$stmt->insert_id}." . $imageFileType), 0644);
          echo "The file ". htmlspecialchars( basename( $_FILES["imageToUpload"]["name"])). " has been uploaded.";
        } else {
          $error_message = "Something went wrong whilst trying to add your product. Please try again.";

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
    <link rel="stylesheet" href="stylesheets/alertbox.css">
    <link rel="stylesheet" href="stylesheets/navbar.css">
    <link rel="stylesheet" href="stylesheets/upload.css">
    <link rel="stylesheet" href="stylesheets/styles.css">
    <title>Sell Product - Marketplace</title>
</head>
<body>
  <?php require 'header.php';?>
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
      <textarea name="description" id="description" rows="4" cols="50"></textarea><br>
      <label for="stock">Items in Stock: </label>
      <input type="number" name="stock" id="stock" min="0" max="9999"><br>
      <label for="price">Price: </label>
      <input type="number" name="price" id="price" min="0.00" max="999999" step=".01"><br><br>
      <input type="submit" value="Submit" name="submit">
      
      <?php if (!empty($error_message)): ?>
        <div class="error-message-box">
            <img src="images/alert.png" alt="Alert Icon" class="error-img">
            <div class="error-container">
                <h4 class="error-heading">There was a problem</h4>
                <span class="error-text">
                    <?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?>
                </span>
            </div>
        </div>
    <?php endif; ?>
  </form>

  
</body>
</html>