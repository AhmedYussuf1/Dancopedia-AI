<?php
session_start();
session_destroy();  // Destroy the session to log out the user
header("Location: login.php");  // Redirect to login page
exit();
?>
