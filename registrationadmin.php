<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
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
    <h2>Admin Registration</h2>
    <form action="registrationadmin.php" method="post">
        <label for="admin_email">Email:</label>
        <input type="email" name="admin_email" required><br>

        <label for="admin_password">Password:</label>
        <input type="password" name="admin_password" required><br>

        <input type="submit" name="admin_registration" value="Register">
    </form>
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

// Registration for Administrator
if (isset($_POST['admin_registration'])) {
    $email = sanitizeInput($_POST['admin_email']);
    $password = $_POST['admin_password']; // Note: No hashing (not recommended)

    // Insert admin data
    $adminInsertQuery = "INSERT INTO User (Email, Password, UserType) VALUES ('$email', '$password', 'Administrator')";
    $conn->query($adminInsertQuery);

    echo "Administrator registration successful!";
}

$conn->close();
?>
