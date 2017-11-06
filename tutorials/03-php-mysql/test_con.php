<?php

$user = 'root';
$password = 'root';
$db = 'mytestdatabase';
$host = 'localhost';
$port = 8889;

// First method
$link = mysqli_init();
$success = mysqli_real_connect(
    $link,
    $host,
    $user,
    $password,
    $db,
    $port
);

if ($success) {
    echo "Success";
} else {
    echo "Nope";
}
$link -> close();


// Second method
$c = mysqli_connect("localhost","root","root","mytestdatabase");

if ($c) {
    echo "Success";
} else {
    echo "Nope";
}
$c -> close();

// Third Method
$mysqli = new mysqli($host, $user, $password, $db);

if (mysqli_connect_error()) {
    die('Connect Error ('.mysqli_connect_errno().') '.mysqli_connect_error());
}

echo 'Connected successfully.';

$mysqli->close();


?>