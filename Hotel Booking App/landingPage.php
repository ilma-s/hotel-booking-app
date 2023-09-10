<?php
$conn = mysqli_connect('localhost', 'root', '', 'bookingapp');
session_start();
$_SESSION['registered'] = false;
?>

<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hotel Booking</title>
    <link rel="stylesheet" href="Admin/Landing Page/body.css">
    <link rel="stylesheet" href="Admin/Landing Page/header.css">
    <link rel="stylesheet" href="Admin/Landing Page/footer.css">
    <script src="https://kit.fontawesome.com/d742e0762f.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="fontAwesome/css/all.css">
</head>
<body>

<header>
    <?php
        if(isset($_SESSION['logged_in'])) {
            $userID =  $_SESSION['userID'];
            ?>
            <div class="wrapper">
                <h1>Sarajevo Booking</h1>
                <nav>
                    <button class="SignRegister" onclick="location='Profile/viewProfile.php?userID=<?php echo $userID;?>'">Profile</button>
                    <button class="SignRegister" onclick="location='Registration/logOut.php'">Log Out</button>
                </nav>
            </div>
            <?php
        } else if (!$_SESSION['registered']) {
            ?>
    <div class="wrapper">
        <h1>Hotel Booking</h1>
        <nav>
            <button class="SignRegister" onclick="location='Registration/registration.php'">Register</button>
            <button class="SignRegister" onclick="location='Login/login.php'">Log in</button>
        </nav>
    </div>
    <?php
        }

    ?>
</header>

<main class="wrapper">

    <section class="shade container">

        <img src="../LandingPageNew/Pictures/SarajevoEdited.jpeg" class="mainImage">
        <div class="top-left">
            <p class="firstTitle"> Escape to a New Destination </p>
            <p class="secondTitle"> Book your dream room and enjoy the best rates on the market </p>
        </div>

        <div class="wrapper bottom">
            <form action="Reservation/reservation.php" method="POST">
                <input type="text" placeholder="Destination" name="municipality">
                <input type="date" placeholder="Check-in" name="start-date" required>
                <input type="date" placeholder="Check-out" name="end-date" required>
                <input type="number" placeholder="Guests" min="1" max="30" name="num-people" required>
                <button type="submit" class="buttonForm"><i class="fas fa-search-location"></i></button>
            </form>
        </div>

    </section>

</main>

<footer>

</footer>

</body>
</html>