                    <?php
                    
                    //checks if user is logged in 
                    include('sessioncheck.php');
                    checkSession();
                    
                    
                   
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

                    // SQL query to count total entries
                    $userssql = "SELECT COUNT(*) AS total_users FROM registrations"; 
                    $result = $conn->query($userssql);

                    if ($result->num_rows > 0) {
                        // Fetch the result
                        $row = $result->fetch_assoc();
                        $total_users = $row['total_users'];
                        
                    } 

                    // SQL query to count total entries
                    $hotelssql = "SELECT COUNT(*) AS total_hotels FROM hotels"; 
                    $result = $conn->query($hotelssql);

                    if ($result->num_rows > 0) {
                        // Fetch the result
                        $row = $result->fetch_assoc();
                        $total_hotels = $row['total_hotels'];
                        
                    } 

                    // SQL query to count total entries
                    $bookingssql = "SELECT COUNT(*) AS total_bookings FROM bookings"; 
                    $result = $conn->query($bookingssql);

                    if ($result->num_rows > 0) {
                        // Fetch the result
                        $row = $result->fetch_assoc();
                        $total_bookings = $row['total_bookings'];
                        
                    } 

                    // SQL query to count total entries
                    $roomssql = "SELECT COUNT(*) AS total_rooms FROM rooms"; 
                    $result = $conn->query($roomssql);

                    if ($result->num_rows > 0) {
                        // Fetch the result
                        $row = $result->fetch_assoc();
                        $total_rooms = $row['total_rooms'];
                        
                    } 

                    $conn->close();
                    ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="Adming.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
    <header>
        <nav class="navbar">
            <h2 class="logo">Online Hotel Reservation</h2>
            <div class="user-info">
                <span class="material-icons" id="admin-icon">account_circle</span>
                <span>Admin</span>
                <div class="dropdown" id="dropdown-menu">
                    <a href="profiles.php">Profile</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <aside class="sidebar">
            <ul class="menu-links">
                <li><a href="Admin.php"><span class="material-icons">home</span>Home</a></li>
                <li><a href="bookingsadmin.php"><span class="material-icons">book</span>Booked</a></li>
                <li><a href="checkin.php"><span class="material-icons">check_circle</span>Check In</a></li>
                <li><a href="checkout.php"><span class="material-icons">check_circle_outline</span>Check Out</a></li>
                <li><a href="hotelsform.php"><span class="material-icons">hotel</span>Hotels</a></li>
                <li><a href="roomcateg.php"><span class="material-icons">hotel</span>Rooms</a></li>
                <li><a href="users.php"><span class="material-icons">person</span>Users</a></li>
                <li><a href="userfeedback.php"><span class="material-icons">feedback</span>User Feedback</a></li>
            </ul>
        </aside>
        <!-- Reports Generated --> 
    <div class="report-container">
        <div class="report-card users">
            <div class="report-content">
                <h3>
                    <?php echo $total_users; ?>
                </h3>
                <p>New Users</p>
                <a href="users.php" class="more-info">More Info</a> 
            </div>
        </div>

        <div class="report-card hotels">
            <div class="report-content">
                <h3><?php echo $total_hotels; ?></h3>
                <p>New Hotels</p>
                <a href="hotelsform.php" class="more-info">More Info</a> 
            </div>
        </div>

        <div class="report-card bookings">
            <div class="report-content">
                <h3><?php echo $total_bookings; ?></h3>
                <p>New Bookings</p>
                <a href="bookingsadmin.php" class="more-info">More Info</a> 
            </div>
        </div>

        <div class="report-card rooms">
            <div class="report-content">
                <h3><?php echo $total_rooms; ?></h3>
                <p>New Rooms</p>
                <a href="roomcateg.php" class="more-info">More Info</a> 
            </div>
        </div>
    </div>
    </main>
    <script>
        const adminIcon = document.getElementById('admin-icon');
        const dropdownMenu = document.getElementById('dropdown-menu');

        adminIcon.addEventListener('click', () => {
            dropdownMenu.classList.toggle('show');
        });

        // Close the dropdown if the user clicks outside of it
        window.addEventListener('click', (event) => {
            if (!adminIcon.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    </script>
</body>
</html>
