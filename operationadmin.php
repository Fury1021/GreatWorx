<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "greatworx";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getAllReservationsWithSearch($searchTerm = '')
{
    global $conn;
    
    // Use mysqli_real_escape_string to prevent SQL injection
    $searchTerm = mysqli_real_escape_string($conn, $searchTerm);
    
    $sql = "SELECT * FROM Reservation WHERE 
            EventDescription LIKE '%$searchTerm%' OR 
            EventDate LIKE '%$searchTerm%' OR 
            Hall LIKE '%$searchTerm%' OR 
            NumberOfDays LIKE '%$searchTerm%' OR 
            Request LIKE '%$searchTerm%' OR 
            PackageRatePerDay LIKE '%$searchTerm%' OR 
            Tax LIKE '%$searchTerm%' OR 
            Rent LIKE '%$searchTerm%' OR 
            DownPayment LIKE '%$searchTerm%' OR 
            PaymentStatus LIKE '%$searchTerm%' OR 
            ReservationStatus LIKE '%$searchTerm%'";
    
    $result = $conn->query($sql);

    $reservations = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $reservations[] = $row;
        }
    }

    return $reservations;
}

function updateReservation($reservationID, $field, $value)
{
    global $conn;
    $sql = "UPDATE Reservation SET $field='$value' WHERE ReservationID=$reservationID";
    $conn->query($sql);
}

function closeConnection()
{
    global $conn;
    $conn->close();
}
?>
