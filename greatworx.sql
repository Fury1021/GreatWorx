-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2023 at 02:14 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `greatworx`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CustomerID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `MobileNumber` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CustomerID`, `UserID`, `Name`, `MobileNumber`) VALUES
(1, 1, 'Jasper', '09254687713'),
(2, 3, 'Simon', '095648139'),
(3, 4, 'ryan', '0995544778861'),
(4, 5, 'Johndle', '09966991154'),
(6, 7, 'CUSH', '09966991154'),
(7, 8, 'azi', '0912345678');

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `ReservationID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `EventDescription` varchar(255) DEFAULT NULL,
  `EventDate` date DEFAULT NULL,
  `Hall` char(1) DEFAULT NULL CHECK (`Hall` in ('A','B','C','D')),
  `NumberOfDays` int(11) DEFAULT NULL,
  `Request` text DEFAULT NULL,
  `PackageRatePerDay` decimal(10,2) DEFAULT NULL,
  `Tax` decimal(10,2) DEFAULT NULL,
  `Rent` decimal(10,2) DEFAULT NULL,
  `DownPayment` decimal(10,2) DEFAULT NULL,
  `PaymentStatus` enum('Not Paid','Paid','Not Applicable') DEFAULT NULL,
  `ReservationStatus` enum('For Processing','Booked','Not Available','Close') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`ReservationID`, `CustomerID`, `EventDescription`, `EventDate`, `Hall`, `NumberOfDays`, `Request`, `PackageRatePerDay`, `Tax`, `Rent`, `DownPayment`, `PaymentStatus`, `ReservationStatus`) VALUES
(16, 3, 'Reservation for wedding', '2023-03-29', 'D', 100000, 'request by Jasper', 3000.00, 24000000.00, 99999999.99, 99999999.99, 'Paid', 'For Processing'),
(17, 7, 'MLBB Tournament', '2023-12-06', 'C', 2, '32', 2000.00, 320.00, 4320.00, 2160.00, 'Not Paid', 'For Processing'),
(18, 1, 'MLBB Tournament', '2023-12-13', 'C', 23, 'Jasper', 2000.00, 3680.00, 49680.00, 24840.00, 'Paid', 'For Processing'),
(19, 4, 'MLBB Tournament', '2023-11-28', 'D', 2, 'Jasper', 3000.00, 480.00, 6480.00, 3240.00, 'Not Paid', 'For Processing');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `UserType` enum('Customer','Administrator') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Email`, `Password`, `UserType`) VALUES
(1, 'jasper@gmail.com', 'jas', 'Customer'),
(2, 'diomermanaois@gmail.com', 'manaois', 'Administrator'),
(3, 'simon@gmail.com', 'sim', 'Customer'),
(4, 'ryan@gmail.com', 'jarapa', 'Customer'),
(5, 'jd@gmail.com', 'jd', 'Customer'),
(7, 'customer@gmail.com', 'cus', 'Customer'),
(8, 'azi@gmail.com', '12345', 'Customer'),
(9, 'jas@gmail.com', '123456', 'Administrator'),
(17, '23@gmail.com', '123', 'Administrator');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CustomerID`),
  ADD UNIQUE KEY `UserID` (`UserID`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`ReservationID`),
  ADD KEY `fk_customer` (`CustomerID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `ReservationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `fk_customer` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
