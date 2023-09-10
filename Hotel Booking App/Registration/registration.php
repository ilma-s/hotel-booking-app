<?php
session_start();

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registration</title>
    <link rel="stylesheet" href="../Login/login.css">
    <link rel="stylesheet" href="registration.css">
    <style>
        h1{
            color: #012A4A;
            display: flex;
            flex-direction: column;
            text-align: center;
        }
    </style>
</head>
<body>
<div id="container">
    <h1>Registration Form</h1>
    <form action="registrationProcessing.php" method="POST">

        <a href="hotelAdminRegistration.php" class="goToMain">I want to register a property</a>
        <br>

        <?php include('userRegistrationForm.php');
        $_SESSION['init'] = 1;
        ?>

        <button type="submit">Register</button>
    </form>
</div>
</body>
</html>