<?php
session_start();

if(isset($_SESSION['continue']) && $_SESSION['continue'] == 1) {
    $_SESSION['formData'] = $_POST;
    $_SESSION['continue'] = 0;
    $_SESSION['hotelData'] = [];
}
$_SESSION['userAdmin'] = 1;
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="../Login/login.css">
    <link rel="stylesheet" href="registration.css">

    <style>
        h1{
            color: #012A4A;
            display: flex;
            flex-direction: column;
            text-align: center;
        }
    </style>

</head>
<body>
<div id="container">

    <h1>Now, tell us about your hotel.</h1>

    <form action="registrationProcessing.php" method="POST" enctype="multipart/form-data">

        <div>
            <label for="nameOfHotel">Hotel Name:</label>
            <input type="text" id="nameOfHotel" name="nameOfHotel" required value="<?php echo $_SESSION['hotelData']['nameOfHotel'] ?? ''; ?>">

            <label for="addressName">Address:</label>
            <input type="text" id="addressName" name="addressName" required value="<?php echo $_SESSION['hotelData']['addressName'] ?? ''; ?>">

            <label for="addressNumber">Address Number:</label>
            <input type="text" id="addressNumber" name="addressNumber" required value="<?php echo $_SESSION['hotelData']['addressNumber'] ?? ''; ?>">

            <label for="municipality">Municipality:</label>
            <input type="text" id="municipality" name="municipality" required value="<?php echo $_SESSION['hotelData']['municipality'] ?? ''; ?>">

            <label for="freeParking">Free Parking:</label>
            <input type="checkbox" name="freeParking" id="freeParkingCheckbox" class="radio" value="1" <?php if (isset($_SESSION['hotelData']['freeParking']) && $_SESSION['hotelData']['freeParking'] == 1) echo 'checked'; ?>>
            <label for="freeParkingCheckbox">Yes</label>
            <br>

            <label for="petFriendly">Pet Friendly:</label>
            <input type="checkbox" name="petFriendly" id="petFriendlyCheckbox" class="radio" value="1" <?php if (isset($_SESSION['hotelData']['petFriendly']) && $_SESSION['hotelData']['petFriendly'] == 1) echo 'checked'; ?>>
            <label for="petFriendlyCheckbox">Yes</label>
            <br>

            <label for="freeWiFi">Free WiFi:</label>
            <input type="checkbox" name="freeWiFi" id="freeWiFiCheckbox" class="radio" value="1" <?php if (isset($_SESSION['hotelData']['freeWiFi']) && $_SESSION['hotelData']['freeWiFi'] == 1) echo 'checked'; ?>>
            <label for="freeWiFiCheckbox">Yes</label>
            <br>

            <label for="nonSmokingRooms">Non Smoking Rooms:</label>
            <input type="checkbox" name="nonSmokingRooms" id="nonSmokingRoomsCheckbox" class="radio" value="1" <?php if (isset($_SESSION['hotelData']['nonSmokingRooms']) && $_SESSION['hotelData']['nonSmokingRooms'] == 1) echo 'checked'; ?>>
            <label for="nonSmokingRoomsCheckbox">Yes</label>
            <br>

            <label for="roomService">Room Service:</label>
            <input type="checkbox" name="roomService" id="roomServiceCheckbox" class="radio" value="1" <?php if (isset($_SESSION['hotelData']['roomService']) && $_SESSION['hotelData']['roomService'] == 1) echo 'checked'; ?>>
            <label for="roomServiceCheckbox">Yes</label>
            <br>

            <label for="restaurant">Restaurant:</label>
            <input type="checkbox" name="restaurant" id="restaurantCheckbox" class="radio" value="1" <?php if (isset($_SESSION['hotelData']['restaurant']) && $_SESSION['hotelData']['restaurant'] == 1) echo 'checked'; ?>>
            <label for="restaurantCheckbox">Yes</label>
            <br>

            <label for="bar">Bar:</label>
            <input type="checkbox" name="bar" id="barCheckbox" class="radio" value="1" <?php if (isset($_SESSION['hotelData']['bar']) && $_SESSION['hotelData']['bar'] == 1) echo 'checked'; ?>>
            <label for="barCheckbox">Yes</label>
            <br>

            <label for="elevator">Elevator:</label>
            <input type="checkbox" name="elevator" id="elevatorCheckbox" class="radio" value="1" <?php if (isset($_SESSION['hotelData']['elevator']) && $_SESSION['hotelData']['elevator'] == 1) echo 'checked'; ?>>
            <label for="elevatorCheckbox">Yes</label>
            <br>

            <label for="gym">Gym:</label>
            <input type="checkbox" name="gym" id="gymCheckbox" class="radio" value="1" <?php if (isset($_SESSION['hotelData']['gym']) && $_SESSION['hotelData']['gym'] == 1) echo 'checked';?>>
            <label for="gymCheckbox">Yes</label>
            <br>

            <label for="pool">Pool:</label>
            <input type="checkbox" name="pool" id="poolCheckbox" class="radio" value="1" <?php if (isset($_SESSION['hotelData']['pool']) && $_SESSION['hotelData']['pool'] == 1) echo 'checked'; ?>>
            <label for="poolCheckbox">Yes</label>
            <br>

            <label for="disabilityFriendly">Disability Friendly:</label>
            <input type="checkbox" name="disabilityFriendly" id="disabilityFriendlyCheckbox" class="radio" value="1" <?php if (isset($_SESSION['hotelData']['disabilityFriendly']) && $_SESSION['hotelData']['disabilityFriendly'] == 1) echo 'checked'; ?>>
            <label for="disabilityFriendlyCheckbox">Yes</label>
            <br>

            <label for="stars">Stars:</label>
            <input type="number" min="1" max="5" id="stars" name="stars" class="radio" required value="<?php echo $_SESSION['hotelData']['stars'] ?? ''; ?>">
            <br>

            <label for="description">Description:</label>
            <input type="text" id="description" name="description"  required value="<?php echo $_SESSION['hotelData']['description'] ?? ''; ?>">
            <br>

            <label for="contactEmail">Contact Email:</label>
            <input type="email" id="contactEmail" name="contactEmail" required value="<?php echo $_SESSION['hotelData']['contactEmail'] ?? ''; ?>">
            <br>

            <label for="picture" class="padding" >Insert Picture</label>
            <input type="file" name="pictures[]" multiple>
            <br>
        </div>
        <button type="submit">Register Property</button>
    </form>
</div>
</body>
</html>