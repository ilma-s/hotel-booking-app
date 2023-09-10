<?php ?>

<!doctype html>

<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reservations</title>
    <link rel="stylesheet" type="text/css" href="calendar.css">
    <link rel="stylesheet" href="adminPanel.css">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="Reservation.css">
    <script src="https://kit.fontawesome.com/d742e0762f.js" crossorigin="anonymous"></script>
</head>

<body>
<header class="wrapper">
    <div>
        <h1>Admin Panel</h1>
        <nav>
            <a href="" class="goBack">Go Back</a>
            <a href="../../Registration/logOut.php" class="goBack">Log Out</a>
            <a href="../../Profile/viewProfile.php?userID=<?php echo $_SESSION['userID'];?>" class="goBack">My Profile</a>
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
            <h2>Reservations</h2>

            <form method="GET" class="reservationForm">
                <label for="timePeriod">Filter by time period:</label>
                <select name="timePeriod" id="timePeriod" onchange="this.form.submit()">
                    <?php
                    $timePeriod = $_GET['timePeriod'] ?? 'all';
                    ?>
                    <option value="all" <?php echo ($timePeriod == 'all') ? 'selected' : ''; ?>>All Reservations</option>
                    <option value="last_week" <?php echo ($timePeriod == 'last_week') ? 'selected' : ''; ?>>Last Week</option>
                    <option value="last_month" <?php echo ($timePeriod == 'last_month') ? 'selected' : ''; ?>>Last Month</option>
                    <option value="last_year" <?php echo ($timePeriod == 'last_year') ? 'selected' : ''; ?>>Last Year</option>
                </select>
            </form>



            <?php
            session_start();
            $conn = mysqli_connect('localhost', 'root', '', 'bookingapp');
            if(!isset($_SESSION['userAdmin']) || $_SESSION['userAdmin'] == 0) {
                echo "You do not have access to this resource!";
                echo "<a href='../../Login/login.php'>Login as admin </a>";
                exit();
            } else {
            // Modify the SQL query based on the selected time period
            $sqlQuery = "SELECT * FROM BookingInformationView";

            if ($timePeriod === 'last_week') {
            // Code for 'last_week' option
            $sqlQuery .= " WHERE fromDate >= DATE_SUB(NOW(), INTERVAL 1 WEEK) AND fromDate <= NOW()";
            ?>
            <h3>Reservations made last week:</h3>
            <br>
            <?php
            } elseif ($timePeriod === 'last_month') {
            // Code for 'last_month' option
            $sqlQuery .= " WHERE fromDate >= DATE_SUB(NOW(), INTERVAL 1 MONTH) AND fromDate <= NOW()";
            ?>
            <h3>Reservations made last month: </h3>
            <br>
            <?php
            } elseif ($timePeriod === 'last_year') {
            // Code for 'last_year' option
            $sqlQuery .= " WHERE fromDate >= DATE_SUB(NOW(), INTERVAL 1 YEAR) AND fromDate <= NOW()";
            ?>
            <h3>Reservations made last year: </h3>
            <br>
            <?php
            } else { ?>
                <h3>All reservations: </h3>
            <br> <?php
            }

            $rowQuery = mysqli_query($conn, $sqlQuery);

            if (mysqli_num_rows($rowQuery) > 0) {
            while ($result = mysqli_fetch_assoc($rowQuery)) {
            // Display reservation details
            ?>
            <div>
                <p>Customer: <?php echo $result['customerName']; ?></p>
                <p>Room: <?php echo $result['nameOfRoomType'] . " #" . $result['roomNumber']; ?></p>
                <p>Booked from: <?php echo $result['fromDate'] . " to " . $result['toDate'] . " (" . $result['nightCount'] . " nights)"; ?></p>
                <p>Total cost: <?php echo $result['totalCost']; ?></p>
            </div>
            <br>
            <?php
            }
            } else {
                echo "<h3 class='warning'>No reservations were made!</h3>";
            }

            }
            ?>
        </article>

    </div>
</main>


<footer>
</footer>

</body>

</html>
