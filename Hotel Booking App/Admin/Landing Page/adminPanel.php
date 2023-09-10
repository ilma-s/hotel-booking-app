<!doctype html>

<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="adminPanel.css">
    <link rel="stylesheet" href="header.css">
    <script src="https://kit.fontawesome.com/d742e0762f.js" crossorigin="anonymous"></script>

    <style>
        body {
            font-size: 1.5rem;
            margin: 0;
            overflow: hidden;
        }

        a {
            text-decoration: none;
            color: black;
        }

        nav {
            display: flex;
            flex-direction: row;
            gap: 1.5rem;
        }

        .article-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding: 10px 0 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
        }

        th {
            background-color: #012A4A;
            color: #FFFFFF;
            font-weight: bold;
            padding: 1rem;
            text-align: left;
        }

        td {
            padding: 1rem;
        }

        tr:nth-child(even) td {
            background-color: #EAF3FF;
        }

        tr:hover td {
            background-color: #00386b;
            color: #FFFFFF;
        }

        tr:not(:last-child) td {
            border-bottom: 1px solid #D3E2FF;
        }

        .Section {
            display: flex;
            flex-direction: column;
            height: 30rem;
            justify-content: space-between;
        }

        /* Customizing the scrollbar */
        .Article {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            overflow-y: auto;
            height: 31.3rem;
        }

        .Article::-webkit-scrollbar {
            width: 1.5rem;
        }

        .Article::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .Article::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 0.75rem;
        }

        .Article::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .Article::-webkit-scrollbar-track {
            background-color: #EAF3FF;
        }

        .Article::-webkit-scrollbar-thumb {
            background-color: #012A4A;
        }

        .Article::-webkit-scrollbar-thumb:hover {
            background-color: #012A4A;
        }
    </style>

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
    $_SESSION['hotelID'] = $hotelID; //so it can be used in other files

    $allRoomsQuery = "
        SELECT COUNT(*) AS room_count
        FROM room r
        WHERE r.belongsToHotel = " . $hotelID;

    $availableRoomsQuery = "
        SELECT COUNT(*) AS available_rooms_count
        FROM room r
        WHERE r.belongsToHotel = " . $hotelID . " 
        AND NOT EXISTS (
            SELECT 1
            FROM booking b
            WHERE r.RID = b.room
            AND CURDATE() BETWEEN b.fromDate AND b.toDate
        )
    ";


    $bookedRoomsQuery = "
        SELECT COUNT(*) AS booked_rooms_count
        FROM booking b
        WHERE b.room IN (
            SELECT r.RID
            FROM room r
            WHERE r.belongsToHotel = " . $hotelID . "
        )
        AND CURDATE() BETWEEN b.fromDate AND b.toDate
    ";

}
?>


<body>

<header class="wrapper">
    <div>
        <h1>Admin Panel</h1>
        <nav>
            <a href="" class="goBack">Go Back</a>
            <a href="../../Registration/logOut.php" class="goBack">Log Out</a>
            <a href="../../Profile/viewProfile.php?userID=<?php echo $userID;?>" class="goBack">My Profile</a>
        </nav>
    </div>
</header>

<main>

    <div class="wrapper overallGrid">


        <section class="Section">
            <p><a href="adminPanel.php" class="sideButton homeButton"> <i class="fa-solid fa-house"></i>Home</a></p>
            <p><a href="calendarAndPricing.php" class="sideButton"> <i class="fa-solid fa-calendar-days"></i> Calendar & Pricing</a></p>
            <p><a href="Reservation.php" class="sideButton"><i class="fa-solid fa-calendar-plus"></i>Reservations</a></p>
            <p><a href="Property.php" class="sideButton"><i class="fa-solid fa-hotel"></i>Property</a></p>
            <p><a href="Analytics.php" class="sideButton"><i class="fa-solid fa-chart-line"></i>Analytics</a></p>
        </section>



        <article class="Article">
            <div class="article-header">
                <!-- if there are no rooms, show the link to add rooms-->
                <nav>
                    <a href="../Rooms/view.php">All Rooms (<?php $result = mysqli_query($conn, $allRoomsQuery); $row = mysqli_fetch_assoc($result); echo $row['room_count']; ?>)</a>
                    <a href="../Rooms/viewAvailableRooms.php">Available Rooms (<?php $result = mysqli_query($conn, $availableRoomsQuery); $row = mysqli_fetch_assoc($result); echo $row['available_rooms_count']; ?>)</a>
                    <a href="../Rooms/viewBookedRooms.php">Booked Rooms (<?php $result = mysqli_query($conn, $bookedRoomsQuery); $row = mysqli_fetch_assoc($result); echo $row['booked_rooms_count']; ?>)</a>
                </nav>
                <p><?php $currentDate = date("Y-m-d");
                    echo $currentDate;?></p>
            </div>
            <table>
                <?php
                if ($_SESSION['userAdmin'] == 1) {
                    $sql = 'SELECT * FROM room r, hotel h, roomtype rt WHERE r.roomType = rt.RID AND r.belongsToHotel = h.HID AND h.HID = ' . $hotelID;
                    $query = mysqli_query($conn, $sql);

                    if (mysqli_affected_rows($conn) > 0) { ?>
                        <tr>
                            <th>Room Number</th>
                            <th>Room Type</th>
                            <th>Status</th>
                            <th>Total Bookings</th>
                        </tr>
                        <?php
                        $roomDetailsQuery = "CALL GetRoomDetails(" . $_SESSION['hotelID'] . ")";
                        $roomDetailsResult = mysqli_query($conn, $roomDetailsQuery);

                        while ($row = mysqli_fetch_assoc($roomDetailsResult)) {
                            $roomNumber = $row['roomNumber'];
                            $roomType = $row['nameOfRoomType'];

                            if($row['availability'] == 0) {
                                $availability = "Booked";
                            } else {
                                $availability = "Available";
                            }

                            $numberOfRoomsBooked = $row['numberOfRoomsBooked'];

                            // Output the room details
                            echo '<tr>';
                            echo '<td>' . $roomNumber . '</td>';
                            echo '<td>' . $roomType . '</td>';
                            echo '<td>' . $availability . '</td>';
                            echo '<td>' . $numberOfRoomsBooked . '</td>';
                            echo '</tr>';
                        }

                        // Close the result set
                        mysqli_free_result($roomDetailsResult);
                        } else {
                        ?> <h3>No rooms available!</h3> <?php
                    }
                }
                mysqli_close($conn);
                ?>
            </table>
        </article>


    </div>
</main>


<footer>
</footer>

</body>

</html>
