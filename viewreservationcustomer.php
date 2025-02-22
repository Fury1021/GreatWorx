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

// Handle update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $reservationID = $_POST["reservation_id"];
    $updatedEventDescription = $_POST["updated_eventDescription"];
    $updatedEventDate = $_POST["updated_eventDate"];
    $updatedHall = $_POST["updated_hall"];
    $updatedNumberOfDays = $_POST["updated_numberOfDays"];
    $updatedRequest = $_POST["updated_request"];

    // Update reservation details
    $updateSQL = "UPDATE Reservation 
                  SET EventDescription = '$updatedEventDescription',
                      EventDate = '$updatedEventDate',
                      Hall = '$updatedHall',
                      NumberOfDays = $updatedNumberOfDays,
                      Request = '$updatedRequest'
                  WHERE ReservationID = $reservationID";

    if ($conn->query($updateSQL) === TRUE) {
        echo "Reservation details updated successfully!";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Handle delete form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $reservationID = $_POST["reservation_id"];

    // Delete reservation
    $deleteSQL = "DELETE FROM Reservation WHERE ReservationID = $reservationID";

    if ($conn->query($deleteSQL) === TRUE) {
        echo "Reservation deleted successfully!";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
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

        .edit-btn, .delete-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .edit-btn {
            background-color: #4CAF50;
            color: white;
            margin-right: 5px;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
        }

        .update-form {
            display: none;
        }
    </style>
</head>
<body>

<h1>Customer Reservation Details</h1>
    <button onclick="dashboard()">Dashboard</button>
    <button onclick="profile()">View Profile</button>
    <button onclick="reserveHall()">Reserve</button>
    <button onclick="viewReservations()">Edit Reservation</button>
    <button onclick="logout()">Log out</button>
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
            <th>Action</th>
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
        echo "<td>
                <button class='edit-btn' onclick='showUpdateForm(\"updateForm{$row["ReservationID"]}\")'>Edit</button>
                <form method='post' action='' class='update-form' id='updateForm{$row["ReservationID"]}'>
                    <input type='hidden' name='reservation_id' value='{$row["ReservationID"]}'>
                    <label for='updated_eventDescription'>Event Description:</label>
                    <input type='text' name='updated_eventDescription' value='{$row["EventDescription"]}'>
                    <br>
                    <label for='updated_eventDate'>Event Date:</label>
                    <input type='date' name='updated_eventDate' value='{$row["EventDate"]}'>
                    <br>
                    <label for='updated_hall'>Select Hall:</label>
                    <select name='updated_hall'>
                        <option value='A' " . ($row["Hall"] == 'A' ? 'selected' : '') . ">Hall A</option>
                        <option value='B' " . ($row["Hall"] == 'B' ? 'selected' : '') . ">Hall B</option>
                        <option value='C' " . ($row["Hall"] == 'C' ? 'selected' : '') . ">Hall C</option>
                        <option value='D' " . ($row["Hall"] == 'D' ? 'selected' : '') . ">Hall D</option>
                    </select>
                    <br>
                    <label for='updated_numberOfDays'>Number of Days:</label>
                    <input type='number' name='updated_numberOfDays' min='1' value='{$row["NumberOfDays"]}'>
                    <br>
                    <label for='updated_request'>Request:</label>
                    <textarea name='updated_request' rows='4' cols='50'>{$row["Request"]}</textarea>
                    <br>
                    <button type='submit' name='update'>Update</button>
                    <button type='button' onclick='hideUpdateForm(\"updateForm{$row["ReservationID"]}\")'>Cancel</button>
                </form>
                <form method='post' action=''>
                    <input type='hidden' name='reservation_id' value='{$row["ReservationID"]}'>
                    <button type='submit' class='delete-btn' name='delete'>Delete</button>
                </form>
              </td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No reservations found for this customer.";
}
?>

<script>
    function showUpdateForm(formId) {
        document.getElementById(formId).style.display = 'block';
    }

    function hideUpdateForm(formId) {
        document.getElementById(formId).style.display = 'none';
    }

    
    // JavaScript functions to handle button clicks
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

<?php
// Close the database connection
$conn->close();
?>
