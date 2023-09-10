<?php

$conn = mysqli_connect('localhost', 'root', '', 'bookingapp');

// disable exceptions
mysqli_report(MYSQLI_REPORT_ERROR);

if(isset($_GET['RID'])) {

    $RID = $_GET['RID'];

    $result = mysqli_query($conn, 'delete from room where RID = ' . $RID);

    try {
        if ($result) {
            header('location: view.php');
            exit();
        } else {
            if (mysqli_errno($conn) == 1451) {
                echo "A reservation has been made to this room, can't remove all instances of it! ";
            }
        }
    } catch(mysqli_sql_exception $e) {}

}

if (isset($_GET['name'])) {
    $img_name = $_GET['name'];

    $result = mysqli_query($conn, 'delete from pictures where picture = ' . $img_name);
}