<?php
require_once('sessioncheck.php');

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

// Initialize errors array
$errors = array();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['Username'];
    $password = $_POST['Password'];

    // Validating entered details in the form
    if (empty($username)) {
        $errors['username'] = "Username required";
    }
    if (empty($password)) {
        $errors['password'] = "Password required";
    }

    if (empty($errors)) {
        $sql = "SELECT * FROM registrations WHERE username=? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id']; // Store user ID in session
            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['username'];

            if ($user['role'] === 'Guest') {
                header('Location: Home.php');
                exit();
            } else if ($user['role'] === 'Admin') {
                header('Location: Admin.php');
                exit();
            }
        } else {
            $errors['login_fail'] = "Wrong credentials";
        }
    }
}

// Check if there are errors and display them (useful for debugging)
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<div class='error'>{$error}</div>";
    }
}


?>

