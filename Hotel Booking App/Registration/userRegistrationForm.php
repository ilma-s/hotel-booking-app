<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register User</title>
</head>
<body>
<div id="FLname">
    <label for="firstName">First Name:</label>
    <input type="text" id="firstName" name="firstName" required value="<?php echo isset($_SESSION['formData']['firstName']) ? $_SESSION['formData']['firstName'] : ''; ?>">
    <label for="lastName">Last Name:</label>
    <input type="text" id="lastName" name="lastName" required value="<?php echo isset($_SESSION['formData']['lastName']) ? $_SESSION['formData']['lastName'] : ''; ?>">
</div>
<div>
    <label for="dateOfBirth">Date of Birth:</label>
    <input type="date" id="dateOfBirth" name="dateOfBirth" required value="<?php echo isset($_SESSION['formData']['dateOfBirth']) ? $_SESSION['formData']['dateOfBirth'] : ''; ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required value="<?php echo isset($_SESSION['formData']['username']) ? $_SESSION['formData']['username'] : ''; ?>">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required value="<?php echo isset($_SESSION['formData']['email']) ? $_SESSION['formData']['email'] : ''; ?>">
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <label for="confirmPassword">Confirm Password:</label>
    <input type="password" id="confirmPassword" name="confirmPassword" required>
</div>
<?php

if (isset($_GET['error'])) {
    if(isset($_SESSION)) {
        $firstName = $_SESSION['formData']['firstName'];
        $lastName = $_SESSION['formData']['lastName'];
        $dateOfBirth = $_SESSION['formData']['dateOfBirth'];
        $email = $_SESSION['formData']['email'];
        $username = $_SESSION['formData']['username'];
        $password = $_SESSION['formData']['password'];
        $confirmPassword = $_SESSION['formData']['confirmPassword'];
        if($_SESSION['continue'] = 0) unset($_SESSION['formData']);
    }
    ?>
    <div class="error-banner">
        <p><?php echo $_GET['error']; ?></p>
    </div>
<?php } else {
    // Store the form data in $_SESSION['formData']
    $_SESSION['formData'] = array(
        'firstName' => isset($_POST['firstName']) ? $_POST['firstName'] : '',
        'lastName' => isset($_POST['lastName']) ? $_POST['lastName'] : '',
        'dateOfBirth' => isset($_POST['dateOfBirth']) ? $_POST['dateOfBirth'] : '',
        'email' => isset($_POST['email']) ? $_POST['email'] : '',
        'username' => isset($_POST['username']) ? $_POST['username'] : '',
        'password' => isset($_POST['password']) ? $_POST['password'] : '',
        'confirmPassword' => isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : ''
    );
}  ?>
</body>
</html>