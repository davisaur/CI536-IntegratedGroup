<?php

    include 'db_con.php';

    $error_message = ""; // for storing error messages
    // form check
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $psw = $_POST['psw'];
        $pswconfirm = $_POST['pswconfirm'];
        $addline1 = $_POST['addline1'];
        $addline2 = $_POST['addline2'];
        $city = $_POST['city'];
        $postcode = $_POST['postcode'];
        $phoneNum = $_POST['phoneNum'];

        // check if the phone number is alphanumerial
        if (!ctype_digit($phoneNum)) {
            $error_message = "Phone number should contain only digits.";
        }
        // check if passwords match
        elseif ($psw !== $pswconfirm) {
            $error_message = "Passwords do not match.";
        } else {
            //check if the user already exists in the database
            $stmt = $conn->prepare("SELECT email_address FROM users WHERE email_address = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error_message = "A user with this email address already exists.";
            } else {
                // hash the password 
                $passwordHash = password_hash($psw, PASSWORD_DEFAULT);

                //use prepared statement to avoid sql injection
                $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email_address, password, address_line_1, address_line_2, city, postcode, phone_number)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssss", $fname, $lname, $email, $passwordHash, $addline1, $addline2, $city, $postcode, $phoneNum);

                if ($stmt->execute()) {
                    header("Location: index.php");
                    exit;
                } else {
                    $error_message = "Error: " . $stmt->error;
                }
            }
            $stmt->close();
        }
        
    }
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheets/alertbox.css">
    <link rel="stylesheet" href="stylesheets/register.css">
    <title>Register - Marketplace</title>
</head>
<body>
    <div class="register_wrapper">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="register_container">
                <div class="register_flex">
                    <div class="register_column">
                        <label for="fname">Forename:</label>
                        <input type="text" placeholder="First Name" name="fname" class="field" required>

                        <label for="fname">Surname:</label>
                        <input type="text" placeholder="Last Name" name="lname" class="field" required>

                        <hr class="solid">

                        <label for="email">E-mail Address:</label>
                        <input type="email" placeholder="Enter E-Mail Address" name="email" class="field" required>

                        <label for="psw">Password:</label>
                        <input type="password" placeholder="Enter Password" name="psw" class="field" required>

                        <label for="pswconfirm">Re-enter password:</label>
                        <input type="password" placeholder="Enter Password" name="pswconfirm" class="field" required>
                    </div>
                    <div class="register_column">
                        <label for="addline1">Address Line 1:</label>
                        <input type="text" name="addline1" class="field" required>

                        <label for="addline2">Address Line 2:</label>
                        <input type="text" name="addline2" class="field">

                        <label for="city">City:</label>
                        <input type="text" name="city" class="field" required>

                        <label for="postcode">Postcode:</label>
                        <input type="text" name="postcode" class="field" required>

                        <label for="phoneNum">Phone Number:</label>
                        <input type="tel" name="phoneNum" class="field" required>
                    </div>
                </div>
                <button type="submit">Register</button>
            </div>
        </form>
        <?php if (!empty($error_message)): ?>
                <div class="error-message-box">
                    <img src="images/alert.png" alt="Alert Icon">
                    <div class="error-container">
                        <h4 class="error-heading">There was a problem</h4>
                        <span class="alert-content">
                            <?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </div>
                </div>
            <?php endif; ?>
    </div>  
</body>
</html>