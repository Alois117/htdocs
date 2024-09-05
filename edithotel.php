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
    $name = $_POST['name'];
    $location = $_POST['location'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $uploadDir = 'uploads/'; // Directory to save images
        $imagePath = $uploadDir . $imageName;

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($imageTmpPath, $imagePath)) {
            // Update the hotel details with the new image
            $sql = "UPDATE hotels SET name = ?, location = ?, image = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssi', $name, $location, $imagePath, $id);
        } else {
            echo "Error uploading the image.";
            exit();
        }
    } else {
        // Update the hotel details without changing the image
        $sql = "UPDATE hotels SET name = ?, location = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssi', $name, $location, $id);
    }

    // Execute the query and check if successful
    if ($stmt->execute()) {
        header("Location: hotelsform.php");
    } else {
        echo "Error updating hotel: " . $conn->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
