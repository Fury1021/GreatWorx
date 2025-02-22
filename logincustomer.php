<?php

session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $userPassword = $_POST["password"]; // Use a different variable name

    $servername = "localhost";
    $username = "root";
    $dbPassword = ""; // Use a different variable name for the database connection password
    $dbname = "greatworx";

    $connection = mysqli_connect($servername, $username, $dbPassword, $dbname);

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT CustomerID FROM User JOIN Customer ON User.UserID = Customer.UserID WHERE Email = '$email' AND Password = '$userPassword' AND UserType = 'Customer'";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Customer login successful
        $row = mysqli_fetch_assoc($result);
        $_SESSION["customer_id"] = $row["CustomerID"];
        $_SESSION["customer_email"] = $email;
        header("Location: dashboardcustomer.php"); // Redirect to customer dashboard
        exit();
    } else {
        $error_message = "Invalid email or password for customer.";
    }

    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login</title>
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
		width: 110px;
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
    <h2>Customer Login</h2>
    <?php if (isset($error_message)) { ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php } ?>

    <form method="post" action="">
        <label>Email:</label>
        <input type="email" name="email" required><br>

        <label>Password:</label>
        <input type="password" name="password" required><br>

        <button type="submit">Login</button>
        <button onclick="regcus()">Sign Up</button>
        <button onclick="change()">Change User</button>
    </form>

    <script>
        function regcus() {
            // Redirect to the page for viewing reservations
            window.location.href = 'registrationcustomer.php';
        }
        function change() {
            // Redirect to the page for viewing reservations
            window.location.href = 'choose.php';
        }
    </script>
    
</body>
</html>
