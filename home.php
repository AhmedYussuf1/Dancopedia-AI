<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    // If not logged in as admin, redirect to login page
    header("Location: login_page.php"); // Redirect to the login page
    exit();
}

// Admin is logged in, show the home page content
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Admin</title>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
    <p>You are logged in as an admin.</p>

    <a href="admin_dashboard.php">Go to Admin Dashboard</a>
    <a href="logout.php">Logout</a>
</body>
</html>
