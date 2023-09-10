<?php

session_start();

$conn = mysqli_connect('localhost', 'root', '', 'bookingapp');

if (isset($_GET['TID'])) {
    $_SESSION['TID'] = $_GET['TID'];
    var_dump($_SESSION['TID']);
    $query = mysqli_query($conn, 'SELECT * FROM roomtype rt WHERE rt.RID = ' . $_GET['TID']);
    $roomType = mysqli_fetch_assoc($query);
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
        $title = 'Edit Room Type';
        include('../../Includes/head.php') ?>

        <style>
            h1{
                display: flex;
                flex-direction: row;
                justify-content: center;
            }
        </style>
    </head>

<body>

<div class="wrapper">
    <main class="wrapper">

        <form action="editRoomTypeProcessing.php" method="POST">

            <input type="hidden" name="RID" id="RID" required value="<?php echo $_SESSION['RID'];?>">

            <label for="nameOfRoomType">Name of room type:</label>
            <input type="text" name="nameOfRoomType" id="nameOfRoomType" required value="<?php echo $roomType['nameOfRoomType'];?>">
            <br>

            <label for="numberOfBeds">Number of beds:</label>
            <input type="number" min="1" name="numberOfBeds" id="numberOfBeds" required value="<?php echo $roomType['numberOfBeds'];?>">
            <br>

            <label for="size">What is the size of this room type, in square meters?</label>
            <input type="number" min="1" name="size" id="size" required value="<?php echo $roomType['size'];?>">
            <br>


            <label for="balconyOption">Does this room type come with a balcony?</label>
            <input type="radio" name="balconyOption" id="yesBalcony" value="1" <?php if ($roomType['balcony'] == 1) echo 'checked'; ?> required>
            <label for="yesBalcony">Yes</label>
            <input type="radio" name="balconyOption" id="noRadio" value="0" <?php if ($roomType['balcony'] == 1) echo 'checked'; ?> required>
            <label for="noRadio">No</label>
            <br>

            <label for="view">View:</label>
            <input type="radio" name="view" id="cityView" value="cityView" required value="<?php echo $roomType['size'];?>">
            <label for="cityView">City View</label>

            <input type="radio" name="view" id="mountainView" value="mountainView">
            <label for="mountainView">Mountain View</label>
            <br>

            <label for="bathroomOption">Does this room type have a private bathroom? </label>

            <input type="radio" name="bathroomOption" id="yesBathroom" value="1">
            <label for="yesBathroom"> Yes</label>

            <input type="radio" name="bathroomOption" id="noBathroom" value="0">
            <label for="noBathroom> No</label>
        <br>
        <br>

        <label for="picture">Insert Picture</label>
            <input type="file" name="picture" id="picture">

            <p class="addRemoveImg">Add or remove images for this room. <br> Changes will reflect on all rooms of this type.</p>
            <button type="submit" class="edit">Edit Room</button>

        </form>
    </main>

    <br>
    <?php
    $sql = "SELECT picture FROM pictures WHERE roomType = " . $_SESSION['RID'];
    $query1 = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($query1)) :
        $query2 = mysqli_query($conn, "SELECT picture FROM pictures WHERE roomType = " . $_SESSION['RID']);
        while($row = mysqli_fetch_assoc($query2)):?>
            <div>
                <img src="image.php?name=<?php echo $row['picture']?>" alt="hotel room" style="height: 200px">
                <h4><a href="delete.php?name=<?php echo $row['picture']?>"">Remove Image</a></h4>
            </div>
        <?php endwhile;

    endwhile; ?>
    <br>
    <?php

    ?>
    <form action="">
        <label for="picture">Insert Picture</label>
        <input type="file" name="picture" id="picture">
        <br>
        <button type="submit">Update Room</button>
    </form>
</div>
</body>
</html>