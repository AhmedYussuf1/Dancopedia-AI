<?php
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $link = $_POST['link'];
    $genre = $_POST['genre'];
    $region = $_POST['region'];

    $stmt = $conn->prepare("INSERT INTO dances (name, link, genre, region) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $link, $genre, $region);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Error: ' . $conn->error;
    }
    $stmt->close();
    $conn->close();
}
?>
