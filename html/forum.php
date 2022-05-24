<?php


require_once '../utils/db.php';

if (isset($_GET['forum_name'])) {
    $name = $_GET['forum_name'];
    $data = array();
    $i = 0;

    $query = "SELECT forum_id FROM Forums WHERE forum_name='$name'";
    $conn = sqlinit();
    $result = mysqli_query($conn, $query);

    $forum_id = mysqli_fetch_row($result)[0]; 
    if (!forum_id) header("Location: /index.php");
    
    // select top 20 posts
    $query = "SELECT post_id, creator_id, title, content, datetime_created FROM Posts WHERE parent_forum_id=$forum_id LIMIT 20";
    $result = mysqli_query($conn, $query);
    if (!$result) die("Error: " . mysqli_error($conn));

    while ($row = mysqli_fetch_row($result)) { 
        $post_id = $row[0];
        $user_id = $row[1];
        $title   = $row[2];
        $content = $row[3];
        $time_created = $row[4];

        $query = "SELECT username from Users WHERE user_id='$user_id'";
        $results = mysqli_query($conn, $query);
        $username = mysqli_fetch_row($results)[0];

        $data['posts'][$i]['post_id'] = $post_id;
        $data['posts'][$i]['username'] = $username;
        $data['posts'][$i]['title'] = $title;
        // $data['posts'][$i]['content'] = $content;
        $data['posts'][$i]['datetime_created'] = $time_created;
    }

    // echo json_encode($data);
    // var_dump($data);

} else {
    header("Location: /index.php");
    die();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style><?php include_once '../static/globals.css'; ?> </style>
    <style><?php include_once '../static/forum.css'; ?></style>
    <title>Home Page</title>
</head>
<body>
    <?php include_once '../components/navbar.php'; ?>

    <div id="forum-name">
        <h1><?php echo $name; ?></h1>
    </div>
    
    <?php

    function render_new_post($post_id, $user, $title, $time_created) {
        return <<< EOD
        <a href='/post.php?id=$post_id'>
            <div class='post'>
                <h1 class='post-title'>$title</h1>
                <div class='post-text'>
                    <p>created by: <b>$user</b> at <b>$time_created</b></p>
                </div>
            </div>
        </a>
        EOD;

    }

    foreach ($data['posts'] as $key => $post) {
        //echo json_encode($data);
        $post_id = $post['post_id'];
        $user = $post['username'];
        $title = $post['title'];
        $time_created = $post['datetime_created'];
        echo render_new_post($post_id, $user, $title, $time_created);
    }

    ?>

</body>
</html>
