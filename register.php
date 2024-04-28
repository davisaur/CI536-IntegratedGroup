<?php
error_reporting(E_ALL); // used for debug, sometimes php doesnt explicitly say error msgs

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

    // check if passwords match
    if ($psw !== $pswconfirm) {
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

            //use prepeared statement to avoid sql injection
            $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email_address, password, address_line_1, address_line2, city, postcode, phone_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssss", $fname, $lname, $email, $passwordHash, $addline1, $addline2, $city, $postcode, $phoneNum);

            if ($stmt->execute()) {
                echo "Registration successful!";
                header("Location: index.html");
                exit;
            } else {
                $error_message = "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }
    echo $error_message;
}
$conn->close();