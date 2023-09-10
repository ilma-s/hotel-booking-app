<?php

session_start();

$conn = mysqli_connect('localhost', 'root', '', 'bookingapp');

$userID = $_SESSION['userID'];

$monthlyProfits = [];
$labels = [];

// Retrieve the earliest month with data
$firstMonthQuery = "
    SELECT MIN(CONCAT(YEAR(b.fromDate), '-', MONTH(b.fromDate))) AS first_month
    FROM booking b, room r, hotel h
    WHERE b.room = r.RID AND r.belongsToHotel = ". $_SESSION['hotelID']."; 
";

$firstMonthResult = mysqli_query($conn, $firstMonthQuery);
$row = mysqli_fetch_assoc($firstMonthResult);
$firstMonth = $row['first_month'];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];

    // Prepare the chart data for the specific date range
    $query = "
        SELECT
            YEAR(b.fromDate) AS year,
            MONTH(b.fromDate) AS month,
            SUM(DATEDIFF(b.toDate, b.fromDate) * r.price) AS profit
        FROM
            booking b
        JOIN
            room r ON b.room = r.RID
        WHERE
            b.fromDate >= '$fromDate' AND b.toDate <= '$toDate'
        GROUP BY
            YEAR(b.fromDate), MONTH(b.fromDate)
        ORDER BY
            YEAR(b.fromDate), MONTH(b.fromDate)
    ";

    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $monthlyProfits[] = $row['profit'];
        $labels[] = date('M', strtotime($row['year'] . '-' . $row['month'] . '-01'));
    }
} else {
    // Prepare the chart data for the overall monthly statistics
    $query = "
        SELECT
            YEAR(b.fromDate) AS year,
            MONTH(b.fromDate) AS month,
            SUM(DATEDIFF(b.toDate, b.fromDate) * r.price) AS profit
        FROM
            booking b
        JOIN
            room r ON b.room = r.RID
        GROUP BY
            YEAR(b.fromDate), MONTH(b.fromDate)
        ORDER BY
            YEAR(b.fromDate), MONTH(b.fromDate)
    ";

    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $monthlyProfits[] = $row['profit'];
        $labels[] = date('M', strtotime($row['year'] . '-' . $row['month'] . '-01'));
    }
}

// Create an array to hold the monthly profits and labels for all months
$allProfits = [];
$allLabels = [];

// Iterate over all 12 months
for ($i = 1; $i <= 12; $i++) {
    $monthLabel = date('M', strtotime('2000-' . $i . '-01'));

    // Check if the month exists in the retrieved data
    $monthIndex = array_search($monthLabel, $labels);
    if ($monthIndex !== false) {
        $allProfits[] = $monthlyProfits[$monthIndex];
    } else {
        $allProfits[] = null;
    }

    $allLabels[] = $monthLabel;
}

// Create the chart configuration
$chartConfig = [
    'type' => 'line',
    'data' => [
        'labels' => $allLabels,
        'datasets' => [
            [
                'label' => 'Monthly Profits',
                'data' => $allProfits,
                'backgroundColor' => 'rgba(0, 123, 255, 0.3)',
                'borderColor' => 'rgba(0, 123, 255, 1)',
                'borderWidth' => 1,
            ],
        ],
    ],
    'options' => [
        'scales' => [
            'y' => [
                'beginAtZero' => true,
            ],
        ],
    ],
];

// Convert the chart configuration to JSON
$chartData = json_encode($chartConfig);

// Create the URL for the QuickChart API
$apiUrl = 'https://quickchart.io/chart?c=' . urlencode($chartData);

?>


<!doctype html>

<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Analytics</title>
    <link rel="stylesheet" href="adminPanel.css">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" type="text/css" href="calendar.css">
    <link rel="stylesheet" href="analytics.css">
    <script src="https://kit.fontawesome.com/d742e0762f.js" crossorigin="anonymous"></script>


    <style>
        ..chart-container img {
            max-width: 100%;
            height: auto;
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
            <h2>Profit Analytics</h2>
            <h3 class="generateTitle">Generate Monthly Profit Report</h3>
            <form action="" method="POST">
                <label for="fromDate">From:</label>
                <input type="date" id="fromDate" name="fromDate" required>

                <label for="toDate">To:</label>
                <input type="date" id="toDate" name="toDate" required>

                <button type="submit" class="generate">Generate</button>
            </form>
            <div class="chart-flex">
                <div class="chart-container">
                    <img src="https://quickchart.io/chart?c=<?php $height = "200px"; $width = "200px"; echo urlencode(json_encode($chartConfig)); ?>&width=<?php echo $width; ?>&height=<?php echo $height; ?>" alt="">
                </div>
            </div>
        </article>

    </div>
</main>


<footer>
</footer>

</body>

</html>
