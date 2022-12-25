<?php
include("connection.php");


switch($_REQUEST['action']){
	case 'addPost':
        // sanitise and insert post
        $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
        $content = mysqli_real_escape_string($conn, $_POST['content']);
        $statement = "INSERT INTO posts (user_id, content) VALUES('{$user_id}', '{$content}');";
        $result = mysqli_query($conn, $statement);
        if ($result) {
            $success = true;
            $error = '';
        } else {
            $success = false;
            $error = mysqli_error($conn);
        }

        // output a json string of the success and error variables back to the js
        $output = array('success' => $success, 'error' => $error);
        echo json_encode($output);
		break;
        
	case 'addComment':
        // sanitise and insert comment
        $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
        $content = mysqli_real_escape_string($conn, $_POST['content']);
        $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
        $statement = "INSERT INTO comments (user_id, content, post_id) VALUES('{$user_id}', '{$content}', '{$post_id}');";
        $result = mysqli_query($conn, $statement);
        if ($result) {
            $success = true;
            $error = '';
        } else {
            $success = false;
            $error = mysqli_error($conn);
        }

        // output a json string of the success and error variables back to the js
        $output = array('success' => $success, 'error' => $error);
        echo json_encode($output);
		break;
    
    case 'getPosts':
        if ($_POST['request'] == 'homepage') {
            $statement = "SELECT users.id, users.email, posts.post_id, posts.user_id, posts.content, posts.time FROM posts LEFT JOIN users ON posts.user_id = users.id;";
        } else {
            $request = mysqli_real_escape_string($conn, $_POST['request']);
            $statement = "SELECT users.id, users.email, posts.post_id, posts.user_id, posts.content, posts.time FROM posts LEFT JOIN users ON posts.user_id = users.id WHERE users.id = {$request};";
        }
        $result = mysqli_query($conn, $statement);
        $output = array();
        if(!$result){
            echo mysqli_error($conn);
        } else {
            while($row = mysqli_fetch_assoc($result)) {
                $output[] = $row;
            }
        }
        
        echo json_encode($output);
        break;
    
    case 'getComments':
        $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
        $statement = "SELECT users.id, users.email, comments.post_id, comments.user_id, comments.content, comments.comment_id, comments.time FROM comments LEFT JOIN users ON comments.user_id = users.id WHERE comments.post_id = {$post_id};";
        $result = mysqli_query($conn, $statement);
        $output = array();
        if(!$result){
            echo mysqli_error($conn);
        } else {
            while($row = mysqli_fetch_assoc($result)) {
                $output[] = $row;
            }
        }

        echo json_encode($output);
        break;
    
    case 'getAbout':
        $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
        $statement = "SELECT * from profiles WHERE user_id = {$user_id};";
        $result = mysqli_query($conn, $statement);
        $output = array();
        if(!$result){
            echo mysqli_error($conn);
        } else {
            while($row = mysqli_fetch_assoc($result)) {
                $output[] = $row;
            }
        }
        echo json_encode($output);
        break;
    
    case 'submitAbout':
        // sanitise and insert comment
        $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
        $bio = mysqli_real_escape_string($conn, $_POST['bio']);
        $mobile_no = mysqli_real_escape_string($conn, $_POST['mobile_no']);
        $statement = "UPDATE profiles 
        SET bio = '{$bio}', mobile_no = '{$mobile_no}'
        WHERE user_id = {$user_id};";
        $result = mysqli_query($conn, $statement);
        if ($result) {
            $success = true;
            $error = '';
        } else {
            $success = false;
            $error = mysqli_error($conn);
        }

        // output a json string of the success and error variables back to the js
        $output = array('success' => $success, 'error' => $error);
        echo json_encode($output);
        break;
    
    case 'deletePost':
        // sanitise and define query to delete post
        $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
        $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
        // post id and user id must match as a double check
        $statement = "DELETE from posts WHERE post_id = {$post_id} AND user_id = {$user_id};";
        $result = mysqli_query($conn, $statement);
        if ($result) {
            $success = true;
            $error = '';
        } else {
            $success = false;
            $error = mysqli_error($conn);
        }

        // output a json string of the success and error variables back to the js
        $output = array('success' => $success, 'error' => $error);
        echo json_encode($output);
        break;
        
	default:
        // If no action request sent by ajax call
		echo "No action was provided";
}
?>
