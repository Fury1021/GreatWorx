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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saveChanges'])) {
    $newName = $_POST['newName'];
    $newMobileNumber = $_POST['newMobileNumber'];
    $newEmail = $_POST['newEmail'];
    $newPassword = $_POST['newPassword']; // Note: Insecure, consider hashing

    // Update customer details in the database
    $updateProfileSql = "UPDATE Customer
                         SET Name = ?, MobileNumber = ?
                         WHERE CustomerID = ?";
    $updateProfileStmt = $conn->prepare($updateProfileSql);
    $updateProfileStmt->bind_param("ssi", $newName, $newMobileNumber, $customerDetails['CustomerID']);
    $updateProfileStmt->execute();
    $updateProfileStmt->close();

    // Update email in the User table
    $updateEmailSql = "UPDATE User SET Email = ? WHERE UserID = ?";
    $updateEmailStmt = $conn->prepare($updateEmailSql);
    $updateEmailStmt->bind_param("si", $newEmail, $customerDetails['UserID']);
    $updateEmailStmt->execute();
    $updateEmailStmt->close();

    // Update password in the database (insecure, consider hashing)
    $updatePasswordSql = "UPDATE User SET Password = ? WHERE UserID = ?";
    $updatePasswordStmt = $conn->prepare($updatePasswordSql);
    $updatePasswordStmt->bind_param("si", $newPassword, $customerDetails['UserID']);
    $updatePasswordStmt->execute();
    $updatePasswordStmt->close();

    // Refresh customer details after update
    $customerDetails['Name'] = $newName;
    $customerDetails['MobileNumber'] = $newMobileNumber;
    $customerDetails['Email'] = $newEmail;

    header('Location: logincustomer.php');
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Customer</title>
	<style>
        body {
		font-family: 'Century Gothic';
		text-align: center;
		}

		h2{ 
		text-align: center;
		font-size: 50px;
		}

		form {
		margin: 0 auto;
		width: 400px;
		padding: 1em;
		border: 2px solid #CCC;
		border-radius: 1em;
		font-size: 20px;
		}

		label {
		display: inline-block;
		width: 170px;
		text-align: justify;
		}
			
		button {
		background-color: #04AA6D;
		border: none;
		color: white;
		text-align: center;
		text-decoration: none;
		display: inline-block;
		font-size: 14px;
		padding: 8px 10px;
        margin: 5px;
        cursor: pointer;
		}
    </style>
</head>

<body>
    <?php if (isset($customerDetails)) : ?>
        <h1>Update Customer</h1>
        <button onclick="dashboard()">Dashboard</button>
        <button onclick="profile()">View Profile</button>
        <button onclick="reserveHall()">Reserve</button>
        <button onclick="viewReservations()">Edit Reservation</button>
        <button onclick="logout()">Log out</button>
        <!-- Customer Profile Update Form -->
        <form method="post" action="">
            <h2>Edit Profile</h2>
            <label for="newName">New Name:</label>
            <input type="text" id="newName" name="newName" value="<?php echo $customerDetails['Name']; ?>" required>
            <br>
            <label for="newMobileNumber">New Mobile Number:</label>
            <input type="text" id="newMobileNumber" name="newMobileNumber" value="<?php echo $customerDetails['MobileNumber']; ?>" required>
            <br>
            <label for="newEmail">New Email:</label>
            <input type="email" id="newEmail" name="newEmail" value="<?php echo $customerDetails['Email']; ?>" required>
            <br>
            <label for="newPassword">New Password:</label>
            <input type="password" id="newPassword" name="newPassword" required>
            <br>
            <input type="submit" name="saveChanges" value="Save Changes">
        </form>
    <?php else : ?>
        <p>Error fetching customer details.</p>
    <?php endif; ?>
    <script>
        // JavaScript functions to handle button clicks
        function dashboard() {
            // Redirect to the page for reservation
            window.location.href = 'dashboardcustomer.php';
        }
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
        function logout(){
            window.location.href = 'logincustomer.php'
        }
    </script>
</body>

</html>
