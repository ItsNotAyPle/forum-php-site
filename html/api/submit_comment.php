<?php

require_once '../../utils/is_logged_in.php';
require_once '../../utils/db.php';

if (!isset($_POST['content'])) die("content not set");
if (!isset($_POST['post_id'])) die("post_id not set");

if (is_logged_in()) {
    echo "LOGGED IN";

    if (!isset($_POST['post_id'])) die("post_id not set");
    if (!isset($_POST['content'])) die("content not set");
    if (!isset($_POST['post_page'])) die("post_page not set");
    
    $creator_id = $_SESSION['id'];
    $post_id = $_POST['post_id'];
    $content = $_POST['content'];
    $post_page = $_POST['post_page'];


    $query = "INSERT INTO Comments (creator_id, post_id, content) VALUES ($creator_id, $post_id, '$content')";
    $conn = sqlinit();

    $results = mysqli_query($conn, $query);
    if (!$results) {
        echo http_response_code(500);
    } else {
        echo http_response_code(200);
    }

    header("Location: $post_page");
} else {
    echo http_response_code(401);
}

// echo json_encode(array($_POST['content'], $_POST['post_id']));
