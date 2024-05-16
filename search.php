<?php
    include 'db_con.php';
    require 'session.php';

    $html = '';

    if(isset($_REQUEST['search']) && isset($_REQUEST['category'])) {
        // both search query and category filter applied
        $search = $_REQUEST['search'];
        $category_id = $_REQUEST['category'];
        debug_to_console("Search Parameter and Category Filter Applied.");

        $sql = "SELECT id, name, price, img_file_type FROM `products` WHERE name LIKE '%$search%' AND category = $category_id;";
        $result = $conn->query($sql);

        if($result->num_rows > 0) {
            $results = array();
            
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
            debug_to_console("Success, products filtered by both search query and category.");

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
            $html = "<h2>No products found matching the search query in this category.</h2>";
            debug_to_console("No products found matching the search query in the selected category.");
        }

    } else if(isset($_REQUEST['search'])) {
        $search = $_REQUEST['search'];
        debug_to_console("Search Parameter Set.");

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
    } else if(isset($_REQUEST['category'])) {
        // Only category filter applied
        $category_id = $_REQUEST['category'];
        debug_to_console("Category Filter Applied: " . $category_id);
    
        $sql = "SELECT id, name, price, img_file_type FROM `products` WHERE category = $category_id;";
        $result = $conn->query($sql);
    
        if($result->num_rows > 0) {
            $results = array();
        
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
            debug_to_console("Success, products filtered by category.");

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
            $html = "<h2>No products found in this category.</h2>";
            debug_to_console("No products found in the selected category.");
        }
    } else {
        debug_to_console("No search parameter or category filter detected.");
    }

    function get_filter_categories() {
        include 'db_con.php';
        $searchParam = isset($_GET['search']) ? urlencode($_GET['search']) : '';
        $categoryParam = isset($_GET['category']) ? $_GET['category'] : '';
        $query = $conn->query("SELECT * FROM category;");
        $filter_categories = "";
        if($query->num_rows > 0) {
            while($row = $query->fetch_assoc()) {
                $categoryId = $row["id"];
                $categoryName = $row["category"];
                $activeClass = ($categoryId == $categoryParam) ? 'class="active"' : '';
                $filter_categories .= "<li><a href=\"search.php?category={$categoryId}&search={$searchParam}\" {$activeClass}>{$categoryName}</a></li>";
            }
        }
        return $filter_categories;
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
    <?php require 'header.php';?>
    <div class="search-flex">
        <div class="filters-container">
            <p>Categories</p>
            <ul>
                <?php echo get_filter_categories();?>
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
        </div>
    </div>
</body>
</html>