<?php
session_start();


// Check if 'customer_id' is set in the session
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$customerID = $_SESSION['customer_id'];

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

// Retrieve customer's reservation details
$sql = "SELECT * FROM Reservation WHERE CustomerID = '$customerID'";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Reservation Details</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>

<h1>Customer Reservation Details</h1>

<?php
if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr>
            <th>ReservationID</th>
            <th>Event Description</th>
            <th>Event Date</th>
            <th>Hall</th>
            <th>Number of Days</th>
            <th>Request</th>
            <th>Package Rate Per Day</th>
            <th>Tax</th>
            <th>Rent</th>
            <th>Down Payment</th>
            <th>Payment Status</th>
            <th>Reservation Status</th>
          </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["ReservationID"] . "</td>";
        echo "<td>" . $row["EventDescription"] . "</td>";
        echo "<td>" . $row["EventDate"] . "</td>";
        echo "<td>" . $row["Hall"] . "</td>";
        echo "<td>" . $row["NumberOfDays"] . "</td>";
        echo "<td>" . $row["Request"] . "</td>";
        echo "<td>$" . $row["PackageRatePerDay"] . "</td>";
        echo "<td>$" . $row["Tax"] . "</td>";
        echo "<td>$" . $row["Rent"] . "</td>";
        echo "<td>$" . $row["DownPayment"] . "</td>";
        echo "<td>" . $row["PaymentStatus"] . "</td>";
        echo "<td>" . $row["ReservationStatus"] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No reservations found for this customer.";
}
?>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
    