<?php
// Database configuration
$host = "localhost";
$user = "root"; // Change to your database username
$password = ""; // Change to your database password
$database = "users_db"; // Change to your database name

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>