<?php
session_start();

$conn = mysqli_connect('localhost', 'root', '', 'bookingapp');


$userID = $_SESSION['userID'];

// Function to check if a year is a leap year
function isLeapYear($year)
{
    return ($year % 4 === 0 && $year % 100 !== 0) || $year % 400 === 0;
}

// Function to get the number of days in a month
function getDaysInMonth($month, $year)
{
    $daysInMonth = [
        1 => 31, 2 => 28, 3 => 31, 4 => 30, 5 => 31, 6 => 30,
        7 => 31, 8 => 31, 9 => 30, 10 => 31, 11 => 30, 12 => 31
    ];

    if ($month === 2 && isLeapYear($year)) {
        return 29;
    }

    return $daysInMonth[$month];
}

// Get month and year from query parameters
$month = isset($_GET['month']) ? $_GET['month'] : date('n');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

// Validate month and year values
$month = max(1, min(12, intval($month)));
$year = max(1900, min(2100, intval($year)));

// Get the number of days in the selected month
$daysInMonth = getDaysInMonth($month, $year);

// Get the first day of the selected month
$firstDay = date('N', strtotime("$year-$month-01"));

// Create an array of month names
$monthNames = [
    'January', 'February', 'March', 'April', 'May', 'June', 'July',
    'August', 'September', 'October', 'November', 'December'
];

$condition = 5;
?>

<!doctype html>

<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calendar & Pricing</title>
    <link rel="stylesheet" type="text/css" href="calendar.css">
    <link rel="stylesheet" href="adminPanel.css">
    <link rel="stylesheet" href="header.css">
    <script src="https://kit.fontawesome.com/d742e0762f.js" crossorigin="anonymous"></script>

    <style>

        nav {
            display: flex;
            flex-direction: row;
            gap: 25px;
        }

        .date-container {
            position: relative;
        }

        .day-number {
            text-align: center;
            margin-bottom: 5px;
        }

        .tooltiptext {
            visibility: hidden;
            width: 80px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -60px;
            opacity: 0;
            transition: visibility 0.3s, opacity 0.3s;
            font-size: 14px;
        }

        td:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        .Section {
            display: flex;
            flex-direction: column;
            height: 30rem;
        }

        .Article {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            overflow-y: auto;
            height: 31.3rem;
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

            <h2> Calendar & Pricing </h2>

            <div class="container">
                <div class="calendar">
                    <p class="month-year">
                        <a href="?month=<?php echo $month - 1; ?>&year=<?php echo $year; ?>">&lt;</a>
                        <?php echo $monthNames[$month - 1] . ' ' . $year; ?>
                        <a href="?month=<?php echo $month + 1; ?>&year=<?php echo $year; ?>">&gt;</a>
                    </p>
                    <table class="calendar-table">
                        <thead>
                        <tr>
                            <th>Mon</th>
                            <th>Tue</th>
                            <th>Wed</th>
                            <th>Thu</th>
                            <th>Fri</th>
                            <th>Sat</th>
                            <th>Sun</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Initialize day counter
                        $dayCounter = 1;

                        // Create calendar rows
                        for ($row = 1; $row <= 6; $row++) {
                            echo '<tr>';

                            // Create calendar cells
                            for ($col = 1; $col <= 7; $col++) {
                                if (($row == 1 && $col < $firstDay) || ($dayCounter > $daysInMonth)) {
                                    echo '<td></td>';
                                } else {
                                    $date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $dayCounter));
                                    // Add tooltip container
                                    echo '<td><a href="?date=' . $date . '&cell=' . $row . '-' . $col . '">';
                                    echo '<div class="date-container">';
                                    echo '<span class="day-number" style="display: inline-block; text-decoration: none;">' . $dayCounter . '</span>';

                                    $numberOfRoomsBookedQuery = "SELECT GetBookedRoomsCount('$date', '" . $_SESSION['hotelID'] . "') AS booked_rooms_count";
                                    $numberOfRoomsBookedResult = mysqli_query($conn, $numberOfRoomsBookedQuery);
                                    $resultRow = mysqli_fetch_assoc($numberOfRoomsBookedResult);
                                    $numberOfRoomsBooked = $resultRow['booked_rooms_count'];

                                    $word = ($numberOfRoomsBooked == 1) ? 'Room' : 'Rooms';


                                    echo '<span class="tooltiptext">' . $numberOfRoomsBooked . ' ' . $word . ' Booked</span>';
                                    echo '</div>';
                                    echo '</td>';

                                    $dayCounter++;
                                }
                            }

                            echo '</tr>';

                            // Break the loop if all days have been displayed
                            if ($dayCounter > $daysInMonth) {
                                break;
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="calendar" id="calendar">
                    <?php
                    if (isset($_GET['date'])) {
                        // Store the scroll position in the session
                        $_SESSION['scroll_position'] = $_GET['cell'];

                        $date = $_GET['date'];

                        echo $date;
                        echo "<br>";

                        $sqlQuery = "SELECT * FROM BookingInformationView WHERE HID = " . $_SESSION['hotelID'] . " AND ('$date' BETWEEN fromDate AND toDate OR '$date' = fromDate)";

                        $rowQuery = mysqli_query($conn, $sqlQuery);

                        if(mysqli_num_rows($rowQuery) > 0) {
                            while ($result = mysqli_fetch_assoc($rowQuery)) { ?>
                                <div>
                                    <p>Customer: <?php echo $result['customerName'];?></p>
                                    <p>Room: <?php echo $result['nameOfRoomType'] . " #" . $result['roomNumber'];?></p>
                                    <p>Booked from: <?php echo $result['fromDate'] . " to " . $result['toDate'] . " (" . $result['nightCount'] . " nights)";?></p>
                                    <p>Total cost: <?php echo $result['totalCost'];?></p>
                                </div>
                                <br>
                                <?php
                            }
                        } else {
                            echo "<h3>No reservations during this date!</h3>";
                        }

                    } else {
                        // Clear the scroll position if no date is set
                        unset($_SESSION['scroll_position']);
                        ?>
                        <style>
                            .calendar + .calendar {
                                visibility: hidden;
                            }
                        </style>
                    <?php
                    }

                    if (isset($_SESSION['scroll_position'])) {
                        $scrollPosition = $_SESSION['scroll_position'];
                        echo '<script>window.location.hash = "#cell-' . $scrollPosition . '";</script>';
                        unset($_SESSION['scroll_position']);
                    }
                    ?>
                </div>

            </div>

        </article>

    </div>
</main>


<footer>

</footer>

</body>

</html>