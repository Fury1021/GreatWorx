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

// Initialize the search term to an empty string
$searchTerm = '';

// Check if the search parameter is provided in the URL
if (isset($_GET['search'])) {
    // Use mysqli_real_escape_string to prevent SQL injection
    $searchTerm = mysqli_real_escape_string($conn, $_GET['search']);
}

// Fetch customer details from the database with the search term
$sql = "SELECT * FROM Customer 
        INNER JOIN User ON Customer.UserID = User.UserID 
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
        }
    </style>
</head>

<body>

    <h2>Customer Details</h2>

    <?php
    if ($result->num_rows > 0) {
        echo "<table>
            <tr>
                <th>Customer ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile Number</th>            
            </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . $row["CustomerID"] . "</td>
                <td>" . $row["Name"] . "</td>
                <td>" . $row["Email"] . "</td>
                <td>" . $row["MobileNumber"] . "</td>
            </tr>";
        }

        echo "</table>";
    } else {
        echo "No customers found.";
    }

    $conn->close();
    ?>

</body>

</html>
