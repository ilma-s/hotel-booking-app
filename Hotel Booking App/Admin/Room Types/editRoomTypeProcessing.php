<?php
$conn = mysqli_connect('localhost', 'root', '', 'bookingapp');

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['RID'])) {
    $price = $_POST['price'];
    $TV = $_POST['TV'];
    $AC = $_POST['AC'];
    $heating = $_POST['heating'];
    $miniBar = $_POST['miniBar'];
    $lastEntryPerson = $_SESSION['userID'];

    // disable exceptions
    mysqli_report(MYSQLI_REPORT_ERROR);


    $sql = "UPDATE room SET price = $price,
                            TV = $TV,
                            AC = $AC,
                            heating = $heating,
                            miniBar = $miniBar,
                            lastEntryPerson = $lastEntryPerson
    WHERE RID = '" . $_POST['RID'] . "'";

    $result = mysqli_query($conn, $sql);

    if($result) {
        echo header('location: view.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    exit();
}

