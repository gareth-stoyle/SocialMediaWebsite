<!DOCTYPE html>
<html lang="en">
<head>
<title>Login</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<?php
    include("connection.php");
    include("header.php");
    session_start();
?>        
</head>

<body>
<?php
// output error or success message if one is given
if(isset($_GET['error'])){
    echo "<div class='alert alert-danger'>{$_GET['error']} </div>";
} else if(isset($_GET['success'])){
    echo "<div class='alert alert-success'>{$_GET['success']} </div>";
}

// Check if logged in -> RESUME BY CHECKING THIS WORKS 
if ($_SESSION['id'] == session_id() && $_SESSION['loggedin']) {
    header("Location: home.php");
    exit;
}

// check for existence of sessionkey
if(isset($_COOKIE['sessionkey'])){
    // sanitise and retrieve user with sessionkey
    $session_cookie = mysqli_real_escape_string($conn, $_COOKIE['sessionkey']);
    $get_key = "SELECT * FROM users WHERE sessionkey = {$session_cookie};";
    $result = mysqli_query($conn, $get_key);
    if (!$result) {
        // if sessionkey doesn't exist in db, destroy session and reload page
        // CHECK THIS WORKS WHEN SESSIONKEY IS TAMPERED WITH
        session_destroy();
        header("Location: login.php");
        exit;
    } else {
        // otherwise, set session variables and load homepage
        while ($row = mysqli_fetch_assoc($result)) {
            if ($_COOKIE['sessionkey'] == $row['sessionkey']) {
                $_SESSION['id'] = session_id();
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $row['id'];
                header("Location: home.php");
            }
        }
    }
}
echo $_SESSION['id'], $_SESSION['loggedin'];
?>

<div id="login_form">
    <!-- Login form that posts to authenticate.php -->
    <form action="authenticate.php" method="post">
        <h2>Login</h2>
        Email: <input type="email" name="email" placeholder="email" required><br>
        Password: <input type="password" id="password" name="password" required><br>
        <input name="remember" id="remember" type="checkbox" /> Remember me<br>
        <input id="login" class="button" type="submit" value="Login"></center>
    </form>
</div>
<?php include("footer.php"); ?>  
</body>
</html>