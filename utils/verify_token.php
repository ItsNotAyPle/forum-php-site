<?php

require_once 'db.php';

function verify_token($id) {
    session_start();
    $conn = sqlinit();
    $query = "SELECT token FROM Tokens WHERE id=$id";
    
    $results = mysqli_query($conn, $query);
    if(!$results) return false;

    $token = mysqli_fetch_row($results);
    if ($token != NULL && isset($_SESSION['token']))  
        if ($_SESSION['token'] == $token[0]) return true;
    

    return false;
}

var_dump(verify_token(1));