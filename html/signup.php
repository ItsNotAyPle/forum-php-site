<?php


if (isset($_POST['username'])) {
    if(!isset($_POST['password'])) header("Location: /signup.php");
    require_once '../utils/random_string.php';
    require_once '../utils/db.php';

    $username = $_POST['username'];
    $salt = random_string(35);
    $password = $_POST['password'];
    $hash_password = password_hash("$password$salt", PASSWORD_BCRYPT);

    $query = "INSERT INTO Users (username, salt, password_hash, pfp_filename) VALUES ('$username', '$salt', '$hash_password', 'default.png')";
    $conn = sqlinit();
    if (mysqli_query($conn, $query)) header("Location: /login.php?username=$username");
    else die("Error: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style><?php require_once '../static/globals.css' ?></style>
    <style><?php require_once '../static/signup.css' ?></style>
    <title>Signup Page</title>
</head>
<body>
    <div id="form-wrapper">
        <form method="POST">
            <ul>
               <li>
                   <input type="text" name="username" placeholder="username">
                </li>
                <li>
                   <input type="password" name="password" placeholder="password">
               </li> 
               <li id="submit">
                   <input type="submit" value="submit">
               </li>
            </ul>
        </form>
    </div>
</body>
</html>