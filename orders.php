<?php
include 'db_con.php';
require 'session.php';
if (isset($_SESSION['loggedin'])) {
    $user_id = $_SESSION['userid'];

    $orders_query = "SELECT 
    orders.product_id, 
    orders.quantity,
    products.name,
    products.img_file_type,
    products.price,
    DATE_FORMAT(orders.date_ordered, '%d %M %Y') AS date_ordered
        FROM orders 
        JOIN products ON orders.product_id = products.id 
        WHERE orders.user_id = ?
        ORDER BY orders.date_ordered DESC; ";

    $stmt = $conn->prepare($orders_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    header("Location: index.php");
    exit();
}
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
    <?php require 'header.php'; ?>

    <div class="orders-wrapper">
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $product_id = htmlspecialchars($row['product_id']);
                $product_name = htmlspecialchars($row['name']);
                $product_price = htmlspecialchars($row['price']);
                $quantity_ordered = htmlspecialchars($row['quantity']);
                $date_ordered = htmlspecialchars($row['date_ordered']);
                $img_file_type = htmlspecialchars($row['img_file_type']);
                ?>
                <div class="order-container">
                <a href="product.php?id=<?php echo $product_id?>">    
                    <div class="order-img">
                        <img src="images/products/<?php echo $product_id . "." . $img_file_type ?>" alt="" class="product-img">
                    </div>
                </a>
                    <div class="order-content">
                        <a href="product.php?id=<?php echo $product_id?>">
                            <h4 class="product-name"><?php echo $product_name; ?></h4>
                            <span role="text" class="product-price">£ <?php echo $product_price; ?></span>
                        </a>
                        <span role="text" class="quantity-ordered">Quantity ordered: <?php echo $quantity_ordered; ?></span>
                        <span role="text" class="date-ordered">Date ordered: <?php echo $date_ordered; ?></span>

                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No orders found.</p>";
        }
        ?>
    </div>
</body>

</html>