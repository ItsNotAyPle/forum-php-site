<?php

function sqlinit() {
    $servername = 'localhost';
    $username   = 'root';
    $password   = 'password';
    $database   = 'forum_site_php';

    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn) {
        throw new Exception("Connection error " . mysqli_connect_error());
    }

    return $conn;
}