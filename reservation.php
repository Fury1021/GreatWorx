<?php
session_start();


// Connect to your database (replace with actual database connection logic)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "greatworx";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $eventDescription = $_POST["eventDescription"];
    $eventDate = $_POST["eventDate"];
    $hall = $_POST["hall"];
    $numberOfDays = $_POST["numberOfDays"];
    $request = $_POST["request"];

    // Retrieve package rate per day based on the selected hall
    $packageRates = [
        'A' => 1000.00,
        'B' => 1500.00,
        'C' => 2000.00,
        'D' => 3000.00
    ];
    $packageRatePerDay = $packageRates[$hall];

    // Calculate rent, tax, and down payment
    $tax = 0.08 * $packageRatePerDay * $numberOfDays;
    $rent = $packageRatePerDay * $numberOfDays + $tax;
    $downPayment = 0.5 * $rent;

    // Retrieve payment and reservation status
    $paymentStatus = $_POST["paymentStatus"];
    $reservationStatus = $_POST["reservationStatus"];

    // Check if 'customer_id' is set in the session
    if (!isset($_SESSION['customer_id'])) {
        echo "Error: CustomerID is not set in the session.";
        exit();
    }

    // Retrieve customerID from the session
    // Check if 'customer_id' is set in the session
if (!isset($_SESSION['customer_id'])) {
    echo "Error: CustomerID is not set in the session.";
    exit();
}

// Retrieve customerID from the session
    $customerID = $_SESSION['customer_id'];

    // Check if customer_id exists in the customer table
    $checkCustomerQuery = "SELECT * FROM Customer WHERE CustomerID = '$customerID'";
    $result = $conn->query($checkCustomerQuery);

    if ($result->num_rows === 0) {
        echo "Error: CustomerID does not exist in the Customer table.";
        exit();
    }

    // Check if the corresponding UserID exists in the User table
    $checkUserQuery = "SELECT * FROM User WHERE UserID = (SELECT UserID FROM Customer WHERE CustomerID = '$customerID')";
    $resultUser = $conn->query($checkUserQuery);

    if ($resultUser->num_rows === 0) {
        echo "Error: Corresponding UserID does not exist in the User table.";
        exit();
    }

    // Insert data into the database with customerID
    $sql = "INSERT INTO Reservation (CustomerID, EventDescription, EventDate, Hall, NumberOfDays, Request, PackageRatePerDay, Tax, Rent, DownPayment, PaymentStatus, ReservationStatus)
            VALUES ('$customerID', '$eventDescription', '$eventDate', '$hall', $numberOfDays, '$request', $packageRatePerDay, $tax, $rent, $downPayment, '$paymentStatus', '$reservationStatus')";

    if ($conn->query($sql) === TRUE) {
        echo "Reservation successfully submitted!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Form</title>
	<style>
        body {
            font-family: 'Century Gothic';
            margin: 20px;
			text-align: center;
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

<h1>Reservation Form</h1>
    <button onclick="dashboard()">Dashboard</button>
    <button onclick="profile()">View Profile</button>
    <button onclick="reserveHall()">Reserve</button>
    <button onclick="viewReservations()">Edit Reservation</button>
    <button onclick="logout()">Log out</button>

<form action="reservation.php" method="post">
    <!-- Event Details -->
    <label for="eventDescription">Event Description:</label>
    <input type="text" id="eventDescription" name="eventDescription" required><br>

    <label for="eventDate">Event Date:</label>
    <input type="date" id="eventDate" name="eventDate" required><br>

    <!-- Hall Selection -->
    <label for="hall">Select Hall:</label>
    <select id="hall" name="hall" required>
        <option value="A">Hall A</option>
        <option value="B">Hall B</option>
        <option value="C">Hall C</option>
        <option value="D">Hall D</option>
    </select><br>

    <!-- Reservation Details -->
    <label for="numberOfDays">Number of Days:</label>
    <input type="number" id="numberOfDays" name="numberOfDays" min="1" required><br>

    <label for="request">Request:</label>
    <textarea id="request" name="request" rows="4" cols="50"></textarea><br>

    <!-- Computed Values (will be filled by PHP) -->
    <p>Computed Values:</p>
    <p id="computedRent">Rent: $0.00</p>
    <p id="computedTax">Tax (8%): $0.00</p>
    <p id="computedDownPayment">Down Payment (50%): $0.00</p>

    <!-- Hidden Fields for Initial Status -->
    <input type="hidden" name="paymentStatus" value="Not Paid">
    <input type="hidden" name="reservationStatus" value="For Processing">

    <!-- Submit Button -->
    <input type="submit" value="Reserve Hall">
</form>
<a href="viewreservationcustomer.php"><button>View Records</button></a>

<script>
    // Function to update computed values
    function updateComputedValues() {
        var numberOfDays = document.getElementById("numberOfDays").value;
        var hall = document.getElementById("hall").value;

        var packageRates = {
            'A': 1000.00,
            'B': 1500.00,
            'C': 2000.00,
            'D': 3000.00
        };

        var packageRatePerDay = packageRates[hall];
        var tax = 0.08 * packageRatePerDay * numberOfDays;
        var rent = packageRatePerDay * numberOfDays + tax;
        var downPayment = 0.5 * rent;

        // Update computed values on the form
        document.getElementById("computedRent").innerText = "Rent: $" + rent.toFixed(2);
        document.getElementById("computedTax").innerText = "Tax (8%): $" + tax.toFixed(2);
        document.getElementById("computedDownPayment").innerText = "Down Payment (50%): $" + downPayment.toFixed(2);


        
    }

    // Attach the function to the change event of relevant form fields
    document.getElementById("numberOfDays").addEventListener("change", updateComputedValues);
    document.getElementById("hall").addEventListener("change", updateComputedValues);

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
        function dashboard() {
        // Redirect to the page for reservation
            window.location.href = 'dashboardcustomer.php';
        }
</script>

</body>
</html>

