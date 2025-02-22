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
    // ... (unchanged)
}

// Handle delete form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    // ... (unchanged)
}

// Retrieve all reservation details with search term
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM Reservation 
        WHERE EventDescription LIKE '%$searchTerm%' 
        OR EventDate LIKE '%$searchTerm%' 
        OR Hall LIKE '%$searchTerm%' 
        OR NumberOfDays LIKE '%$searchTerm%' 
        OR Request LIKE '%$searchTerm%' 
        OR PaymentStatus LIKE '%$searchTerm%' 
        OR ReservationStatus LIKE '%$searchTerm%'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Reservations</title>
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

        input[type="text"] {
            padding: 5px;
            font-size: 14px;
        }

        input[type="submit"] {
            padding: 5px 10px;
            font-size: 16px;
            background-color: #04AA6D;
            border: none;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h1>All Reservations</h1>
<button onclick="dashboard()">Dashboard</button>
<button onclick="profile()">View Profile</button>
<button onclick="manageres()">Manage Reservation Status</button>
<button onclick="managecus()">Manage Customer Details</button>
<button onclick="logout()">Log out</button>

<div>
    <form method="get" action="">
        <label for="search">Search:</label>
        <input type="text" name="search" id="search" value="<?= $searchTerm ?>">
        <input type="submit" value="Search">
    </form>
</div>

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
                    <label for='updated_paymentStatus'>Payment Status:</label>
                    <select name='updated_paymentStatus'>
                        <option value='Not Paid' " . ($row["PaymentStatus"] == 'Not Paid' ? 'selected' : '') . ">Not Paid</option>
                        <option value='Paid' " . ($row["PaymentStatus"] == 'Paid' ? 'selected' : '') . ">Paid</option>
                        <option value='Not Applicable' " . ($row["PaymentStatus"] == 'Not Applicable' ? 'selected' : '') . ">Not Applicable</option>
                    </select>
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
    echo "No reservations found.";
}
?>

<script>
    // JavaScript functions to handle button clicks
    function showUpdateForm(formId) {
        document.getElementById(formId).style.display = 'block';
    }

    function hideUpdateForm(formId) {
        document.getElementById(formId).style.display = 'none';
    }

    function manageres() {
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