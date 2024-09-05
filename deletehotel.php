<?php

//checks if user is logged in 
include('sessioncheck.php');
checkSession();



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

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the room from the database
    $stmt = $conn->prepare("DELETE FROM hotels WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: hotelsform.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No ID specified.";
}

$conn->close();
?>
