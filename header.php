<div class="topnav">
    <a href="index.php" class="home-button"><img src="images/bazaar_white_logo.png" alt="Bazaar Logo" class="header-logo"></a>
    <select name="categories" class="categories-selector" id="categoryDropdown">
        <option value="" disabled selected hidden>Categories</option>
        <option value="0" data-category="1">Fashion</option>
        <option value="1" data-category="2">Electronics</option>
        <option value="2" data-category="3">Books</option>
        <option value="3" data-category="4">DVDs, CDs &amp; Media</option>
        <option value="4" data-category="5">Home, Garden &amp; DIY</option>
        <option value="5" data-category="6">Pets</option>
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