<?php
// Include the navbar (which already contains session_start())
include('navbar.php');
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
<div class="modal fade" role="dialog" tabindex="-1" id="modal-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirm Account Delete</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body"><button class="btn btn-danger" type="button">Confirm Account Delete</button></div>
            <div class="modal-footer"><button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>
<div class="container mt-4 mb-4">
    <h1>User Settings</h1>
</div>
<form>
    <div class="container mt-4 mb-4">
        <h2 style="text-shadow: 1px 1px;">Website Theme Preference</h2><select class="form-select" style="width: 200px;">
            <optgroup label="Themes">
                <option value="1" selected="">Light</option>
                <option value="2">Dark</option>
                <option value="3">Green</option>
                <option value="4">Blue</option>
            </optgroup>
        </select>
    </div>
    <div class="container mt-4 mb-4">
        <h2 style="text-shadow: 1px 1px;">Email Preferences</h2>
        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1"><label class="form-check-label" for="formCheck-1">Blog Posts</label></div>
        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-3"><label class="form-check-label" for="formCheck-3">Events</label></div>
        <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-2"><label class="form-check-label" for="formCheck-2">New Dances</label></div>
    </div>
    <div class="container mt-4 mb-4">
        <h2 style="text-shadow: 1px 1px;">Account Settings</h2>
        <div><input class="form-control mb-2" type="password" name="User_New_Password" placeholder="New Password" style="width: 200px;"></div>
        <div><input class="form-control" type="password" name="User_Old_Password" placeholder="Old Password" style="width: 200px;"></div>
    </div>
    <div class="container"><button class="btn btn-primary" type="submit">Update Preferences</button></div>
</form>
<div class="container mt-5"><button class="btn btn-danger" type="button" data-bs-target="#modal-1" data-bs-toggle="modal">Delete Account</button></div>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/Simple-Slider-swiper-bundle.min.js"></script>
<script src="assets/js/Simple-Slider.js"></script>
</body>

</html>
