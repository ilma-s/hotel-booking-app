<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="registration.css">
    <title>Document</title>
</head>
<body>


<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);


include '../Migrations/db_connection.php';
$conn = OpenCon();
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if($_SESSION['init'] == 1) {
    $_SESSION['formData'] = $_POST;
    $_SESSION['init'] = 0;
    $_SESSION['userAdmin'] = 0;
} else if($_SESSION['continue'] == 0) {
    $_SESSION['hotelData'] = $_POST;
}

$firstName = $_SESSION['formData']['firstName'];
$lastName = $_SESSION['formData']['lastName'];
$dateOfBirth = $_SESSION['formData']['dateOfBirth'];
$email = $_SESSION['formData']['email'];

$username = $_SESSION['formData']['username'];
$password = $_SESSION['formData']['password'];
$confirmPassword = $_SESSION['formData']['confirmPassword'];
//hash the password before storing it in the database
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$admin = $_SESSION['userAdmin'];

$email_check_query = "SELECT * FROM users WHERE LOWER(email)= LOWER('$email') LIMIT 1";  //LIMIT 1 only returns one row
$username_check_query = "SELECT * FROM users WHERE LOWER(username) = LOWER('$username') LIMIT 1";

$result_email = mysqli_query($conn, $email_check_query);
$result_username = mysqli_query($conn, $username_check_query);
$user = mysqli_fetch_assoc($result_email);
if ($user) {
    if ($user['email'] === $email) {
        header("Location: registration.php?error=Email already exists");
        exit();
    }
}
$user = mysqli_fetch_assoc($result_username);
if ($user) {
    if ($user['username'] === $username) {
        header("Location: registration.php?error=Username already exists");
        exit();
    }
}

if($password !== $confirmPassword) {
    $_SESSION['formData'] = $_POST;
    $_POST= [];
    header("Location: registration.php?error=Passwords need to match!");
    exit();
}

//prepare and bind parameters to prevent sql injection -> sql code and the data are sent separately to ensure any possible malicious code is treated as data and not sql code
$insert_query = "INSERT INTO users (firstName, lastName, dateOfBirth, email, username, password, isAdmin) VALUES (?, ?, ?, ?, ?, ?, ?)";
//prepare the statement
$statement = $conn->prepare($insert_query);
//bind parameters
$statement->bind_param("sssssss", $firstName, $lastName, $dateOfBirth, $email, $username, $hashed_password, $admin);
//execute the statement
$statement->execute();

$firstEntryPerson = mysqli_insert_id($conn);
$update_query = "UPDATE users SET firstEntryPerson = $firstEntryPerson WHERE UID = $firstEntryPerson";
mysqli_query($conn, $update_query);
if ($statement->affected_rows > 0) {
    $_SESSION['logged_in'] = true;
    $_SESSION['userID'] = $firstEntryPerson;

    if($_SESSION['userAdmin']==0)
    {
        echo "<p class='warning'>Registration sucessful!";
        echo "<a href='../landingPage.php' class='goToMain'> Go to the main page </a></p>";
        //header ('Location: ../landingPage.php');
    }
    else {

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
        $admin = $firstEntryPerson;

        $_SESSION['hotelData'] = $_POST;

        $insert_hotel = "INSERT INTO hotel (nameOfHotel, addressName, addressNumber, municipality, freeParking, petFriendly, firstEntryPerson, freeWiFi, nonSmokingRooms, roomService, restaurant, bar, elevator, description, stars, gym, pool, disabilityFriendly, contactEmail, admin)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

//prepare the statement
        $statement_hotel = $conn->prepare($insert_hotel);
//bind parameters
        $statement_hotel->bind_param(
            "ssssiiiiiiiiisiiiisi",
            $nameOfHotel,
            $addressName,
            $addressNumber,
            $municipality,
            $freeParking,
            $petFriendly,
            $firstEntryPerson,
            $freeWiFi,
            $nonSmokingRooms,
            $roomService,
            $restaurant,
            $bar,
            $elevator,
            $description,
            $stars,
            $gym,
            $pool,
            $disabilityFriendly,
            $contactEmail,
            $admin
        );
//execute the statement
        $result_hotel = $statement_hotel->execute();

        //don't process images if the hotel itself cant be added
        if(!$result_hotel) {
            echo "Hotel not added";
            var_dump($_SESSION['hotelData']);
            exit();
        }

        $uploadDir = '../Uploads/'; //the dir where we want to store the uploaded files
        $pictures = $_FILES['pictures'];
        $imagesAdded = true;

        $getHIDSql = "SELECT h.HID FROM hotel h WHERE h.admin = " . $_SESSION['userID'];
        $HIDQuery = mysqli_query($conn, $getHIDSql);
        $HIDResult = mysqli_fetch_assoc($HIDQuery);


        // Check if pictures were uploaded
        if (isset($_FILES['pictures']) && !empty($_FILES['pictures']['name'][0])) {
            // Start a database transaction
            mysqli_begin_transaction($conn);

            try {
                // Prepare the INSERT statement
                $imgSql = "INSERT INTO pictures (roomType, picture, hotel) VALUES (null, ?, ?)";
                $stmt = mysqli_prepare($conn, $imgSql);

                // Loop through each uploaded picture
                foreach ($pictures['tmp_name'] as $i => $tmp_name) {
                    $img_name = uniqid() . '.jpg';
                    $uploadFile = $uploadDir . $img_name;

                    // Move uploaded file to the specified directory
                    if (move_uploaded_file($tmp_name, $uploadFile)) {
                        // Bind the parameters and execute the prepared statement
                        mysqli_stmt_bind_param($stmt, "si", $img_name, $HIDResult['HID']);
                        mysqli_stmt_execute($stmt);

                        if (mysqli_stmt_affected_rows($stmt) < 1) {
                            $imagesAdded = false;
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
                    header('Location: loadingScreen.php');
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



    }
}

$statement->close();
$_POST[] = []; ?>

</body>
</html>