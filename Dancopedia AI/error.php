<?php
session_start(); // Start the session

// Database connection setup
DEFINE('DATABASE_HOST', 'localhost');
DEFINE('DATABASE_PORT', 3307);  // MySQL custom port for XAMPP
DEFINE('DATABASE_DATABASE', 'dance_ai_db');  // Your database name
DEFINE('DATABASE_USER', 'root');  // Default user for XAMPP
DEFINE('DATABASE_PASSWORD', 'ics311');  // Default password is empty for XAMPP

// Create connection
$conn = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE, DATABASE_PORT);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape the input values to prevent SQL Injection
    $user_username = $conn->real_escape_string(trim($_POST['username']));
    $user_password = trim($_POST['password']);

    // Prepare SQL query to get user info
    $stmt = $conn->prepare("SELECT admin_id, username, password_hash FROM admins WHERE username = ?");
    $stmt->bind_param("s", $user_username);  // "s" means it's a string
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($admin_id, $username, $password_hash);

    // Check if user exists and password is correct
    if ($stmt->fetch()) {
        // Password verification using password_verify()
        if (password_verify($user_password, $password_hash)) {
            // Password is correct, set session variables
            $_SESSION['admin_id'] = $admin_id;
            $_SESSION['username'] = $username;

            // Redirect to the home page
            header("Location: http://localhost/Dancopedia%20AI/");  // After successful login, go to home.php
            exit();  // Exit after redirect
        } else {
            // Invalid password
            $_SESSION['message'] = "Invalid password!";
            header("Location: error.php");
            exit();
        }
    } else {
        // No user found with that username
        $_SESSION['message'] = "No user found with that username!";
        header("Location: error.php");
        exit();
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dance USA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2 class="my-5 text-center">Login</h2>

    <!-- Display error message if there is one -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <!-- Login form -->
    <form method="POST" action="login.php">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>

    <!-- Register Button -->
    <div class="mt-3 text-center">
        <a href="register.php" class="btn btn-secondary">Register</a>
    </div>
</div>

</body>
</html>
