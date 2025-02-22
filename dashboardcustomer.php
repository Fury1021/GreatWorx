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
    </style>
</head>
<body>
    <h1>Welcome to the Dashboard</h1>


    <button onclick="profile()">View Profile</button>
    <button onclick="reserveHall()">Reserve</button>
    <button onclick="viewReservations()">Edit Reservation</button>
    <button onclick="logout()">Log out</button>

    <!-- Iframe to display customer details -->

    <!-- Iframe to display reservation status -->
    <iframe id="reservationStatus" src="viewonlycustomer.php" frameborder="0"></iframe>

    <!-- Buttons for actions -->


    <script>
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
    </script>
</body>
</html>
