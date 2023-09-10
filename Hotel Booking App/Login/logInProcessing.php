<?php

session_start();

include '../Migrations/db_connection.php';
$conn = OpenCon();

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$username = $_POST['username'];
$password = $_POST['password'];

//prepare and bind parameters to prevent sql injection -> sql code and the data are sent separately to ensure any possible malicious code is treated as data and not sql code
$select_query = "select * from users where username = ?";
//prepare the statement
$statement = $conn->prepare($select_query);
//bind parameters
$statement->bind_param("s", $username);
//execute the statement
$statement->execute();

$result = $statement->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $storedHashedPassword = $row['password'];

    if(!password_verify($password, $storedHashedPassword)) {
        var_dump($password, $storedHashedPassword);
        header('Location: login.php?loginError=Incorrect username and/or password');
        exit();
    }

    // if it is an array it evaluates to true, and we enter into this block where we set all these indices in $_SESSION variable
    $_SESSION['logged_in'] = true;
    $_SESSION['userID'] = $row['UID'];
    $_SESSION['userName'] = $row['firstName'] . ' ' . $row['lastName'];
    $_SESSION['userAdmin'] = $row['isAdmin'];

    $userID = $_SESSION['userID'];

    if($_SESSION['userAdmin']==1) {
        if(isset($_SESSION['loginSet']) && $_SESSION['loginSet'] == 1) {
            header('Location: ../Admin/Rooms/add.php');
        }
        header('Location: ../Admin/Landing Page/adminPanel.php');
    } else if (isset($_SESSION['previousPage'])) {
        header('Location: ' . $_SESSION['previousPage']);
        exit;
    }

    else {
        header('Location: ../landingPage.php');
    }
    exit();

} else {var_dump($result);
    echo "<br>";
    header('Location: login.php?loginError=Incorrect username and/or password');
}

$statement->close();