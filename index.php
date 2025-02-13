<?php 
// Start session to track user login status
session_start();
//dummy test
// Database connection
$servername = "localhost";
$username = "root";  // Your MySQL username
$password = "ics311";      // Your MySQL password
$dbname = "dance_ai_db";  // Your MySQL database name
$port = 3307; // MySQL default port, change if needed

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get dance data
$sql = "SELECT * FROM dances";  // Ensure this matches your table and column names
$result = $conn->query($sql);
?>

<!-- Add Favicon -->
<link rel="icon" href="favicon.ico" type="image/x-icon">

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dance USA - Home</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Body takes full height */
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        /* Main container should take all available space except the footer */
        .main-content {
            flex: 1;
        }

        /* Footer styling */
        footer {
            background-color: #343a40;
            color: white;
            padding: 10px;
            text-align: center;
        }

        /* About section styling */
        .about-section {
            padding: 50px 0;
            background-color: #f8f9fa;
        }

        .about-section h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Image styling */
        .image-container img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .image-container {
            padding: 10px;
        }

        /* Chatbot container - position it on the right side and hidden by default */
        .chat-popup {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 350px;
            height: 450px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            display: none; /* Hidden by default */
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            padding: 20px;
            overflow-y: auto;
        }

        .chat-box-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
        }

        .open-button {
            padding: 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 24px;
            cursor: pointer;
        }

        .open-button i {
            font-size: 20px;
        }

        .chatbox-text-area {
            width: 100%;
            height: 100px;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            border: 1px solid #ddd;
        }

        .btn {
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            padding: 8px 15px;
            border: none;
            cursor: pointer;
        }

        .cancel {
            background-color: #f44336;
        }

        /* Button for closing chat */
        .cancel i {
            margin-right: 8px;
        }

        /* Optional: Add a smooth transition for the search bar */
        .search-bar input {
            transition: width 0.4s ease;
        }

        .search-bar input:focus {
            width: 250px;
        }

    </style>
</head>
<body>

    <!-- Navigation Bar (Same as on other pages) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="images/logo.png" alt="Dance USA Logo" class="img-fluid" style="height: 50px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="classical_dances.php">Classical Dances</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="folk_dances.php">Folk Dances</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contemporary_dances.php">Contemporary Dances</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="blog.php">Blog</a>
                    </li>
                    <li class="nav-item search-bar">
                        <input type="text" id="search-bar" class="form-control" placeholder="Search for dances..." onkeyup="searchDances()">
                    </li>
                    <?php if (isset($_SESSION['username'])): ?>
                        <!-- Check if the logged-in user is an admin -->
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin_dashboard.php">Admin</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Home Page Content -->
    <div class="main-content">
        <header class="text-center my-5">
            <div class="container">
                <h1 class="display-3">Welcome to the United States of Dances</h1>
                <p class="lead">Explore the history, styles, and cultural significance of U.S. dance forms.</p>
            </div>
        </header>

        <div class="container text-center my-5">
            <div class="row">
                <div class="col-md-4 image-container">
                    <img src="images/image1.jpg" alt="Dance Image 1" class="img-fluid">
                </div>
                <div class="col-md-4 image-container">
                    <img src="images/image2.jpg" alt="Dance Image 2" class="img-fluid">
                </div>
                <div class="col-md-4 image-container">
                    <img src="images/image3.jpg" alt="Dance Image 3" class="img-fluid">
                </div>
            </div>
        </div>

        <section class="about-section">
            <div class="container">
                <h2>About Dance USA</h2>
                <p>Dance USA is dedicated to showcasing the rich and diverse dance cultures of the world. We provide insights, history, and visual representations of various dance forms from every corner of the globe. Whether you're a dance enthusiast or someone looking to explore new styles, Dance USA brings the world of movement to your fingertips.</p>
            </div>
        </section>
    </div>

    <!-- Dance Table Section -->
    <div class="container my-5">
        <h2 class="text-center">Dance Listings</h2>

        <!-- Filters Section -->
        <div class="d-flex justify-content-between mb-3">
            <input type="text" id="search-name" class="form-control w-25" placeholder="Search by Name" onkeyup="filterDances()">
            <input type="text" id="search-genre" class="form-control w-25" placeholder="Search by Genre" onkeyup="filterDances()">
            <input type="text" id="search-region" class="form-control w-25" placeholder="Search by Region" onkeyup="filterDances()">
        </div>

        <table class="dance-table table table-bordered">
            <thead>
                <tr>
                    <th onclick="sortTable(0)">Name <i class="fa fa-sort"></i></th>
                    <th onclick="sortTable(1)">Genre <i class="fa fa-sort"></i></th>
                    <th onclick="sortTable(2)">Region <i class="fa fa-sort"></i></th>
                    <th>Description</th>
                    <th>Link</th>
                </tr>
            </thead>
            <tbody id="dance-list">
                <?php
                // Check if any data is returned
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['genre'] . "</td>";
                        echo "<td>" . $row['region'] . "</td>";
                        echo "<td>" . $row['description'] . "</td>";
                        echo "<td><a href='#'>View Details</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- CHAT BOX -->
    <div class="chat-box-container">
        <button class="open-button" onclick="openChatBox()">Chat <i class="fa-regular fa-comment"></i></button>
        <div class="chat-popup" id="myForm">
            <form id="chat-form" class="form-container">
                <div class="d-flex justify-content-between">
                    <h2>Chat</h2>
                    <h2><i class="fa-regular fa-comments"></i></h2>
                </div>

                <div class="chat-messages" id="chat-messages"></div>

                <label id="message-input" for="msg"><b>Message</b></label>
                <textarea placeholder="Type your message..." name="input" class="chatbox-text-area" required></textarea>

                <button id="chat-submit" type="submit" class="btn">Send <i class="fa-regular fa-paper-plane"></i></button>
                <button type="button" class="btn cancel" onclick="closeChatBox()">Close <i class="fa-regular fa-rectangle-xmark"></i></button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Open the Chatbox
        function openChatBox() {
            document.getElementById('myForm').style.display = 'block';
        }

        // Close the Chatbox
        function closeChatBox() {
            document.getElementById('myForm').style.display = 'none';
        }

        // Send message to backend and receive response
        $("#chat-form").submit(function(event) {
            event.preventDefault();
            const userMessage = $("textarea[name='input']").val().trim();
            if (userMessage) {
                $('#chat-messages').append('<p><strong>You:</strong> ' + userMessage + '</p>');
                $.ajax({
                    type: 'POST',
                    url: 'chatbot_backend.php',  // Path to your PHP backend file
                    data: { input: userMessage },
                    success: function(response) {
                        $('#chat-messages').append('<p><strong>Chatbot:</strong> ' + response + '</p>');
                        $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
                        $("textarea[name='input']").val(''); 
                    },
                    error: function() {
                        $('#chat-messages').append('<p><strong>Chatbot:</strong> Sorry, something went wrong.</p>');
                    }
                });
            } else {
                alert('Please type a message!');
            }
        });

        // Search function
        function searchDances() {
            let query = document.getElementById('search-bar').value;
            if (query) {
                $.ajax({
                    type: 'GET',
                    url: 'search.php',  // Create this PHP file to fetch filtered data
                    data: { search: query },
                    success: function(response) {
                        $('#dance-list').html(response);  // Update the dance table
                    },
                    error: function() {
                        alert('Error occurred while searching');
                    }
                });
            } else {
                alert('Please enter a search query.');
            }
        }
    </script>

</body>
</html>

