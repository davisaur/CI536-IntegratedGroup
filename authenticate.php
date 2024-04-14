<?php
// start session
session_start();
include_once 'db_con.php';
if (isset($_POST['username'], $_POST['password'])) {
    // Retrieve username and password from form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL statement to fetch user from database
    $stmt = $conn->prepare('SELECT username, password FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists, verify password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Password is correct, log in the user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: profile.php');
            exit;
        } else {
            // Incorrect password
            echo 'Incorrect password!';
        }
    } else {
        // User does not exist
        echo 'User does not exist!';
    }
}



//use to hide database details from public view
// // $DatabaseAccess = parse_ini_file('./access.ini');

// $SERVER_NAME = $DatabaseAccess['servername'];
// $DB_USER = $DatabaseAccess['username'];
// $DB_PASS = $DatabaseAccess['password'];
// $DB_NAME = $DatabaseAccess['dbname'];

// $con = mysqli_connect($SERVER_NAME, $DB_USER, $DB_PASS, $DB_NAME);
// if (mysqli_connect_errno()) {
//     exit('Failed to connect to MySQL: ' . mysqli_connect_error());
// }

// // Now we check if the data from the login form was submitted, isset() will check if the data exists.
// if (!isset($_POST['username'], $_POST['password'])) {
//     // Could not get the data that should have been sent.
//     exit('Please fill both the username and password fields!');
// }

// $sql = "SELECT * FROM accounts";
// $result  = $con-> query($sql);
// while($row = $result->fetch_assoc()){
//     print_r($row);
// }
// $con->close();

// // Prepare our SQL, preparing the SQL statement will prevent SQL injection.
// if ($stmt = $con->prepare('SELECT email_address ,  password FROM users WHERE email = ?')) {
//     // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
//     $stmt->bind_param('s', $_POST['email']);
//     $stmt->execute();
//     // Store the result so we can check if the account exists in the database.
//     $stmt->store_result();
//     if ($stmt->num_rows > 0) {
//         $stmt->bind_result($id, $password);
//         $stmt->fetch();
//         // Account exists, now we verify the password.
//         // Note: remember to use password_hash in your registration file to store the hashed passwords.
//         if (password_verify($_POST['password'], $password)) {
//             // Verification success! User has logged-in!
//             // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
//             session_regenerate_id();
//             $_SESSION['loggedin'] = TRUE;
//             $_SESSION['name'] = $_POST['email'];
//             $_SESSION['id'] = $id;
//             echo 'Welcome back, ' . htmlspecialchars($_SESSION['name'], ENT_QUOTES) . '!';
//         } else {
//             // Incorrect password
//             echo 'Incorrect username and/or password!';
//         }
//     } else {
//         // Incorrect username
//         echo 'Incorrect username and/or password!';
//     }
//     $stmt->close();
// } -->
?>