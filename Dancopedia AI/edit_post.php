<?php
session_start();
include('db_connection.php');  // Include your database connection

// Ensure that the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");  // Redirect to login page if not logged in or not an admin
    exit();
}

// Check if an ID is passed and fetch the post
if (isset($_GET['id'])) {
    $post_id = $_GET['id'];
    $postQuery = $conn->query("SELECT * FROM posts WHERE id = $post_id");

    // Ensure that the post exists
    if ($postQuery->num_rows == 0) {
        echo "Post not found.";
        exit();
    }

    $post = $postQuery->fetch_assoc();
} else {
    echo "No post ID provided.";
    exit();
}

// Handle the form submission for updating the post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Debugging: Check if form data is being passed correctly
    var_dump($_POST);

    $title = $_POST['title'];
    $content = $_POST['content'];
    $post_id = $_POST['post_id'];  // Get the post_id from the hidden field

    // Debugging: Check if the post_id is passed correctly
    echo "Post ID: " . $post_id . "<br>";
    echo "Title: " . $title . "<br>";
    echo "Content: " . $content . "<br>";

    // Debugging: Print the query before executing
    echo "UPDATE posts SET title = '$title', content = '$content' WHERE id = $post_id<br>";

    $updateQuery = "UPDATE posts SET title = ?, content = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssi", $title, $content, $post_id);

    // Debugging: Check if the query executes successfully
    if ($stmt->execute()) {
        echo "Post updated successfully.";
        header("Location: admin_dashboard.php?success=Post updated successfully.");
        exit();
    } else {
        // Log the error
        echo "Error updating post: " . $stmt->error;
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Include the navbar -->
    <?php include('navbar.php'); ?>

    <!-- Admin Content -->
    <div class="container mt-5">
        <h1>Edit Post</h1>

        <!-- Edit Form -->
        <form method="POST" action="">
            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">  <!-- Hidden Post ID -->
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content" rows="5" required><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
