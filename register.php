<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="main.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script type="text/javascript" src="pwstrength-bootstrap.min.js"></script>
    <?php
        include("connection.php");
    ?>        
</head>

<body>
    <div class="header">
        <img src="images/logo.jpg" class="logo" />
        <div class="header-right">
            <a href="home.php">Feed</a>
            <a href="register.php">Register</a>
            <a href="logoin.php">Login</a>
        </div>
    </div>

    <?php
    // output error if one is given
    if (isset($_GET['error'])) {
        echo "<div class='alert alert-danger'>{$_GET['error']} </div>";
    }
    ?>

    <div id="registration_form" class="main">
        <!-- Registration form that posts to PHP_SELF -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <p><strong>Sign-up with a unique email and secure password</strong></p>
            Email: <input type="email" name="email" placeholder="email" required><br>
            Re-type Email: <input type="email" name="email2" placeholder="email" required><br>
            Password: <input type="password" id="password" name="password" required><br>
            Re-type Password: <input type="password" name="password2" required><br>
            <input id="register_button" class="button" type="submit" value="Add User"></center>
        </form>

        <?php

            if(isset($_POST['email'])) {
                // throw error and exit if posted password is less than 8 chars
                // or if password or email don't match
                if (strlen($_POST['password']) < 8) {
                    header("Location: register.php?error=Password must be 8 characters long.");
                    exit;
                } else if ($_POST['password'] != $_POST['password2']) {
                    header("Location: register.php?error=Passwords don't match.");
                    exit;
                } else if ($_POST['email'] != $_POST['email2']) {
                    header("Location: register.php?error=Emails don't match.");
                    exit;
                }

                // sanitise string and hash password
                $email = mysqli_real_escape_string($conn, $_POST['email']);
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                
                // SQL query to check for duplicate email
                $email_check = "SELECT * FROM users WHERE email = '{$email}';";
                $check_result = mysqli_query($conn, $email_check);
                if (!$check_result) {
                    echo mysqli_error($conn);
                } else {
                    while ($row = mysqli_fetch_assoc($check_result)) {
                        // throw error and exit if email already exists in db
                        if ($_POST['email'] == $row['email']) {
                            header("Location: register.php?error=Select different email");
                            exit;
                        }
                    }
                }

                // SQL query to insert new user into two db tables
                $users_statement = "INSERT INTO users (email, sessionkey, password) VALUES('{$email}', 0, '{$password}');";
                $users_result = mysqli_query($conn, $users_statement);
                if (!$users_result) {
                    echo "Database error: ".mysqli_error($conn);
                } else {
                    $user_id = mysqli_insert_id($conn);
                }

                $profile_statement = "INSERT INTO profiles (user_id, bio, mobile_no) VALUES('{$user_id}', 'This is my bio.', '555-555-5555');";
                $profile_result = mysqli_query($conn, $profile_statement);
                if ($profile_result) {
                    // on success, redirect to login page with success msg
                    header("Location: login.php?success=Registration successful, please log in!");
                } else {
                    echo "Database error: ".mysqli_error($conn);
                }
            }
        ?>

    <script>
        // Password checker that then disables form submission
        $(document).ready(function () {
            $('#register').prop("disabled", true);
            let options = {};
            options.common = {
                onScore: function (options, word, totalScoreCalculated) {
                    console.log(totalScoreCalculated);
                    if(totalScoreCalculated<20){
                        $('#register').prop("disabled", true);
                    }else{
                        $('#register').prop("disabled", false);
                    }
                    return totalScoreCalculated;
                }
            };
        $('#password').pwstrength(options);
    });

        </script>
    </div>
    <?php include("footer.php"); ?>  
</body>
</html>