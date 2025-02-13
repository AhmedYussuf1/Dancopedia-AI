<?php
include('db_connection.php');

// Check if ID is provided and valid
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?status=deleted");  // Redirect to dashboard after deletion
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
