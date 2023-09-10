<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="reservation.css">
    <script src="https://kit.fontawesome.com/d742e0762f.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="fontAwesome/css/all.css">
    <link rel="stylesheet" href="../Admin/Landing%20Page/header.css">
    <link rel="stylesheet" href="../banner.css">
    <style>
        .star_rating {
            color: gray;
            cursor: pointer;
            font-size: 20px;
        }

        .star_rating.selected {
            color: gold;
        }

        .star_rating.no-hover {
            color: gray;
            cursor: default;
        }
        .star_rating:hover {
            color: gold;
            cursor: default;
        }
        .starInHotel{
            color:gold;
        }
        .stars-container{
            display: flex;
            flex-direction: row;
            font-size: 30px;
        }
        a{
            text-decoration: none;
            color: darkblue;
            font-size: 22px;
        }
        a:hover{
            color: #6A87FF;
        }
    </style>

    <script>
        function selectStars(stars) {
            document.getElementById('stars').value = stars;
            highlightStars(stars);
        }

        function highlightStars(stars) {
            const starsContainer = document.getElementsByClassName('star_rating');
            for (let i = 0; i < starsContainer.length; i++) {
                if (i < stars) {
                    starsContainer[i].classList.add('selected');
                } else {
                    starsContainer[i].classList.remove('selected');
                }
            }
        }

        function disableHover() {
            const starsContainer = document.getElementsByClassName('star_rating');
            for (let i = 0; i < starsContainer.length; i++) {
                starsContainer[i].removeEventListener('mouseover', hoverHandler);
                starsContainer[i].removeEventListener('mouseout', hoverHandler);
            }
        }

        function hoverHandler() {
            const starsContainer = document.getElementsByClassName('star_rating');
            const selectedStars = document.getElementById('stars').value;
            for (let i = 0; i < starsContainer.length; i++) {
                if (i < selectedStars) {
                    starsContainer[i].classList.add('selected');
                } else {
                    starsContainer[i].classList.remove('selected');
                }
            }
        }

        function removeAllFilters() {
            const filterCheckboxes = document.querySelectorAll('input[type="checkbox"]');
            filterCheckboxes.forEach((checkbox) => {
                checkbox.checked = false;
            });

            // Optionally, you can clear the star rating selection as well
            document.getElementById('stars').value = 0;
            highlightStars(0);

            // Submit the form to remove filters
            document.querySelector('form').submit();
        }

    </script>
</head>
<body>

<header>
            <div class="wrapper">
                <h1>Sarajevo Booking</h1>
</header>


<?php
session_start();
include '../Migrations/db_connection.php';
$connection  = OpenCon();

include 'availableRooms.php';

function getHotelsByRooms($rooms)
{
    global $connection;

    $roomIds = implode(',', $rooms);
    $query = "SELECT h.HID, h.nameOfHotel, h.stars
              FROM hotel h
              JOIN room r ON h.HID = r.belongsToHotel
              WHERE r.RID IN ($roomIds)
              GROUP BY h.HID";
    $result = mysqli_query($connection, $query);

    if ($result) {
        $hotels = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $hotels[] = $row;
        }
        return $hotels;
    }

    return false;
}

function getFilteredHotels($filters, $stars, $startDate, $endDate, $numPeople, $municipality)
{
    $availableRooms = getAvailableRooms($startDate, $endDate, $numPeople, $municipality);

    if ($availableRooms) {
        $hotels = getHotelsByRooms($availableRooms);


        if ($hotels) {
            $filteredHotels = [];

            foreach ($hotels as $hotel) {
                $hotelId = $hotel['HID'];
                $hotelStars = $hotel['stars'];

                $filtered = true;
                foreach ($filters as $filter) {
                    if (!checkHotelFilter($hotelId, $filter)) {
                        $filtered = false;
                        break;
                    }
                }

                if ($filtered && $stars > 0 && $stars !== $hotelStars) {
                    $filtered = false;
                }

                if ($filtered) {
                    $filteredHotels[] = $hotel;
                }
            }

            return $filteredHotels;
        }
    }

    return false;
}

function checkHotelFilter($hotelId, $filter)
{
    global $connection;

    $query = "SELECT $filter FROM hotel WHERE HID = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $hotelId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $value = $row[$filter];

        if ($value != 1) {
            return false;
        }
    } else {
        return false;
    }

    return true;
}
$startDate = $_POST['start-date'] ?? null;
$endDate = $_POST['end-date'] ?? null;
$numPeople = $_POST['num-people'] ?? null;
$municipality = $_POST['municipality'] ?? null;

$_SESSION['startDate']=$startDate;
$_SESSION['endDate']=$endDate;
$_SESSION['numPeople']=$numPeople;
$_SESSION['municipality'] = $municipality;


$filters = $_POST['filters'] ?? array();
$selectedStars = $_POST['stars'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['filters'] = $filters;
    $_SESSION['stars'] = $selectedStars;
}

$filteredHotels = getFilteredHotels($filters, $selectedStars, $_SESSION['startDate'], $_SESSION['endDate'], $_SESSION['numPeople'], $_SESSION['municipality']);

echo '<p class="properties"><strong>PROPERTIES FOUND</strong></p>';

echo '<div class="page">';
echo '<div class="right-container">';

if ($filteredHotels) {
    foreach ($filteredHotels as $hotel) {
        $hotelId = $hotel['HID'];
        $hotelName = $hotel['nameOfHotel'];

        echo '<article class="givenHotel">';

        echo '<a href="hotel_details.php?hotel_id=' . $hotelId . '" target="_blank"><h2>' . $hotelName . '</h2></a>';

        $query = "SELECT description, averageRating FROM hotel WHERE HID = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "i", $hotelId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $description = $row['description'];
            $averageRating = $row['averageRating'];

            echo '<section class="hotel">';
            $pictureQuery = "SELECT picture FROM pictures WHERE hotel = ? AND roomType IS NULL";
            $stmt = mysqli_prepare($connection, $pictureQuery);
            mysqli_stmt_bind_param($stmt, "i", $hotelId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            echo '<div class="hotelPicture">';
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $imageName = $row['picture'];


                $imagePath = "../Uploads/" . $imageName;

                echo '<img src="' . $imagePath . '">';

            } else {
                echo "<p class='warning'>No image found for the specified hotel.</p>";
            }
            echo '</div>';
            echo '<div class="hotelDetails">';
            echo '<p><strong>Description:</strong> ' . $description . '</p>';
            if ($averageRating < 3) {
                echo '<p><strong>Averge Rating:</strong> ' . $averageRating . '</p>';
            } elseif ($averageRating >= 3 && $averageRating < 3.9) {
                echo '<p><strong>Good:</strong> ' . $averageRating . '</p>';
            } elseif ($averageRating >= 4.0 && $averageRating < 4.6) {
                echo '<p><strong>Very Good:</strong> ' . $averageRating . '</p>';
            } elseif ($averageRating >= 4.6 && $averageRating <= 5) {
                echo '<p><strong>Fabulous:</strong> ' . $averageRating . '</p>';
            }
            echo '<div class="stars-container">';
            for ($i = 0; $i < $hotel['stars']; $i++) {
                echo '<span class="starInHotel">★</span>';
            }
            echo '</div>';
            echo '</div>';

        } else {
            echo '<p class="warning">Hotel details not found.</p>';
        }
        echo '</section>';
        echo '</article>';

        echo '<br>';
    }
} else {
    echo '<p class="warning">No hotels found.</p>';
}
echo '</div>';
?>
<div class="left-container">
<form method="POST" action="">
    <label><strong>Filters:</strong></label>
    <div>
        <input type="checkbox" name="filters[]" id="fitness" value="gym" <?php if (in_array('gym', $filters)) echo 'checked'; ?>>
        <label for="fitness">Fitness</label>
    </div>
    <div>
        <input type="checkbox" name="filters[]" id="pool" value="pool" <?php if (in_array('pool', $filters)) echo 'checked'; ?>>
        <label for="pool">Pool</label>
    </div>
    <div>
        <input type="checkbox" name="filters[]" id="free-parking" value="freeParking" <?php if (in_array('freeParking', $filters)) echo 'checked'; ?>>
        <label for="free-parking">Free Parking</label>
    </div>
    <div>
        <input type="checkbox" name="filters[]" id="free-wifi" value="freeWifi" <?php if (in_array('freeWifi', $filters)) echo 'checked'; ?>>
        <label for="free-wifi">Free WiFi</label>
    </div>
    <div>
        <input type="checkbox" name="filters[]" id="restaurant" value="restaurant" <?php if (in_array('restaurant', $filters)) echo 'checked'; ?>>
        <label for="restaurant">Restaurant</label>
    </div>
    <div>
        <input type="checkbox" name="filters[]" id="bar" value="bar" <?php if (in_array('bar', $filters)) echo 'checked'; ?>>
        <label for="bar">Bar</label>
    </div>
    <div>
        <input type="checkbox" name="filters[]" id="pet-friendly" value="petFriendly" <?php if (in_array('petFriendly', $filters)) echo 'checked'; ?>>
        <label for="pet-friendly">Pet-friendly</label>
    </div>
    <div>
        <input type="checkbox" name="filters[]" id="disability-friendly" value="disabilityFriendly" <?php if (in_array('disabilityFriendly', $filters)) echo 'checked'; ?>>
        <label for="disability-friendly">Facilities for disabled guests</label>
    </div>

    <input type="hidden" name="start-date" value="<?php echo $startDate; ?>">
    <input type="hidden" name="end-date" value="<?php echo $endDate; ?>">
    <input type="hidden" name="num-people" value="<?php echo $numPeople; ?>">
    <input type="hidden" name="municipality" value="<?php echo $municipality; ?>">

    <br>

    <div class="starsForm">
    <label for="stars">Hotel Stars</label>
        <div>
            <?php
            for ($i = 1; $i <= 5; $i++) {
                $selected = ($i <= $selectedStars) ? 'selected' : '';
                echo '<span class="star_rating ' . $selected . '" data-star="' . $i . '" onclick="selectStars(' . $i . '); disableHover()">' . '★</span>';
            }
            ?>
        </div>

    <input type="hidden" name="stars" id="stars" value="<?php echo $selectedStars; ?>">
    <button type="submit" class="filterButton">Apply Filter</button>
    <button type="button" onclick="removeAllFilters()" class="filterButton">Remove Filters</button>

    </div>
</form>
</div>
</body>
</html>