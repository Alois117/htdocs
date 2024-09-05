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
    <link rel="stylesheet" href="roomcateg.css">
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
                <!-- Hotel Form -->
                <div class="hotelform-container">
                    <h2>Add Hotel</h2>
                    <form action="addhotel.php" method="post" enctype="multipart/form-data">
                        <label for="name">Hotel Name</label>
                        <input type="text" id="name" name="name" required>

                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" required>

                        <label for="image">Image</label>
                        <input type="file" id="image" name="image" required>

                        <button type="submit">Save</button>
                        <button type="reset">Cancel</button>
                    </form>
                </div>

                <!-- Hotel Table -->
                <div class="table-container">
                <form id="searchForm" method="GET" action="hotelsform.php">
                    <input type="text" name="search" placeholder="Search rooms...">
                    <button class="search" type="submit">Search</button>
                    <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                        <button id="backBtn" class="btn" onclick="window.location.href='hotelsform.php'">Back to All Rooms</button>
                    <?php endif; ?>
                </form>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Img</th>
                                <th>Hotel</th>
                                <th>Location</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
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
                            $sql = "SELECT id, image, name, location FROM hotels WHERE 
                                    name LIKE '%$search%' OR 
                                    location LIKE '%$search%'
                                    LIMIT $offset, $limit";
                                     } else {
                                        $sql = "SELECT id, image, name, location FROM hotels 
                                                LIMIT $offset, $limit";
                                    }


                          
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                $counter = 1;
                                while($row = $result->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . $counter++ . '</td>';
                                    echo '<td><img src="' . $row["image"] . '" alt="' . '" width="100"></td>';
                                    echo '<td>Name: ' . $row["name"] . '</td>';
                                    echo '<td>'. $row['location'] . '</td>';
                                    echo '<td><a href="#" class="edit-btn" data-id="' . $row["id"] . '" data-image="' . $row["image"] . '" data-name="' . $row["name"] . '" data-location="' . $row["location"] . '">Edit</a> 
                                    <a href="deletehotel.php?id=' . $row["id"] . '" class="delete-btn" onclick="return confirmDelete()">Delete</a>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="4">No hotels available</td></tr>';
                            }

                             // Pagination calculations
                        $sql = "SELECT COUNT(id) AS total FROM hotels";
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
        </div>
    </main>
                        <!--Edit Hotel Modal-->
            <div id="editHotelModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Edit Hotel</h2>
                    <form action="edithotel.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="edit-hotel-id">

                        <label for="edit-name">Hotel Name</label>
                        <input type="text" id="edit-name" name="name" required>

                        <label for="edit-location">Location</label>
                        <input type="text" id="edit-location" name="location" required>

                        <label for="edit-image">Image</label>
                        <input type="file" id="edit-image" name="image">
                        <img id="edit-current-image" src="" alt="Hotel Image" width="100">
                        <div class="button-container">
                        <button class="update-button" type="submit">Update</button>
                        <button class="cancel-button" type="reset">Cancel</button>
                        </div>
                    
                    </form>
                </div>
            </div>
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this hotel?');
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

         // Modal JavaScript
         const modal = document.getElementById('editHotelModal');
        const span = document.getElementsByClassName('close')[0];

        function openModal(hotel) {
            document.getElementById('edit-hotel-id').value = hotel.id;
            document.getElementById('edit-name').value = hotel.name;
            document.getElementById('edit-location').value = hotel.location;
            document.getElementById('edit-current-image').src = hotel.image;

            modal.style.display = 'block';
        }

        function closeModal() {
            modal.style.display = 'none';
        }

        span.onclick = function() {
            closeModal();
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "block";
            }}

        const editButtons = document.getElementsByClassName('edit-btn');
        for (let button of editButtons) {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const hotelId = this.getAttribute('data-id');
                const hotelname = this.getAttribute('data-name');
                const hotellocation = this.getAttribute('data-location');
                const hotelImage = this.getAttribute('data-image');
                const hotel = {
                    id: hotelId,
                    name: hotelname,
                    location: hotellocation,
                    image: hotelImage
                };
                openModal(hotel);
            });
        }


        function nextPage() {
            var currentPage = <?php echo $page; ?>;
            var totalPages = <?php echo $total_pages; ?>;
            if (currentPage < totalPages) {
                window.location.href = "hotelsform.php?page=" + (currentPage + 1);
            }
        }

        function previousPage() {
            var currentPage = <?php echo $page; ?>;
            if (currentPage > 1) {
                window.location.href = "hotelsform.php?page=" + (currentPage - 1);
            }
        }
    </script>
</body>
</html>
