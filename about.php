<?php
//checks if user is logged in 
include('sessioncheck.php');
checkSession();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="abouts.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
	<title>About Us</title>
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
					<li class="active"><a href="about.php">About us</a></li>
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
		<div class="container">
			<div class="content-section">
				<div class="title">
					<h1>About Us</h1>
				</div>
				<div class="content">
					<h3>Welcome to our online hotel reservation platform, where your dream vacation is just a few clicks away.</h3>
					<p>Here we understand the importance of finding the perfect accomodation for your travels. Our user-friendly interface allows you to effortlessly browse through a wide selection of hotels ensuring you find the ideal match for your needs and preferences. With secure booking options and realtime availability updates, you can trust us to make your reseravation process seamless and stress-free. Let us be your trusted companion in creating unforgateful travel experience </p>
					<div class="button">
						<a href="">Read More</a>
					</div>
				</div>
				<div class="social">
					<a href=""><i class="fab fa-facebook"></i></a>
					<a href=""><i class="fab fa-twitter"></i></a>
					<a href=""><i class="fab fa-instagram"></i></a>
				</div>				
			</div>
			<div class="image-section">
				<img src="BgImage.jpg">
			</div>			
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
                        <li><a href="">Bookings</a></li>
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




