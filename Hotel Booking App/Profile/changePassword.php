<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="profile.css">
    <title>Change Password</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="../Login/login.css">
</head>
<body>

    <?php if (isset($_GET['changePasswordError'])) { ?>
        <div class="error-banner">
            <p><?php echo $_GET['changePasswordError']; ?></p>
        </div>
    <?php } ?>
    <?php
    session_start();

    include '../Migrations/db_connection.php';
    $conn = OpenCon();

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_GET['param'])) {
            $userID = $_GET['param'];

            // Retrieve the current user's password from the database
            $sql = "SELECT password FROM users WHERE UID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $storedHashedPassword = $row['password'];

                // Verify if the entered current password matches the one in the database
                $enteredCurrentPassword = $_POST['currentPassword'];
                $newPassword = $_POST['newPassword'];
                $confirmPassword = $_POST['confirmPassword'];

                $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                if (password_verify($enteredCurrentPassword, $storedHashedPassword)) {
                    if ($newPassword === $confirmPassword) {
                        // Update the password in the database
                        $updateSql = "UPDATE users SET password = ? WHERE UID = ?";
                        $updateStmt = $conn->prepare($updateSql);
                        $updateStmt->bind_param("si", $hashedNewPassword, $userID);
                        $updateStmt->execute();

                        if ($updateStmt->affected_rows > 0) {
                            header("Location: updateProfile.php?userID=$userID");
                        } else {
                            header('Location: changePassword.php?changePasswordError=Failed to update the password.');
                        }

                        $updateStmt->close();
                    } else {
                        header('Location: changePassword.php?changePasswordError=New password and confirm password do not match.');
                    }
                } else {
                    header('Location: changePassword.php?changePasswordError=Incorrect current password. Please try again.');
                }
            } else {
                echo "No user found.";
            }

            $stmt->close();
        }
    }

    $conn->close();
    ?>
    <h1>Change Password</h1>
    <form action="" method="POST">

        <div class="alignment">
            <label for="currentPassword">Current Password:</label>
            <input type="password" id="currentPassword" name="currentPassword" required>


            <label for="newPassword">New Password:</label>
            <input type="password" id="newPassword" name="newPassword" required>

            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" id="confirmPassword" name="confirmPassword" required>

            <input type="submit" value="Change Password" class="button">
        </div>
    </form>
</div>

</body>
</html>