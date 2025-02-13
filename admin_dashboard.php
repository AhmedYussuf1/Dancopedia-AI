<?php
session_start();
include('db_connection.php');  // Include your database connection

// Ensure that the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");  // Redirect to login page if not logged in or not an admin
    exit();
}

// Fetch posts data
$postsQuery = $conn->query("SELECT * FROM posts");

// Fetch dances data
$dancesQuery = $conn->query("SELECT * FROM dances");

// Fetch users data
$usersQuery = $conn->query("SELECT * FROM users");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Include the navbar -->
    <?php include('navbar.php'); ?>

    <!-- Admin Content -->
    <div class="container mt-5">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?php echo $_SESSION['username']; ?>. You are logged in as an admin.</p>
        
        <!-- Posts Section -->
        <h2>Posts</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $postsQuery->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo substr($row['content'], 0, 50); ?>...</td>
                        <td>
                            <a href="edit_post.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="delete_post.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Dances Section -->
        <h2>Dances</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $dancesQuery->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['dance_id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo substr($row['description'], 0, 50); ?>...</td>
                        <td>
                            <a href="edit_dance.php?id=<?php echo $row['dance_id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="delete_dance.php?id=<?php echo $row['dance_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this dance?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Users Section -->
        <h2>Users</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $usersQuery->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['user_id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['role']; ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $row['user_id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="delete_user.php?id=<?php echo $row['user_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
