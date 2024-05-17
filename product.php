<?php
    include 'db_con.php';
    require 'session.php';

    $html = '';
    $id = NULL;
    $name = '';
    $price = 0.0;

    if(isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];
        debug_to_console("ID Parameter Set.");

        $sql = "SELECT * FROM `products` WHERE id='$id';";
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
            $qtyInStock = $results['quantity'];

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
<?php require 'header.php';?>
<div class="product-body">
    <h1 class="product-page-title"><?php echo $name; ?></h1>
    <div class="product-flex">
        <div class="product-image-container">
            <img src="images/products/<?php echo "$id.$imgFileType";?>" alt="PLACEHOLDER IMAGE" class="product-page-image">
        </div>
        <div class="product-details-container">
            <form action="basket.php" method="POST">
                <span role="text" class="product-page-price">£<?php echo $price; ?></span>
                <input type="submit" value="Add to Basket" class="add-basket-button">
                <input type="hidden" id="id" name="id" value="<?php echo $id;?>">
                <input type="number" name="quantity" min="1" max="<?php echo $qtyInStock; ?>" value="1" class="quantity-selector">
            </form>
            <h3 class="product-description-title">Product Description</h3>
            <p class="product-description-contents"><?php echo $description;?></p>
        </div>
    </div>
</div>
</body>
</html>