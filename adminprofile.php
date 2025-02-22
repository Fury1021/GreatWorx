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

// Get the admin user ID from the session
if (isset($_SESSION['admin_email'])) {
    $adminEmail = $_SESSION['admin_email'];

    // Fetch admin details from the database
    $sql = "SELECT Email, Password FROM User WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $adminEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    $adminDetails = $result->fetch_assoc();
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
    <title>Admin Profile</title>
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
    <?php if (isset($adminDetails)) : ?>
        <h1>Admin Profile</h1>
        <button onclick="dashboard()">Dashboard</button>
        <button onclick="profile()">View Profile</button>
        <button onclick="manageres()">Manage Reservation Status</button>
        <button onclick="managecus()">Manage Customer Details</button>
        <button onclick="logout()">Log out</button>
        <p><strong>Email:</strong> <?php echo $adminDetails['Email']; ?></p>
        <p><strong>Password:</strong> <?php echo $adminDetails['Password']; ?></p>

        <!-- Add links/buttons for further actions, e.g., edit profile, change password, etc. -->
        <button onclick="CES()">Change Email and Password</button>
    <?php else : ?>
        <p>Error fetching admin details.</p>
    <?php endif; ?>
    <script>
  function manageres() {
        // Redirect to the page for viewing all reservations
        window.location.href = 'updatereservationbyadmin.php';
    }

    function profile() {
        window.location.href = 'adminprofile.php';
    }

    function managecus() {
        window.location.href = 'viewcustomeronlybyadmin.php';
    }
    function logout() {
        window.location.href = 'loginadmin.php';
    }

    function dashboard() {
        // Redirect to the page for dashboard
        window.location.href = 'dashboardadmin.php';
    }
    function CES() {
        // Redirect to the page for dashboard
        window.location.href = 'updateadminprofile.php';
    }
    </script>
</body>

</html>
