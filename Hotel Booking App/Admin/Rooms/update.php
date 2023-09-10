<?php

$conn = mysqli_connect('localhost', 'root', '', 'bookingapp');

if (isset($_GET['RID'])) {
    $_SESSION['RID'] = $_GET['RID'];
    $query = mysqli_query($conn, 'SELECT * FROM room WHERE RID = ' . $_GET['RID']);
    $room = mysqli_fetch_assoc($query);
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
        $title = 'Edit Room';
        include('../../Includes/head.php') ?>

        <style>
            h1{
                display: flex;
                flex-direction: row;
                justify-content: center;
                color: #012A4A;
                font-weight: lighter;
            }
        </style>
    </head>

<body>

<div class="wrapper">
    <main class="wrapper">
        <h1>
            Updating Room <?php echo $_SESSION['RID'];?>
        </h1>
        <form action="updateProcessing.php" method="POST">
            <input type="hidden" name="RID" id="RID" required value="<?php echo $_SESSION['RID'];?>">

            <label for="price">Price:</label>
            <input type="number" name="price" id="price" required value="<?php echo $room['price'];?>">
            <br>

            <div>
                <label for="TV">TV:</label>
                <input type="radio" name="TV" id="TVYes" value="1" <?php if ($room['TV'] == 1) echo 'checked'; ?> required>
                <label for="TVYes">Yes</label>
                <input type="radio" name="TV" id="TVNo" value="0" <?php if ($room['TV'] == 0) echo 'checked'; ?> required>
                <label for="TVNo">No</label>
            </div>

            <div>
            <label for="AC">AC:</label>
            <input type="radio" name="AC" id="ACYes" value="1" <?php if ($room['AC'] == 1) echo 'checked'; ?> required>
            <label for="ACYes">Yes</label>
            <input type="radio" name="AC" id="ACNo" value="0" <?php if ($room['AC'] == 0) echo 'checked'; ?> required>
            <label for="ACNo">No</label>
            </div>


            <div>
            <label for="heating">Heating:</label>
            <input type="radio" name="heating" id="heatingYes" value="1" <?php if ($room['heating'] == 1) echo 'checked'; ?> required>
            <label for="heatingYes">Yes</label>
            <input type="radio" name="heating" id="heatingNo" value="0" <?php if ($room['heating'] == 0) echo 'checked'; ?> required>
            <label for="heatingNo">No</label>
            </div>

            <div>
            <label for="miniBar">Minibar:</label>
            <input type="radio" name="miniBar" id="miniBarYes" value="1" <?php if ($room['miniBar'] == 1) echo 'checked'; ?> required>
            <label for="miniBarYes">Yes</label>
            <input type="radio" name="miniBar" id="miniBarNo" value="0" <?php if ($room['miniBar'] == 0) echo 'checked'; ?> required>
            <label for="miniBarNo"> No </label>
            </div>

            <p class="addRemoveImg">Add or remove images for this room. <br> Changes will reflect on all rooms of this type.</p>
            <button type="submit" class="edit">Edit Room</button>

        </form>
    </main>

    <br>
    <?php
    $rt = $room['roomType'];
    $sql = "SELECT picture FROM pictures WHERE roomType = $rt";
    $query = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($query)) :
        $query = mysqli_query($conn, "SELECT picture FROM pictures WHERE roomType = '$rt'");
        while($row = mysqli_fetch_assoc($query)):?>
            <div>
                <img src="image.php?name=<?php echo $row['picture']?>" alt="" style="height: 200px">
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