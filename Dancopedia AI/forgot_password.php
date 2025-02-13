<?php
session_start();

// Database connection setup
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

// Handle POST request for forgot password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = $conn->real_escape_string(trim($_POST['email']));
    
    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT user_id, username FROM users WHERE email = ?");
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Generate a unique token for password reset
        $token = bin2hex(random_bytes(50)); // Create a random token
        $expires = date("U") + 3600; // Set expiration time (1 hour)

        // Insert token and expiration time into the database
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $user_email, $token, $expires);
        $stmt->execute();

        // Send password reset email
        $reset_link = "http://yourwebsite.com/reset_password.php?token=" . $token;
        $subject = "Password Reset Request";
        $message = "To reset your password, click the following link: " . $reset_link;
        mail($user_email, $subject, $message);

        $_SESSION['message'] = "Password reset link has been sent to your email.";
        header("Location: forgot_password.php");
        exit();
    } else {
        $_SESSION['message'] = "Email not found.";
        header("Location: forgot_password.php");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Dance USA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Include Navbar -->
<?php include('navbar.php'); ?>

<div class="container">
    <h2 class="my-5 text-center">Forgot Password</h2>

    <!-- Display error message if there is one -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info" role="alert">
            <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <!-- Forgot password form -->
    <form method="POST" action="forgot_password.php">
        <div class="mb-3">
            <label for="email" class="form-label">Enter your email address</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Send Password Reset Link</button>
    </form>

    <div class="mt-3 text-center">
        <a href="login.php" class="btn btn-secondary">Back to Login</a>
    </div>

</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
