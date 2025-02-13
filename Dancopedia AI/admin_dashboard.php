<?php  
session_start();
include('db_connection.php');  // Include your database connection

// Ensure that the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");  // Redirect to login page if not logged in or not an admin
    exit();
}

// Handle new dance submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_dance"])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $genre = $_POST['genre'];  // New field for genre
    $region = $_POST['region'];  // New field for region
    $image_url = $_POST['image_url'];  // Optional: You can add image URL if needed
    $video_url = $_POST['video_url'];  // Optional: You can add video URL if needed
    $admin_id = $_SESSION['user_id'];  // Assuming the admin's user ID is stored in session
    
    // Insert the dance details into the database
    $stmt = $conn->prepare("INSERT INTO dances (name, description, genre, region, image_url, video_url, admin_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $name, $description, $genre, $region, $image_url, $video_url, $admin_id);
    $stmt->execute();
}

// Handle new user submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_user"])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $full_name = $_POST['full_name'];  // Capture full name of the user
    
    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, role, full_name) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $password, $role, $full_name);
    $stmt->execute();
}

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
    <?php include('navbar.php'); ?>
    <div class="container mt-5">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?php echo $_SESSION['username']; ?>. You are logged in as an admin.</p>
        
        <!-- Add Dance Form -->
        <h2>Add New Dance</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Dance Name" required class="form-control mb-2">
            <textarea name="description" placeholder="Dance Description" required class="form-control mb-2"></textarea>
            <input type="text" name="genre" placeholder="Genre" required class="form-control mb-2">
            <input type="text" name="region" placeholder="Region" required class="form-control mb-2">
            <input type="text" name="image_url" placeholder="Image URL" class="form-control mb-2">
            <input type="text" name="video_url" placeholder="Video URL" class="form-control mb-2">
            <button type="submit" name="add_dance" class="btn btn-success">Add Dance</button>
        </form>

        <!-- Add User Form -->
        <h2>Add New User</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required class="form-control mb-2">
            <input type="email" name="email" placeholder="Email" required class="form-control mb-2">
            <input type="password" name="password" placeholder="Password" required class="form-control mb-2">
            <input type="text" name="full_name" placeholder="Full Name" required class="form-control mb-2">
            <select name="role" class="form-control mb-2">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit" name="add_user" class="btn btn-success">Add User</button>
        </form>

        <!-- Dances Section -->
        <h2>List of Dances</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Genre</th>
                    <th>Region</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $dancesQuery->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['dance_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo substr(htmlspecialchars($row['description']), 0, 50); ?>...</td>
                        <td><?php echo htmlspecialchars($row['genre']); ?></td>
                        <td><?php echo htmlspecialchars($row['region']); ?></td>
                        <td>
                            <a href="edit_dance.php?id=<?php echo $row['dance_id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="delete_dance.php?id=<?php echo $row['dance_id']; ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Users Section -->
        <h2>List of Users</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Full Name</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $usersQuery->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $user['user_id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $user['user_id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="delete_user.php?id=<?php echo $user['user_id']; ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
