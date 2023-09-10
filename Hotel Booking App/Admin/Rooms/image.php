<?php

$name = $_GET['name'];

header('Content-type: image/jpeg');

if (true) {
    echo file_get_contents('../Uploads/' . $name);
} else {
    echo file_get_contents('https://m.media-amazon.com/images/I/51oo7r7A7vL.jpg');
}
