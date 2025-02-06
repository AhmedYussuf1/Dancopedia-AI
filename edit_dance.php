<?php
session_start();
include('db_connection.php');  // Include your database connection

// Ensure that the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Check if an ID is passed and fetch the dance
if (isset($_GET['id'])) {
    $dance_id = $_GET['id'];
    $danceQuery = $conn->query("SELECT * FROM dances WHERE dance_id = $dance_id");

    if ($danceQuery->num_rows == 0) {
        echo "Dance not found.";
        exit();
    }

    $dance = $danceQuery->fetch_assoc();
} else {
    echo "No dance ID provided.";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $updateQuery = "UPDATE dances SET name = ?, description = ? WHERE dance_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssi", $name, $description, $dance_id);

    if ($stmt->execute()) {
        echo "Dance updated successfully.";
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error updating dance: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Include the navbar -->
    <?php include('navbar.php'); ?>

    <div class="container mt-5">
        <h1>Edit Dance</h1>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $dance['name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="5" required><?php echo $dance['description']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
