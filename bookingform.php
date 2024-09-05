<?php
    require 'bookcont.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Reservation Form</title>
    <link rel="stylesheet" type="text/css" href="bookingform.css">
</head>
<body> 
    <div class="container">
        <h2>Room Reservation Form</h2>
        <form action="bookings.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <div id="fullnameError" class="error"></div>
                <label for="fullname">Fullname:</label>
                <input type="text" id="fullname" name="fullname" required>
            </div>
            <div class="form-group">
                <div id="emailError" class="error"></div>
                <label for="email">Email Address:</label>
                <input type="text" id="email" name="email" required>
            </div>
            <div class="form-group">
                <div id="phoneError" class="error"></div>
                <label for="phone">Phone Number (include country code):</label>
                <input type="text" id="phone" name="phone" placeholder="+1234567890" required>
            </div>
            <div class="form-group">
                <div id="checkinError" class="error"></div>
                <label for="checkin">Check-in Date:</label>
                <input type="date" id="checkin" name="checkin" placeholder="YYYY-MM-DD" required>
            </div>
            <div class="form-group">
                <div id="checkoutError" class="error"></div>
                <label for="checkout">Check-out Date:</label>
                <input type="date" id="checkout" name="checkout" placeholder="YYYY-MM-DD" required>
            </div>
            <div class="form-group">
                <label for="amount">Total Amount:</label>
                <input type="text" id="amount" name="amount" readonly>
            </div>
            <div class="form-group">
                <button type="submit">Submit</button>
                <button type="button" onclick="goBack()">Back</button>
            </div>
        </form>
    </div>
    <script>
        const amountPerDay = <?php echo json_encode($selectedRoomPrice); ?>;

        function calculateAmount() {
            const checkin = new Date(document.getElementById('checkin').value);
            const checkout = new Date(document.getElementById('checkout').value);

            if (checkin && checkout) {
                const timeDiff = checkout - checkin;
                const daysDiff = timeDiff / (1000 * 60 * 60 * 24);
                
                if (daysDiff > 0) {
                    const totalAmount = daysDiff * amountPerDay;
                    document.getElementById('amount').value = totalAmount.toFixed(2);
                } else {
                    document.getElementById('amount').value = '';
                    alert("Check-out date must be later than check-in date");
                }
            } else {
                document.getElementById('amount').value = '';
                alert("Please select both check-in and check-out dates");
            }
        }

        function validateFullname() {
            const fullname = document.getElementById('fullname').value.trim();
            const fullnameError = document.getElementById('fullnameError');
            if (/^[a-zA-Z ]+$/.test(fullname)) {
                fullnameError.textContent = '';
            } else {
                fullnameError.textContent = 'Name should contain only letters';
                fullnameError.style.color = 'red'; // Set error message color
            }
        }

        function validateEmail() {
            const email = document.getElementById('email').value.trim();
            const emailError = document.getElementById('emailError');
            if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                emailError.textContent = '';
            } else {
                emailError.textContent = 'Email should contain @ symbol and be valid';
                emailError.style.color = 'red'; // Set error message color
            }
        }

        function validatePhone() {
            const phone = document.getElementById('phone').value.trim();
            const phoneError = document.getElementById('phoneError');
            if (/^\+\d+$/.test(phone)) {
                phoneError.textContent = '';
            } else {
                phoneError.textContent = 'Phone number should contain only numbers and include country code';
                phoneError.style.color = 'red'; // Set error message color
            }
        }

        function goBack() {
            window.history.back();
        }

        function setMinDate() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('checkin').setAttribute('min', today);
            document.getElementById('checkout').setAttribute('min', today);
        }

        document.getElementById('checkin').addEventListener('change', calculateAmount);
        document.getElementById('checkout').addEventListener('change', calculateAmount);
        document.getElementById('fullname').addEventListener('input', validateFullname);
        document.getElementById('email').addEventListener('input', validateEmail);
        document.getElementById('phone').addEventListener('input', validatePhone);
        document.addEventListener('DOMContentLoaded', setMinDate); // Set the minimum date on page load
    </script>

</body>
</html>
