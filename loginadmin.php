<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $servername = "localhost";
    $username = "root";
    $db_password = ""; // Rename to avoid conflict with the variable name

    $dbname = "greatworx";

    $connection = mysqli_connect($servername, $username, $db_password, $dbname);

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Use prepared statement to prevent SQL injection
    $query = "SELECT * FROM User WHERE Email = ? AND Password = ? AND UserType = 'Administrator'";
    $stmt = mysqli_prepare($connection, $query);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ss", $email, $password);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        // Admin login successful
        $_SESSION["admin_email"] = $email;
        header("Location: dashboardadmin.php"); // Redirect to admin dashboard
        exit();
    } else {
        $error_message = "Invalid email or password for administrator.";
        // Additional error handling
        echo "Error: " . mysqli_error($connection);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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
    <h2>Admin Login</h2>
    <?php if (isset($error_message)) { ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php } ?>

    <form method="post" action="">
        <label>Email:</label>
        <input type="email" name="email" required><br>

        <label>Password:</label>
        <input type="password" name="password" required><br>

        <button type="submit">Login</button>
        <button onclick="regadmin()">Sign Up</button>
        <button onclick="change()">Change User</button>
    </form>
    <script>
        function regadmin() {
            // Redirect to the page for viewing reservations
            window.location.href = 'registrationadmin.php';
        }
        function change() {
            // Redirect to the page for viewing reservations
            window.location.href = 'choose.php';
        }
    </script>
</body>
</html>
