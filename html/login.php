<?php
    if (isset($_POST['username']) && isset($_POST['password'])) {
        require_once '../utils/on_login.php';
        require_once '../utils/db.php';
        
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $query = "SELECT user_id, salt, password_hash FROM Users WHERE username='$username'";
        $conn = sqlinit();
        
        $results = mysqli_query($conn, $query);
        if (!$results) die(mysqli_error($conn));

        $data = mysqli_fetch_row($results);
        $id = $data[0];
        $salt = $data[1];
        $password_hash = $data[2];

        if (password_verify("$password$salt", $password_hash)) {
            echo http_response_code(200);
            on_login($id, $username);
            header("Location: /");
        }  else {
            echo http_response_code(401);
            header("Location: /login.php?username=$username&error=Incorrect Password");       
        }

        die();
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
                   <input type="text" name="username" placeholder="username" value=<?php echo $_GET['username']?>>
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