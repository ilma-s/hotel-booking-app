<?php
session_start();
include '../Migrations/db_connection.php';
$connection = OpenCon();

if (isset($_GET['roomID'])) {


    $roomID = $_GET['roomID'];
    $fromDate = $_SESSION['startDate'];
    $toDate = $_SESSION['endDate'];
    $customerID = $_SESSION['userID'];

    echo $roomID;

    $insertQuery = "INSERT INTO booking (customer, room, fromDate, toDate, firstEntryPerson) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connection, $insertQuery);
    mysqli_stmt_bind_param($stmt, "iissi", $customerID, $roomID, $fromDate, $toDate, $customerID);

    $hotelIDQuery = mysqli_query($connection, "SELECT belongsToHotel FROM room WHERE RID = $roomID");

    if ($hotelIDQuery) {
        $hotelIDRow = mysqli_fetch_assoc($hotelIDQuery);
        $hotelID = $hotelIDRow['belongsToHotel'];

        if (mysqli_stmt_execute($stmt)) {
            header('Location: hotel_details.php?hotel_id=' . $hotelID . '&bookingSuccessful=You have successfully booked the room!.');
        } else {
            header('Location: hotel_details.php?hotel_id=' . $hotelID . '&bookingError=Something went wrong! Try again.');
        }
    }
}