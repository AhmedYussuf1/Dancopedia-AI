<?php
session_start(); // Start the session to track user login status

// Database connection variables
$servername = "localhost";  // MySQL server (usually localhost)
$username = "root";         // MySQL username (default root for XAMPP)
$password = "ics311";       // MySQL password (you've set this as "ics311")
$dbname = "dance_ai_db";    // Your actual database name
$port = 3307;               // Assuming this is your MySQL port for XAMPP, usually 3306 but you mentioned 3307

// Create the connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is an admin
function is_admin($user_id, $conn) {
    $stmt = $conn->prepare("SELECT role FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    return $user['role'] === 'admin'; // Assuming 'role' column stores user roles like 'admin'
}

// Handle blog creation (insert new blog post)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_blog'])) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $image = null;

        // Handle image upload (optional)
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image = $_FILES['image']['name'];
            $target = 'uploads/' . basename($image);
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                echo "Error uploading the image.";
            }
        }

        // Insert the new blog post into the database
        $stmt = $conn->prepare("INSERT INTO posts (user_id, title, content, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $title, $content, $image);

        if ($stmt->execute()) {
            echo "Blog post created successfully!";
            header("Location: blog.php"); // Redirect to the blog page to show the new post
            exit;
        } else {
            echo "Error creating the blog post: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "You must be logged in to create a blog post.";
    }
}

// Handle post editing (update logic)
if (isset($_GET['edit']) && isset($_SESSION['user_id'])) {
    $post_id = $_GET['edit'];

    // Fetch the post details to display in the edit form
    $stmt = $conn->prepare("SELECT title, content, image, user_id FROM posts WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
        $title = $post['title'];
        $content = $post['content'];
        $image = $post['image'];
        $post_owner_id = $post['user_id'];
    } else {
        echo "Post not found.";
        exit;
    }

    // Check if the logged-in user is the owner or an admin
    if ($_SESSION['user_id'] != $post_owner_id && !is_admin($_SESSION['user_id'], $conn)) {
        echo "You do not have permission to edit this post.";
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_blog'])) {
        $new_title = $_POST['title'];
        $new_content = $_POST['content'];

        // Handle image upload for update (optional)
        $new_image = $image; // Keep existing image unless a new one is uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $new_image = $_FILES['image']['name'];
            $target = 'uploads/' . basename($new_image);
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                echo "Error uploading the image.";
            }
        }

        // Update the post in the database
        $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, image = ? WHERE id = ?");
        $stmt->bind_param("sssi", $new_title, $new_content, $new_image, $post_id);

        if ($stmt->execute()) {
            echo "Blog post updated successfully!";
            header("Location: blog.php"); // Redirect to the blog page to avoid re-submitting the form on refresh
            exit;
        } else {
            echo "Error updating the post: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Handle post deletion
if (isset($_GET['delete']) && isset($_SESSION['user_id'])) {
    $post_id = $_GET['delete'];

    // Fetch the post details to verify user ownership or admin status
    $stmt = $conn->prepare("SELECT user_id FROM posts WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
        $post_owner_id = $post['user_id'];
        
        // Check if the user is the post owner or admin
        if ($_SESSION['user_id'] != $post_owner_id && !is_admin($_SESSION['user_id'], $conn)) {
            echo "You do not have permission to delete this post.";
            exit;
        }

        // Delete the post
        $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $post_id);

        if ($stmt->execute()) {
            echo "Blog post deleted successfully!";
            header("Location: blog.php"); // Redirect to blog page after deletion
            exit;
        } else {
            echo "Error deleting the post: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Post not found.";
        exit;
    }
}

// Query to fetch all blog posts to display on the page
$sql = "SELECT posts.id, posts.title, posts.content, posts.image, users.username, posts.user_id FROM posts JOIN users ON posts.user_id = users.user_id ORDER BY posts.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .post {
            background: #fff;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .post-title {
            font-size: 22px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .post-content {
            margin-bottom: 10px;
        }
        .post-image {
            max-width: 100%; /* Make sure image is responsive */
            height: auto;
            margin-top: 15px;
            border-radius: 8px;
        }
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
        }
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<?php include('navbar.php'); ?>

<!-- Blog Content -->
<div class="container-sm">
    <h1 class="text-center">Blog</h1>

    <!-- Create Blog Section (only for logged-in users) -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <button onclick="document.getElementById('createBlogModal').style.display='block'" class="btn btn-primary mb-4">Create New Blog</button>
    <?php else: ?>
        <p>You must be logged in to create a blog post.</p>
    <?php endif; ?>

    <!-- Display All Blog Posts -->
    <?php while ($post = $result->fetch_assoc()): ?>
        <div class="post">
            <div class="post-title"><?= htmlspecialchars($post['title']) ?></div>
            <div class="post-content"><?= nl2br(htmlspecialchars($post['content'])) ?></div>
            <?php if ($post['image']): ?>
                <img src="uploads/<?= htmlspecialchars($post['image']) ?>" alt="Post Image" class="post-image">
            <?php endif; ?>
            <p class="text-muted">Posted by <?= htmlspecialchars($post['username']) ?></p>

            <!-- Edit/Delete Links (if user is the post owner or admin) -->
            <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $post['user_id'] || is_admin($_SESSION['user_id'], $conn))): ?>
                <a href="blog.php?edit=<?= $post['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="blog.php?delete=<?= $post['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</div>

<!-- Create Blog Modal -->
<div id="createBlogModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('createBlogModal').style.display='none'">&times;</span>
        <h2>Create New Blog Post</h2>
        <form action="blog.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="image">Image (Optional)</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>
            <button type="submit" name="create_blog" class="btn btn-primary mt-3">Create Post</button>
        </form>
    </div>
</div>

<!-- Bootstrap JS (for modal functionality) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // JavaScript to handle modal display
    window.onclick = function(event) {
        var modal = document.getElementById('createBlogModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

</body>
</html>
