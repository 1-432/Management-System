<?php
// Configuration
$servername = "localhost";
$username = "root";       // <-- CHANGE THIS
$password = "your_password"; // <-- CHANGE THIS
$dbname = "tech_management"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Stop execution and show error if connection fails
    die("Connection failed: " . $conn->connect_error);
}
?>