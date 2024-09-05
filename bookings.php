<?php

require_once "bookcont.php";

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

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $amount = $conn->real_escape_string($_POST['amount']); 
    
    // Validate and format dates
    $checkin_date = date('Y-m-d', strtotime($checkin));
    $checkout_date = date('Y-m-d', strtotime($checkout));
    
    $hotelname = $_SESSION['selected_hotelname'];
    $hotellocation = $_SESSION['selected_hotellocation'];
    $roomcategory = $_SESSION['selected_roomcategory'];
    $roomprice = $_SESSION['selected_roomprice'];

    $mpesaphone = ltrim($phone, '+');
    $totalamount = (int)$amount;
    $status = 'pending';
    $bookingdate = date('Y-m-d');
    $userid = $_SESSION['user_id'];
    
    // Check if dates are valid
    if ($checkin_date === false || $checkout_date === false) {
        echo "Invalid date format. Please enter dates in mm/dd/yyyy format.";
        exit;
    }

    // M-Pesa API credentials
    $consumerKey = 'rjxU6ufBFSuY5l8quZEeKmoy4UaGb56UWSXiWbTKniblRWSJ';
    $consumerSecret = 'apQeZ2HoILxAXYUE462AgUig16XNnnDUzQ5Rj0Afw6jphuUpAoh8ddnstBrlupBe';

    // Get the OAuth token
    $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $credentials = base64_encode($consumerKey . ':' . $consumerSecret);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $credentials));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($curl);
    if ($response === false) {
        die('Curl error: ' . curl_error($curl));
    }

    $result = json_decode($response);
    if (isset($result->access_token)) {
        $accessToken = $result->access_token;
    } else {
        die('Failed to obtain access token. Response: ' . $response);
    }

    curl_close($curl);

    // echo 'Access Token: ' . $accessToken . '<br>';

    // Prepare STK Push request
    $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
    $timestamp = date('YmdHis');
    $shortcode = '174379';
    $lipaNaMpesaOnlinePasskey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
    $password = base64_encode($shortcode . $lipaNaMpesaOnlinePasskey . $timestamp);

    $curl_post_data = array(
        'BusinessShortCode' => $shortcode,
        'Password' => $password,
        'Timestamp' => $timestamp,
        'TransactionType' => 'CustomerPayBillOnline',
        'Amount' => $totalamount,
        'PartyA' => $mpesaphone,
        'PartyB' => $shortcode,
        'PhoneNumber' => $mpesaphone,
        'CallBackURL' => 'https://callback.php',  
        'AccountReference' => 'Book',
        'TransactionDesc' => 'Payment'
    );

    $data_string = json_encode($curl_post_data);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessToken
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

    $response = curl_exec($curl);

    if ($response === false) {
        die('Curl error: ' . curl_error($curl));
    } else {
        $result = json_decode($response, true);

        // Check if the response contains an error
        if (isset($result['errorMessage'])) {
            die('STK Push error: ' . $result['errorMessage']);
        } else {
            // echo 'STK Push Response: ' . $response;

            // Insert record into database
            $stmt = $conn->prepare("INSERT INTO bookings (userid, fullname, email, phone, checkin, checkout, amount, hotelname, hotellocation, roomcategory, roomprice, bookingdate, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                echo "Error preparing statement: " . $conn->error;
                exit;
            }

            $stmt->bind_param("issssssssssss", $userid, $fullname, $email, $phone, $checkin_date, $checkout_date, $amount, $hotelname, $hotellocation, $roomcategory, $roomprice, $bookingdate, $status);

            if ($stmt->execute()) {
                // Redirect to success page
                header("Location: mybookings.php");
                echo "Booking successful";
                exit;
            } else {
                echo "Error inserting record into database: " . $stmt->error;
            }
            $stmt->close();
        }
    }

    curl_close($curl);
}

// Close the database connection
$conn->close();

?>
