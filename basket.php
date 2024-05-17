<?php 
    include 'db_con.php';
    require 'session.php';

    $html = '';

    if(isset($_SESSION['loggedin'])) {
        $userID = $_SESSION['userid'];
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            if(isset($_POST['id']) && isset($_POST['quantity'])) {
                $sql = "SELECT * FROM `basket` WHERE product_id = {$_POST['id']} AND user_id = $userID";
                $productInBasket = $conn->query($sql);

                if($productInBasket->num_rows > 0) {
                    $sql = "UPDATE basket SET quantity = quantity + {$_POST['quantity']} WHERE product_id = {$_POST['id']} AND user_id = $userID";
                    $updateBasket = $conn->query($sql);
                } else {
                    $stmt = $conn->prepare("INSERT INTO basket (user_id, product_id, quantity) VALUES (?, ?, ?);");
                    $stmt->bind_param("iii", $userID, $_POST['id'], $_POST['quantity']);
                    if(!$stmt->execute()) {
                        echo "Uh oh!";
                    }
                }
            }

            if(isset($_POST['remove'])) {
                $sql = "DELETE FROM `basket` WHERE product_id = {$_POST['remove']} AND user_id = $userID";
                $removeItem = $conn->query($sql);
            }
        }

        $sql = "SELECT * FROM `basket` WHERE user_id='$userID';";
        $basketResults = $conn->query($sql);

        if($basketResults->num_rows > 0) {
            $basket = []; // Initialize an empty basket array

            // Fetch all basket items
            while ($basketItem = $basketResults->fetch_assoc()) {
                $basket[] = $basketItem; // Store each basket item in the basket array
            }

            // Fetch all corresponding products in one query
            $productIds = array_column($basket, 'product_id');
            $productIdsString = implode(',', $productIds);
            $sql = "SELECT * FROM `products` WHERE id IN ($productIdsString)";
            $productResults = $conn->query($sql);

            // Map product IDs to products for easier access
            $products = [];
            while ($product = $productResults->fetch_assoc()) {
                $products[$product['id']] = $product;
            }

            // Output basket items
            foreach ($basket as $basketItem) {
                $productId = $basketItem['product_id'];
                $product = $products[$productId];

                $html .= "<div class=\"item\">
                    <a href=\"product.php?id={$product['id']}\">
                    <img src=\"images/products/{$product['id']}.{$product['img_file_type']}\" alt=\"\" class=\"product-img\">
                    <h4 class=\"product-name\">{$product['name']}</h4>
                    <span role=\"text\" class=\"product-price\">£{$product['price']}</span>
                    </a>
                    <div class=\"basket-item-buttons-container\">
                        <input type=\"number\" name=\"quantity\" min=\"1\" max=\"{$basketItem['quantity']}\" value=\"{$basketItem['quantity']}\" class=\"basket-quantity-selector\">
                        <form action=\"basket.php\" method=\"POST\">
                            <input type=\"hidden\" name=\"remove\" value=\"{$product['id']}\">
                            <input type=\"submit\" value=\"Remove\" class=\"basket-remove-button\">
                        </form>
                    </div>
                </div>";
            }
        }
    } else {
        header('Location: index.php');
        exit();
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basket - Marketplace</title>
    <link rel="stylesheet" href="stylesheets/basket.css">
    <link rel="stylesheet" href="stylesheets/navbar.css">
    <link rel="stylesheet" href="stylesheets/styles.css">
</head>
<body>
    <?php require 'header.php';?>
    <div class="search-container">
        <?php echo $html;?>
    </div>
    <div class="checkout-container">
        <input type="button" value="Checkout!" class="basket-checkout-button">
    </div>
</body>
</html>