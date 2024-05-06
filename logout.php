<?php
session_start();
session_destroy();
// redirect to the login page:
header('Location: index.php');
