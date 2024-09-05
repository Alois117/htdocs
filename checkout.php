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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="bookingadmins.css">
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
        <div class="content">
            <div class="container">
                <div class="table-container">
                    <div class="table-header">
                        <form id="searchForm" method="GET" action="bookingsadmin.php">
                            <input type="text" name="search" placeholder="Search bookings...">
                            <button type="submit">Search</button>
                            <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                                <button id="backBtn" class="btn" onclick="window.location.href='bookingsadmin.php'">Back to All Bookings</button>
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
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
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

                            // Handle status update
                            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                $id = $_POST['id'];
                                $action = $_POST['action'];
                                if ($action == 'checkin') {
                                    $status = 'CheckedIn';
                                } elseif ($action == 'checkout') {
                                    $status = 'CheckedOut';
                                }

                                $update_sql = "UPDATE bookings SET status='$status' WHERE id=$id";
                                $conn->query($update_sql);
                            }

                            // Pagination logic
                            $limit = 5; // Show 5 entries per page
                            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                            $offset = ($page - 1) * $limit;

                            // Search logic
                            $search = isset($_GET['search']) ? $_GET['search'] : '';
                            if (!empty($search)) {
                                $sql = "SELECT * FROM bookings WHERE 
                                    fullname LIKE '%$search%' OR 
                                    email LIKE '%$search%' OR 
                                    phone LIKE '%$search%' OR 
                                    hotelname LIKE '%$search%' OR 
                                    hotellocation LIKE '%$search%' OR 
                                    roomcategory LIKE '%$search%' 
                                    LIMIT $offset, $limit";
                            } else {
                                $sql = "SELECT * FROM bookings WHERE status='CheckedIn' OR status='CheckedOut' LIMIT $offset, $limit";
                            }
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
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
                                    echo "<td>" . $row["status"] . "</td>";
                                    echo '<td>
                                            <form method="post" action="">
                                                <input type="hidden" name="id" value="' . $row["id"] . '">
                                                <button type="submit" name="action" value="checkout" class="approve-btn">CheckOut</button>
                                                
                                            </form>
                                          </td>';
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='13'>No bookings found</td></tr>";
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
                                $count_sql = "SELECT COUNT(id) AS total FROM bookings WHERE status = 'CheckIn'";
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
                        <button onclick="previousPage()" id="prev-btn" <?php if ($page <= 1) echo 'disabled'; ?>>Previous</button>
                        <span id="page-info">Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
                        <button onclick="nextPage()" id="next-btn" <?php if ($page >= $total_pages) echo 'disabled'; ?>>Next</button>
                    </div>
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

        function nextPage() {
            var currentPage = <?php echo $page; ?>;
            var totalPages = <?php echo $total_pages; ?>;
            if (currentPage < totalPages) {
                window.location.href = "bookingsadmin.php?page=" + (currentPage + 1);
            }
        }

        function previousPage() {
            var currentPage = <?php echo $page; ?>;
            if (currentPage > 1) {
                window.location.href = "bookingsadmin.php?page=" + (currentPage - 1);
            }
        }
    </script>
</body>
</html>
