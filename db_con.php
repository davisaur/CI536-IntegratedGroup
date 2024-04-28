<?php
// db details
$db_host = 'db.davisaur.me';
$db_user = 'groupproj';
$db_pass = '*r!%sV$nPZ5@%W%4';
$db_name = 'groupproj';


// creating connection to db
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
