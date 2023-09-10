<?php
session_start();
?>
<!doctype html>

<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Property</title>
    <link rel="stylesheet" href="adminPanel.css">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" type="text/css" href="calendar.css">
    <link rel="stylesheet" href="property.css">
    <script src="https://kit.fontawesome.com/d742e0762f.js" crossorigin="anonymous"></script>

    <style>
        nav {
            display: flex;
            flex-direction: row;
            gap: 25px;
        }
    </style>

</head>

<body>

<header class="wrapper">
    <div>
        <h1>Admin Panel</h1>
        <nav>
            <a href="" class="goBack">Go Back</a>
            <a href="../../Registration/logOut.php" class="goBack">Log Out</a>
            <a href="../../Profile/viewProfile.php?userID=<?php echo $_SESSION['$userID'];?>" class="goBack">My Profile</a>
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
            <h2> Property </h2>
            <div class="grid">
                <p class="paragraph">Want to edit information about your hotel? <br><br><a href="../Hotel/editHotel.php?HID=<?php echo $_SESSION['hotelID']; ?>" class="buttons"> Edit hotel </a></p>
                <p class="paragraph">
                    Want to add more rooms or room types? <br><br><a href="../Rooms/add.php" class="roomButton"> Add rooms</a> <a href="../Room%20Types/addRoomType.php" class="roomButton">Add room types</a></p>
            </div>
        </article>
    </div>
</main>


<footer>
</footer>

</body>

</html>