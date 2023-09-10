<?php

$conn = mysqli_connect('localhost', 'root', '', 'bookingapp');

session_start();

// disable exceptions
mysqli_report(MYSQLI_REPORT_ERROR);

if(isset($_GET['param'])) {

    $UID = $_GET['param'];

    //if the field firstEntryPerson (foreign key) is not null, the user can not be deleted due to foreign key violations
    //set firstEntryPerson to null first
    $changeFirstEntryPerson = mysqli_query($conn, 'UPDATE users SET firstEntryPerson = null WHERE UID =' . $UID);

    $result = mysqli_query($conn, 'DELETE FROM users WHERE UID = ' . $UID);

    try {
        if ($result) {
            $_SESSION = [];
            header('location: ../landingPage.php');
            exit();
        } else {
            echo "fail";
        }
    } catch(mysqli_sql_exception $e) {}

} else {
    echo "no uid";
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Delete Profile</title>
</head>
<body>

</body>
</html>
