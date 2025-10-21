<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'business_website');

// Attempt to connect to MySQL database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . $conn->connect_error);
}
?>
