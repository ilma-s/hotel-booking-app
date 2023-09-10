<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link rel="stylesheet" href="view.css">
    <link rel="stylesheet" href="../../Profile/profile.css">
    <link rel="stylesheet" href="../Landing%20Page/adminPanel.css">
</head>
<?php

session_start();

$conn = mysqli_connect('localhost', 'root', '', 'bookingapp');

if(!isset($_SESSION['userAdmin']) || $_SESSION['userAdmin'] == 0) {
    echo "<div class='warning'><p>You do not have access to this resource!</p>";
    echo "<a href='../../Login/login.php' class='logIn'>Login as admin </a></div>";
    exit();
} else {
    $userID = $_SESSION['userID'];
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT h.HID from hotel h, users u WHERE h.admin = " . $userID));
    $hotelID = $row['HID'];

    $availableRoomsQuery = "
        SELECT *
        FROM room r
        WHERE r.belongsToHotel = " . $hotelID . " 
        AND NOT EXISTS (
            SELECT 1
            FROM booking b
            WHERE r.RID = b.room
            AND CURDATE() BETWEEN b.fromDate AND b.toDate
        )
    ";

    $availableRoomsResult = mysqli_query($conn, $availableRoomsQuery);

    if(mysqli_num_rows($availableRoomsResult) == 0) {
        ?>
        <h1>No currently available rooms!</h1>
        <h3>You can view all your booked rooms <a href="viewBookedRooms.php">here.</a></h3>
        <?php
        exit();
    }

}
?>


<header>
    <div class="header-wrapper">
        <h2>Admin Page</h2>
        <nav>
            <a href="add.php">New Room</a>
        </nav>
    </div>
</header>

<head>
    <?php
    $title = 'View Available Rooms';
    include('../../Includes/head.php'); ?>
    <link rel="stylesheet" href="view.css">
    <link rel="stylesheet" href="../../Profile/profile.css">
</head>
<body>

<main>
    <div class="table-wrapper">
        <table class="fl-table">
            <thead>
            <tr>
                <?php
                $sql = "SELECT column_name FROM information_schema.columns WHERE table_schema = 'bookingapp' AND table_name = 'room'";
                $columns = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($columns)) : ?>
                    <th><?php echo $row['COLUMN_NAME'] ?></th>
                <?php endwhile; ?>
                <th>Pictures</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            <?php
            while($row = mysqli_fetch_assoc($availableRoomsResult)): ?>
                <tr>
                    <td><?php echo $row['RID']; ?></td>
                    <td><?php echo $row['belongsToHotel']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['roomType']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><?php echo $row['firstEntryPerson']; ?></td>
                    <td><?php echo $row['lastEntryPerson']; ?></td>
                    <td><?php echo $row['firstEntryDate']; ?></td>
                    <td><?php echo $row['lastEntryDate']; ?></td>
                    <td><?php echo $row['cityView']; ?></td>
                    <td><?php echo $row['AC']; ?></td>
                    <td><?php echo $row['heating']; ?></td>
                    <td><?php echo $row['mountainView']; ?></td>
                    <td><a href="update.php?RID=<?php echo $row['RID']; ?>">Edit</a></td>
                    <td><a href="delete.php?RID=<?php echo $row['RID']; ?>">Delete</a></td>
                    <td><a href="viewPictures.php?roomType=<?php echo $row['roomType'];?>">View Pictures</a></td>
                </tr>
            <?php endwhile; ?>
            <tbody>
        </table>
    </div>
</main>
</body>
</html>


