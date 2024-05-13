<?php
    include 'db_con.php';
    require 'session.php';

    $html = '';

    if(isset($_REQUEST['search'])) {
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
    } else {
        debug_to_console("No search parameter detected.");

    }

    function get_filter_categories() {
        include 'db_con.php';
        $query = $conn->query("SELECT * FROM category;");
        $filter_categories = "";
        if($query->num_rows > 0) {
          $results = array();
          while($row = $query->fetch_assoc()) {
            $results[] = $row;
          }
        
          foreach($results as $category) {
            $filter_categories .= "<li><a href=\"search.php?categories={$category["id"]}\">{$category["category"]}</a></li>";
          }

          return $filter_categories;
        }
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