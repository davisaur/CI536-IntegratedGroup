<?php
    include 'db_con.php';
    require 'session.php';

    if(isset($_SESSION['loggedin'])) {
        $userID = $_SESSION['userid'];
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $sql = "SELECT b.product_id, b.quantity, b.user_id, p.quantity FROM basket b JOIN products p ON b.product_id = p.id WHERE b.quantity > p.quantity AND b.user_id = $userID;";
            // if(!$qtyCheck = $conn->query($sql)) {
            //     printf("Error message: %s\n", $qtyCheck->error);
            // }

            if($qtyCheck = $conn->query($sql)) {
                if(!$qtyCheck->num_rows > 0) {
                    $sql = "UPDATE products p JOIN basket b ON p.id = b.product_id SET p.quantity = p.quantity - b.quantity";
                    $decreaseStock = $conn->query($sql);

                    $sql = "INSERT INTO orders (user_id, product_id, quantity, date_ordered) SELECT user_id, product_id, quantity, NOW() FROM basket WHERE user_id = $userID";
                    $moveToOrders = $conn->query($sql);

                    $sql = "DELETE FROM basket WHERE user_id = $userID";
                    $moveToOrders = $conn->query($sql);
                    header("Location: orders.php");
                    exit();
                }
            } else {
                echo $qtyCheck->$error;
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=	, initial-scale=1.0">
    <title>Checkout - Marketplace</title>
</head>
<body>
    
</body>
</html>