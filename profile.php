<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profile Page</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>
    <script src='main.js' defer></script>
</head>

<?php
    // check if user logged in
    require_once('authorise.php');
    if (!isset($_GET['id'])) {
        echo "<p class='alert'>No ID Specified!</p>";
        exit;
    }
?>

<body>
    <?php include('header.php'); ?>

    <main>
        <div id="button_div">
            <input type="hidden" id='user_id' value="<?php echo $_SESSION['user_id']; ?>">
            <input type='button' onclick="getAbout()" id='about_button' value='About' />
            <input type='button' onclick="getPosts()" id='get_posts_button' value='Posts' />
            <p id="success_message"></p>
        </div>
        
        <div class="posts" id="posts">
            <p id="success_message"></p>
        </div>
        <div id="about"></div>
    </main>
    <?php include('footer.php'); ?>
</body>
</html>