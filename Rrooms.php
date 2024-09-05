<?php

require_once 'bookcont.php';

function checkSession() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: Sign in.php');
        exit();
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms</title>
    <link rel="stylesheet" href="hotelrooms.css">
</head>
<body>
<div class="section">
		<header>
			<nav class="navbar">
				<h2 class="logo">Online Hotel Reservation<span>.</span></h2>
				<ul class="menu-links">
					<span id="close-menu-btn" class="material-symbols-outlined">close</span>
					<li><a href="home.php">Home</a></li>
					<li><a href="hotels.php">Hotels</a></li>
					<li><a href="mybookings.php">Bookings</a></li>
					<li><a href="about.php">About us</a></li>
					<li><a href="contact.php">Contact Us</a></li>
				</ul>
				<span id="menu-btn" class="material-symbols-outlined">menu</span>
			</nav>
		</header>
<div class="popular_container">
    <h2 class="section_header">Available Rooms</h2>
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

        // Get the selected hotel from the URL parameter
        $hotel = $_SESSION['selected_hotelname'];
        

        // Fetch data from the rooms table for the selected hotel
        $sql = "SELECT category, price, image FROM rooms WHERE hotel = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $hotel);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo '<div class="popular_card">';
                echo '<img src="' . $row["image"] . '" alt="Room Image">';
                echo '<div class="popular_content">';
                echo '<div class="popular_card_header">';
                echo '<h4>' . $row["category"] . '</h4>';
                echo "<form action='bookcont.php' method='post'>";
                echo "<input type='hidden' name='category' value='" . $row["category"] . "'>";
                echo "<input type='hidden' name='price' value='" . $row["price"] . "'>";
                echo "<button type='submit' name='room_button' class='booking_button'>Book Now</button>";
                echo "</form>";
                echo '</div>';
                echo '<p>' . 'Ksh' . $row["price"] . " " . 'Per Night' . '</p>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "No rooms available for this hotel.";
        }

        $stmt->close();
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
                        <li><a href="Home.php">Home</a></li>
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
	</script>
</body>
</html>
