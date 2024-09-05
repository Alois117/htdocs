<?php
session_start();

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    // Fetch user information from the database using $_SESSION['user_id']
    // Assuming you have a function getUserInfoFromDatabase() to fetch user info
    $userInfo = getUserInfoFromDatabase($_SESSION['user_id']);
    // Retrieve the username from the user info
    $username = $userInfo['username'];
} else {
    // If user is not logged in, set default username or handle as needed
    $username = "Guest";
}
?>
