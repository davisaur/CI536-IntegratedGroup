<?php
    include 'db_con.php';
    require 'session.php';

    $categories = $conn->query("SELECT * FROM category");
    $html = '';
    if($categories->num_rows > 0) {
        while($category = $categories->fetch_assoc()) {
            $products = $conn->query("SELECT * FROM products WHERE category = {$category['id']}");
            if($products->num_rows > 0) {
                $html .= "<div class=\"wrapper\">
                    <h2>Top in {$category['category']}</h2>
                    <div class=\"container\">";
                while($item = $products->fetch_assoc()) {
                    $html .= "<a href=\"product.php?id={$item['id']}\">
                        <div class=\"item\">
                        <img src=\"images/products/{$item['id']}.{$item['img_file_type']}\" class=\"product-img\">
                        <h4 class=\"product-name\">{$item['name']}</h4>
                        <span role=\"text\" class=\"product-price\">£{$item['price']}</span>
                        </div>
                    </a>";
                }
                $html .= "</div>
                </div>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheets/navbar.css">
    <link rel="stylesheet" href="stylesheets/styles.css">
    <title>Homepage - Marketplace</title>
</head>
<body>
    <?php require 'header.php';?>
    <div class="home">
        <?php echo $html; ?>
    </div>
</body>
</html>