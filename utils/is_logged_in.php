<?php

require_once 'db.php';

function is_logged_in() {
    session_start();
    try {
        if (isset($_SESSION['id'])) {
            $id = $_SESSION['id'];
            $query = "SELECT token FROM Tokens WHERE id=$id";
            $conn = sqlinit();
            
            $results = mysqli_query($conn, $query);
            if (!$results) throw new Exception("FAILED TO FETCH TOKEN FROM USER");
            
            $token = mysqli_fetch_row($results)[0];
            if ($token == NULL) throw new Exception("Token doesnt exist");
            
            if (isset($_SESSION['token'])) {
                $sess_token = $_SESSION['token'];
                if ($sess_token != $token) throw new Exception("Token is wrong");
            }
            
            // final checks to make sure all the data we need is there
            if (!isset($_SESSION['username'])) throw new Exception("username not set");
            if (!isset($_SESSION['id'])) throw new Exception("id not set"); // this is already checked
            if (!isset($_SESSION['token'])) throw new Exception("token not set");
        } else throw new Exception("id not set");
    } catch (Exception $e) {
        session_destroy();
        return false;
    } 
    
    return true;
}
