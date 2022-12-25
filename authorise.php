<?php 
include("connection.php");

session_start();
if ($_SESSION['id'] != session_id() || !isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    //The user does not have a valid session set so check the cookie to see if there is something to be retrieved
    if(isset($_COOKIE['sessionkey'])){
        // sanitise and retrieve sessionkey user from db
        $session_cookie = mysqli_real_escape_string($conn, $_COOKIE['sessionkey']);
        $get_key = "SELECT * FROM users WHERE sessionkey = {$session_cookie};";
        $result = mysqli_query($conn, $get_key);
        if (!$result) {
            // CHECK THIS WORKS INSIDE THIS IF STATEMENT IF SESSION KEY IS TAMPERED WITH
            echo mysqli_error($conn);
            session_destroy();
            header("Location: login.php?error=Error: please log in");
            exit;
        } else {
            // if sessionkey is found, update session variables and allow to progress
            while ($row = mysqli_fetch_assoc($result)) {
                if ($_COOKIE['sessionkey'] == $row['sessionkey']) {
                    $_SESSION['id'] = session_id();
                    $_SESSION['loggedin'] = true;
                    $_SESSION['user_id'] = $row['id'];
                }
            }
        }
    } else {
        // if no sessionkey cookie, and no session variables, send to login.php
        session_destroy();
        header("Location: login.php?error=Error: please log in");
        exit;
    }
}
?>