<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "greatworx";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_customer"])) {
    $customerIDToDelete = $_POST["customer_id_to_delete"];

    // Check if there are reservations for the customer
    $checkReservationsSQL = "SELECT * FROM Reservation WHERE CustomerID = '$customerIDToDelete'";
    $reservationResult = $conn->query($checkReservationsSQL);

    if ($reservationResult->num_rows > 0) {
        echo "Cannot delete customer. There are reservations associated with this customer.";
    } else {
        // Perform the delete operation if there are no reservations
        $deleteCustomerSQL = "DELETE FROM Customer WHERE CustomerID = '$customerIDToDelete'";
        if ($conn->query($deleteCustomerSQL) === TRUE) {
            echo "Customer deleted successfully!";
        } else {
            echo "Error deleting customer: " . $conn->error;
        }
    }
}

// Fetch customer details from the database
$sql = "SELECT * FROM Customer INNER JOIN User ON Customer.UserID = User.UserID";
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM Customer INNER JOIN User ON Customer.UserID = User.UserID
        WHERE Name LIKE '%$searchTerm%' OR Email LIKE '%$searchTerm%' OR MobileNumber LIKE '%$searchTerm%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Details</title>
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
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
    </style>
</head>

<body>

    <h2>Customer Details</h2>
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
        echo "<table>
            <tr>
                <th>Customer ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile Number</th>
                <th>Action</th>
            </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . $row["CustomerID"] . "</td>
                <td>" . $row["Name"] . "</td>
                <td>" . $row["Email"] . "</td>
                <td>" . $row["MobileNumber"] . "</td>
                <td>
                    <a href='updatecustomerbyadmin.php?customerID=" . $row["CustomerID"] . "' class='edit-btn'>Update</a>
                    <form method='post' style='display:inline;'>
                        <input type='hidden' name='customer_id_to_delete' value='" . $row["CustomerID"] . "'>
                        <button type='submit' name='delete_customer' class='delete-btn'>Delete</button>
                    </form>
                </td>
            </tr>";
        }

        echo "</table>";
    } else {
        echo "No customers found.";
    }

    $conn->close();
    ?>
    <script>
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