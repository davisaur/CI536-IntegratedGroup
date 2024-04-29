<?php
    $servername = "db.davisaur.me";
    $username = "groupproj";
    $password = "*r!%sV\$nPZ5@%W%4"; 
    $dbname = "groupproj"; 

    $html = '';

    if(isset($_REQUEST['search'])) {
        $search = $_REQUEST['search'];
        debug_to_console("Search Parameter Set.");
        
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        if ($conn->connect_error) {
            debug_to_console("Connection to SQL server failed.");
            http_response_code(500);
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT id, name, price, img_file_type FROM `products` WHERE name LIKE '%$search%';";
        $result = $conn->query($sql);

        if($result->num_rows > 0) {
            $results = array();
            
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
            debug_to_console("Success, item searched.");

            foreach($results as $item) {
                $html .= "<a href=\"product.php?id={$item['id']}\">
                    <div class=\"item\">
                    <img src=\"images/products/{$item['id']}.{$item['img_file_type']}\" alt=\"\" class=\"product-img\">
                    <h4 class=\"product-name\">{$item['name']}</h4>
                    <span role=\"text\" class=\"product-price\">£{$item['price']}</span>
                    </div>
                </a>";
            }
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
    <div class="search-flex">
        <div class="filters-container">
            <p>Categories</p>
            <ul>
                <li><a href="#">Fashion</a></li>
                <li><a href="#">Electronics</a></li>
                <li><a href="#">Books</a></li>
                <li><a href="#">DVDs, CDs &amp; Media</a></li>
                <li><a href="#">Home, Garden &amp; DIY</a></li>
                <li><a href="#">Pets</a></li>
            </ul>
            <p>Price</p>
            <ul>
                <li><a href="#">Under £5</a></li>
                <li><a href="#">£5-£10</a></li>
                <li><a href="#">£10-£25</a></li>
                <li><a href="#">£25-£50</a></li>
                <li><a href="#">£50-£100</a></li>
                <li><a href="#">Over £100</a></li>
            </ul>
        </div>
        <div class="search-container">
            <?php echo $html; ?>
            <!-- <div class="item">
                <img src="images/placeholder.jpeg" alt="" class="product-img">
                <h4 class="product-name">Product Name, Lorem ipsum dolor sit amet.</h4>
                <span role="text" class="product-price">£9.99</span>
            </div>
            <div class="item">
                <img src="images/placeholder.jpeg" alt="" class="product-img">
                <h4 class="product-name">Product Name, Lorem ipsum dolor sit amet.</h4>
                <span role="text" class="product-price">£9.99</span>
            </div>
            <div class="item">
                <img src="images/placeholder.jpeg" alt="" class="product-img">
                <h4 class="product-name">Product Name, Lorem ipsum dolor sit amet.</h4>
                <span role="text" class="product-price">£9.99</span>
            </div>
            <div class="item">
                <img src="images/placeholder.jpeg" alt="" class="product-img">
                <h4 class="product-name">Product Name, Lorem ipsum dolor sit amet.</h4>
                <span role="text" class="product-price">£9.99</span>
            </div>
            <div class="item">
                <img src="images/placeholder.jpeg" alt="" class="product-img">
                <h4 class="product-name">Product Name, Lorem ipsum dolor sit amet.</h4>
                <span role="text" class="product-price">£9.99</span>
            </div>
            <div class="item">
                <img src="images/placeholder.jpeg" alt="" class="product-img">
                <h4 class="product-name">Product Name, Lorem ipsum dolor sit amet.</h4>
                <span role="text" class="product-price">£9.99</span>
            </div> -->
        </div>
    </div>
</body>
</html>