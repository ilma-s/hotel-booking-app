<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Profile</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="viewProfile.css">
</head>
<body>
<h1>User Profile</h1>

<article class="grid">
<div class="left-container">

    <?php
    session_start();
    if (!isset($_SESSION['userID'])) {
        header('location: ../Login/login.php');
        exit();
    }

    $userID = $_SESSION['userID'];

    if (!isset($_GET['userID'])) {
        echo "<p class='warning'>Unavailable Resource!</p>";
        exit();
    }

    $requestedUserID = $_GET['userID'];


    //deny access to unauthorized users - users cant view other users' profile by setting a query param in the URL
    if ($userID != $requestedUserID) {
        echo "<p class='warning'>You do not have the access to this resource!</p>";
        exit();
    }

    include '../Migrations/db_connection.php';
    $conn = OpenCon();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if (isset($_GET['userID']) && isset($_SESSION['logged_in'])) {
        $userID = $_GET['userID'];
    } else {
        echo "<p class='warning'>You do not have the access to this resource!</p>";
        ?>
        <p>Try <a href="../Login/login.php">logging into</a> your account</p>
        <?php
        exit();
    }

    $sql = "SELECT * FROM users WHERE UID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>

        <section class="background">
        <div class="data">
            <p><strong>First Name: </strong> <?php echo $row['firstName']; ?></p>
            <p> <strong>Last Name: </strong> <?php echo $row['lastName']; ?></p>
            <p> <strong>Date of Birth: </strong><?php echo $row['dateOfBirth']; ?></p>
            <p> <strong>Username: </strong><?php echo $row['username']; ?></p>
            <p><strong>Email:</strong> <?php echo $row['email']; ?></p>
            <br>
        </div>

        <a href="updateProfile.php?param=<?php echo $userID; ?>" class="updateProfile">Update Profile</a>
        <a href="deleteProfile.php?param=<?php echo $userID; ?>" class="updateProfile">Delete Profile</a>
        </section>

<?php
    } else {
        echo '<p class="warning">No user found.</p>';
    }

    if (isset($_SESSION['userAdmin']) && $_SESSION['userAdmin'] == 1) {
        exit();
    }
    ?>
</div>

<div class="right-container">
    <section class="background">
    <?php
    $sql = "SELECT r.RID, rt.nameOfRoomType, rt.numberOfBeds, rt.balcony, r.minibar, r.TV, rt.privateBathroom, rt.mountainView, rt.cityView, b.fromDate, b.toDate, b.BID
                FROM booking b
                INNER JOIN room r ON b.room = r.RID
                INNER JOIN roomtype rt ON r.roomType = rt.RID
                WHERE b.customer = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h2>Booked Rooms:</h2>";

        while ($row = $result->fetch_assoc()) {
            echo "<div class='background'>";
            echo "<div class='data'>";
            echo "<h2><strong>Room Name:</strong> " . $row['nameOfRoomType'] . "</h2>";
            echo "<p><strong>Date:</strong> " . $row['fromDate'] .  " - " . $row['toDate']."</p>";
            echo "<p><strong>Number of Beds:</strong> " . $row['numberOfBeds'] . "</p>";
            echo "<p><strong>Balcony:</strong> " . ($row['balcony'] ? "Yes" : "No") . "</p>";
            echo "<p><strong>Minibar:</strong> " . ($row['minibar'] ? "Yes" : "No") . "</p>";
            echo "<p><strong>Flat Screen TV:</strong> " . ($row['TV'] ? "Yes" : "No") . "</p>";
            echo "<p><strong>Private Bathroom:</strong> " . ($row['privateBathroom'] ? "Yes" : "No") . "</p>";
            echo "<p><strong>Mountain View:</strong> " . ($row['mountainView'] ? "Yes" : "No") . "</p>";
            echo "<p><strong>City View:</strong> " . ($row['cityView'] ? "Yes" : "No") . "</p>";
            echo "</div>";

            $endDate = $row['toDate'];
            $currentDate = date('Y-m-d');
            if ($endDate < $currentDate) {
                $reviewSql = "SELECT * FROM review WHERE booking = ?";
                $reviewStmt = $conn->prepare($reviewSql);
                $reviewStmt->bind_param("i", $row['BID']);
                $reviewStmt->execute();
                $reviewResult = $reviewStmt->get_result();

                if ($reviewResult->num_rows > 0) {

                }
                else {
                    echo '<a href="submitReview.php?BID=' . $row['BID'] . '" class="review-button">Submit Review</a>';
                }
            }
            echo "</div>";
        }
    } else {
        echo "<p class='warning no-rooms-found'>No booked rooms found.</p>";
    }

    $stmt->close();
    $conn->close();
    ?>
    </section>
</div>
</article>

</body>
</html>
