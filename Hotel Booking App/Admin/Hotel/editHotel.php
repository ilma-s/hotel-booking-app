<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = mysqli_connect('localhost', 'root', '', 'bookingapp');

if (isset($_GET['HID'])) {
    $_SESSION['HID'] = $_GET['HID'];
    $query = mysqli_query($conn, 'SELECT * FROM hotel WHERE HID = ' . $_GET['HID']);
    $hotel = mysqli_fetch_assoc($query);
}

if(isset($_POST['HID'])) {
    $nameOfHotel = $_POST['nameOfHotel'];
    $addressName = $_POST['addressName'];
    $addressNumber = $_POST['addressNumber'];
    $municipality = $_POST['municipality'];

    $freeParking = $_POST['freeParking'] ?? 0;
    $petFriendly = $_POST['petFriendly'] ?? 0;
    $freeWiFi = $_POST['freeWiFi'] ?? 0;
    $nonSmokingRooms = $_POST['nonSmokingRooms'] ?? 0;
    $roomService = $_POST['roomService'] ?? 0;
    $restaurant = $_POST['restaurant'] ?? 0;
    $bar = $_POST['bar'] ?? 0;
    $elevator = $_POST['elevator'] ?? 0;
    $gym = $_POST['gym'] ?? 0;
    $pool = $_POST['pool'] ?? 0;
    $disabilityFriendly = $_POST['disabilityFriendly'] ?? 0;

    $description = $_POST['description'];
    $contactEmail = $_POST['contactEmail'];
    $stars = $_POST['stars'];

    $updateHotelSql = "UPDATE hotel 
                    SET nameOfHotel = '$nameOfHotel', addressName = '$addressName', addressNumber = '$addressNumber', municipality = '$municipality', freeParking = '$freeParking', petFriendly = '$petFriendly', freeWiFi = '$freeWiFi',
                        nonSmokingRooms = '$nonSmokingRooms', roomService = '$roomService', restaurant = '$restaurant', bar = '$bar', elevator = '$elevator', gym = '$gym', pool = '$pool', disabilityFriendly = '$disabilityFriendly',
                        description = '$description', contactEmail = '$contactEmail', stars = '$stars', lastEntryPerson = '" . $_SESSION['userID'] . "' WHERE HID = " . $_SESSION['HID'];

    $result = mysqli_query($conn, $updateHotelSql);



    if ($result) {
        echo header('location: ../Landing Page/adminPanel.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="../../Login/login.css">
    <head>
        <?php
        $title = 'Edit Hotel';
        include('../../Includes/head.php') ?>

        <style>
            h1{
                display: flex;
                flex-direction: row;
                justify-content: center;
                color: #012A4A;
                font-weight: lighter;
            }
        </style>
    </head>

<body>

<div class="wrapper">
    <main class="wrapper">
        <form action="" method="POST">
        <input type="hidden" name="HID" id="HID" required value="<?php echo $_SESSION['hotelID']; ?>">

            <div>
                <label for="nameOfHotel">Hotel Name:</label>
                <input type="text" id="nameOfHotel" name="nameOfHotel" required value="<?php echo $hotel['nameOfHotel'] ?? ''; ?>">

                <label for="addressName">Address:</label>
                <input type="text" id="addressName" name="addressName" required value="<?php echo $hotel['addressName'] ?? ''; ?>">

                <label for="addressNumber">Address Number:</label>
                <input type="text" id="addressNumber" name="addressNumber" required value="<?php echo $hotel['addressNumber'] ?? ''; ?>">

                <label for="municipality">Municipality:</label>
                <input type="text" id="municipality" name="municipality" required value="<?php echo $hotel['municipality'] ?? ''; ?>">

                <label for="freeParking">Free Parking:</label>
                <input type="checkbox" name="freeParking" id="freeParkingCheckbox" class="radio" value="1" <?php if (isset($hotel['freeParking']) && $hotel['freeParking'] == 1) echo 'checked'; ?>>
                <label for="freeParkingCheckbox">Yes</label>
                <br>

                <label for="petFriendly">Pet Friendly:</label>
                <input type="checkbox" name="petFriendly" id="petFriendlyCheckbox" class="radio" value="1" <?php if (isset($hotel['petFriendly']) && $hotel['petFriendly'] == 1) echo 'checked'; ?>>
                <label for="petFriendlyCheckbox">Yes</label>
                <br>

                <label for="freeWiFi">Free WiFi:</label>
                <input type="checkbox" name="freeWiFi" id="freeWiFiCheckbox" class="radio" value="1" <?php if (isset($hotel['freeWiFi']) && $hotel['freeWiFi'] == 1) echo 'checked'; ?>>
                <label for="freeWiFiCheckbox">Yes</label>
                <br>

                <label for="nonSmokingRooms">Non Smoking Rooms:</label>
                <input type="checkbox" name="nonSmokingRooms" id="nonSmokingRoomsCheckbox" class="radio" value="1" <?php if (isset($hotel['nonSmokingRooms']) && $hotel['nonSmokingRooms'] == 1) echo 'checked'; ?>>
                <label for="nonSmokingRoomsCheckbox">Yes</label>
                <br>

                <label for="roomService">Room Service:</label>
                <input type="checkbox" name="roomService" id="roomServiceCheckbox" class="radio" value="1" <?php if (isset($hotel['roomService']) && $hotel['roomService'] == 1) echo 'checked'; ?>>
                <label for="roomServiceCheckbox">Yes</label>
                <br>

                <label for="restaurant">Restaurant:</label>
                <input type="checkbox" name="restaurant" id="restaurantCheckbox" class="radio" value="1" <?php if (isset($hotel['restaurant']) && $hotel['restaurant'] == 1) echo 'checked'; ?>>
                <label for="restaurantCheckbox">Yes</label>
                <br>

                <label for="bar">Bar:</label>
                <input type="checkbox" name="bar" id="barCheckbox" class="radio" value="1" <?php if (isset($hotel['bar']) && $hotel['bar'] == 1) echo 'checked'; ?>>
                <label for="barCheckbox">Yes</label>
                <br>

                <label for="elevator">Elevator:</label>
                <input type="checkbox" name="elevator" id="elevatorCheckbox" class="radio" value="1" <?php if (isset($hotel['elevator']) && $hotel['elevator'] == 1) echo 'checked'; ?>>
                <label for="elevatorCheckbox">Yes</label>
                <br>

                <label for="gym">Gym:</label>
                <input type="checkbox" name="gym" id="gymCheckbox" class="radio" value="1" <?php if (isset($hotel['gym']) && $hotel['gym'] == 1) echo 'checked';?>>
                <label for="gymCheckbox">Yes</label>
                <br>

                <label for="pool">Pool:</label>
                <input type="checkbox" name="pool" id="poolCheckbox" class="radio" value="1" <?php if (isset($hotel['pool']) && $hotel['pool'] == 1) echo 'checked'; ?>>
                <label for="poolCheckbox">Yes</label>
                <br>

                <label for="disabilityFriendly">Disability Friendly:</label>
                <input type="checkbox" name="disabilityFriendly" id="disabilityFriendlyCheckbox" class="radio" value="1" <?php if (isset($hotel['disabilityFriendly']) && $hotel['disabilityFriendly'] == 1) echo 'checked'; ?>>
                <label for="disabilityFriendlyCheckbox">Yes</label>
                <br>

                <label for="stars">Stars:</label>
                <input type="number" min="1" max="5" id="stars" name="stars" class="radio" required value="<?php echo $hotel['stars'] ?? ''; ?>">
                <br>

                <label for="description">Description:</label>
                <input type="text" id="description" name="description"  required value="<?php echo $hotel['description'] ?? ''; ?>">
                <br>

                <label for="contactEmail">Contact Email:</label>
                <input type="email" id="contactEmail" name="contactEmail" required value="<?php echo $hotel['contactEmail'] ?? ''; ?>">
                <br>

            <button type="submit" class="edit">Edit Hotel</button>

        </form>
    </main>

    <br>
    <?php
    $sql = "SELECT picture FROM pictures WHERE roomType = null";
    $query = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($query)) :
        $query1 = mysqli_query($conn, "SELECT picture FROM pictures WHERE roomType = 'null'");
        while($row = mysqli_fetch_assoc($query1)):?>
            <div>
                <img src="image.php?name=<?php echo $row['picture']?>" alt="hotel picture" style="height: 200px">
                <h4><a href="delete.php?name=<?php echo $row['picture']?>"">Remove Image</a></h4>
            </div>
        <?php endwhile;

    endwhile; ?>
    <br>
    <?php

    ?>
</div>
</body>
</html>