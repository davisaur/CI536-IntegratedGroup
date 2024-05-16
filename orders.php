<?php
    include 'db_con.php';
    require 'session.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheets/navbar.css">
    <link rel="stylesheet" href="stylesheets/orders.css">
    <link rel="stylesheet" href="stylesheets/styles.css">
    <title>Your Orders - Marketplace</title>
</head>
<body>
    <?php require 'header.php';?>
    <div class="orders-wrapper">
        <div class="order-container">
            <div class="order-img">
                <img src="images/placeholder.jpeg" alt="" class="product-img">
            </div>
            <div class="order-content">
                <h4 class="product-name">NAME</h4>
                <span role="text" class="product-price">£PRICE</span>
                <span role="text" class="quantity-ordered">Quantity ordered: QUANTITY ORDERED</span>
            </div>
        </div>
    </div>
</body>
</html>