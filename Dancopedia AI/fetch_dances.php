<?php
// Database connection
DEFINE('DATABASE_HOST', 'localhost');
DEFINE('DATABASE_PORT', 3307);  // Adjust if using custom port in XAMPP
DEFINE('DATABASE_DATABASE', 'dance_ai_db');
DEFINE('DATABASE_USER', 'root');
DEFINE('DATABASE_PASSWORD', 'ics311');

$conn = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE, DATABASE_PORT);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch all dances
$sql = "SELECT dance_id, name, video_url, description, genre, region, created_at, admin_id FROM dances";
$result = $conn->query($sql);

// Fetch and output the rows
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["dance_id"] . "</td>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["video_url"] . "</td>";
        echo "<td>" . $row["genre"] . "</td>";
        echo "<td>" . $row["region"] . "</td>";
        echo "<td>" . $row["created_at"] . "</td>";
        echo "<td>" . $row["admin_id"] . "</td>";
        echo "<td>
                <button type='button' class='btn btn-danger' onclick='deleteDance(" . $row["dance_id"] . ")'>Delete</button>
              </td>";
        echo "</tr>";
    }
} else {
    echo "No dances found.";
}

$conn->close();
?>
