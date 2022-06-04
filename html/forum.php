<?php

function fetch_forum_id($conn, $name) {
    $query = "SELECT forum_id FROM Forums WHERE forum_name='$name'";
    return mysqli_query($conn, $query);
}

require_once '../utils/db.php';
require_once '../utils/is_logged_in.php';

?>

<?php if(isset($_GET['forum_name']) && isset($_GET['create_post'])): ?>
<?php

$forum_name = $_GET['forum_name'];

if (!is_logged_in()) die(header("Location: forum.php?forum_name=$forum_name"));

$conn = sqlinit();
$name = mysqli_real_escape_string($conn, $forum_name);
$results = fetch_forum_id($conn, $name);
if (!$results) die(mysqli_error($conn));

$id = mysqli_fetch_row($results)[0];
if ($id == NULL) {
    header("Location: /");
}


?>

    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style><?php include_once '../static/globals.css'; ?></style>
    <style><?php include_once '../static/new_post.css'; ?></style>
    <title>New post</title>
</head>
<body>        
    <div id="submit-wrapper">
        <h1><?php echo $name; ?></h1>
        <p>Make a post</p>
        <div id="form-area">
            <form name="submit_form" action="/api/new_post.php" method="POST">
                <input type="hidden" name="forum_id" value="<?php echo $id; ?>">
                <input type="text" name="title" placeholder="title">
                <textarea name="content" rows="40" placeholder="Content..."></textarea>
                <input name="submit" type="submit">
            </form>
        </div>
    </div>
</body>
</html>
            
<?php else: ?>
<?php



if (isset($_GET['forum_name'])) {
    $name = $_GET['forum_name'];
    $data = array();
    $i = 0;

    $conn = sqlinit();
    $result = fetch_forum_id($conn, $_GET['forum_name']);

    $forum_id = mysqli_fetch_row($result)[0]; 
    if (!$forum_id) header("Location: /index.php");
    
    // select top 20 posts
    $query = "SELECT post_id, creator_id, title, content, datetime_created FROM Posts WHERE parent_forum_id=$forum_id";
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
        $i++;
    }


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

    <?php if(is_logged_in()): ?>
    <a href='/forum.php?forum_name=<?php echo $name; ?>&create_post=1'>Create new post</a>
    <?php endif; ?>


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

    foreach ($data['posts'] as $post) {
        $post_id = $post['post_id'];
        $user = $post['username'];
        $title = $post['title'];
        $time_created = $post['datetime_created'];
        echo render_new_post($post_id, $user, $title, $time_created);
    }

    ?>

</body>
</html>

<?php endif; ?>