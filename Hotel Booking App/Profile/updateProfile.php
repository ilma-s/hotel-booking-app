<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="../Login/login.css">
    <title> Update Profile </title>
</head>
<body>
<?php if (isset($_GET['updateProfileError'])) { ?>
    <div class="error-banner">
        <p><?php echo $_GET['updateProfileError']; ?></p>
    </div>
<?php } ?>

<?php
session_start();

include '../Migrations/db_connection.php';
$conn = OpenCon();

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $dateOfBirth = $_POST['dateOfBirth'];
        $email = $_POST['email'];
        $newUsername = $_POST['username']; // New username value
        var_dump($_POST);
        if($newUsername !== $_SESSION['username']) {

            $checkUsernameQuery = "SELECT * FROM users WHERE username = ? and UID != " . $userID;
            $checkStmt = $conn->prepare($checkUsernameQuery);
            $checkStmt->bind_param("s", $newUsername);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows > 0) {
                header('Location: updateProfileError.php?updateProfileError=Username already exists. Please choose a different username.');

                $checkStmt->close();
            } else {
                $checkStmt->close();

                $sql = "UPDATE users SET firstName = ?, lastName = ?, dateOfBirth = ?, email = ?, username = ? WHERE UID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssi", $firstName, $lastName, $dateOfBirth, $email, $newUsername, $userID);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    header("Location: viewProfile.php?userID=$userID");
                    exit;
                } else {
                    header('Location: updateProfileError.php?updateProfileError=Failed to update user information.');
                }

                $stmt->close();
            }
        }
    }

    $sql = "SELECT * FROM users WHERE UID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        <h1> Update Profile </h1>
        <form action="" method="POST" >


            <input type="hidden" name="UID" id="UID" required value="<?php echo $row['UID'];?>">
            <p class="alignment">
            <label>First Name:</label>
                <input type="text" name="firstName" value="<?php echo $row['firstName']; ?>">

            <label>Last name:</label>
                <input type="text" name="lastName" value="<?php echo $row['lastName']; ?>">

            <label>Date of Birth:</label>
                <input type="date" name="dateOfBirth" value="<?php echo $row['dateOfBirth']; ?>">

            <label>Email:</label>
                <input type="email" name="email" value="<?php echo $row['email']; ?>">

           <label>Username:</label>
                <input type="text" name="username" value="<?php echo $row['username']; ?>">



            </p>
            <a href="changePassword.php?param=<?php echo $userID; ?>" class="buttonHref">Change Password</a>
                <input type="submit" value="Update" class="button">
        </form>

        <?php
    } else {
        echo '<p class="warning">No user found.</p>';
    }

    $stmt->close();
} else {
    echo "<p class='warning'> User not logged in. </p";
}

$conn->close();
?>

</body>
</html>