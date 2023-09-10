<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <style>
        h2 {
            display: flex;
            flex-direction: row;
            justify-content: center;
            margin-left: 110px;
        }
    </style>
</head>
<body>
<?php if (isset($_GET['loginError'])) { ?>
    <div class="error-banner">
        <p><?php echo $_GET['loginError']; ?></p>
    </div>
<?php } ?>

<form id="auth-form" action="logInProcessing.php" method="POST">
    <h2>Log in to your account</h2>
    <div class="container">
        <label for="username"><b>Username</b></label>
        <input required type="text" placeholder="Enter Username" name="username" id="username">

        <label for="password"><b>Password</b></label>
        <input required type="password" placeholder="Enter Password" name="password" id="password">

        <button type="submit">Login</button>
    </div>
</form>
</body>
</html>