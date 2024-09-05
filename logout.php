<?php
session_start(); // Start the session

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Destroy the session
    session_destroy();
    // Redirect to login page
    header('Location: Sign in.php');
    exit();
} else {
    // If the user is not logged in, redirect to login page
    header('Location: Sign in.php');
    exit();
}
?>
