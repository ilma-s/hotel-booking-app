<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <title>Loading...</title>
    <link rel="stylesheet" href="registration.css">
    <style>
        /* CSS styles for the loading screen */


        #loading-screen {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 4px solid #333;
            border-top-color: #777;
            animation: spin 1s infinite linear;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>
<div id="loading-screen">
    <div class="spinner"></div>
</div>

<script>
    // JavaScript to remove the loading screen and display content after a delay
    document.addEventListener('DOMContentLoaded', function() {
        var loadingScreen = document.getElementById('loading-screen');
        var spinner = document.querySelector('.spinner');
        var content = document.createElement('div');
        content.innerHTML = "<div class='warning'>Verification Complete!" +
            "<p> <a href='../Admin/Rooms/add.php' class='goToMain'>Click here </a>to continue </p></div>";

        // Simulate a delay of 3 seconds
        setTimeout(function() {
            spinner.style.animation = 'none';
            loadingScreen.style.display = 'none';
            document.body.appendChild(content);
        }, 3000);
    });
</script>

<?php
    $_SESSION['first'] = 1;
?>

</body>
</html>







