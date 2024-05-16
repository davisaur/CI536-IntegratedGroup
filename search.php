<?php
    include 'db_con.php';
    require 'session.php';

    $html = '';

    if(isset($_REQUEST['search']) || isset($_REQUEST['category']) || isset($_REQUEST['price'])) {
        // Initialize variables for search term, category, and price
        $search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
        $category_id = isset($_REQUEST['category']) ? $_REQUEST['category'] : '';
        $price = isset($_REQUEST['price']) ? $_REQUEST['price'] : '';

        // Generate SQL condition based on search term, category, and price
        $condition = generateCondition($search, $category_id, $price);

        // Build the SQL query
        $sql = "SELECT id, name, price, img_file_type FROM `products` $condition;";
        $result = $conn->query($sql);

        if($result->num_rows > 0) {
            $results = array();
            
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
            debug_to_console("Success, products filtered.");

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
            $html = "<h2>No products found.</h2>";
            debug_to_console("No products found.");
        }
    } else {
        debug_to_console("No filters applied.");
    }

    function generateCondition($search, $category_id, $price) {
        $condition = "WHERE 1=1"; // Initialize condition with a true statement

        // Add search term condition
        if(!empty($search)) {
            $condition .= " AND name LIKE '%$search%'";
        }

        // Add category condition
        if(!empty($category_id)) {
            $condition .= " AND category = $category_id";
        }

        // Add price condition
        if(!empty($price)) {
            switch($price) {
                case '1':
                    $condition .= " AND price < 5";
                    break;
                case '2':
                    $condition .= " AND price BETWEEN 5 AND 10";
                    break;
                case '3':
                    $condition .= " AND price BETWEEN 10 AND 25";
                    break;
                case '4':
                    $condition .= " AND price BETWEEN 25 AND 50";
                    break;
                case '5':
                    $condition .= " AND price BETWEEN 50 AND 100";
                    break;
                case '6':
                    $condition .= " AND price > 100";
                    break;
            }
        }

        return $condition;
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
                <li><a href="search.php?price=1">Under £5</a></li>
                <li><a href="search.php?price=2">£5-£10</a></li>
                <li><a href="search.php?price=3">£10-£25</a></li>
                <li><a href="search.php?price=4">£25-£50</a></li>
                <li><a href="search.php?price=5">£50-£100</a></li>
                <li><a href="search.php?price=6">Over £100</a></li>
            </ul>
        </div>
        <div class="search-container">
            <?php echo $html; ?>
        </div>
    </div>
</body>
</html>