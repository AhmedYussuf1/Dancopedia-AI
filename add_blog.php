<?php
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];

    $stmt = $conn->prepare("INSERT INTO blogs (title, content, category) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $content, $category);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Error: ' . $conn->error;
    }
    $stmt->close();
    $conn->close();
}
?>
