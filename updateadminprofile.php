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

    // Fetch the admin's user ID from the database
    $sql = "SELECT UserID FROM User WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $adminEmail);
    $stmt->execute();
    $stmt->bind_result($adminUserID);
    $stmt->fetch();
    $stmt->close();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newEmail = $_POST["new_email"];
    $newPassword = $_POST["new_password"];

    // Update admin details in the database
    $sql = "UPDATE User SET Email = ?, Password = ? WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $newEmail, $newPassword, $adminUserID);
    $stmt->execute();
    $stmt->close();

    // Redirect to the admin profile page after updating
    header("Location: adminprofile.php");
    exit();
}

// Fetch current admin details from the database
$sql = "SELECT Email, Password FROM User WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $adminUserID);
$stmt->execute();
$stmt->bind_result($email, $password);
$stmt->fetch();
$stmt->close();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin Profile</title>
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
    <h1>Edit Admin Profile</h1>
    <button onclick="dashboard()">Dashboard</button>
    <button onclick="profile()">View Profile</button>
    <button onclick="manageres()">Manage Reservation Status</button>
    <button onclick="managecus()">Manage Customer Details</button>
    <button onclick="logout()">Log out</button>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="new_email">New Email:</label>
        <input type="email" id="new_email" name="new_email" value="<?php echo $email; ?>" required><br>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" value="<?php echo $password; ?>" required><br>

        <input type="submit" value="Save Changes">
    </form>
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
</script>
</body>

</html>
