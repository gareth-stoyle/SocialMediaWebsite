<!DOCTYPE html>
<html lang="en">
<head>
    <title>Social Feed</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="main.css">
    <script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>
    <script src='main.js' defer></script>
</head>

<?php
    // check if user logged in
    require_once('authorise.php');
?>

<body>
    <?php include('header.php'); ?>

    <h2>Your Feed</h2>

    <div id="create_post_container">
        <input type='textarea' id='post_textarea'>
        <input type="hidden" id='user_id' value="<?php echo $_SESSION['user_id']; ?>">
        <input type='button' id='create_post_button' value='Post' />
        <p id="success_message"></p>
    </div>
    
    <div class="posts" id="posts">
        


        
    </div>
    <script>
        $(function() {
            getPosts();
        });
    </script>

    <?php include('footer.php'); ?>
</body>
</html>