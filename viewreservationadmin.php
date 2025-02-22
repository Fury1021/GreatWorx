<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reservations</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h1>View Reservations</h1>

<?php
include 'operationadmin.php';

// Initialize the search term to an empty string
$searchTerm = '';

// Check if the search parameter is provided in the URL
if (isset($_GET['search'])) {
    // Use mysqli_real_escape_string to prevent SQL injection
    $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);
}

$reservations = getAllReservationsWithSearch($searchTerm);

if (!empty($reservations)) {
    echo "<table>";
    echo "<tr><th>ReservationID</th><th>EventDescription</th><th>EventDate</th><th>Hall</th><th>NumberOfDays</th><th>Request</th><th>PackageRatePerDay</th><th>Tax</th><th>Rent</th><th>DownPayment</th><th>PaymentStatus</th><th>ReservationStatus</th></tr>";

    foreach ($reservations as $row) {
        echo "<tr>";
        echo "<td>{$row['ReservationID']}</td>";
        echo "<td>{$row['EventDescription']}</td>";
        echo "<td>{$row['EventDate']}</td>";
        echo "<td>{$row['Hall']}</td>";
        echo "<td>{$row['NumberOfDays']}</td>";
        echo "<td>{$row['Request']}</td>";
        echo "<td>{$row['PackageRatePerDay']}</td>";
        echo "<td>{$row['Tax']}</td>";
        echo "<td>{$row['Rent']}</td>";
        echo "<td>{$row['DownPayment']}</td>";
        echo "<td>{$row['PaymentStatus']}</td>";
        echo "<td>{$row['ReservationStatus']}</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No reservations found.";
}

closeConnection();
?>

</body>
</html>
