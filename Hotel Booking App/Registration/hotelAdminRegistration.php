<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registration</title>
    <link rel="stylesheet" href="../Login/login.css">

    <style>
        h1{
            display: flex;
            flex-direction: column;
            text-align: center;
            color: #012A4A;
        }
    </style>

</head>
<body>

<?php
session_start();
$_SESSION['continue'] = 1;
$_SESSION['init'] = 0;
?>

<div id="container">
    <h1>First, tell us a bit about yourself.</h1>
    <form action="newHotelRegistration.php" method="POST">

        <br>
        <?php
        include('userRegistrationForm.php');
        ?>
        <button type="submit">Next Page</button>
    </form>

</div>
</body>
</html>