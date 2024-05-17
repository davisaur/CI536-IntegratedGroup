<div class="topnav">
    <a href="index.php" class="home-button"><img src="images/bazaar_white_logo.png" alt="Bazaar Logo" class="header-logo"></a>
    <select name="categories" class="categories-selector" id="categoryDropdown">
        <option value="" disabled selected hidden>Categories</option>
        <?php 
            include 'db_con.php';
            $query = $conn->query("SELECT * FROM category;");
            $filter_categories = "";
            if($query->num_rows > 0) {
                while($row = $query->fetch_assoc()) {
                    $categoryId = $row["id"];
                    $categoryName = $row["category"];
                    $filter_categories .= "<option value=\"$categoryId\" data-category=\"$categoryId\">{$categoryName}</option>";
                }
            }
            echo $filter_categories;
        ?>
    </select>
    <input type="text" placeholder="Search for products..." id="searchbar">
    <?php

    if (!isset($_SESSION['loggedin'])) {
        // user is not logged in, show Register and Login buttons
        echo '<a href="register.php" id="account-button">Register</a>';
        echo '<a href="login.php" id="account-button">Login</a>';
    } else {
        // user is logged in, show Logout button
        if($_SESSION["user_type"] === "admin") {
            echo '<a href="upload.php" class="sell-button nav-button">Sell Product</a>';
        } 
        echo '<a href="basket.php" class="basket-button nav-button">Basket</a>';
        echo '<a href="orders.php" class="account-button nav-button">Your Orders</a>';
        echo '<a href="logout.php" id="account-button">Logout</a>';
    }
    ?>

</div>
<script src="scripts/search.js"></script>