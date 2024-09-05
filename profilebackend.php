<?php

session_start();

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

$errors = array();

// Check if user ID is provided
if(isset($_SESSION['user_id'])) {
    // Get the user ID
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM registration WHERE user_id = $user_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        
        $user_info = $result->fetch_assoc();

        $user_id = $user_info['user_id'];
        $firstname = $user_info['firstname'];
        $middlename = $user_info['middlename'];
        $lastname = $user_info['lastname'];
        $gender = $user_info['gender'];
        $idpassport = $user_info['idpassport'];
        $phonenumber = $user_info['phonenumber'];
        $email = $user_info['email'];
        $role = $user_info['role'];
        $verified = $user_info['verified'];
        $token = $user_info['token'];
        
    }
} else {
    
    echo "user ID not provided!";
}



// if user click on save button
if (isset($_POST['save'])) {
    
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $idpassport = $_POST['idpassport'];
    $phonenumber = $_POST['phonenumber'];
    $email = $_POST['email'];


    //validating entered details in the form
    if (empty($firstname)) {
        $errors['firstname'] = "First name required";
    }
    if (empty($middlename)) {
        $errors['middlename'] = "Middle name required";
    }
    if (empty($lastname)) {
        $errors['lastname'] = "Last name required";
    }
    if (empty($gender)) {
        $errors['gender'] = "Gender required";
    }
    if (empty($idpassport)) {
        $errors['idpassport'] = "ID/Passport required";
    }
    if (empty($phonenumber)) {
        $errors['phonenumber'] = "Phone number required";
    } else {
        if (!preg_match('/^(07|01|\+2541|\+2547)/', $phonenumber)) {
            $errors['phonenumber'] = "Phone number must start with 07, 01, +2541, or +2547";
        }
        if (strlen($phonenumber) < 10) {
            $errors['phonenumber'] = "Phone number must be at least 10 characters long";
        }
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email address";
    }
    if (empty($email)) {
        $errors['email'] = "Email required";
    }
    

    if (count($errors) === 0) {
        $token  = bin2hex(random_bytes(20));
        $verified = $verified;
        $role = $role;
        
        $update_user_sql = "UPDATE users SET firstname='$firstname', middlename='$middlename', lastname='$lastname', gender='$gender',
        phonenumber='$phonenumber', idpassport='$idpassport', email='$email', role='$role', verified='$verified', token='$token' WHERE user_id=$user_id";
       
       if ($conn->query($update_user_sql) === TRUE) {
            $_SESSION['success_p'] = "profile updated successfully";
            header('location: profile.php');
            exit();
        } else {
            echo "Error updating profile: " . $conn->error;
        }
            
    } else {
        $_SESSION['error_p'] = "Error updating profile, Try again: " . $conn->error;
    }
}

$conn->close();
?>
