<?php
// this is the configuration when we are trying it on localhost
DEFINE('DATABASE_HOST', 'localhost');
DEFINE('DATABASE_PORT', 3307);  // Custom port for XAMPP MySQL
DEFINE('DATABASE_DATABASE', 'dance_ai_db');
DEFINE('DATABASE_USER', 'root');
DEFINE('DATABASE_PASSWORD', 'ics311');

// Create connection
$conn = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE, DATABASE_PORT);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect form data from the registration form
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$role = 'user';  // Default role for all new users, you can change this if needed

// Hash the password for security
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if email already exists
$query = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // If email already exists
    echo "Email is already registered!";
} else {
    // Prepare and bind SQL query to insert user data
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);  // Add the role field

    // Execute the query
    if ($stmt->execute()) {
        // Registration successful
        echo "Registration successful!";
        // Redirect to login page after registration (optional)
        header("Location: login_register.html");
        exit();
    } else {
        // Error occurred while inserting into the database
        echo "Error: " . $stmt->error;
    }
}

// Close the connection
$stmt->close();
$conn->close();
?>
