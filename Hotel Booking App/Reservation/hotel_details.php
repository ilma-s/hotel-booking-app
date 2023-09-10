<?php session_start();?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,500,0,200" />
    <title>Hotel Details</title>
    <link rel="stylesheet" href="hotelDetails.css">
    <link rel="stylesheet" href="../Admin/Landing Page/header.css">
    <link rel="stylesheet" href="../banner.css">
    <link rel="stylesheet" href="../Profile/profile.css">
    <script src="https://kit.fontawesome.com/d742e0762f.js" crossorigin="anonymous"></script>
</head>
<body>
<?php if (isset($_GET['bookingError'])) { ?>
    <div class="error-banner">
        <p><?php echo $_GET['bookingError']; ?></p>
    </div>
<?php } ?>
<?php if (isset($_GET['bookingSuccessful'])) { ?>
    <div class="success-banner">
        <p><?php echo $_GET['bookingSuccessful']; ?></p>
    </div>
<?php } ?>
<main>
    <?php
    include '../Migrations/db_connection.php';
    include 'availableRooms.php';

    $connection = OpenCon();

    // Function to get the hotel details based on the hotel ID
    function getHotelById($connection, $hotelId)
    {
        $query = "SELECT * FROM hotel WHERE HID = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "i", $hotelId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        return false;
    }

    // Function to get the hotel ID based on the room ID
    function getHotelIdByRoomId($connection, $roomId)
    {
        $query = "SELECT belongsToHotel FROM room WHERE RID = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "i", $roomId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['belongsToHotel'];
        }
        return false;
    }

    function getHotelRooms($connection, $hotelId): array
    {
        $availableRooms = getAvailableRooms($_SESSION['startDate'], $_SESSION['endDate'], $_SESSION['numPeople'], $_SESSION['municipality']);
        $roomsByHotel = array();

        foreach ($availableRooms as $roomId) {
            $roomHotelId = getHotelIdByRoomId($connection, $roomId);
            if ($roomHotelId == $hotelId) {
                $roomsByHotel[] = getRoomDetails($connection, $roomId);
            }

        }

        return $roomsByHotel;
    }
    function getRoomDetails($connection, $roomId)
    {
        $query = "SELECT r.RID, rt.numberOfBeds, r.roomType, r.price, rt.nameOfRoomType, r.TV, r.AC, r.heating, r.miniBar, rt.mountainView, rt.cityView, rt.balcony, rt.privateBathroom
                  FROM room r
                  JOIN roomtype rt ON r.roomType = rt.RID 
                  WHERE r.RID = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "i", $roomId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        return false;
    }

    // Function to display hotel rooms
    function displayHotelRooms($rooms, $connection)
    {
        foreach ($rooms as $room) {
            echo '<section class="rooms">';
            echo '<p class="RoomName">Room Name: ' . $room['nameOfRoomType'] . '</p>';

            echo '<div class="room">';

            $pictureQuery = "SELECT picture FROM pictures WHERE roomType = ?";
            $stmt = mysqli_prepare($connection, $pictureQuery);
            mysqli_stmt_bind_param($stmt, "i", $room['roomType']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $imageName = $row['picture'];

                $imagePath = "../Uploads/" . $imageName;

                echo '<img src="' . $imagePath . '">';

            } else {
                echo "<p class='warning'>No image found for the specified hotel.</p>";
            }

            echo '<div class="roomInformation">';

            echo '<article class="beds&price">';
            echo '<p>Number of Beds: ' . $room['numberOfBeds'] . '</p>';

            $startDate = new DateTime($_SESSION['startDate']) ;
            $endDate = new DateTime($_SESSION['endDate']);

            $interval = $startDate->diff($endDate);
            $numberOfDays = intval($interval->format('%a'));

            echo '<p>Price: $' . $room['price']*$numberOfDays . '</p>';
            echo '</article>';


            echo '<div class="featuresFlex">';
            if ($room['AC'] == 1) {
                echo '<p class="features"><span class="material-symbols-outlined">ac_unit</span> AC unit</p>';
            }

            if ($room['TV'] == 1) {
                echo '<p class="features"><span class="material-symbols-outlined">tv</span> Flat screen TV</p>';
            }

            if ($room['heating'] == 1) {
                echo '<p class="features"><span class="material-symbols-outlined">mode_heat</span> Heating</p>';
            }

            if ($room['cityView'] == 1) {
                echo '<p class="features"><span class="material-symbols-outlined">location_city</span> City View</p>';
            }

            if ($room['mountainView'] == 1) {
                echo '<p class="features"><span class="material-symbols-outlined">landscape</span> Mountain View</p>';
            }

            if ($room['balcony'] == 1) {
                echo '<p class="features"><span class="material-symbols-outlined">deck</span> Balcony</p>';
            }

            if ($room['privateBathroom'] == 1) {
                echo '<p class="features"><span class="material-symbols-outlined">bathtub</span> Private Bathroom</p>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';

            echo '<div class="buttonSection">';
            if (isset($_SESSION['userID']) && $_SESSION['userID']){
                ?>
                <a href="booking.php?roomID=<?php echo $room['RID']; ?>"><button type="button" class="bookButton">Book</button></a>
                <?php
            } else {
                $_SESSION['previousPage'] = $_SERVER['REQUEST_URI'];
                echo '<a href="../Login/login.php"><button type="button" class="bookButton">Book</button></a>';
            }
            echo '</div>';
            echo'</section>';
        }
    }

    $hotelId = $_GET['hotel_id'] ?? null;

    if ($hotelId) {
    $hotel = getHotelById($connection, $hotelId);

    if ($hotel) {
        echo '<h3 class="hotelName">' . $hotel['nameOfHotel'] . '</h3>';
        echo '<section class="description">';
        echo '<p><strong>Municipality: </strong> ' . $hotel['municipality'] . '</p>';
        echo '<p><strong>Description: </strong>' . $hotel['description'] . '</p>';


        echo '<div class="featuresFlex">';
        if ($hotel['freeWiFi'] == 1) {
            echo ' <p class="features"><span class="material-symbols-outlined">wifi</span> Free WiFi</p>';
        }

        if ($hotel['nonSmokingRooms'] == 1) {
            echo ' <p class="features"><span class="material-symbols-outlined">smoke_free</span> Non smoking rooms</p>';
        }
        else {
            echo '<p class="features"><span class="material-symbols-outlined">smoking_rooms</span> Smoking rooms</p>';
        }

        if ($hotel['roomService'] == 1) {
            echo '<p class="features"><span class="material-symbols-outlined">room_service</span> Room service</p>';
        }

        if ($hotel['restaurant'] == 1) {
            echo '<p class="features"><span class="material-symbols-outlined">restaurant</span> Restaurant</p>';
        }

        if ($hotel['bar'] == 1) {
            echo '<p class="features"><span class="material-symbols-outlined">local_bar</span> Bar</p>';
        }

        if ($hotel['elevator'] == 1) {
            echo '<p class="features"><span class="material-symbols-outlined">elevator</span> Elevator</p>';
        }

        if ($hotel['gym'] == 1) {
            echo '<p class="features"><span class="material-symbols-outlined">fitness_center</span> Fitness Center</p>' ;
        }

        if ($hotel['pool'] == 1) {
            echo '<p class="features"><span class="material-symbols-outlined">pool</span> Pool</p>';
        }

        if ($hotel['freeParking'] == 1) {
            echo '<p class="features"><span class="material-symbols-outlined">local_parking</span> Free Parking</p>';
        }

        if ($hotel['petFriendly'] == 1) {
            echo '<p class="features"><span class="material-symbols-outlined">pets</span> Pet Friendly</p>';
        }

        if ($hotel['roomService'] == 1) {
            echo '<p class="features"><span class="material-symbols-outlined">room_service</span> Room Service</p>';
        }

        if ($hotel['disabilityFriendly'] == 1) {
            echo '<p class="features"><span class="material-symbols-outlined">accessible</span> Facilities for disabled guests</p>';
        }

        echo '</div>';

        $rooms = getHotelRooms($connection,$hotelId);
        if ($rooms) {
            displayHotelRooms($rooms, $connection);
        } else {
            echo '<div class="error-banner">No rooms found for this hotel.</div>';
        }
    } else {
        echo '<div class="error-banner">Hotel not found.</div>';
    }
}
   else {
    echo '<div class="error-banner">Invalid hotel ID.</div>';
}
    ?>
</div>
</main>
</body>
</html>