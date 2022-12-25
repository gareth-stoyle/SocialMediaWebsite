<?php

include("connection.php");

// check if password and email were posted
if (isset($_POST['email']) && isset($_POST['password'])) {
    // santiise and then retrieve user
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $statement = "SELECT * FROM users WHERE email = '{$email}';";
    $result = mysqli_query($conn, $statement);
    if (!$result) {
        echo mysqli_error($conn);
    } else {
        // first set location incase of issue with retrieving email
        $location = 'login.php?error=Incorrect email or password entered';
        while ($row = mysqli_fetch_assoc($result)) {
            // if password correct, start session and direct to home.php
            if (password_verify($_POST['password'], $row['password'])) {
                // SUCCESS
                session_start();
                $_SESSION['id'] = session_id();
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['loggedin'] = true;
                $location = 'home.php';
                
                // if remember me ticked, randomly generate the key and store it in a database
                if ($_POST['remember'] == "on") {
                    $expiry = time() + 3600*24*7; //expire in 7 days
                    $session_key = rand(1000000, 100000000); // between 1 and 100 million
                    setcookie('sessionkey', $session_key, $expiry);
                    $update = "UPDATE `users` SET `sessionkey` = '{$session_key}' WHERE `users`.`email` = '{$email}';";
                    $updated = mysqli_query($conn, $update);
                    if (!$updated) {
                        echo mysqli_error($conn);
                    }
                }
            } else {
                // if password incorrect
                $location = 'login.php?error=Incorrect email or password entered';
            }
        }
    }
    // send to defined location if email and pass is set
    header("Location: $location");
} else {
    echo "<strong>Error: Page Unavailable</strong>";
}

?>

