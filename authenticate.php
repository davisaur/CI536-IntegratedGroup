<?php
error_reporting(E_ALL);

include 'db_con.php';
session_start();
if (isset($_POST['usr'], $_POST['password'])) {
    $username = $_POST['usr'];
    $password = $_POST['password'];

    $stmt = $conn->prepare('SELECT * FROM users WHERE email_address = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0 ) {
        $row = $result->fetch_assoc();
        if($password === $row['password']){

            //set session to logged in *crucial* do not remove
            $_SESSION['loggedin'] = true;

            header("Location: index.html");
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "User not found.";
    }
} else {
    echo "Username and password are required.";
}

// use for hashing after registration implemented (password_verify($password, $row['password'])) 
?> 