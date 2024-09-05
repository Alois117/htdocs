<?php
//checks if user is logged in 
include('sessioncheck.php');
checkSession();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link rel="stylesheet" href="mybooking.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">    
</head>
<body>
    <header>
        <nav class="navbar">
            <h2 class="logo">Online Hotel Reservation<span>.</span></h2>
            <ul class="menu-links">
                <span id="close-menu-btn" class="material-symbols-outlined">close</span>
                <li><a href="Home.php">Home</a></li>
                <li><a href="hotels.php">Hotels</a></li>
                <li class="active"><a href="mybookings.php">Bookings</a></li>
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
    <main>
        <div class="content">
            <div class="container">
                <div class="table-container">
                    <div class="table-header">
                        <form id="searchForm" method="GET" action="mybookings.php">
                            <input type="text" name="search" placeholder="Search bookings...">
                            <button type="submit">Search</button>
                            <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                                <button id="backBtn" class="btn" onclick="window.location.href='mybookings.php'">Back to All Bookings</button>
                            <?php endif; ?>
                        </form>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Hotel Name</th>
                                <th>Room Category</th>
                                <th>Room Price</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once 'signin.php';
                            // Database connection details
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

                            $userid = $_SESSION['user_id'];

                            // Pagination logic
                            $limit = 5; // Show 5 entries per page
                            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                            $offset = ($page - 1) * $limit;

                            // Search logic
                            $search = isset($_GET['search']) ? $_GET['search'] : '';
                            if (!empty($search)) {
                                $sql = "SELECT * FROM bookings WHERE 
                                    (fullname LIKE '%$search%' OR 
                                    email LIKE '%$search%' OR 
                                    phone LIKE '%$search%' OR 
                                    hotelname LIKE '%$search%' OR 
                                    hotellocation LIKE '%$search%' OR 
                                    roomcategory LIKE '%$search%') AND 
                                    userid='$userid' 
                                    ORDER BY bookingdate DESC
                                    LIMIT $offset, $limit";
                                
                            } else {
                                $sql = "SELECT * FROM bookings WHERE userid = '$userid' ORDER BY bookingdate DESC
                                        LIMIT $offset, $limit";
                            }
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $status_class = strtolower($row["status"]);
                                    echo "<tr>";
                                    echo "<td>" . $row["id"] . "</td>";
                                    echo "<td>" . $row["fullname"] . "</td>";
                                    echo "<td>" . $row["email"] . "</td>";
                                    echo "<td>" . $row["phone"] . "</td>";
                                    echo "<td>" . $row["hotelname"] . "</td>";
                                    echo "<td>" . $row["roomcategory"] . "</td>";
                                    echo "<td>" . $row["roomprice"] . "</td>";
                                    echo "<td>" . $row["checkin"] . "</td>";
                                    echo "<td>" . $row["checkout"] . "</td>";
                                    echo "<td>" . $row["amount"] . "</td>";
                                    echo "<td class='" . $status_class . "'>" . $row["status"] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='11'>You have no bookings found</td></tr>";
                            }

                            // Pagination calculations specific to the user
                            if (!empty($search)) {
                                $count_sql = "SELECT COUNT(id) AS total FROM bookings WHERE 
                                    fullname LIKE '%$search%' OR 
                                    email LIKE '%$search%' OR 
                                    phone LIKE '%$search%' OR 
                                    hotelname LIKE '%$search%' OR 
                                    hotellocation LIKE '%$search%' OR 
                                    roomcategory LIKE '%$search%'";
                            } else {
                                $count_sql = "SELECT COUNT(id) AS total FROM bookings WHERE userid = '$userid'";
                            }
                            $count_result = $conn->query($count_sql);
                            $count_row = $count_result->fetch_assoc();
                            $total_records = $count_row['total'];
                            $total_pages = ceil($total_records / $limit);

                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                    <!-- Pagination controls -->
                    <div class="pagination-controls">
                        <button onclick="previousPage()" id="prev-btn" <?php if($page <= 1) echo 'disabled'; ?>>Previous</button>
                        <span id="page-info">Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
                        <button onclick="nextPage()" id="next-btn" <?php if($page >= $total_pages) echo 'disabled'; ?>>Next</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
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
      
        function nextPage() {
            var currentPage = <?php echo $page; ?>;
            var totalPages = <?php echo $total_pages; ?>;
            if (currentPage < totalPages) {
                window.location.href = "mybookings.php?page=" + (currentPage + 1);
            }
        }

        function previousPage() {
            var currentPage = <?php echo $page; ?>;
            if (currentPage > 1) {
                window.location.href = "mybookings.php?page=" + (currentPage - 1);
            }
        }

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
