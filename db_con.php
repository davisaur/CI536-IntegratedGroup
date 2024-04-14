<?php
// Database configuration
$db_host = 'localhost';
$db_user = 'ps750_CI536';
$db_pass = 'GROUPPROJECT123';
$db_name = 'ps750_group_proj';

// Create database connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>