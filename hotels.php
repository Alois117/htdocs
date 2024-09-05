<?php
//checks if user is logged in 
include ('sessioncheck.php');
checkSession();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotels</title>
    <link rel="stylesheet" href="hotel.css">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    
<header>
    <nav class="navbar">
        <h2 class="logo">Online Hotel Reservation<span>.</span></h2>
        <ul class="menu-links">
            <span id="close-menu-btn" class="material-symbols-outlined">close</span>
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="hotels.php">Hotels</a></li>
            <li><a href="mybookings.php">Bookings</a></li>
            <li><a href="about.php">About us</a></li>
            <li><a href="contact.php">Contact Us</a></li>
            <li>
                    <div class="user-info">
                        <span class="material-icons" id="guest-icon">account_circle</span>
                        <span>Guest</span>
                        <div class="dropdown" id="dropdown-menu">
                            <a href="profiles.php">Profile</a>
                            <a href="logout.php">Logout</a>
                    </div>
                    </div>
                </li>
        </ul>
        <span id="menu-btn" class="material-symbols-outlined">menu</span>
    </nav>
</header>

<div class="popular_container">
    <h2 class="section_header">Popular Hotels</h2>
    <div class="popular_grid">
        <?php
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

        // Fetch data from the hotels table
        $sql = "SELECT name, location, image FROM hotels";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo '<div class="popular_card">';
                echo '<img src="' . $row["image"] . '" alt="Hotel Image">';
                echo '<div class="popular_content">';
                echo '<div class="popular_card_header">';
                echo '<h4>' . $row["name"] . '</h4>';
                echo "<form action='bookcont.php' method='get'>";
                echo "<input type='hidden' name='hotel' value='" . $row["name"] . "'>";
                echo "<input type='hidden' name='location' value='" . $row["location"] . "'>";
                echo "<button type='submit' name='hotel_button' class='room_button'>Available Rooms</button>";
                echo "</form>";
                echo '</div>';
                echo '<p>' . $row["location"] . '</p>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "0 results";
        }

        $conn->close();
        ?>
    </div>
</div> 
<footer>
        <div class="row">
                <div class="col">
                    <h3> Contact Us <div class="underline"><span></span></div></h3>
                    <P>90137 98-Kibwezi</P>
                    <p class="email-id">aloismbithi01@gmail.com</p>
                    <h4>+254711840122</h4>
                </div>
                <div class="col">
                    <h3>Quick Links <div class="underline"><span></span></div></h3>
                    <ul>
                        <li><a href="Home.html">Home</a></li>
                        <li><a href="hotels.php">Hotels</a></li>
                        <li><a href="mybookings.php">Bookings</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col">
                    <h3>Follow Us <div class="underline"><span></span></div></h3>
                    <div class="soc-icon">
                        <a href=""><i class="fab fa-facebook"></i></a>
                        <a href=""><i class="fab fa-twitter"></i></a>
                        <a href=""><i class="fab fa-instagram"></i></a>
                        <a href=""><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
         <hr>
         <p class="copyright">Powered by Mavericks @ 2024 - All Rights Reserved</p>
    </footer> 

<script>
    const header = document.querySelector("header");
    const menuBtn = document.querySelector("#menu-btn");
    const closeMenuBtn = document.querySelector("#close-menu-btn");

    // Toggle mobile menu on menu button click
    menuBtn.addEventListener("click", () => {
        header.classList.toggle("show-mobile-menu");
    });

    // Close mobile menu on close button click
    closeMenuBtn.addEventListener("click", () => {
        header.classList.remove("show-mobile-menu");
    });

    const guestIcon = document.getElementById('guest-icon');
        const dropdownMenu = document.getElementById('dropdown-menu');

        guestIcon.addEventListener('click', () => {
            dropdownMenu.classList.toggle('show');
        });

        // Close the dropdown if the user clicks outside of it
        window.addEventListener('click', (event) => {
            if (!guestIcon.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
</script>

</body>
</html>
