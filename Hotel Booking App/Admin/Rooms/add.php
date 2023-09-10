<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'bookingapp');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['userAdmin']) || !$_SESSION['userAdmin']) {
    header('Location: ../../Login/login.php');
    //echo '<h2>You are not an admin!</h2';
    exit;
}

if (isset($_POST['roomType'])) {
    if (!$conn) {
        header('location: ../../login.php');
    }

    $hotelNameSql = "SELECT h.HID as hotelID FROM hotel h WHERE h.admin = " . $_SESSION['userID'];
    $hotelNameResult = mysqli_fetch_assoc(mysqli_query($conn, $hotelNameSql));
    $belongsToHotel = $hotelNameResult['hotelID'];

    $roomTypeSql = "SELECT rt.RID as roomTypeID FROM roomtype rt WHERE LOWER(rt.nameOfRoomType) = '" . strtolower($_POST['roomType']) . "'";
    $roomTypeResult = mysqli_fetch_assoc(mysqli_query($conn, $roomTypeSql));
    if(mysqli_affected_rows($conn) == 0) {
        echo "<p class='warning'>Invalid room type entered.</p>";
        exit();
    }
    $roomType = $roomTypeResult['roomTypeID'];

    //if there is a roomNumber already in the table, that room exists
    $roomNumber = $_POST['roomNumber'];
    $checkRoomNumberSql = "SELECT COUNT(*) AS numRooms FROM room WHERE roomNumber = '$roomNumber'";
    $checkRoomNumberResult = mysqli_fetch_assoc(mysqli_query($conn, $checkRoomNumberSql));
    if($checkRoomNumberResult['numRooms'] > 0) {
        echo "<p class='warning'>This room already exists!</p>";
        var_dump($checkRoomNumberResult['numRooms'] );
        exit();
    }

    $price = $_POST['price'];
    $TV = $_POST['TV'];
    $AC = $_POST['AC'];
    $heating = $_POST['heating'];
    $miniBar = $_POST['miniBar'];

    $picture = $_FILES['picture']['name']; //retrieves the original name of the uploaded file


    $uploadDir = '../Uploads/'; //the dir where we want to store the uploaded files
    $img_name = uniqid() . '.jpg';
    $uploadFile = $uploadDir . $img_name;
    move_uploaded_file($_FILES['picture']['tmp_name'], $uploadFile);


    $sql = "INSERT INTO room (belongsToHotel, roomType, price, firstEntryPerson, TV, AC, heating, miniBar, roomNumber) 
        VALUES ($belongsToHotel, $roomType, $price, {$_SESSION['userID']}, $TV, $AC, $heating, $miniBar, $roomNumber)";

    $result = mysqli_query($conn, $sql);

    if(!$result) {
        echo "Error: " . mysqli_error($conn);
    }

    if($result) {
        header('location: view.php');
        exit;
    } else{
        echo "<p class='warning'>Room not added.</p>";
    }

    exit();
}

?>

<!doctype html>
<html lang="en">
<head>
    <?php
    $title = 'Add a Room';
    include('../../Includes/head.php');

    $usersFirstNameSql = "SELECT u.firstName FROM users u WHERE u.UID = " . $_SESSION['userID'];
    $usersFirstNameResult = mysqli_fetch_assoc(mysqli_query($conn, $usersFirstNameSql));
    $usersFirstName = $usersFirstNameResult['firstName'];
    ?>
    <link rel="stylesheet" href="../../Login/login.css">
    <style>
        h1{
            display: flex;
            flex-direction: column;
            text-align: center;
        }

        div{
            text-align: start;
        }


    </style>
</head>
<body>
<header>

</header>
<main>

    <!-- here we access $_SESSION variable and print (echo) value stored under user_name key -->
    <h1>Hello, <?php echo $usersFirstName; ?> !</h1>
    <?php
    if(isset($_SESSION['success']) && $_SESSION['success'] == 1) {
        ?>
        <h3>Add some rooms here.</h3>
        <?php
    }
    ?>
    <!-- empty action means submit to the current URL -->
    <form action="" method="POST" id="roomForm">

        <a href="view.php" class="goToRooms">All Rooms</a>

        <label for="roomType">Room type:</label>
        <select name="roomType" id="roomType">
            <?php
            session_start();
            $conn = mysqli_connect('localhost', 'root', '', 'bookingapp');

            // Check if the connection was successful
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $sql = "SELECT DISTINCT nameOfRoomType FROM roomtype rt WHERE rt.firstEntryPerson = " . $_SESSION['userID'];
            $result = mysqli_query($conn, $sql);

            // Check if any options were retrieved from the database
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $roomType = $row['nameOfRoomType'];
                    $selected = isset($_POST['roomType']) && $_POST['roomType'] === $roomType ? 'selected' : '';
                    echo "<option value='$roomType' $selected>$roomType</option>";
                }
            } else {
                echo "<option value='' disabled>No room types found</option>";
            }

            // Close the database connection
            mysqli_close($conn);
            ?>
        </select>

        <label for="price">Price: </label>
        <input type="number" min="0" name="price" id="price" required>

        <label for="roomNumber">Room Number: </label>
        <input type="number" min="0" name="roomNumber" id="roomNumber" required>

        <div>
        <label for="cityView" class="alignment">TV: </label>
        <input type="radio" name="TV" id="TVYes" value="1" required><label for="TVYes">Yes</label>
        <input type="radio" name="TV" id="TVNo" value="0" required><label for="TVNo">No</label>
        </div>

        <div>
        <label for="AC">AC: </label>
        <input type="radio" name="AC" id="ACYes" value="1" required><label for="ACYes">Yes</label>
        <input type="radio" name="AC" id="ACNo" value="0" required><label for="ACNo">No</label>
        </div>

        <div>
        <label for="heating">Heating: </label>
        <input type="radio" name="heating" id="heatingYes" value="1" required><label for="heatingYes">Yes</label>
        <input type="radio" name="heating" id="heatingNo" value="0" required><label for="heatingNo">No</label>
        </div>

        <div>
        <label for="mountainView">Minibar: </label>
        <input type="radio" name="miniBar" id="minibarYes" value="1" required><label for="minibarYes">Yes</label>
        <input type="radio" name="miniBar" id="minibarNo" value="0" required><label for="minibarNo">No</label>
        </div>
        <button type="submit">Add Room</button>
    </form>
</main>
</body>
</html>