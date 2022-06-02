<?php

require_once '../../utils/is_logged_in.php';
require_once '../../utils/db.php';

if(isset($_POST['forum_id']) && isset($_POST['content']) && isset($_POST['title'])) {
    if (!is_logged_in()) {
        die(http_response_code(401));
    }

    $conn = sqlinit();

    $forum_id = mysqli_real_escape_string($conn, $_POST['forum_id']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $user_id = $_SESSION['id'];

    $query = "INSERT INTO Posts (creator_id, title, content, parent_forum_id) VALUES ($user_id, '$title', '$content', $forum_id)";
    $results = mysqli_query($conn, $query);
    if (!$results) {
        echo http_response_code(500);
        die(mysqli_error($conn));
    }

    $post_id = mysqli_insert_id($conn);

    echo http_response_code(200);
    header("Location: /post.php?id=$post_id");
} else {
    echo http_response_code(500);
}