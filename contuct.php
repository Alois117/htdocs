<?php
session_start();
// Database connection
$servername = "localhost";
$username = "root";
$password = "Kwanduti2008#";
$dbname = "mytestdb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if name, email, and message are set
    if (isset($_POST['name'], $_POST['email'], $_POST['message'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        // Prepare and bind the SQL statement
        $stmt = $conn->prepare("INSERT INTO ContactMessages (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['success_send'] = "Message send successfully";
            header('location: contact.php');
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        $_SESSION['success_error'] = "Please fill out all fields in the form.";
    }
}

// Close connection
$conn->close();
?>
