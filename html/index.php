<?php

require_once '../utils/db.php';

$conn = sqlinit();
$query = "SELECT id, name FROM Sections";
$results = mysqli_query($conn, $query);
$data = array();
$i = 0;

if (!$results) die("Failed to fetch sections");

while ($row = mysqli_fetch_row($results)) {
    $section_id = $row[0];
    $query = "SELECT forum_name FROM Forums WHERE section_id=$section_id";
    $forums = mysqli_query($conn, $query);
    if (!$results) die("Failed to fetch forums");
    
    $data[$i]['section_id'] = $row[0];
    $data[$i]['section_name'] = $row[1];
    
    $j = 0;
    while ($forum = mysqli_fetch_row($forums)) {
        $data[$i]['forums'][$j]['forum_name'] = $forum[0];
        $j++;
    }
    
    $i++;
}

// just for testing
//$data = json_encode($data);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style><?php include_once '../static/globals.css'; ?></style>
    <style><?php include_once '../static/index.css'; ?></style>
    <title>Home Page</title>
</head>
<body>
    <?php include_once '../components/navbar.php'; ?>
    
    <?php
    function render_new_forum($name, $posts=0) {
        return <<< EOD
        <a href="/forum.php?forum_name=$name">
            <div class='forum-wrapper'>
                <div class='forum'>
                    <h3>$name</h3>
                    <p>posts: $posts</p>
                </div>
            </div>
        </a>
        
        EOD;
    }
    
    
    foreach($data as $section) {
        $section_name = $section['section_name'];
        echo "<div class='section'>";
        echo "<h1>$section_name</h1>";
        
        foreach($section['forums'] as $forum) {
            $name = $forum['forum_name'];
            echo render_new_forum($name);
        }
        
        echo "</div>";
    }
    
    ?>
    
</body>
</html>
