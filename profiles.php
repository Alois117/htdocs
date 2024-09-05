<?php
session_start(); // Start the session at the beginning of the file

// Database connection
$servername = "localhost";
$username = "root";
$password = "Kwanduti2008#";
$dbname = "mytestdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    
    // Fetch user ID from registration table
    $sql = "SELECT id FROM registrations WHERE username = '$username'";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userid = $user['id'];
    } else {
        // Handle case where user is not found
        die("User not found.");
    }
    
    // Fetch user profile data
    $sql = "SELECT * FROM user_profile WHERE user_id = '$userid'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $profile = $result->fetch_assoc();
    } else {
        // If no profile data, initialize an empty array
        $profile = [
            'first_name' => '',
            'middle_name' => '',
            'last_name' => '',
            'gender' => '',
            'id_passport' => '',
            'phone_number' => '',
            'email' => ''
        ];
    }
} else {
    // Handle case where Username is not set in session
    die("No user is logged in.");
}

// Update profile information
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $id_passport = $_POST['id_passport'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];

    if (!empty($profile['first_name'])) {
        // Update existing profile
        $sql = "UPDATE user_profile SET 
                first_name='$first_name',
                middle_name='$middle_name',
                last_name='$last_name',
                gender='$gender',
                id_passport='$id_passport',
                phone_number='$phone_number',
                email='$email'
                WHERE user_id=$userid";
    } else {
        // Insert new profile
        $sql = "INSERT INTO user_profile (user_id, first_name, middle_name, last_name, gender, id_passport, phone_number, email)
                VALUES ('$userid', '$first_name', '$middle_name', '$last_name', '$gender', '$id_passport', '$phone_number', '$email')";
    }

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_p'] = "Profile updated successfully";
    } else {
        $_SESSION['error_p'] = "Error updating profile: " . $conn->error;
    }

    header('Location: profiles.php');
    exit();
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="profiles.css">
</head>
<body>
    <div class="prof">
        <div class="container">
            <h1>User Profile</h1>

            <?php
                if (isset($_SESSION['success_p'])) {
            ?>
            <div class="alert-success">
                <b><?php
                    echo $_SESSION['success_p'];
                ?></b>
            </div>
            <?php
                unset($_SESSION['success_p']);
                } else {
                if (isset($_SESSION['error_p'])) {
            ?>
            <div class="alert-error">
                <b><?php
                    echo $_SESSION['error_p'];
                ?></b>
            </div>
            <?php
                unset($_SESSION['error_p']);
                }
                }
            ?>
            
            
            <!-- Profile Information Display -->
            <div class="profile-info active">
                <input type="hidden" id="id" name="id" value="<?php  echo $user_id ?>">
                <div class="name">
                    <p><strong>First Name:</strong> <br><br><span id="firstname"><i><?php echo $profile['first_name'] ?></i></span></p>
                    <p><strong>Middle Name:</strong> <br><br><span id="middlename"><i><?php echo $profile['middle_name'] ?></i></span></p>
                </div>
                <div class="name">
                    <p><strong>Last Name:</strong> <br><br><span id="lastname"><i><?php echo $profile['last_name'] ?></i></span></p>
                </div>
                <div class="name1">
                    <p><strong>Gender:</strong> <br><br><span id="gender"><i><?php echo $profile['gender'] ?></i></span></p>
                    <p><strong>ID/Passport:</strong> <br><br><span id="idpassport"><i><?php echo $profile['id_passport'] ?></i></span></p>
                </div>
                <div class="name2">
                    <p><strong>Phone Number:</strong> <br><br><span id="phonenumber"><i><?php echo $profile['phone_number'] ?></i></span></p>
                    <p><strong>Email:</strong> <br><br><span id="email"><i><?php echo $profile['email'] ?></i></span></p>
                </div>
                
                <div class="buttons">
                    <button class="edit" onclick="toggleEdit(true)">Edit</button>
                </div>
                <div class="form-group">
                    <p>Go back home? <a href="home.php" style="color: purple;"><b>Home</b></a></p>
                </div>
            </div>
            
            <!-- Profile Information Edit Form -->
            <div class="edit-form">
                <form action="profiles.php" method="post">
                    <div class="form-group">
                        <input type="hidden" id="id" name="id" value="<?php echo $user_id ?>">
                        <label for="first_name">First Name: <?php echo $profile['first_name'] ?></label><br>
                        <input type="text" id="first_name" name="first_name" value="<?php echo $profile['first_name'] ?>">
                        <?php if (isset($errors['first_name'])): ?>
                            <div class="alert alert-danger">
                                <li><?php echo $errors['first_name'] ?></li>
                            </div>
                        <?php endif; ?>
                    </div>    
                    <div class="form-group">
                        <label for="middle_name">Middle Name: <?php echo $profile['middle_name'] ?></label><br>
                        <input type="text" id="middle_name" name="middle_name" value="<?php echo $profile['middle_name'] ?>">
                        <?php if (isset($errors['middle_name'])): ?>
                            <div class="alert alert-danger">
                                <li><?php echo $errors['middle_name'] ?></li>
                            </div>
                        <?php endif; ?>
                    </div>   
                    <div class="form-group">
                        <label for="last_name">Last Name: <?php echo $profile['last_name'] ?></label><br>
                        <input type="text" id="last_name" name="last_name" value="<?php echo $profile['last_name'] ?>">
                        <?php if (isset($errors['last_name'])): ?>
                            <div class="alert alert-danger">
                                <li><?php echo $errors['last_name'] ?></li>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender: <?php echo $profile['gender'] ?></label><br><br>
                        <select id="gender" name="gender">
                            <option value="<?php echo $profile['gender'] ?>"><?php echo $profile['gender'] ?></option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                        <?php if (isset($errors['gender'])): ?>
                            <div class="alert alert-danger">
                                <li><?php echo $errors['gender'] ?></li>
                            </div>
                        <?php endif; ?><br><br>
                    </div>    
                    <div class="form-group">
                        <label for="id_passport">ID/Passport: <?php echo $profile['id_passport'] ?></label><br>
                        <input type="text" id="id_passport" name="id_passport" value="<?php echo $profile['id_passport'] ?>">
                        <?php if (isset($errors['id_passport'])): ?>
                            <div class="alert alert-danger">
                                <li><?php echo $errors['id_passport'] ?></li>
                            </div>
                        <?php endif; ?>
                    </div>  
                    <div class="form-group">  
                        <label for="phone_number">Phone number:  <?php echo $profile['phone_number'] ?></label><br>
                        <input type="text" id="phone_number" name="phone_number" value="<?php echo $profile['phone_number'] ?>">
                        <?php if (isset($errors['phone_number'])): ?>
                            <div class="alert alert-danger">
                                <li><?php echo $errors['phone_number'] ?></li>
                            </div>
                        <?php endif; ?>
                    </div>    
                    <div class="form-group">
                        <label for="email">Email:  <?php echo $profile['email'] ?></label><br>
                        <input type="email" id="email" name="email" value="<?php echo $profile['email'] ?>">
                        <?php if (isset($errors['email'])): ?>
                            <div class="alert alert-danger">
                                <li><?php echo $errors['email'] ?></li>
                            </div>
                        <?php endif; ?>
                    </div> 
                    <div class="buttons">
                        <button class="save" type="submit" name="save">Save</button>
                        <button class="cancel" type="button" onclick="toggleEdit(false)">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            // Function to toggle between display and edit forms
            function toggleEdit(editMode) {
                document.querySelector('.profile-info').classList.toggle('active', !editMode);
                document.querySelector('.edit-form').classList.toggle('active', editMode);
            }
        </script>
    </div>
</body>
</html>
