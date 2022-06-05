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
    $query = "SELECT forum_id, forum_name FROM Forums WHERE section_id=$section_id";
    $forums = mysqli_query($conn, $query);
    if (!$results) die("Failed to fetch forums");
    
    $data[$i]['section_id'] = $row[0];
    $data[$i]['section_name'] = $row[1];
    
    $j = 0;
    while ($forum = mysqli_fetch_row($forums)) {
        $data[$i]['forums'][$j]['forum_id'] = $forum[0];
        $data[$i]['forums'][$j]['forum_name'] = $forum[1];
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
    
    <dl id="sections">
        <?php
            function render_new_forum($name, $description, $posts) {
                return <<< EOD
                    <dd class="forum">
                        <div class="forum-info">
                            <a href="/forum.php?forum_name=$name">
                                <b>
                                    <p class="forum-name">$name</p>
                                </b>
                            </a>
                            <p class="forum-posts">posts: $posts</p>
                        </div>
                        <p class="forum-desc">$description</p>
                    </dd>                
                EOD;
            }

            
            foreach($data as $section) {
                $section_name = $section['section_name'];
                echo "<div class='section'>";
                echo "<div class='title'>";
                    echo "<h1>$section_name</h1>";
                echo "</div>";

                foreach($section['forums'] as $forum) {
                    $id = $forum['forum_id'];
                    $name = $forum['forum_name'];
                    $total = 0;
                    $results = mysqli_query($conn, "SELECT COUNT(*) AS total FROM Posts  WHERE parent_forum_id=$id");
                    if ($results) {
                        $total = mysqli_fetch_assoc($results)['total'];
                        echo render_new_forum($name, "", $total);
                        continue;
                    } 
                    
                    echo render_new_forum($name, "", 0);
                }

                echo "</div>";
            }
        ?>
        </div>
    </dl>
</body>
</html>

<?php

mysqli_close($conn);

?>