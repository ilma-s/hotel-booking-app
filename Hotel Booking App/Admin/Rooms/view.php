<?php
session_start();

$conn = mysqli_connect('localhost', 'root', '', 'bookingapp');

if(!isset($_SESSION['userID'])) {
    $_SESSION['loginSet'] = 1;
    $userID = $_SESSION['userID'];
    header('Location: ../../Login/login.php');
}

$userID = $_SESSION['userID'];

$query = mysqli_query($conn, "SELECT r.*, rt.nameOfRoomType 
         FROM room r, hotel h, users u, roomtype rt 
         WHERE r.belongsToHotel = h.HID 
         AND (r.firstEntryPerson = " . $userID . " OR h.firstEntryPerson = " . $userID . ") 
         AND u.UID = " . $userID . "
         AND r.roomType = rt.RID
         ORDER BY rt.RID ASC");

if(mysqli_num_rows($query) == 0) {
    ?>
    <h1>No rooms found!</h1>
    <h3>Try adding some <a href="add.php">here.</a></h3>
    <?php
    exit();
}

$picturesQuery = mysqli_query($conn, 'select picture from pictures p, room r where r.roomType = p.roomType');

?>
<!doctype html>
<html lang="en">
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
    $title = 'View Rooms';
    include('../../Includes/head.php') ?>
    <link rel="stylesheet" href="view.css">
</head>
<body>

<main>
    <div class="table-wrapper">
        <table class="fl-table">
            <thead>
            <tr>
                <th><?php echo "Room Type" ?></th>
                <th><?php echo "Price" ?></th>
                <th><?php echo "Room Number" ?></th>
                <th><?php echo "TV" ?></th>
                <th><?php echo "AC" ?></th>
                <th><?php echo "Heating" ?></th>
                <th><?php echo "Minibar" ?></th>
                <th>Edit</th>
                <th>Delete</th>
                <th>View Pictures</th>
            </tr>
            </thead>
            <tbody>
            <?php while($row = mysqli_fetch_assoc($query)): ?>
                <tr>
                    <td><?php echo $row['nameOfRoomType']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><?php echo $row['roomNumber']?></td>
                    <td><?php if ($row['TV'] == 1) echo "Yes";else echo "No"; ?></td>
                    <td><?php if ($row['AC'] == 1) echo "Yes"; else echo "No"; ?></td>
                    <td><?php if ($row['heating'] == 1) echo "Yes"; else echo "No"; ?></td>
                    <td><?php if ($row['miniBar'] == 1) echo "Yes"; else echo "No";  ?></td>
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