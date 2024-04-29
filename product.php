<?php
    $servername = "db.davisaur.me";
    $username = "groupproj";
    $password = "*r!%sV\$nPZ5@%W%4"; 
    $dbname = "groupproj"; 

    $html = '';
    $id = NULL;
    $name = '';
    $price = 0.0;

    if(isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];
        debug_to_console("ID Parameter Set.");
        
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        if ($conn->connect_error) {
            debug_to_console("Connection to SQL server failed.");
            http_response_code(500);
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT id, name, price, description, img_file_type FROM `products` WHERE id='$id';";
        $result = $conn->query($sql);

        if($result->num_rows > 0) {
            $results = array();
            
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                $results = $row;
            }
            debug_to_console("Success, item searched.");

            $name = $results['name'];
            $price = $results['price'];
            $description = $results['description'];
            $imgFileType = $results['img_file_type'];

        } else {
            $html = "<h2>No result found :(</h2>";
            debug_to_console("No result.");
        }
    } else {
        debug_to_console("No search parameter detected.");

    }

    function debug_to_console($data) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);
    
        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="stylesheets/navbar.css">
    <link rel="stylesheet" href="stylesheets/styles.css">
    <script src="scripts/search.js"></script>
</head>
<body>
    <div class="topnav">
        <a href="/" id="home-button">Home</a>
        <select name="categories" id="categories">
            <option value="" disabled selected hidden>Categories</option>
            <option value="0">Fashion</option>
            <option value="1">Electronics</option>
            <option value="2">Books</option>
            <option value="3">DVDs, CDs &amp; Media</option>
            <option value="4">Home, Garden &amp; DIY</option>
            <option value="5">Pets</option>
        </select>
        <input type="text" placeholder="Search for products..." id="searchbar">
        <a href="/basket" id="basket-button">Basket</a>
        <a href="/account" id="account-button">Your Account</a>
    </div>
<div class="product-body">
    <h1 class="product-page-title"><?php echo $name; ?></h1>
    <div class="product-flex">
        <div class="product-image-container">
            <img src="images/products/<?php echo "$id.$imgFileType";?>" alt="PLACEHOLDER IMAGE" class="product-page-image">
        </div>
        <div class="product-details-container">
            <h3 class="product-description-title">Product Description</h3>
            <p class="product-description-contents"><?php echo $description;?></p>
            <span role="text" class="product-page-price">£<?php echo $price; ?></span>
            <input type="button" value="Add to Basket" class="add-basket-button">
            <input type="number" name="Quantity" min="1" max="100" value="1" class="quantity-selector">
        </div>
    </div>
</div>
</body>
</html>