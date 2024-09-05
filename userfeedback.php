<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="userfeedback.css">
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
                <li><a href="#"><span class="material-icons">home</span>Home</a></li>
                <li><a href="bookingsadmin.php"><span class="material-icons">book</span>Booked</a></li>
                <li><a href="checkin.php"><span class="material-icons">check_circle</span>Check In</a></li>
                <li><a href="checkout.php"><span class="material-icons">check_circle_outline</span>Check Out</a></li>
                <li><a href="hotelsform.php"><span class="material-icons">hotel</span>Hotels</a></li>
                <li><a href="roomcateg.php"><span class="material-icons">hotel</span>Rooms</a></li>
                <li><a href="users.php"><span class="material-icons">person</span>Users</a></li>
                <li><a href="userfeedback.php"><span class="material-icons">feedback</span>User Feedback</a></li>
            </ul>
        </aside>
        <div class="container">
            <div class="table-container">
                <form id="searchForm" method="GET" action="userfeedback.php">
                    <input type="text" name="search" placeholder="Search users...">
                    <button class="search" type="submit">Search</button>
                    <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                        <button id="backBtn" class="btn" onclick="window.location.href='userfeedback.php'">Back to All Users</button>
                    <?php endif; ?>
                </form>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            
                            <th>#</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        function checkSession() {
                            if (!isset($_SESSION['user_id'])) {
                                header('Location: Sign in.php');
                                exit();
                            }
                        }

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

                        // Pagination logic
                        $limit = 5; // Show 5 entries per page
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $offset = ($page - 1) * $limit;

                        // Search logic
                        $search = isset($_GET['search']) ? $_GET['search'] : '';
                        if (!empty($search)) {
                            $sql = "SELECT id, name, email, message FROM ContactMessages WHERE 
                                    name LIKE '%$search%' OR 
                                    email LIKE '%$search%'
                                    LIMIT $offset, $limit";
                        } else {
                            $sql = "SELECT id, name, email, message FROM ContactMessages 
                                    LIMIT $offset, $limit";
                        }

                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . $row["name"] . "</td>";
                                echo "<td>" . $row["email"] . "</td>";
                                echo "<td>" . $row["message"] . "</td>";
                                echo '<td><a href="#" class="edit-btn" data-id="' . $row["id"] . '" data-name="' . $row["name"] . '" data-email="' . $row["email"] . '" data-message="' . $row["message"] . '">Edit</a> 
                                <a href="deletemessage.php?id=' . $row["id"] . '" class="delete-btn" onclick="return confirmDelete()">Delete</a>';
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No Messages found</td></tr>";
                        }

                        // Pagination calculations
                        $sql = "SELECT COUNT(id) AS total FROM ContactMessages";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        $total_records = $row['total'];
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
    </main>
    </main>
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this message?');
        }
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
                window.location.href = "userfeedback.php?page=" + (currentPage + 1);
            }
        }

        function previousPage() {
            var currentPage = <?php echo $page; ?>;
            if (currentPage > 1) {
                window.location.href = "userfeedback.php?page=" + (currentPage - 1);
            }
        }
    </script>
</body>
</html>
