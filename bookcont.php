<?php
session_start(); // Start the session

// Database connection
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

// Check if the hotel-btn is clicked
if (isset($_GET['hotel_button'])) {
    // Store the selected row details in sessions
    $_SESSION['selected_hotelname'] = $_GET['hotel'];
    $_SESSION['selected_hotellocation'] = $_GET['location'];

      // Debugging: Print received values
      echo "Hotel: " . $_POST['hotel'] . "<br>";
      echo "Location: " . $_POST['location'] . "<br>";
        
    header('Location: Rrooms.php');
    exit();
}

// Check if the room_button is clicked
if (isset($_POST['room_button'])) {
    // Store the selected row details in sessions
    $_SESSION['selected_roomcategory'] = $_POST['category'];
    $_SESSION['selected_roomprice'] = $_POST['price'];
        
    header('Location: bookingform.php');
    exit();
}

$selectedRoomPrice = isset($_SESSION['selected_roomprice']) ? $_SESSION['selected_roomprice'] : 0;
?>
