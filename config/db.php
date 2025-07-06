<?php
$host = 'localhost';         // or 127.0.0.1
$user = 'root';              // change if not using root
$password = '';              // enter your MySQL password if set
$dbname = 'portfolio_db';    // use your actual database name

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Optional: set charset
$conn->set_charset("utf8");
?>
