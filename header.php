<div class="topnav">
    <a href="index.php" id="home-button">Home</a>
    <select name="categories" id="categories">
        <option value="" disabled selected hidden>Categories</option>
        <option value="0">Fashion</option>
        <option value="1">Electronics</option>
        <option value="2">Books</option>
        <option value="3">DVDs, CDs &amp; Media</option>
        <option value="4">Home, Garden &amp; DIY</option>
        <option value="5">Pets</option>
    </select>
    <input type="text" placeholder="Search for products..." id="searchbar">
    <a href="/basket" id="basket-button">Basket</a>
    <a href="/account" id="account-button">Your Account</a>
    <?php
    // start the session if it hasn't already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['loggedin'])) {
        // user is not logged in, show Register and Login buttons
        echo '<a href="register.php" id="account-button">Register</a>';
        echo '<a href="login.php" id="account-button">Login</a>';
    } else {
        // user is logged in, show Logout button
        echo '<a href="logout.php" id="account-button">Logout</a>';
    }
    ?>

</div>
<script src="scripts/search.js"></script>