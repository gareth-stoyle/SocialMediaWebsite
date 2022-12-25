<?php
//destroys the session so next time session_start() is run a new session id will be generated
session_start();
if (isset($_COOKIE['sessionkey'])) {
    unset($_COOKIE['sessionkey']);
    setcookie('sessionkey', '', time() - 3600, ''); // empty value and old timestamp
}
session_destroy();
header("Location: login.php?success=You have been successfully logged out.");
?>
