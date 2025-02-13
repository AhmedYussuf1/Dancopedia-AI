<?php
// Database connection details
$servername = "localhost";
$username = "root";  // Replace with your DB username
$password = "";  // Replace with your DB password
$dbname = "dance_ai_db";  // Replace with your DB name
$port = 3307;  // Assuming this is your MySQL port for XAMPP

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);


// Check if the connection was successful
if ($conn->connect_error) {
    // If there was an error, display it and exit
    die("Connection failed: " . $conn->connect_error);
} else {
    // If the connection is successful, you are connected to the database
    // You can now run queries
}
?>
