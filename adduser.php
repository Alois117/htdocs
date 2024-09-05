<?php

//checks if user is logged in 
include('sessioncheck.php');
checkSession();



// Database connection details
$servername = "localhost";
$username = "root";
$password = "Kwanduti2008#"; 
$dbname = "mytestdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input (basic validation)
    if (empty($username) || empty($email) || empty($password)) {
        echo "<script>alert('All fields are required.');</script>";
    } else {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO registrations (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert('New user added successfully');</script>";
            header("Location: users.php");
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }
}

// Close connection
$conn->close();
?>
