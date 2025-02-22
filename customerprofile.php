<?php
session_start();

// Assuming you have a database connection established
$servername = "localhost";
$username = "root";
$db_password = ""; // Replace with your actual database password
$dbname = "greatworx";

// Create a connection
$conn = new mysqli($servername, $username, $db_password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the customer user ID from the session
if (isset($_SESSION['customer_email'])) {
    $customerEmail = $_SESSION['customer_email'];

    // Fetch customer details from the database
    $sql = "SELECT Customer.*, User.Email 
            FROM Customer
            JOIN User ON Customer.UserID = User.UserID
            WHERE User.Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $customerEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    $customerDetails = $result->fetch_assoc();
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profile</title>
	<style>
        body {
            font-family: 'Century Gothic';
            margin: 20px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            margin: 10px;
			background-color: #04AA6D;
			border: none;
			color: white;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			cursor: pointer;
			font-family: 'Century Gothic';
        }
    </style>
</head>

<body>
    
    <?php if (isset($customerDetails)) : ?>
        <h1>Customer Profile</h1>
        <button onclick="dashboard()">Dashboard</button>
        <button onclick="profile()">View Profile</button>
        <button onclick="reserveHall()">Reserve</button>
        <button onclick="viewReservations()">Edit Reservation</button>
        <button onclick="logout()">Log out</button>
        <p><strong>Email:</strong> <?php echo $customerDetails['Email']; ?></p>
        <p><strong>Name:</strong> <?php echo $customerDetails['Name']; ?></p>
        <p><strong>Mobile Number:</strong> <?php echo $customerDetails['MobileNumber']; ?></p>
        <button onclick="editprofile()">Edit Profile</button>

        <!-- Add links/buttons for further actions, e.g., edit profile, change password, etc. -->
     
    <?php else : ?>
        <p>Error fetching customer details.</p>
    <?php endif; ?>
    <script>
        function dashboard() {
        // Redirect to the page for reservation
            window.location.href = 'dashboardcustomer.php';
        }
        // JavaScript functions to handle button clicks
        function reserveHall() {
            // Redirect to the page for reservation
            window.location.href = 'reservation.php';
        }

        function viewReservations() {
            // Redirect to the page for viewing reservations
            window.location.href = 'viewreservationcustomer.php';
        }

        function profile(){
            window.location.href = 'customerprofile.php'
        }
        function editprofile(){
            window.location.href = 'updatecustomerprofile.php'
        }
        function logout(){
            window.location.href = 'logincustomer.php'
        }
    </script>
</body>

</html>
