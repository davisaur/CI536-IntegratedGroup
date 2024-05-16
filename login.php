<?php
session_start();

include 'db_con.php';

$error_message = "";

if (isset($_POST['usr'], $_POST['password'])) {
    $username = $_POST['usr'];
    $password = $_POST['password'];

    // the s is for datatype string  & ? are for sql injection prevention
    $stmt = $conn->prepare('SELECT * FROM users WHERE email_address = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // checks if email address/username exists first before checking password

    if ($result->num_rows > 0 ) {
        $row = $result->fetch_assoc();
        //password_verify is used to hash
        if(password_verify($password, $row['password'])){

            //set session to logged in 
            $_SESSION['loggedin'] = true;

            header("Location: index.php");
            exit();
        } else {
            $error_message = "Invalid username/password.";
        }
    } else {
        $error_message =  "Invalid username/password.";
    }
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheets/login.css">
    <title>Login</title>
</head>
<body>
    <div class="login_wrapper">
        <div class="login_container">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="username">Username:</label>
                <input type="text" id="username" name="usr" required>
                <br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <br>
                <input type="submit" value="Submit">
            </form>

            <?php if (!empty($error_message)): ?>
                <div class="error-message-box">
                    <img src="images/alert.png" alt="Alert Icon">
                    <div class="error-container">
                        <span class="alert-content">
                            <?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

