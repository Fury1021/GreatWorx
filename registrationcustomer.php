<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration</title>
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
    <h2>Customer Registration</h2>
    <form action="registrationcustomer.php" method="post">
        <label for="customer_email">Email:</label>
        <input type="email" name="customer_email" required><br>

        <label for="customer_password">Password:</label>
        <input type="password" name="customer_password" required><br>

        <label for="customer_name">Name:</label>
        <input type="text" name="customer_name" required><br>

        <label for="customer_mobile">Mobile Number:</label>
        <input type="text" name="customer_mobile" required><br>

        <input type="submit" name="customer_registration" value="Register">
    </form>
    <button onclick="cancel()">Cancel</button>
    <script>
    function cancel() {
            // Redirect to the page for viewing reservations
            window.location.href = 'logincustomer.php';
        }
    </script>
</body>
</html>


<?php
// Assuming you have a database connection established
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "greatworx";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize and validate input
function sanitizeInput($input) {
    return htmlspecialchars(stripslashes(trim($input)));
}

// Registration for Customer
if (isset($_POST['customer_registration'])) {
    $email = sanitizeInput($_POST['customer_email']);
    $password = $_POST['customer_password']; // Note: No hashing (not recommended)
    $name = sanitizeInput($_POST['customer_name']);
    $mobileNumber = sanitizeInput($_POST['customer_mobile']);

    // Check if the email already exists
    $checkEmailQuery = "SELECT UserID FROM User WHERE Email = '$email'";
    $result = $conn->query($checkEmailQuery);

    if ($result->num_rows > 0) {
        echo "Email already exists. Please choose a different email.";
    } else {
        // Insert user data
        $userInsertQuery = "INSERT INTO User (Email, Password, UserType) VALUES ('$email', '$password', 'Customer')";
        $conn->query($userInsertQuery);

        $userID = $conn->insert_id; // Get the last inserted ID

        // Insert customer data
        $customerInsertQuery = "INSERT INTO Customer (UserID, Name, MobileNumber) VALUES ('$userID', '$name', '$mobileNumber')";
        $conn->query($customerInsertQuery);

        echo "Customer registration successful!";
        header('Location: logincustomer.php');
    }
}

$conn->close();
?>

