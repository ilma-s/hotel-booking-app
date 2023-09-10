<?php
$conn = mysqli_connect('localhost', 'root', '', 'bookingapp');
session_start();

if (isset($_GET['name'])) {
    $imageName = $_GET['name'];
    $imagePath = '../../Uploads/' . $imageName;

    // Set the appropriate headers for image display
    header('Content-Type: image/jpeg');
    header('Content-Length: ' . filesize($imagePath));

    // Output the image content
    readfile($imagePath);
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?php
if (isset($_GET['roomType'])) {
    $roomType = $_GET['roomType'];
    $query = mysqli_query($conn, "SELECT picture FROM pictures WHERE roomType = '$roomType'");
    while ($row = mysqli_fetch_assoc($query)):
        ?>
        <img src="viewPictures.php?name=<?php echo $row['picture']?>" alt="hotel room images" style="height: 100px">
    <?php
    endwhile;
}
?>
</body>
</html>
