<?php
// Start the session
session_start();

// Include necessary files and scripts
// include('path_to_your_database_connection_file.php');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Validate the email address
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Check if the email exists in the database
        // $query = "SELECT * FROM users WHERE email = '$email'";
        // $result = mysqli_query($conn, $query);
        
        // if (mysqli_num_rows($result) > 0) {
            // Generate a unique reset token
            $resetToken = bin2hex(random_bytes(32));
            
            // Save the reset token and its expiration in the database
            // $query = "UPDATE users SET reset_token = '$resetToken', token_expiration = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = '$email'";
            // mysqli_query($conn, $query);
            
            // Send the reset email
            // mail($email, "Password Reset Request", "Please click the link to reset your password: https://yourwebsite.com/reset_password.php?token=$resetToken");

            $_SESSION['success'] = "A password reset link has been sent to your email.";
        // } else {
            // $_SESSION['errors']['email'] = "Email address not found.";
        // }
    } else {
        $_SESSION['errors']['email'] = "Invalid email address.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password</title>
    <link rel="stylesheet" type="text/css" href="sign up.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="main">
    <div class="navbar">
        <div class="icon">
            <h2 class="logo">Online Hotel Reservation</h2>
        </div>
        <div class="menu">
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="hotels.php">Hotels</a></li>
                <li><a href="mybookings.php">Bookings</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="wrapper">
    <form action="forgot_password.php" method="POST">
        <h1>Forgot Password</h1>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="success-message"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <div class="input-box">
            <input type="email" name="email" id="email" placeholder="Enter your email" required>
            <?php if (isset($_SESSION['errors']['email'])): ?>
                <div class="error-message"><?php echo $_SESSION['errors']['email']; unset($_SESSION['errors']['email']); ?></div>
            <?php endif; ?>
            <i class='bx bx-envelope'></i>
        </div>
        <button type="submit" class="btn">Send Reset Link</button>
        <div class="register-link">
            <p>Remember your password? <a href="Sign in.php">Login</a></p>
        </div>
    </form>
</div>
</body>
</html>
