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
    <title>User Management</title>
    <link rel="stylesheet" href="userzs.css">
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
        <div class="container">
            <div class="table-container">
                <button id="newUserBtn" class="btn btn-primary mb-2">+ New User</button>
                <form id="searchForm" method="GET" action="users.php">
                    <input type="text" name="search" placeholder="Search users...">
                    <button class="search" type="submit">Search</button>
                    <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                        <button id="backBtn" class="btn" onclick="window.location.href='users.php'">Back to All Users</button>
                    <?php endif; ?>
                </form>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            
                            <th>#</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Password</th>
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

                        // Pagination logic
                        $limit = 5; // Show 5 entries per page
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $offset = ($page - 1) * $limit;

                        // Search logic
                        $search = isset($_GET['search']) ? $_GET['search'] : '';
                        if (!empty($search)) {
                            $sql = "SELECT id, username, email, password FROM registrations WHERE 
                                    username LIKE '%$search%' OR 
                                    email LIKE '%$search%'
                                    LIMIT $offset, $limit";
                        } else {
                            $sql = "SELECT id, username, email, password FROM registrations 
                                    LIMIT $offset, $limit";
                        }

                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . $row["username"] . "</td>";
                                echo "<td>" . $row["email"] . "</td>";
                                echo "<td>" . $row["password"] . "</td>";
                                echo '<td><a href="#" class="edit-btn" data-id="' . $row["id"] . '" data-username="' . $row["username"] . '" data-email="' . $row["email"] .  '">Edit</a> 
                                <a href="deleteuser.php?id=' . $row["id"] . '" class="delete-btn" onclick="return confirmDelete()">Delete</a>';
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No users found</td></tr>";
                        }

                        // Pagination calculations
                        $sql = "SELECT COUNT(id) AS total FROM registrations";
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
        <!--Add new user modal-->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="userForm" action="adduser.php" method="POST">
                <h2>New User</h2>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <button type="submit">Save</button>
                    <button type="button" class="cancel">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!--Edit User Modal-->
     <div id="editUserModal" class="modal">
                <div class="usermodal-content">
                    <span class="close">&times;</span>
                    <h2>Edit User</h2>
                    <form action="edituser.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="edit-user-id">

                        <label for="edit-username">Username</label>
                        <input type="text" id="edit-username" name="username" required>

                        <label for="edit-email">Email</label>
                        <input type="text" id="edit-email" name="email" required>

                        <label for="edit-password">Password</label>
                        <input type="text" id="edit-password" name="password" required>
                        <div class="button-container">
                        <button class="update-button" type="submit">Update</button>
                        <button class="cancel-button" type="reset">Cancel</button>
                        </div>
                    
                    </form>
                </div>
            </div>

    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this user?');
        }

        
    // Add User Modal
    // Get the modal
    var addUserModal = document.getElementById("userModal");

    // Get the button that opens the modal
    var addUserBtn = document.getElementById("newUserBtn");

    // Get the <span> element that closes the modal
    var addUserCloseSpan = document.getElementsByClassName("close")[0];

    // Get the cancel button that closes the modal
    var addUserCancelBtn = document.querySelector(".cancel");

    // When the user clicks the button, open the modal 
    addUserBtn.onclick = function() {
        addUserModal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    addUserCloseSpan.onclick = function() {
        addUserModal.style.display = "none";
    }

    // When the user clicks the cancel button, close the modal
    addUserCancelBtn.onclick = function() {
        addUserModal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == addUserModal) {
            addUserModal.style.display = "none";
        }
    }

    // Edit User Modal
    // Get the modal
    var editUserModal = document.getElementById('editUserModal');

    // Get the <span> element that closes the modal
    var editUserCloseSpan = document.getElementsByClassName('close')[1];

    // Open the modal with user data
    function openEditUserModal(user) {
        document.getElementById('edit-user-id').value = user.id;
        document.getElementById('edit-username').value = user.username;
        document.getElementById('edit-email').value = user.email;
        document.getElementById('edit-password').value = user.password;

        editUserModal.style.display = 'block';
    }

    // Close the modal
    function closeEditUserModal() {
        editUserModal.style.display = 'none';
    }

    // When the user clicks on <span> (x), close the modal
    editUserCloseSpan.onclick = function() {
        closeEditUserModal();
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == editUserModal) {
            modal.style.display = "block";
            closeEditUserModal();
        }
    }
    


    // Add event listeners to edit buttons
    const editButtons = document.getElementsByClassName('edit-btn');
    for (let button of editButtons) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const userId = this.getAttribute('data-id');
            const userUsername = this.getAttribute('data-username');
            const userEmail = this.getAttribute('data-email');
            const userPassword = this.getAttribute('data-password');
            const user = {
                id: userId,
                username: userUsername,
                email: userEmail,
                password: userPassword
            };
            openEditUserModal(user);
        });
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
                window.location.href = "users.php?page=" + (currentPage + 1);
            }
        }

        function previousPage() {
            var currentPage = <?php echo $page; ?>;
            if (currentPage > 1) {
                window.location.href = "users.php?page=" + (currentPage - 1);
            }
        }

    </script>
    
</body>
</html>
