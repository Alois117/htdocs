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
    $category = $_POST['category'];
    $price = $_POST['price'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $uploadDir = 'uploads/'; // Directory to save images
        $imagePath = $uploadDir . $imageName;

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($imageTmpPath, $imagePath)) {
            // Update the room details with the new image
            $sql = "UPDATE rooms SET category = ?, price = ?, image = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sdsi', $category, $price, $imagePath, $id);
        } else {
            echo "Error uploading the image.";
            exit();
        }
    } else {
        // Update the room details without changing the image
        $sql = "UPDATE rooms SET category = ?, price = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sdi', $category, $price, $id);
    }

    // Execute the query and check if successful
    if ($stmt->execute()) {
        header("Location: roomcateg.php"); 
        exit();
    } else {
        echo "Error updating room: " . $conn->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
