<?php
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

// Function to sanitize input
function sanitizeInput($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data); // Use real_escape_string to prevent SQL injection
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set and not empty
    if (isset($_POST['Username'], $_POST['Email'], $_POST['Enter_password'], $_POST['Confirm_password'], $_POST['role'])) {
        // Sanitize input
        $username = sanitizeInput($_POST['Username']);
        $email = sanitizeInput($_POST['Email']);
        $password = sanitizeInput($_POST['Enter_password']);
        $confirm_password = sanitizeInput($_POST['Confirm_password']);
        $role = sanitizeInput($_POST['role']);

        // Validate Username (only letters)
        if (!preg_match("/^[a-zA-Z]+$/", $username)) {
            echo "Username should only contain letters";
            exit();
        }

        // Validate Email (must contain @ symbol)
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format";
            exit();
        }

        // Check if password matches confirm password
        if ($password !== $confirm_password) {
            echo "Passwords do not match";
            exit();
        }

        // Check if email already exists
        $stmt = $conn->prepare("SELECT * FROM registrations WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Email already in use";
            exit();
        }

        // Check if username already exists
        $stmt = $conn->prepare("SELECT * FROM registrations WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Username already exists";
            exit();
        }

        // Insert new user into database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO registrations (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

        if ($stmt->execute()) {
            echo "Signup successful";
            // Redirect to login page
            header("Location: Sign in.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Please fill out all the required fields.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
