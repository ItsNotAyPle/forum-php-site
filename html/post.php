<?php

require_once '../utils/db.php';
require_once '../utils/is_logged_in.php';

if (!isset($_GET['id'])) header("Location: /");
$id = $_GET['id'];

$conn = sqlinit();
$query = "Select title, content, views, datetime_created from Posts WHERE post_id=$id";
$results = mysqli_query($conn, $query);
if (!$results) die("FAILED TO FETCH POST");

$data = mysqli_fetch_row($results);
if ($data == NULL) header("Location: /");

$comments = array();
$i = 0;


$title = $data[0];
$content = $data[1];
$views = $data[2];
$time_created = $data[3];

$query = "Select creator_id, content, datetime_created FROM Comments WHERE post_id=$id";
$results = mysqli_query($conn, $query);

if (!$results) die("FAILED TO FETCH POST");

while($row = mysqli_fetch_row($results)) {
    $user_id = $row[0];
    $query = "SELECT username FROM Users WHERE user_id=$user_id";
    $user_results = mysqli_query($conn, $query);
    $comments[$i]['username'] = mysqli_fetch_row($user_results)[0];
    $comments[$i]['content'] = $row[1];
    $comments[$i]['time_created'] = $row[2];
    $i++;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style><?php include_once '../static/globals.css'; ?></style>
    <style><?php include_once '../static/post.css'; ?></style>
    <title>Post Page</title>
</head>
<body>
    <?php include_once '../components/navbar.php'; ?>
    
    <div id='post-wrapper'>
        <div id='info-wrapper'>
            <h1><?php echo htmlspecialchars($title); ?></h1> 
            <p><?php echo $time_created; ?></p>
        </div>
        <div id='main-content'>
            <p><?php echo htmlspecialchars($content); ?></p>
        </div>
    </div>
    
    <div id="form-wrapper">
        <form id="form" action="/api/submit_comment.php" method="POST">
            <ul>
                <li><input type="hidden" name="post_id" value="<?php echo $_GET['id'] ?>"/></li>
                <li><input type="hidden" name="post_page" value="/post.php?id=<?php echo $_GET['id']; ?>"/></li>
                <?php
                    if (is_logged_in()) {
                        echo "<li><input type='text' name='content' placeholder='Post a comment'/></li>";
                    } else {
                        echo "<li><input type='text' name='content' placeholder='Sign in to post a comment' readonly/></li>";
                    }
                ?>
            </ul>
        </form>
    </div>
    
    <div id="comments">
    <?php
        foreach($comments as $comment) {
            $username = htmlspecialchars($comment['username']);
            $content = htmlspecialchars($comment['content']);
            $time_created = $comment['time_created'];
            
            echo <<< EOD
                <div class='comment'>
                    <div class='comment-info'>
                        <a href='/user.php?user=$username'>$username</a>
                        <p>$time_created</p>
                    </div>
                    <p>$content</p>
                </div>
            EOD;
        }
    ?>
    </div>
    
</body>
</html>
