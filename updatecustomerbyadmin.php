<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "greatworx";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if customerID is set in the URL parameters
if (!isset($_GET['customerID'])) {
    header("Location: customer_details.php"); // Redirect if customerID is not provided
    exit();
}

$customerID = $_GET['customerID'];

// Fetch customer details based on customerID
$sql = "SELECT * FROM Customer INNER JOIN User ON Customer.UserID = User.UserID WHERE Customer.CustomerID = '$customerID'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Customer not found.";
    exit();
}

$row = $result->fetch_assoc();

// Handle update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $updatedName = $_POST["updated_name"];
    $updatedEmail = $_POST["updated_email"];
    $updatedMobileNumber = $_POST["updated_mobileNumber"];

    // Update customer details
    $updateSQL = "UPDATE Customer 
                  SET Name = '$updatedName',
                      MobileNumber = '$updatedMobileNumber'
                  WHERE CustomerID = $customerID";

    if ($conn->query($updateSQL) === TRUE) {
        echo "Customer details updated successfully!";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Customer Details</title>
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

    <h2>Update Customer Details</h2>
    <button onclick="dashboard()">Dashboard</button>
    <button onclick="profile()">View Profile</button>
    <button onclick="manageres()">Manage Reservation Status</button>
    <button onclick="managecus()">Manage Customer Details</button>
    <button onclick="logout()">Log out</button>
    <form method="post" action="viewcustomeronlybyadmin.php">
        <label for="updated_name">Name:</label>
        <input type="text" name="updated_name" value="<?php echo $row["Name"]; ?>" required><br>

        <label for="updated_email">Email:</label>
        <input type="email" name="updated_email" value="<?php echo $row["Email"]; ?>" required><br>

        <label for="updated_mobileNumber">Mobile Number:</label>
        <input type="text" name="updated_mobileNumber" value="<?php echo $row["MobileNumber"]; ?>" required><br>

        <button type="submit">Update Customer</button>
        
    </form>

    <script>function manageres() {
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

<?php
// Close the database connection
$conn->close();
?>
