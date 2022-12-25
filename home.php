<!DOCTYPE html>
<html lang="en">
<head>
    <title>Social Feed</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="main.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>
    <script src='main.js' defer></script>
</head>

<?php
    // check if user logged in
    require_once('authorise.php');
?>

<body>
    <?php include('header.php'); ?>
    <div class="main">
        <div id="create_post_container">
            <input type='textarea' id='post_textarea'>
            <input type="hidden" id='user_id' value="<?php echo $_SESSION['user_id']; ?>">
            <input type='button' id='create_post_button' value='Post' />
            <p id="success_message"></p>
        </div>
        
        <div class="posts" id="posts"></div>
        <script>
            $(function() {
                getPosts();
            });
        </script>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>