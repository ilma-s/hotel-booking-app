<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

var_dump($_SESSION['userID']);

// Check if user is logged in as admin
if (!isset($_SESSION['userAdmin']) || !$_SESSION['userAdmin']) {
    header('Location: ../../Login/login.php');
    exit();
}

$conn = mysqli_connect('localhost', 'root', '', 'bookingapp');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = mysqli_query($conn, 'SELECT rt.RID from roomtype rt');

if (isset($_POST['nameOfRoomType'])) {
    $firstEntryPerson = $_SESSION['userID'];

        if ($_POST['view'] == "cityView") {
            $cityView = 1;
            $mountainView = 0;
        } else {
            $mountainView = 1;
            $cityView = 0;
        }

    $uploadDir = '../../Uploads/'; //the dir where we want to store the uploaded files
    $pictures = $_FILES['pictures'];
    $imagesAdded = true;

    $getHIDSql = "SELECT h.HID FROM hotel h WHERE h.admin = " . $_SESSION['userID'];
    $HIDQuery = mysqli_query($conn, $getHIDSql);
    $HIDResult = mysqli_fetch_assoc($HIDQuery);


        $sql = "INSERT INTO roomtype(nameOfRoomType, numberOfBeds, balcony, cityView, firstEntryPerson, mountainView, privateBathroom, size)
            VALUES ('{$_POST['nameOfRoomType']}',
                    '{$_POST['numberOfBeds']}',
                     {$_POST['balconyOption']},
                     $cityView,
                     $firstEntryPerson,
                     $mountainView,
                     {$_POST['bathroomOption']},
                     {$_POST['size']}
            )";

        $result = mysqli_query($conn, $sql);

        if (!$result) {
            echo mysqli_error($conn);
        } else {
            $_SESSION['success'] = 1; //mark that we go to add.php from here
            if (isset($_FILES['pictures']) && !empty($_FILES['pictures']['name'][0])) {
                // Start a database transaction
                mysqli_begin_transaction($conn);

                print_r($_FILES['pictures']['tmp_name']);

                try {
                    // Prepare the INSERT statement
                    $imgSql = "INSERT INTO pictures (picture, hotel, firstEntryPerson) VALUES (?, ?, ?)";
                    $stmt = mysqli_prepare($conn, $imgSql);

                    // Loop through each uploaded picture
                    foreach ($pictures['tmp_name'] as $i => $tmp_name) {
                        $img_name = uniqid() . '.jpg';
                        $uploadFile = $uploadDir . $img_name;

                        // Move uploaded file to the specified directory
                        if (move_uploaded_file($tmp_name, $uploadFile)) {
                            // Bind the parameters and execute the prepared statement
                            mysqli_stmt_bind_param($stmt, "sii", $img_name, $HIDResult['HID'], $_SESSION['userID']);
                            mysqli_stmt_execute($stmt);

                            if (mysqli_stmt_affected_rows($stmt) < 1) {
                                $imagesAdded = false;
                                echo error();
                                break; // Exit the loop if an image fails to be added
                            }
                        } else {
                            $imagesAdded = false;
                            break; // Exit the loop if an image fails to be moved
                        }
                    }

                    if ($imagesAdded) {
                        // Commit the transaction if all images were added successfully
                        mysqli_commit($conn);
                        header('Location: ../Landing Page/adminPanel.php');
                        exit();
                    } else {
                        // Rollback the transaction if any image failed to be added
                        mysqli_rollback($conn);
                        echo "Error adding images. Please try again.";
                    }
                } catch (Exception $e) {
                    // Rollback the transaction on any exception
                    mysqli_rollback($conn);
                    echo "Error adding images: " . $e->getMessage();
                } finally {
                    // Close the prepared statement
                    mysqli_stmt_close($stmt);
                }
            }
            //header('Location: ../Rooms/add.php');
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
    <link rel="stylesheet" href="addRoom.css">
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="../../Login/login.css">
    <title>Add Room Types</title>
    <style>
        h1{
            font-weight: lighter;
            display: flex;
            flex-direction: row;
            text-align: center;
        }

        h2 {
            font-weight: lighter;
            display: flex;
            flex-direction: row;
            text-align: center;
            margin-top: 10px;
        }

    </style>
</head>

<body>
<form action="" method="POST" id="roomTypeForm" enctype="multipart/form-data">

<h2 class="question">What kinds of rooms do you offer?</h2>

    <div id="roomTypeContainer">

        <label for="nameOfRoomType">Name of the room type:</label>
        <input type="text" name="nameOfRoomType" id="nameOfRoomType">
        <br>

        <label for="numberOfBeds">Number of beds:</label>
        <input type="number" min="1" name="numberOfBeds" id="numberOfBeds">
        <br>

        <label for="size">What is the size of this room type, in square meters?</label>
        <input type="number" min="1" name="size" id="size">
        <br>

        <label for="balconyOption">Does this room type come with a balcony?</label>

        <input type="radio" name="balconyOption" id="yesBalcony" value="1">
        <label for="yesBalcony">Yes</label>
        <input type="radio" name="balconyOption" id="noRadio" value="0">
        <label for="noRadio">No</label>
        <br>

        <label for="view">View:</label>
        <input type="radio" name="view" id="cityView" value="cityView">
        <label for="cityView">City View</label>

        <input type="radio" name="view" id="mountainView" value="mountainView">
        <label for="mountainView">Mountain View</label>
        <br>

        <label for="bathroomOption">Does this room type have a private bathroom? </label>

        <input type="radio" name="bathroomOption" id="yesBathroom" value="1">
        <label for="yesBathroom"> Yes</label>

        <input type="radio" name="bathroomOption" id="noBathroom" value="0">
        <label for="noBathroom> No</label>
        <br><br>

        <label for="picture">Insert Picture</label>
        <input type="file" name="pictures[]" multiple>

    </div>

    <button type="submit" class="edit">Add Room Type</button>

</form>

</body>

</html>
