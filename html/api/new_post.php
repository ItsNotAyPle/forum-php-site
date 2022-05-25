<?php

require_once '../../utils/is_logged_in.php';
require_once '../../utils/db.php';

if(isset($_POST['forum_id']) && isset($_POST['content']) && isset($_POST['title']) && is_logged_in()) {
    $forum_id = $_POST['forum_id'];
    $content = $_POST['content'];
    $title = $_POST['title'];
    $user_id = $_SESSION['id'];

    $query = "INSER INTO Posts (creator_id, title, content, parent_forum_id) VALUES ($user_id, $title, '$content', $forum_id)";
    $conn = sqlinit();
    $results = mysqli_query($conn, $query);
    if (!$results) {
        echo http_response_code(500);
        die(mysqli_error($conn));
    }

    echo http_response_code(200);
}