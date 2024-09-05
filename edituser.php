<?php

//checks if user is logged in 
include('sessioncheck.php');
checkSession();



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
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password before saving it to the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL statement to update the user
    $sql = "UPDATE registrations SET username = ?, email = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $username, $email, $hashedPassword, $id);

    // Execute the query and check if successful
    if ($stmt->execute()) {
        header("Location: users.php");
        exit();
    } else {
        echo "Error updating user: " . $conn->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
