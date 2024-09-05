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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $location = $_POST['location'];
    
    // Check if file was uploaded without errors
    if(isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $target_dir = "hotels/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);

        // Select file type
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Valid file extensions
        $extensions_arr = array("jpg","jpeg","png","gif");

        // Check extension
        if (in_array($imageFileType, $extensions_arr)) {
            // Upload file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                // Insert record
                $stmt = $conn->prepare("INSERT INTO hotels (name, location, image) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $name, $location, $target_file); 
                if ($stmt->execute()) {
                    header("Location: hotelsform.php");
                    exit(); // Exit after redirect
                } else {
                    echo "Error: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Failed to move uploaded file.";
            }
        } else {
            echo "Invalid file extension.";
        }
    } else {
        echo "Error uploading file.";
    }
}

$conn->close();

?>
