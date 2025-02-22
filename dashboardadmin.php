<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        /* Add any styling as needed */
        body {
            font-family: 'Century Gothic';
            text-align: center;
            margin: 20px;
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

        iframe {
            border: 2px solid #ccc;
            width: 100%;
            height: 300px; /* Set the desired height */
        }

        input {
            padding: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h1>Welcome to the Dashboard</h1>

    <div>
        <label for="search">Search:</label>
        <input type="text" id="search" oninput="search()">
        <button onclick="clearSearch()">Clear</button>
    </div>

    <button onclick="profile()">View Profile</button>
    <button onclick="manageReservationStatus()">Manage Reservation Status</button>
    <button onclick="manageCustomerDetails()">Manage Customer Details</button>
    <button onclick="logout()">Log out</button>

    <!-- Iframe to display customer details -->
    <iframe id="customerDetails" src="viewcustomer.php" frameborder="0"></iframe>

    <!-- Iframe to display reservation status -->
    <iframe id="reservationStatus" src="viewreservationadmin.php" frameborder="0"></iframe>

    <script>
        // JavaScript functions to handle button clicks and search
        function manageReservationStatus() {
            // Redirect to the page for managing reservation status
            window.location.href = 'updatereservationbyadmin.php';
        }

        function manageCustomerDetails() {
            // Redirect to the page for managing customer details
            window.location.href = 'viewcustomeronlybyadmin.php';
        }

        function profile() {
            window.location.href = 'adminprofile.php';
        }

        function logout() {
            window.location.href = 'loginadmin.php';
        }

        function search() {
            // Get the search input value
            var searchTerm = document.getElementById('search').value;

            // Update the src attribute of both iframes with the search term
            document.getElementById('customerDetails').src = 'viewcustomer.php?search=' + searchTerm;
            document.getElementById('reservationStatus').src = 'viewreservationadmin.php?search=' + searchTerm;
        }

        function clearSearch() {
            // Clear the search input and reload the original iframes
            document.getElementById('search').value = '';
            document.getElementById('customerDetails').src = 'viewcustomer.php';
            document.getElementById('reservationStatus').src = 'viewreservationadmin.php';
        }
    </script>
</body>
</html>
