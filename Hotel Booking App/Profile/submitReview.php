<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header('location: ../Login/login.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $bookingID = $_POST['BID'];
        $staff = $_POST['staff'];
        $comfort = $_POST['comfort'];
        $freeWifi = $_POST['freeWifi'];
        $facilities = $_POST['facilities'];
        $valueForMoney = $_POST['valueForMoney'];
        $cleanliness = $_POST['cleanliness'];
        $location = $_POST['location'];
        $comment = $_POST['comment'];


        if (empty($bookingID) || empty($staff) || empty($comfort) || empty($freeWifi) || empty($facilities) || empty($valueForMoney) || empty($cleanliness) || empty($location)) {
            echo "Please fill in all required fields.";
            exit();
        }

        $minRating = 1;
        $maxRating = 5;
        $ratings = [$staff, $comfort, $freeWifi, $facilities, $valueForMoney, $cleanliness, $location];

        foreach ($ratings as $rating) {
            if ($rating < $minRating || $rating > $maxRating) {
                echo "Invalid rating value. Ratings should be between $minRating and $maxRating.";
                exit();
            }
        }

        $maxCommentLength = 1000;
        if (strlen($comment) > $maxCommentLength) {
            echo "Comment exceeds the maximum allowed length of $maxCommentLength characters.";
            exit();
        }

        include '../Migrations/db_connection.php';
        $conn = OpenCon();

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "INSERT INTO review (booking, staff, comfort, freeWifi, facilities, valueForMoney, cleanliness, location, comment, dateSubmitted) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE())";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiiiiiss", $bookingID, $staff, $comfort, $freeWifi, $facilities, $valueForMoney, $cleanliness, $location, $comment);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Review submitted successfully.";
            header("location: viewProfile.php?userID=" . $_SESSION['userID']);
        } else {
            echo "Failed to submit the review.";
        }

        $stmt->close();
        $conn->close();

}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="../Login/login.css">
    <link rel="stylesheet" href="review.css">
    <style>

    </style>
</head>
<body>

<h1>Hotel Review</h1>
<form action="submitReview.php" method="POST">

    <p class="center">

    <label for="rating-booking" class="booking">Booking</label>
    <input type="hidden" id="rating-booking" name="BID" value="<?php echo $_GET['BID']; ?>">

    <div>
    <div class="form-row">
    <label for="rating-staff">Staff:</label>
    <select id="rating-staff" name="staff" required>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
    </select>
    </div>

    <div class="form-row">
    <label for="rating-comfort">Comfort:</label>
    <select id="rating-comfort" name="comfort" required>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
    </select>
    </div>

    <div class="form-row">
    <label for="rating-freeWifi">Free Wi-Fi:</label>
    <select id="rating-freeWifi" name="freeWifi" required>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
    </select>
    </div>

    <div class="form-row">
    <label for="rating-facilities">Facilities:</label>
    <select id="rating-facilities" name="facilities" required>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
    </select>
    </div>

    <div class="form-row">
    <label for="rating-valueForMoney">Value for Money:</label>
    <select id="rating-valueForMoney" name="valueForMoney" required>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
    </select>
    </div>


    <div class="form-row">
        <label for="rating-cleanliness">Cleanliness:</label>
        <select id="rating-cleanliness" name="cleanliness" required>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
    </div>

    <div class="form-row">
    <label for="rating-location">Location:</label>
    <select id="rating-location" name="location" required>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
    </select>
    </div>

    <div class="form-row comment">
    <label for="comment">Comment:</label>
    <textarea id="comment" name="comment"></textarea>
    </div>
    </div>

    <input type="submit" value="Submit Review" class="button">

</p>


</form>
</body>
</html>