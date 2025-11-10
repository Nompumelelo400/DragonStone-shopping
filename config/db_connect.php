<?php
// File: config/db_connect.php
// Purpose: To create a coectio to the MySQL database
// Author: Nompumelelo 
// Date: 2024-06-15

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "dragonstone_db";

// Create connection using mysqli
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    // echo "Database connected successfully!";
}
?>
