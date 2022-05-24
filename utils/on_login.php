<?php

require_once 'db.php';

// this function isnt for signing the user in but
// making sure the tokens are correct and the correct
// data is in the session
function on_login($id, $username) {
    session_start();

    $query = "SELECT token FROM Tokens WHERE id=$id";
    $conn = sqlinit();
    $results = mysqli_query($conn, $query);
    if(!$results) die(mysqli_error($conn));

    $data = mysqli_fetch_row($results);

    if ($data != NULL) {
        $query = "DELETE FROM Tokens WHERE id=$id";
        $results = mysqli_query($conn, $query);
        if (!$results) die("FAILED TO DELETE THE TOKEN");
    }
    
    $query = "INSERT INTO Tokens (id) VALUES ($id)";
    $results = mysqli_query($conn, $query);
    if (!$results) die("FAILED TO INSERT NEW TOKEN");
    
    $query = "SELECT token FROM Tokens WHERE id=$id";
    $results = mysqli_query($conn, $query);
    if (!$results) die("FAILED TO FETCH THE NEW TOKEN");
    
    $token = mysqli_fetch_row($results)[0];

    $_SESSION['id'] = $id;
    $_SESSION['username'] = $username;
    $_SESSION['token'] = $token;
}