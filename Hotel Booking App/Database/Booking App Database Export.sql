-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2023 at 03:49 PM
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
-- Database: `bookingapp`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `bookingCount` (IN `roomId` INT)   BEGIN
    DECLARE bookingCount INT;
    SELECT COUNT(*) INTO bookingCount
    FROM booking b
    WHERE b.room = roomId;
    SELECT bookingCount;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetRoomDetails` (IN `hotelId` INT)   BEGIN
    SELECT r.roomNumber, rt.nameOfRoomType, IsRoomAvailable(r.RID) AS availability, CountRoomBookings(r.RID) AS numberOfRoomsBooked
    FROM room r
    JOIN roomtype rt ON r.roomType = rt.RID
    WHERE r.belongsToHotel = hotelId;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `CountRoomBookings` (`roomId` INT) RETURNS INT(11) READS SQL DATA BEGIN
    DECLARE bookingCount INT;

    -- Count the number of times the room ID appears in the bookings table up until now
    SELECT COUNT(*) INTO bookingCount
    FROM booking b
    WHERE b.room = roomId
      AND (CURDATE() BETWEEN b.fromDate AND b.toDate OR b.fromDate = CURDATE());

    RETURN bookingCount;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `GetBookedRoomsCount` (`selectedDate` DATE, `hotelID` INT) RETURNS INT(11) DETERMINISTIC BEGIN
    DECLARE booked_rooms_count INT;

    SELECT COUNT(*) INTO booked_rooms_count
    FROM booking b
    JOIN room r ON b.room = r.RID
    WHERE r.belongsToHotel = hotelID
    AND selectedDate BETWEEN b.fromDate AND b.toDate;

    RETURN booked_rooms_count;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `IsRoomAvailable` (`roomId` INT) RETURNS TINYINT(1) READS SQL DATA BEGIN
    DECLARE isAvailable TINYINT(1);

    -- Check if the room is available based on the current date
    SET isAvailable = NOT EXISTS (
        SELECT 1
        FROM booking b
        WHERE b.room = roomId
          AND CURDATE() BETWEEN b.fromDate AND b.toDate
    );

    RETURN isAvailable;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
                           `BID` int(11) NOT NULL,
                           `customer` int(11) NOT NULL,
                           `room` int(11) NOT NULL,
                           `fromDate` date NOT NULL,
                           `toDate` date NOT NULL,
                           `firstEntryPerson` int(11) NOT NULL,
                           `lastEntryPerson` int(11) DEFAULT NULL,
                           `firstEntryDate` date NOT NULL DEFAULT CURRENT_DATE,
                           `lastEntryDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`BID`, `customer`, `room`, `fromDate`, `toDate`, `firstEntryPerson`, `lastEntryPerson`, `firstEntryDate`, `lastEntryDate`) VALUES
(1, 1, 1, '2023-06-10', '2023-06-12', 1, NULL, '2023-06-08', NULL),
(2, 2, 1, '2023-06-15', '2023-06-18', 2, NULL, '2023-06-08', NULL),
(4, 4, 2, '2023-06-15', '2023-06-17', 4, NULL, '2023-06-08', NULL),
(5, 5, 3, '2023-06-10', '2023-06-11', 5, NULL, '2023-06-08', NULL),
(6, 6, 3, '2023-06-14', '2023-06-15', 6, NULL, '2023-06-08', NULL),
(8, 8, 4, '2023-06-16', '2023-06-18', 8, NULL, '2023-06-08', NULL),
(10, 10, 5, '2023-06-13', '2023-06-15', 10, NULL, '2023-06-08', NULL),
(13, 13, 7, '2023-06-10', '2023-06-11', 13, NULL, '2023-06-08', NULL),
(14, 14, 7, '2023-06-13', '2023-06-14', 14, NULL, '2023-06-08', NULL),
(15, 15, 8, '2023-06-10', '2023-06-12', 15, NULL, '2023-06-08', NULL),
(16, 16, 8, '2023-06-14', '2023-06-16', 16, NULL, '2023-06-08', NULL),
(17, 17, 9, '2023-06-10', '2023-06-11', 17, NULL, '2023-06-08', NULL),
(18, 18, 9, '2023-06-13', '2023-06-14', 18, NULL, '2023-06-08', NULL),
(19, 19, 10, '2023-06-10', '2023-06-12', 19, NULL, '2023-06-08', NULL),
(20, 20, 10, '2023-06-16', '2023-06-18', 20, NULL, '2023-06-08', NULL),
(21, 1, 11, '2023-06-10', '2023-06-12', 1, NULL, '2023-06-08', NULL),
(22, 2, 11, '2023-06-15', '2023-06-18', 2, NULL, '2023-06-08', NULL),
(24, 4, 12, '2023-06-15', '2023-06-17', 4, NULL, '2023-06-08', NULL),
(25, 5, 13, '2023-06-10', '2023-06-11', 5, NULL, '2023-06-08', NULL),
(26, 6, 13, '2023-06-14', '2023-06-15', 6, NULL, '2023-06-08', NULL),
(27, 7, 14, '2023-06-10', '2023-06-12', 7, NULL, '2023-06-08', NULL),
(28, 8, 14, '2023-06-16', '2023-06-18', 8, NULL, '2023-06-08', NULL),
(29, 9, 15, '2023-06-10', '2023-06-11', 9, NULL, '2023-06-08', NULL),
(30, 10, 15, '2023-06-13', '2023-06-15', 10, NULL, '2023-06-08', NULL),
(31, 11, 16, '2023-06-10', '2023-06-12', 11, NULL, '2023-06-08', NULL),
(32, 12, 16, '2023-06-15', '2023-06-17', 12, NULL, '2023-06-08', NULL),
(33, 13, 17, '2023-06-10', '2023-06-11', 13, NULL, '2023-06-08', NULL),
(34, 14, 17, '2023-06-13', '2023-06-14', 14, NULL, '2023-06-08', NULL),
(35, 15, 18, '2023-06-10', '2023-06-12', 15, NULL, '2023-06-08', NULL),
(36, 16, 18, '2023-06-14', '2023-06-16', 16, NULL, '2023-06-08', NULL),
(37, 17, 19, '2023-06-10', '2023-06-11', 17, NULL, '2023-06-08', NULL),
(38, 18, 19, '2023-06-13', '2023-06-14', 18, NULL, '2023-06-08', NULL),
(39, 19, 20, '2023-06-10', '2023-06-12', 19, NULL, '2023-06-08', NULL),
(40, 20, 20, '2023-06-16', '2023-06-18', 20, NULL, '2023-06-08', NULL),
(41, 1, 21, '2023-06-10', '2023-06-12', 1, NULL, '2023-06-08', NULL),
(42, 2, 21, '2023-06-15', '2023-06-18', 2, NULL, '2023-06-08', NULL),
(43, 3, 22, '2023-06-10', '2023-06-12', 3, NULL, '2023-06-08', NULL),
(44, 4, 22, '2023-06-15', '2023-06-17', 4, NULL, '2023-06-08', NULL),
(45, 5, 23, '2023-06-10', '2023-06-11', 5, NULL, '2023-06-08', NULL),
(46, 6, 23, '2023-06-14', '2023-06-15', 6, NULL, '2023-06-08', NULL),
(47, 7, 24, '2023-06-10', '2023-06-12', 7, NULL, '2023-06-08', NULL),
(48, 8, 24, '2023-06-16', '2023-06-18', 8, NULL, '2023-06-08', NULL),
(49, 9, 25, '2023-06-10', '2023-06-11', 9, NULL, '2023-06-08', NULL),
(50, 10, 25, '2023-06-13', '2023-06-15', 10, NULL, '2023-06-08', NULL),
(51, 11, 26, '2023-06-10', '2023-06-12', 11, NULL, '2023-06-08', NULL),
(52, 12, 26, '2023-06-15', '2023-06-17', 12, NULL, '2023-06-08', NULL),
(54, 1, 1, '2023-05-10', '2023-05-12', 1, NULL, '2023-06-08', NULL),
(55, 2, 1, '2023-05-15', '2023-05-18', 2, NULL, '2023-06-08', NULL),
(56, 3, 2, '2023-05-10', '2023-05-12', 3, NULL, '2023-06-08', NULL),
(57, 4, 2, '2023-05-03', '2023-05-05', 4, NULL, '2023-06-08', NULL),
(58, 5, 3, '2023-05-18', '2023-05-22', 5, NULL, '2023-06-08', NULL),
(59, 6, 3, '2023-05-25', '2023-05-28', 6, NULL, '2023-06-08', NULL),
(60, 7, 4, '2023-05-01', '2023-05-03', 7, NULL, '2023-06-08', NULL),
(61, 8, 4, '2023-05-10', '2023-05-15', 8, NULL, '2023-06-08', NULL),
(62, 9, 5, '2023-05-12', '2023-05-16', 9, NULL, '2023-06-08', NULL),
(63, 10, 5, '2023-05-20', '2023-05-23', 10, NULL, '2023-06-08', NULL),
(64, 20, 25, '2023-05-19', '2023-05-23', 20, NULL, '2023-06-08', NULL),
(65, 19, 25, '2023-05-14', '2023-05-17', 19, NULL, '2023-06-08', NULL),
(66, 13, 7, '2023-05-22', '2023-05-25', 13, NULL, '2023-06-08', NULL),
(67, 14, 7, '2023-05-28', '2023-06-01', 14, NULL, '2023-06-08', NULL),
(68, 15, 8, '2023-05-06', '2023-05-09', 15, NULL, '2023-06-08', NULL),
(69, 16, 8, '2023-05-14', '2023-05-18', 16, NULL, '2023-06-08', NULL),
(70, 17, 9, '2023-05-20', '2023-05-23', 17, NULL, '2023-06-08', NULL),
(71, 18, 9, '2023-05-27', '2023-05-31', 18, NULL, '2023-06-08', NULL),
(72, 19, 10, '2023-05-08', '2023-05-12', 19, NULL, '2023-06-08', NULL),
(73, 20, 10, '2023-05-16', '2023-05-19', 20, NULL, '2023-06-08', NULL),
(75, 2, 11, '2023-05-27', '2023-05-30', 2, NULL, '2023-06-08', NULL),
(76, 3, 12, '2023-05-02', '2023-05-04', 3, NULL, '2023-06-08', NULL),
(77, 4, 12, '2023-05-10', '2023-05-13', 4, NULL, '2023-06-08', NULL),
(78, 5, 13, '2023-05-15', '2023-05-17', 5, NULL, '2023-06-08', NULL),
(79, 6, 13, '2023-05-20', '2023-05-23', 6, NULL, '2023-06-08', NULL),
(80, 7, 14, '2023-05-05', '2023-05-08', 7, NULL, '2023-06-08', NULL),
(81, 8, 14, '2023-05-10', '2023-05-14', 8, NULL, '2023-06-08', NULL),
(82, 9, 15, '2023-05-18', '2023-05-20', 9, NULL, '2023-06-08', NULL),
(83, 2, 15, '2023-05-25', '2023-05-28', 2, NULL, '2023-06-08', NULL),
(84, 3, 16, '2023-05-01', '2023-05-03', 3, NULL, '2023-06-08', NULL),
(85, 4, 16, '2023-05-07', '2023-05-10', 4, NULL, '2023-06-08', NULL),
(86, 5, 17, '2023-05-12', '2023-05-14', 5, NULL, '2023-06-08', NULL),
(87, 6, 17, '2023-05-18', '2023-05-21', 6, NULL, '2023-06-08', NULL),
(88, 5, 18, '2023-05-25', '2023-05-27', 5, NULL, '2023-06-08', NULL),
(89, 6, 18, '2023-05-29', '2023-06-01', 6, NULL, '2023-06-08', NULL),
(90, 7, 19, '2023-05-06', '2023-05-09', 7, NULL, '2023-06-08', NULL),
(91, 8, 19, '2023-05-11', '2023-05-15', 8, NULL, '2023-06-08', NULL),
(92, 9, 20, '2023-05-17', '2023-05-20', 9, NULL, '2023-06-08', NULL),
(93, 10, 20, '2023-05-23', '2023-05-26', 10, NULL, '2023-06-08', NULL),
(94, 11, 21, '2023-05-01', '2023-05-03', 11, NULL, '2023-06-08', NULL),
(95, 12, 21, '2023-05-07', '2023-05-10', 12, NULL, '2023-06-08', NULL),
(96, 13, 22, '2023-05-13', '2023-05-15', 13, NULL, '2023-06-08', NULL),
(97, 14, 22, '2023-05-18', '2023-05-21', 14, NULL, '2023-06-08', NULL),
(98, 15, 23, '2023-05-26', '2023-05-29', 15, NULL, '2023-06-08', NULL),
(99, 16, 23, '2023-05-31', '2023-06-01', 16, NULL, '2023-06-08', NULL),
(100, 17, 24, '2023-05-03', '2023-05-06', 17, NULL, '2023-06-08', NULL),
(101, 18, 24, '2023-05-08', '2023-05-12', 18, NULL, '2023-06-08', NULL);

--
-- Triggers `booking`
--
DELIMITER $$
CREATE TRIGGER `UpdateBooking` BEFORE UPDATE ON `booking` FOR EACH ROW BEGIN
    IF NEW.firstEntryDate != OLD.firstEntryDate OR NEW.firstEntryPerson != OLD.firstEntryPerson THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cannot modify firstEntryDate and firstEntryPerson columns.';
    END IF;
    SET NEW.lastEntryDate = CURRENT_TIMESTAMP();
    SET NEW.lastEntryPerson = USER();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `bookinginformationview`
-- (See below for the actual view)
--
CREATE TABLE `bookinginformationview` (
`BID` int(11)
,`customerName` varchar(76)
,`nameOfRoomType` varchar(50)
,`roomID` int(11)
,`roomNumber` int(5)
,`fromDate` date
,`toDate` date
,`nightCount` int(7)
,`price` double
,`totalCost` double
);

-- --------------------------------------------------------

--
-- Table structure for table `hotel`
--

CREATE TABLE `hotel` (
  `HID` int(11) NOT NULL,
  `nameOfHotel` varchar(50) NOT NULL,
  `addressName` varchar(50) NOT NULL,
  `addressNumber` int(3) NOT NULL,
  `municipality` varchar(50) NOT NULL,
  `freeParking` int(1) NOT NULL DEFAULT 0,
  `petFriendly` int(1) NOT NULL DEFAULT 0,
  `firstEntryPerson` int(11) NOT NULL,
  `lastEntryPerson` int(11) DEFAULT NULL,
  `firstEntryDate` date NOT NULL DEFAULT (CURDATE()),
  `lastEntryDate` date DEFAULT NULL,
  `freeWiFi` int(1) NOT NULL DEFAULT 0,
  `nonSmokingRooms` int(1) NOT NULL DEFAULT 0,
  `roomService` int(1) NOT NULL DEFAULT 0,
  `restaurant` int(1) NOT NULL DEFAULT 0,
  `bar` int(1) NOT NULL DEFAULT 0,
  `elevator` int(1) NOT NULL DEFAULT 0,
  `description` varchar(1000) NOT NULL,
  `stars` int(1) NOT NULL DEFAULT 0,
  `gym` int(1) NOT NULL DEFAULT 0,
  `pool` int(1) NOT NULL DEFAULT 0,
  `disabilityFriendly` int(1) NOT NULL DEFAULT 0,
  `contactEmail` varchar(100) NOT NULL,
  `admin` int(11) NOT NULL,
  `averageRating` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel`
--

INSERT INTO `hotel` (`HID`, `nameOfHotel`, `addressName`, `addressNumber`, `municipality`, `freeParking`, `petFriendly`, `firstEntryPerson`, `lastEntryPerson`, `firstEntryDate`, `lastEntryDate`, `freeWiFi`, `nonSmokingRooms`, `roomService`, `restaurant`, `bar`, `elevator`, `description`, `stars`, `gym`, `pool`, `disabilityFriendly`, `contactEmail`, `admin`, `averageRating`) VALUES
(1, 'Hotel M3', 'Aerodromska', 2, 'Ilidža', 1, 0, 1, 0, '2023-06-03', '2023-06-10', 1, 1, 1, 1, 1, 0, 'Featuring free WiFi throughout the property, Hotel M3 offers air-conditioned accommodation in Ilidža, 7.2 km from Sarajevo. Guests can enjoy the on-site restaurant. Free private parking is available on site. Every room has a TV while some rooms have a seating area to relax in after a busy day. A terrace or balcony are featured in certain rooms. All rooms are fitted with a private bathroom. For extra comfort, guess can find free toiletries and a hair dryer. The hotel offers car hire and a free shuttle service. Jahorina is 18 km from Hotel M3 and the nearest airport is Sarajevo International Airport, 1.4 km from the property. The Bus and Railway Stations are at distance of 3.5 km.', 3, 0, 0, 0, '', 1, 3.9),
(2, 'Hotel Grad', 'Safeta Hadžića', 19, 'Centar', 1, 0, 1, 0, '2023-06-03', '2023-06-10', 1, 1, 0, 0, 1, 0, 'Hotel Grad offers air-conditioned accommodation in Sarajevo, 5.9 km from Bascarsija. Guests can enjoy the on-site bar and WiFi offered in all rooms. Modernly-decorated, all rooms are equipped with a flat-screen cable TV. Each room comes with a private bathroom equipped with a bidet. Extras include slippers and a hairdryer. There is a 24-hour front desk at the property and free private parking onsite. Sarajevo War Tunnel is 7.6 km from Hotel Grad, while Latin bridge is 4.8 km from the property. Sarajevo International Airport is 6.1 km away.', 3, 0, 0, 0, 'INFO@HOTELGRAD.BA', 2, 4.52),
(3, 'Hotel Grand', 'Muhameda Ef Pandze', 7, 'Novo Sarajevo', 1, 0, 1, 0, '2023-06-03', '2023-06-10', 1, 1, 0, 1, 0, 1, 'The Grand Hotel is situated just 250 m from Sarajevo Central Station, and 3 km from the historic Bascarsija district. Its à la carte restaurant has a bar and terrace overlooking the hotel garden with lush vegetation and a fountain. All rooms at the Grand Hotel have free Wi-Fi, an LCD cable TV, a private bathroom with a bathtub or shower. Some rooms have air conditioning. Guests can enjoy their breakfast in the comfort of their own room, or choose from the sumptuous breakfast buffet. Private parking is provided free of charge. There is a bus stop 350 m from the building, and the closest grocery store lies just 10 m away. The Zetra Olympic Hall, 2 km away, was the venue of the 1984 Winter Olympics.', 3, 0, 0, 1, 'booking@hotelgrand.com', 3, 3.35),
(4, 'Hotel Boutique Bristol', 'Fra. Filipa Lastrica', 2, 'Novo Sarajevo', 1, 0, 1, 0, '2023-06-03', '2023-06-10', 1, 1, 1, 1, 0, 0, 'Offering a restaurant and a bar, Boutique Bristol Hotel is located 2 km from the centre of Sarajevo and provides air-conditioned accommodation with free WiFi access. A snack bar is available as well, as is a 24-hour front desk. Units here come equipped with a flat-screen satellite TV and a minibar. The private bathrooms are fitted with a shower, a bidet and a hairdryer. Free toiletries and slippers are available. Each unit has a view of the city, the river and the mountains. Hotel Boutique Bristol is 4 km away from the picturesque Sarajevo Old Town, where café bars and shops can be found. The historic Latin Bridge is 3 km away, while the Baščaršija quarter features a well-known bazaar and restaurants and is 3.5 km away. The Miljacka River flows right by the hotel. Sarajevo International Airport is located at a distance of 8 km from the property.', 4, 0, 0, 0, 'maxx.inzinjering@gmail.com', 4, 3),
(5, 'Hollywood Hotel', 'Dr. Pintola', 23, 'Ilidža', 1, 0, 1, 0, '2023-06-03', '2023-06-10', 1, 1, 0, 0, 1, 0, 'Located close to Sarajevos green district of Ilidza and 2 km from Sarajevo International Airport, Hollywood Hotel offers spacious and modern rooms with free WiFi, air conditioning and a bath or shower. Guests can use the hotels indoor pool, sauna and fitness centre free of charge. The property is close to the Vrelo Bosne spring of Bosna River. A supermarket is 100 m from the hotel. The Main Train and Bus Stations are 6 km away. Hotel Hollywood provides free, secured private parking on site. It also offers 18 conference and meeting rooms. Guests can sample a wide selection of local, international and Mediterranean cuisine in one of the 3 restaurants. The hotels outdoor swimming pool as well as a wellness and spa centre with various beauty treatments are available at a surcharge. Guests can also enjoy in sports hall and a bowling centre.', 4, 1, 1, 0, 'info@hotel-hollywood.ba', 5, 3.89);

--
-- Triggers `hotel`
--
DELIMITER $$
CREATE TRIGGER `UpdateHotel` BEFORE UPDATE ON `hotel` FOR EACH ROW BEGIN
    IF NEW.firstEntryDate != OLD.firstEntryDate OR NEW.firstEntryPerson != OLD.firstEntryPerson THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cannot modify firstEntryDate and firstEntryPerson columns.';
    END IF;
    SET NEW.lastEntryDate = CURRENT_TIMESTAMP();
    SET NEW.lastEntryPerson = USER();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pictures`
--

CREATE TABLE `pictures` (
  `PID` int(11) NOT NULL,
  `picture` varchar(999) NOT NULL,
  `roomType` int(11) DEFAULT NULL,
  `hotel` int(11) NOT NULL,
  `firstEntryPerson` int(11) NOT NULL,
  `firstEntryDate` date NOT NULL DEFAULT (CURDATE())
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pictures`
--

INSERT INTO `pictures` (`PID`, `picture`, `roomType`, `hotel`, `firstEntryPerson`, `firstEntryDate`) VALUES
(1, '6841v46173bd6.jpg', NULL, 1, 1, '2023-06-09'),
(4, 'M3-deluxDouble.jpg', 1, 1, 1, '2023-06-09'),
(5, 'M3-deluxTriple.jpg', 2, 1, 1, '2023-06-09'),
(7, 'Grad-Double.jpg', 4, 2, 1, '2023-06-09'),
(8, 'Grad.jpg', NULL, 2, 1, '2023-06-09'),
(9, 'Grad-quadruple.jpg', 4, 2, 1, '2023-06-09'),
(10, 'Grand-Family.jpg', 6, 3, 1, '2023-06-09'),
(11, 'Grand-Single.jpg', 7, 3, 1, '2023-06-09'),
(12, 'Grand-economy.jpg', 8, 3, 1, '2023-06-09'),
(13, 'Grand.jpg', NULL, 3, 1, '2023-06-09'),
(14, 'Bristol-Queen.jpg', 9, 4, 1, '2023-06-09'),
(15, 'Bristol-Queen.jpg', 10, 4, 1, '2023-06-09'),
(16, 'Bristol-King.jpg', 11, 4, 1, '2023-06-09'),
(17, 'Bristol.jpg', NULL, 4, 1, '2023-06-09'),
(18, 'HollyWood.jpg', NULL, 5, 1, '2023-06-09'),
(19, 'Hollywood-Junior.jpg', 12, 5, 1, '2023-06-09'),
(20, 'Hollywood-Tripple.jpg', 13, 5, 1, '2023-06-09');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `RID` int(11) NOT NULL,
  `booking` int(1) NOT NULL,
  `staff` enum('1','2','3','4','5') NOT NULL,
  `comfort` enum('1','2','3','4','5') NOT NULL,
  `freeWifi` enum('1','2','3','4','5') NOT NULL,
  `facilities` enum('1','2','3','4','5') NOT NULL,
  `valueForMoney` enum('1','2','3','4','5') NOT NULL,
  `cleanliness` enum('1','2','3','4','5') NOT NULL,
  `location` enum('1','2','3','4','5') NOT NULL,
  `comment` varchar(500) DEFAULT NULL,
  `dateSubmitted` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`RID`, `booking`, `staff`, `comfort`, `freeWifi`, `facilities`, `valueForMoney`, `cleanliness`, `location`, `comment`, `dateSubmitted`) VALUES
(23, 54, '3', '4', '2', '5', '4', '5', '2', NULL, '2023-06-08'),
(24, 55, '5', '3', '4', '5', '5', '5', '3', NULL, '2023-06-08'),
(25, 56, '4', '2', '5', '3', '4', '4', '5', NULL, '2023-06-08'),
(26, 57, '2', '5', '5', '4', '3', '4', '2', NULL, '2023-06-08'),
(27, 58, '5', '4', '3', '2', '3', '3', '4', NULL, '2023-06-08'),
(28, 59, '3', '4', '2', '5', '5', '5', '5', NULL, '2023-06-08'),
(29, 60, '4', '3', '4', '5', '5', '4', '3', NULL, '2023-06-08'),
(30, 61, '4', '2', '5', '3', '4', '1', '5', NULL, '2023-06-08'),
(31, 62, '2', '5', '5', '4', '3', '4', '5', NULL, '2023-06-08'),
(32, 63, '5', '5', '5', '5', '5', '4', '4', NULL, '2023-06-08'),
(33, 64, '5', '5', '5', '5', '5', '5', '5', NULL, '2023-06-08'),
(34, 65, '5', '3', '4', '5', '5', '5', '5', NULL, '2023-06-08'),
(35, 66, '4', '2', '5', '5', '4', '5', '5', NULL, '2023-06-08'),
(36, 67, '5', '5', '5', '4', '5', '5', '5', NULL, '2023-06-08'),
(37, 68, '5', '5', '5', '5', '5', '3', '4', NULL, '2023-06-08'),
(38, 69, '5', '4', '5', '5', '5', '5', '5', NULL, '2023-06-08'),
(39, 70, '5', '5', '5', '5', '5', '5', '5', NULL, '2023-06-08'),
(40, 71, '4', '5', '5', '5', '4', '5', '5', NULL, '2023-06-08'),
(41, 72, '2', '5', '1', '4', '3', '4', '1', NULL, '2023-06-08'),
(42, 73, '5', '5', '5', '5', '5', '5', '5', NULL, '2023-06-08'),
(43, 74, '3', '4', '2', '5', '3', '5', '4', NULL, '2023-06-08'),
(44, 75, '4', '3', '4', '4', '5', '3', '3', NULL, '2023-06-08'),
(45, 76, '4', '5', '5', '5', '4', '5', '5', NULL, '2023-06-08'),
(46, 77, '2', '5', '5', '4', '5', '4', '5', NULL, '2023-06-08'),
(48, 79, '3', '4', '2', '5', '1', '5', '2', NULL, '2023-06-08'),
(49, 80, '1', '3', '4', '1', '5', '2', '3', NULL, '2023-06-08'),
(50, 81, '4', '2', '5', '3', '4', '1', '5', NULL, '2023-06-08'),
(51, 82, '2', '5', '1', '4', '3', '4', '1', NULL, '2023-06-08'),
(52, 83, '5', '1', '3', '2', '2', '3', '4', NULL, '2023-06-08'),
(53, 84, '3', '4', '2', '5', '1', '5', '2', NULL, '2023-06-08'),
(54, 85, '1', '3', '4', '1', '5', '2', '3', NULL, '2023-06-08'),
(55, 86, '4', '2', '5', '3', '4', '1', '5', NULL, '2023-06-08'),
(56, 87, '2', '5', '1', '4', '3', '4', '1', NULL, '2023-06-08'),
(57, 88, '5', '1', '3', '2', '2', '3', '4', NULL, '2023-06-08'),
(58, 89, '3', '4', '2', '5', '1', '5', '2', NULL, '2023-06-08'),
(59, 90, '1', '3', '4', '1', '5', '2', '3', NULL, '2023-06-08'),
(60, 91, '4', '2', '5', '3', '4', '1', '5', NULL, '2023-06-08'),
(61, 92, '2', '5', '1', '4', '3', '4', '1', NULL, '2023-06-08'),
(62, 93, '5', '1', '3', '2', '2', '3', '4', NULL, '2023-06-08'),
(63, 94, '3', '4', '2', '5', '1', '5', '2', NULL, '2023-06-08'),
(64, 95, '1', '3', '4', '1', '5', '2', '3', NULL, '2023-06-08'),
(65, 96, '4', '2', '5', '3', '4', '1', '5', NULL, '2023-06-08'),
(66, 97, '2', '5', '1', '4', '3', '4', '1', NULL, '2023-06-08'),
(67, 98, '5', '1', '3', '2', '2', '3', '4', NULL, '2023-06-08'),
(68, 99, '3', '4', '2', '5', '1', '5', '2', NULL, '2023-06-08');

--
-- Triggers `review`
--
DELIMITER $$
CREATE TRIGGER `average Hotel Grade Calculation Delete` AFTER DELETE ON `review` FOR EACH ROW BEGIN
    UPDATE hotel h
    SET h.averageRating = ROUND((
        SELECT IFNULL(AVG(r.staff + r.comfort + r.freeWifi + r.facilities + r.valueForMoney + r.cleanliness + r.location)/7/ (SELECT COUNT(*) FROM review r WHERE r.booking = b.BID), 0)
        FROM review r, booking b, room rr
        WHERE r.booking = b.BID AND b.room = rr.RID AND rr.belongsToHotel = h.HID
    ),2);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `average Hotel Grade Calculation Insert` AFTER INSERT ON `review` FOR EACH ROW BEGIN
    UPDATE hotel h
    SET h.averageRating = ROUND((
        SELECT IFNULL(AVG(r.staff + r.comfort + r.freeWifi + r.facilities + r.valueForMoney + r.cleanliness + r.location)/7/ (SELECT COUNT(*) FROM review r WHERE r.booking = b.BID), 0)
        FROM review r, booking b, room rr
        WHERE r.booking = b.BID AND b.room = rr.RID AND rr.belongsToHotel = h.HID
    ),2);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `average Hotel Grade Calculation Update` AFTER UPDATE ON `review` FOR EACH ROW BEGIN
    UPDATE hotel h
    SET h.averageRating = ROUND((
        SELECT IFNULL(AVG(r.staff + r.comfort + r.freeWifi + r.facilities + r.valueForMoney + r.cleanliness + r.location)/7/ (SELECT COUNT(*) FROM review r WHERE r.booking = b.BID), 0)
        FROM review r, booking b, room rr
        WHERE r.booking = b.BID AND b.room = rr.RID AND rr.belongsToHotel = h.HID
    ),2);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `RID` int(11) NOT NULL,
  `belongsToHotel` int(11) NOT NULL,
  `roomType` int(11) NOT NULL,
  `price` double NOT NULL,
  `firstEntryPerson` int(11) NOT NULL,
  `lastEntryPerson` int(11) DEFAULT NULL,
  `firstEntryDate` date NOT NULL DEFAULT (CURDATE()),
  `lastEntryDate` date DEFAULT NULL,
  `TV` int(1) NOT NULL DEFAULT 0,
  `AC` int(1) NOT NULL DEFAULT 0,
  `heating` int(1) NOT NULL DEFAULT 0,
  `miniBar` int(1) NOT NULL DEFAULT 0,
  `roomNumber` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`RID`, `belongsToHotel`, `roomType`, `price`, `firstEntryPerson`, `lastEntryPerson`, `firstEntryDate`, `lastEntryDate`, `TV`, `AC`, `heating`, `miniBar`, `roomNumber`) VALUES
(1, 1, 1, 82, 1, NULL, '2023-06-03', NULL, 1, 1, 0, 1, 202),
(2, 1, 1, 82, 1, NULL, '2023-06-03', NULL, 1, 1, 0, 1, 203),
(3, 1, 1, 82, 1, NULL, '2023-06-03', NULL, 1, 1, 0, 1, 204),
(4, 1, 2, 96, 1, NULL, '2023-06-03', NULL, 1, 1, 1, 1, 302),
(5, 1, 2, 96, 1, NULL, '2023-06-03', NULL, 1, 1, 1, 1, 303),
(7, 2, 3, 89, 1, NULL, '2023-06-03', NULL, 1, 1, 0, 1, 100),
(8, 2, 3, 89, 1, NULL, '2023-06-03', NULL, 1, 1, 0, 1, 101),
(9, 2, 4, 120, 1, NULL, '2023-06-03', NULL, 1, 1, 1, 1, 200),
(10, 2, 4, 120, 1, NULL, '2023-06-03', NULL, 1, 1, 1, 1, 201),
(11, 3, 6, 150, 1, NULL, '2023-06-03', NULL, 1, 1, 0, 0, 55),
(12, 3, 6, 150, 1, NULL, '2023-06-03', NULL, 1, 1, 0, 0, 55),
(13, 3, 6, 150, 1, NULL, '2023-06-03', NULL, 1, 1, 0, 0, 60),
(14, 3, 7, 59, 1, NULL, '2023-06-03', NULL, 1, 1, 0, 0, 70),
(15, 3, 7, 59, 1, NULL, '2023-06-03', NULL, 1, 1, 0, 0, 71),
(16, 3, 8, 75, 1, NULL, '2023-06-03', NULL, 1, 1, 0, 0, 80),
(17, 3, 8, 75, 1, NULL, '2023-06-03', NULL, 1, 1, 0, 0, 81),
(18, 4, 9, 200, 1, NULL, '2023-06-03', NULL, 1, 1, 1, 1, 100),
(19, 4, 9, 200, 1, NULL, '2023-06-03', NULL, 1, 1, 1, 1, 101),
(20, 4, 10, 166, 1, NULL, '2023-06-03', NULL, 1, 1, 1, 1, 102),
(21, 4, 11, 186, 1, NULL, '2023-06-03', NULL, 1, 1, 1, 1, 200),
(22, 4, 11, 186, 1, NULL, '2023-06-03', NULL, 1, 1, 1, 1, 201),
(23, 5, 12, 153, 1, NULL, '2023-06-03', NULL, 1, 1, 1, 1, 10),
(24, 5, 12, 153, 1, NULL, '2023-06-03', NULL, 1, 1, 1, 1, 11),
(25, 5, 13, 259, 1, NULL, '2023-06-03', NULL, 1, 1, 1, 1, 20),
(26, 5, 13, 230, 1, NULL, '2023-06-03', NULL, 1, 1, 1, 1, 21);

--
-- Triggers `room`
--
DELIMITER $$
CREATE TRIGGER `UpdateRoom` BEFORE UPDATE ON `room` FOR EACH ROW BEGIN
    IF NEW.firstEntryDate != OLD.firstEntryDate OR NEW.firstEntryPerson != OLD.firstEntryPerson THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cannot modify firstEntryDate and firstEntryPerson columns.';
    END IF;
    SET NEW.lastEntryDate = CURRENT_TIMESTAMP();
    SET NEW.lastEntryPerson = USER();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `roomtype`
--

CREATE TABLE `roomtype` (
  `RID` int(11) NOT NULL,
  `nameOfRoomType` varchar(50) NOT NULL,
  `numberOfBeds` int(2) NOT NULL,
  `firstEntryPerson` int(11) NOT NULL,
  `lastEntryPerson` int(11) DEFAULT NULL,
  `firstEntryDate` date NOT NULL DEFAULT (CURDATE()),
  `lastEntryDate` date DEFAULT NULL,
  `balcony` int(1) NOT NULL DEFAULT 0,
  `cityView` int(1) NOT NULL DEFAULT 0,
  `mountainView` int(1) NOT NULL DEFAULT 0,
  `privateBathroom` int(1) NOT NULL DEFAULT 0,
  `size` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roomtype`
--

INSERT INTO `roomtype` (`RID`, `nameOfRoomType`, `numberOfBeds`, `firstEntryPerson`, `lastEntryPerson`, `firstEntryDate`, `lastEntryDate`, `balcony`, `cityView`, `mountainView`, `privateBathroom`, `size`) VALUES
(1, 'Delux Double Room', 2, 1, NULL, '2023-06-03', NULL, 1, 0, 1, 1, 25),
(2, 'Delux Triple Room', 3, 1, NULL, '2023-06-03', NULL, 1, 0, 1, 1, 30),
(3, 'Delux Double', 2, 1, NULL, '2023-06-03', NULL, 0, 1, 0, 1, 30),
(4, 'Quadruple Room', 4, 1, NULL, '2023-06-03', NULL, 0, 1, 0, 1, 40),
(6, 'Family Room', 4, 1, NULL, '2023-06-03', NULL, 0, 0, 1, 1, 25),
(7, 'Single Room', 1, 1, NULL, '2023-06-03', NULL, 0, 0, 0, 1, 14),
(8, 'Economy', 2, 1, NULL, '2023-06-03', NULL, 0, 0, 1, 1, 28),
(9, 'Delux Queen Room', 2, 1, NULL, '2023-06-03', NULL, 1, 1, 0, 1, 32),
(10, 'Queen Single Room', 1, 1, NULL, '2023-06-03', NULL, 1, 1, 0, 1, 20),
(11, 'King Room', 2, 1, NULL, '2023-06-03', NULL, 1, 1, 0, 1, 44),
(12, 'Juniore Suite', 1, 1, NULL, '2023-06-03', NULL, 0, 0, 1, 1, 40),
(13, 'Delux Tripple', 3, 1, NULL, '2023-06-03', NULL, 0, 0, 1, 1, 38);

--
-- Triggers `roomtype`
--
DELIMITER $$
CREATE TRIGGER `UpdateRoomType` BEFORE UPDATE ON `roomtype` FOR EACH ROW BEGIN
    IF NEW.firstEntryDate != OLD.firstEntryDate OR NEW.firstEntryPerson != OLD.firstEntryPerson THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cannot modify firstEntryDate and firstEntryPerson columns.';
    END IF;
    SET NEW.lastEntryDate = CURRENT_TIMESTAMP();
    SET NEW.lastEntryPerson = USER();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UID` int(11) NOT NULL,
  `firstName` varchar(25) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `dateOfBirth` date NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(999) NOT NULL,
  `email` varchar(75) NOT NULL,
  `isAdmin` int(2) NOT NULL DEFAULT 0,
  `firstEntryPerson` int(11) DEFAULT NULL,
  `lastEntryPerson` int(11) DEFAULT NULL,
  `firstEntryDate` date NOT NULL DEFAULT (CURDATE()),
  `lastEntryDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UID`, `firstName`, `lastName`, `dateOfBirth`, `username`, `password`, `email`, `isAdmin`, `firstEntryPerson`, `lastEntryPerson`, `firstEntryDate`, `lastEntryDate`) VALUES
(1, 'Ena', 'Milković', '2002-10-22', 'enamilkovic', '$2y$10$8/g2Q45SpxZuJABKrG5Kk.7vbpuUP4zRu7vLx8hUhqJKkiYmnWhJK', 'ena@gmail.com', 1, 1, NULL, '2023-06-01', NULL),
(2, 'Ilma', 'Spahić', '2002-05-15', 'ilmaspahic', '$2y$10$8/g2Q45SpxZuJABKrG5Kk.7vbpuUP4zRu7vLx8hUhqJKkiYmnWhJK', 'ilma@gmail.com', 1, 2, NULL, '2023-06-01', NULL),
(3, 'Anida', 'Duharkić', '2002-05-15', 'anidaduharkic', '$2y$10$8/g2Q45SpxZuJABKrG5Kk.7vbpuUP4zRu7vLx8hUhqJKkiYmnWhJK', 'anida@gmail.com', 1, 3, NULL, '2023-06-01', NULL),
(4, 'Ada', 'Kuskunović', '2002-10-22', 'adakuskunovic', '$2y$10$8/g2Q45SpxZuJABKrG5Kk.7vbpuUP4zRu7vLx8hUhqJKkiYmnWhJK', 'ada@gmail.com', 1, 4, NULL, '2023-06-01', NULL),
(5, 'Omar', 'Osmanović', '2002-07-15', 'omarosmanovic', '$2y$10$8/g2Q45SpxZuJABKrG5Kk.7vbpuUP4zRu7vLx8hUhqJKkiYmnWhJK', 'omar@gmail.com', 1, 5, NULL, '2023-06-01', NULL),
(6, 'Emma', 'Johnson', '1995-08-12', 'emma.johnson', '$2y$10$8/g2Q45SpxZuJABKrG5Kk.7vbpuUP4zRu7vLx8hUhqJKkiYmnWhJK', 'emma.johnson@example.com', 0, 6, NULL, '2023-06-01', NULL),
(7, 'Noah', 'Smith', '1990-04-25', 'noah.smith', '$2y$10$8/g2Q45SpxZuJABKrG5Kk.7vbpuUP4zRu7vLx8hUhqJKkiYmnWhJK', 'noah.smith@example.com', 0, 7, NULL, '2023-06-01', NULL),
(8, 'Olivia', 'Williams', '1998-12-07', 'olivia.williams', '$2y$10$8/g2Q45SpxZuJABKrG5Kk.7vbpuUP4zRu7vLx8hUhqJKkiYmnWhJK', 'olivia.williams@example.com', 0, 8, NULL, '2023-06-01', NULL),
(9, 'Liam', 'Jones', '1993-11-19', 'liam.jones', '$2y$10$8/g2Q45SpxZuJABKrG5Kk.7vbpuUP4zRu7vLx8hUhqJKkiYmnWhJK', 'liam.jones@example.com', 0, 9, NULL, '2023-06-01', NULL),
(10, 'Ava', 'Brown', '1996-09-03', 'ava.brown', '$2y$10$8/g2Q45SpxZuJABKrG5Kk.7vbpuUP4zRu7vLx8hUhqJKkiYmnWhJK', 'ava.brown@example.com', 0, 10, NULL, '2023-06-01', NULL),
(11, 'Sophia', 'Davis', '1991-07-15', 'sophia.davis', '$2y$10$8/g2Q45SpxZuJABKrG5Kk.7vbpuUP4zRu7vLx8hUhqJKkiYmnWhJK', 'sophia.davis@example.com', 0, 11, NULL, '2023-06-01', NULL),
(12, 'Mia', 'Miller', '1997-05-28', 'mia.miller', '$2y$10$8/g2Q45SpxZuJABKrG5Kk.7vbpuUP4zRu7vLx8hUhqJKkiYmnWhJK', 'mia.miller@example.com', 0, 12, NULL, '2023-06-01', NULL),
(13, 'Isabella', 'Wilson', '1994-03-10', 'isabella.wilson', '$2y$10$8/g2Q45SpxZuJABKrG5Kk.7vbpuUP4zRu7vLx8hUhqJKkiYmnWhJK', 'isabella.wilson@example.com', 0, 13, NULL, '2023-06-01', NULL),
(14, 'James', 'Taylor', '1999-01-23', 'james.taylor', '$2y$10$8/g2Q45SpxZuJABKrG5Kk.7vbpuUP4zRu7vLx8hUhqJKkiYmnWhJK', 'james.taylor@example.com', 0, 14, NULL, '2023-06-01', NULL),
(15, 'Benjamin', 'Anderson', '1992-10-05', 'benjamin.anderson', '$2y$10$8/g2Q45SpxZuJABKrG5Kk.7vbpuUP4zRu7vLx8hUhqJKkiYmnWhJK', 'benjamin.anderson@example.com', 0, 15, NULL, '2023-06-01', NULL),
(16, 'Charlotte', 'Moore', '1990-06-18', 'charlotte.moore', '$2y$10$8/g2Q45SpxZuJABKrG5Kk.7vbpuUP4zRu7vLx8hUhqJKkiYmnWhJK', 'charlotte.moore@example.com', 0, 16, NULL, '2023-06-01', NULL),
(17, 'Alexander', 'Clark', '1993-04-01', 'alexander.clark', '$2y$10$8/g2Q45SpxZuJABKrG5Kk.7vbpuUP4zRu7vLx8hUhqJKkiYmnWhJK', 'alexander.clark@example.com', 0, 17, NULL, '2023-06-01', NULL),
(18, 'Amelia', 'Lewis', '1996-02-14', 'amelia.lewis', '$2y$10$8/g2Q45SpxZuJABKrG5Kk.7vbpuUP4zRu7vLx8hUhqJKkiYmnWhJK', 'amelia.lewis@example.com', 0, 18, NULL, '2023-06-01', NULL),
(19, 'Henry', 'Harris', '1995-12-27', 'henry.harris', '$2y$10$8/g2Q45SpxZuJABKrG5Kk.7vbpuUP4zRu7vLx8hUhqJKkiYmnWhJK', 'henry.harris@example.com', 0, 19, NULL, '2023-06-01', NULL),
(20, 'Ella', 'Walker', '1991-11-09', 'ella.walker', '$2y$10$8/g2Q45SpxZuJABKrG5Kk.7vbpuUP4zRu7vLx8hUhqJKkiYmnWhJK', 'ella.walker@example.com', 0, 20, NULL, '2023-06-01', NULL);

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `UpdateUsers` BEFORE UPDATE ON `users` FOR EACH ROW BEGIN
    IF NEW.firstEntryDate != OLD.firstEntryDate OR NEW.firstEntryPerson != OLD.firstEntryPerson THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cannot modify firstEntryDate and firstEntryPerson columns.';
    END IF;
    SET NEW.lastEntryDate = CURRENT_TIMESTAMP();
    SET NEW.lastEntryPerson = USER();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure for view `bookinginformationview`
--
DROP TABLE IF EXISTS `bookinginformationview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bookinginformationview`  AS SELECT `b`.`BID` AS `BID`, concat(`u`.`firstName`,' ',`u`.`lastName`) AS `customerName`, `rt`.`nameOfRoomType` AS `nameOfRoomType`, `r`.`RID` AS `roomID`, `r`.`roomNumber` AS `roomNumber`, `b`.`fromDate` AS `fromDate`, `b`.`toDate` AS `toDate`, to_days(`b`.`toDate`) - to_days(`b`.`fromDate`) AS `nightCount`, `r`.`price` AS `price`, (to_days(`b`.`toDate`) - to_days(`b`.`fromDate`)) * `r`.`price` AS `totalCost` FROM (((`booking` `b` join `users` `u` on(`b`.`customer` = `u`.`UID`)) join `room` `r` on(`b`.`room` = `r`.`RID`)) join `roomtype` `rt` on(`r`.`roomType` = `rt`.`RID`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`BID`),
  ADD KEY `customer` (`customer`),
  ADD KEY `room` (`room`),
  ADD KEY `firstEntryPerson` (`firstEntryPerson`),
  ADD KEY `lastEntryPerson` (`lastEntryPerson`),
  ADD KEY `start_date` (`fromDate`);

--
-- Indexes for table `hotel`
--
ALTER TABLE `hotel`
  ADD PRIMARY KEY (`HID`),
  ADD KEY `firstEntryPerson` (`firstEntryPerson`),
  ADD KEY `lastEntryPerson` (`lastEntryPerson`),
  ADD KEY `admin` (`admin`);

--
-- Indexes for table `pictures`
--
ALTER TABLE `pictures`
  ADD PRIMARY KEY (`PID`),
  ADD KEY `roomType` (`roomType`),
  ADD KEY `hotel` (`hotel`),
  ADD KEY `firstEntryPerson` (`firstEntryPerson`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`RID`),
  ADD KEY `booking` (`booking`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`RID`),
  ADD KEY `belongsToHotel` (`belongsToHotel`),
  ADD KEY `firstEntryPerson` (`firstEntryPerson`),
  ADD KEY `lastEntryPerson` (`lastEntryPerson`),
  ADD KEY `roomType` (`roomType`);

--
-- Indexes for table `roomtype`
--
ALTER TABLE `roomtype`
  ADD PRIMARY KEY (`RID`),
  ADD KEY `firstEntryPerson` (`firstEntryPerson`),
  ADD KEY `lastEntryPerson` (`lastEntryPerson`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UID`),
  ADD UNIQUE KEY `username` (`username`) USING BTREE,
  ADD UNIQUE KEY `email` (`email`) USING BTREE,
  ADD UNIQUE KEY `usernamePassword` (`username`,`password`) USING HASH,
  ADD KEY `firstEntryPerson` (`firstEntryPerson`),
  ADD KEY `lastEntryPerson` (`lastEntryPerson`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `BID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=277;

--
-- AUTO_INCREMENT for table `hotel`
--
ALTER TABLE `hotel`
  MODIFY `HID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pictures`
--
ALTER TABLE `pictures`
  MODIFY `PID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `RID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `RID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `roomtype`
--
ALTER TABLE `roomtype`
  MODIFY `RID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`customer`) REFERENCES `users` (`UID`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`room`) REFERENCES `room` (`RID`),
  ADD CONSTRAINT `booking_ibfk_3` FOREIGN KEY (`firstEntryPerson`) REFERENCES `users` (`UID`),
  ADD CONSTRAINT `booking_ibfk_4` FOREIGN KEY (`lastEntryPerson`) REFERENCES `users` (`UID`);

--
-- Constraints for table `hotel`
--
ALTER TABLE `hotel`
  ADD CONSTRAINT `hotel_ibfk_1` FOREIGN KEY (`firstEntryPerson`) REFERENCES `users` (`UID`),
  ADD CONSTRAINT `hotel_ibfk_2` FOREIGN KEY (`lastEntryPerson`) REFERENCES `users` (`UID`),
  ADD CONSTRAINT `hotel_ibfk_3` FOREIGN KEY (`admin`) REFERENCES `users` (`UID`);

--
-- Constraints for table `pictures`
--
ALTER TABLE `pictures`
  ADD CONSTRAINT `pictures_ibfk_1` FOREIGN KEY (`roomType`) REFERENCES `roomtype` (`RID`),
  ADD CONSTRAINT `pictures_ibfk_2` FOREIGN KEY (`hotel`) REFERENCES `hotel` (`HID`),
  ADD CONSTRAINT `pictures_ibfk_3` FOREIGN KEY (`firstEntryPerson`) REFERENCES `users` (`UID`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`booking`) REFERENCES `booking` (`BID`);

--
-- Constraints for table `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `room_ibfk_1` FOREIGN KEY (`belongsToHotel`) REFERENCES `hotel` (`HID`),
  ADD CONSTRAINT `room_ibfk_2` FOREIGN KEY (`firstEntryPerson`) REFERENCES `users` (`UID`),
  ADD CONSTRAINT `room_ibfk_3` FOREIGN KEY (`lastEntryPerson`) REFERENCES `users` (`UID`),
  ADD CONSTRAINT `room_ibfk_4` FOREIGN KEY (`roomType`) REFERENCES `roomtype` (`RID`);

--
-- Constraints for table `roomtype`
--
ALTER TABLE `roomtype`
  ADD CONSTRAINT `roomtype_ibfk_1` FOREIGN KEY (`firstEntryPerson`) REFERENCES `users` (`UID`),
  ADD CONSTRAINT `roomtype_ibfk_2` FOREIGN KEY (`lastEntryPerson`) REFERENCES `users` (`UID`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`firstEntryPerson`) REFERENCES `users` (`UID`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`lastEntryPerson`) REFERENCES `users` (`UID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
