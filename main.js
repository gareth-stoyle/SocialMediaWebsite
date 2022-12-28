$(document).on('click', '#create_post_button', function(e){ 
    /**
     * Add a post to db
     */
    // cancel the event from doing its default action
    e.preventDefault(); 
    // Remove the alert or success class if it exists from the output
    $('#success_message').removeClass("alert");
    $('#success_message').removeClass("success");
    // get post and check it's not empty
    let content = $('#post_textarea').val();
    let user_id = $('#user_id').val();   
    if (content != ''){ 
        // ajax call to send post details to php file
        url = "ajax.php"; 
        $.ajax({ 
              data: {"action": "addPost", "user_id": user_id, "content": content}, 
              url: url, 
              type: "POST", 
              dataType: "json" 
        }).done(function(response) { 
            console.log(response);
            if (response.success) {
                $('#success_message').addClass("success");
                $('#success_message').html("Successfully added post");
                //Clear the fields to allow for a new user to be added
                $('#post_textarea').val('');
            } else {
                // add alert class and output error message
                $('#success_message').addClass("alert");
                $('#success_message').html("Error creating post");
            }
        }).always( function(){
            // run getPosts to refresh the posts.
            getPosts();
        }).fail(function(response) {
            // add alert class and output error message
            $('#success_message').addClass("alert");
            $('#success_message').html("Error creating post");
            console.log(response);
        });
    }else{ 
        alert("You need to fill in all of the details"); 
    } 
});


$(document).on('click', '.comment_button', function(e){ 
    /**
     * Add a comment to db for a corresponding post
    */
    // cancel the event from doing its default action
    e.preventDefault(); 
    // Remove the alert or success class if it exists from the output
    $('#success_message').removeClass("alert");
    $('#success_message').removeClass("success");
    // get post and check it's not empty
    let user_id = $('#user_id').val();   
    let post_id = $(this).attr('id').replace('post_comment_button_', '');
    let content = $('#comment_textarea_'+post_id).val();
    if (content != ''){ 
        // ajax call to send post details to php file
        url = "ajax.php"; 
        $.ajax({ 
              data: {"action": "addComment", "user_id": user_id, "content": content, "post_id": post_id}, 
              url: url, 
              type: "POST", 
              dataType: "json" 
        }).done(function(response) { 
            console.log(response);
            if (response.success) {
                $('#success_message').addClass("success");
                $('#success_message').html("Successfully added comment");
                //Clear the fields to allow for a new user to be added
                $('#post_textarea').val('');
            } else {
                // add alert class and output error message
                $('#success_message').addClass("alert");
                $('#success_message').html("Error creating comment");
            }
        }).always( function(){
            // run getPosts to refresh the posts.
            getPosts();
        }).fail(function(response) {
            // add alert class and output error message
            $('#success_message').addClass("alert");
            $('#success_message').html("Error creating comment");
            console.log(response);
        });
    }else{ 
        alert("You need to fill in all of the details"); 
    } 
});


function getPosts(){
    /**
     * Retrieve posts from db
    */
    url = "ajax.php";
    let request = getProfileId();
    let session_user_id = $('#user_id').val(); 
    // ajax call to retrieve posts using php file
    $.ajax({
        data: {"action": "getPosts", "request": request}, 
        url: url, 
        type: "POST", 
        dataType: "json" 
    }).done(function(posts_response){
        console.log(posts_response);
        // empty the posts and about div
        $('#about').html('');
        $('#posts').html('');
        // loop through json response filling posts div with posts
        $.each(posts_response, function(index, post){
            $('#posts').append("<div class='post_container' id='post_"+post.post_id+"'>");
            if (session_user_id == post.user_id) {
                $('#post_'+post.post_id).append("<i class='fa fa-trash-o' style='font-size: 1.7em' onclick='deletePost("+post.post_id+")'></i>");
            }
            $('#post_'+post.post_id).append("<h3><a href='profile.php?id="+post.user_id+"'>"+post.email+"</a></h3>");
            $('#post_'+post.post_id).append("<p class='content' id='content_"+post.post_id+"'>"+post.content+"</p>");
            $('#post_'+post.post_id).append("<p class='timestamp'>"+post.time+"</p>");
            // Add edit button if id is a match
            console.log(session_user_id);
            console.log(post.user_id);
            $('#post_'+post.post_id).append("<div class='comment_box' id='post_comment_box_"+post.post_id+"'>");
            $('#post_comment_box_'+post.post_id).append("<input type='text' class='comment_textarea' id='comment_textarea_"+post.post_id+"'>");
            $('#post_comment_box_'+post.post_id).append("<input type='button' class='comment_button' id='post_comment_button_"+post.post_id+"' value='Comment' />");
            $('#post_comment_box_'+post.post_id).append("</div>");
            // for each post, add all comments to that post_container
            getComments(post.post_id);
            $('#post_'+post.post_id).append("</div>");
        });
    }).fail(function(response) {
        console.log(response);
    });
}



function getComments(post_id) {
    /**
     * Retrieve comments of a specified post from db
    */
    url = "ajax.php";
    // ajax call to retrieve comments of given post_id using php file
    $.ajax({
        data: {"action": "getComments", "post_id": post_id}, 
        url: url, 
        type: "POST", 
        dataType: "json" 
    }).done(function(response){
        console.log(response);
        // loop through json response filling appropriate comment div with comment
        $.each(response, function(index, comment){
            $('#post_'+post_id).append("<hr><div class='comment_container' id='comment_"+comment.comment_id+"'>");
            $('#comment_'+comment.comment_id).append("<h4>"+comment.email+"</h4>");
            $('#comment_'+comment.comment_id).append("<p class='content'>"+comment.content+"</p>");
            $('#comment_'+comment.comment_id).append("<p class='timestamp'>"+comment.time+"</p>");
            $('#comment_'+comment.comment_id).append("</div>");
        });
    }).fail(function(response) {
        console.log(response);
    });
}

function getProfileId() {
    let parts = window.location.search.slice(1).split("&");
    let $_GET = {};
    for (var i = 0; i < parts.length; i++) {
        var temp = parts[i].split("=");
        $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
    }
    if (!$_GET['id']) {
        return 'homepage';
    } else {
        return $_GET['id'];
    }
}

function getAbout() {
    /**
     * Retrieve profile details from db
    */
    url = "ajax.php";
    let user_id = getProfileId();
    let session_user_id = $('#user_id').val();   
    // ajax call to retrieve posts using php file
    $.ajax({
        data: {"action": "getAbout", "user_id": user_id}, 
        url: url, 
        type: "POST", 
        dataType: "json" 
    }).done(function(response){
        console.log(response);
        // empty the posts and about div
        $('#about').html('');
        $('#posts').html('');
        // append two divs containing bio and mobile info
        $('#about').append("<div id='bio_div'><h4>Bio: </h4><p id='bio_text'>"+response[0].bio+"</p></div>");
        $('#about').append("<div id='mobile_div'><h4>Mobile: </h4><p id='mobile_text'>"+response[0].mobile_no+"</p></div>");
        // Add edit button if id is a match
        if (session_user_id == user_id) {
            $('#about').append("<input type='button' id='edit_about' onclick='editAbout()' value='Edit' />");
        }
    }).fail(function(response) {
        console.log(response);
    });
}

function editAbout() {
    /**
     * Provide interface for editing mobile and number
     * Update profile details from db
    */
    current_bio = $('#bio_text').text();
    current_mobile = $('#mobile_text').text();
    $('#about').html('');
    $('#about').append("<div id='bio_div'><h4>Bio: </h4><input type='text' id='bio_text_input' value='"+current_bio+"'/></div>");
    $('#about').append("<div id='mobile_'><h4>Mobile: </h4><input type='text' id='mobile_text_input' value='"+current_mobile+"'/></div>");
    $('#about').append("<input type='button' id='submit_about' onclick='submitAbout()' value='Submit' />");
}

function submitAbout() {
    /**
     * Update profile details from db
    */
    // get mobile and bio content and check not empty
    let bio = $('#bio_text_input').val();
    let mobile_no = $('#mobile_text_input').val();  
    let session_user_id = $('#user_id').val();    
    if (bio != '' && mobile_no != ''){ 
        // ajax call to send post details to php file
        url = "ajax.php"; 
        $.ajax({ 
                data: {"action": "submitAbout", "user_id": session_user_id, "bio": bio, "mobile_no": mobile_no}, 
                url: url, 
                type: "POST", 
                dataType: "json" 
        }).done(function(response) { 
            console.log(response);
        }).always( function(){
            // run getAbout to refresh the about section
            getAbout();
        }).fail(function(response) {
            console.log(response);
        });
    }else{ 
        alert("You need to fill in all of the details"); 
    } 
}


function deletePost(post_id) {
    // Remove the alert or success class if it exists from the output
    $('#success_message').removeClass("alert");
    $('#success_message').removeClass("success");
    // get retrieve session user id
    let session_user_id = $('#user_id').val();   
    // ajax call to send post details to php file
    url = "ajax.php"; 
    $.ajax({ 
        data: {"action": "deletePost", "user_id": session_user_id, "post_id": post_id}, 
        url: url, 
        type: "POST", 
        dataType: "json" 
    }).done(function(response) { 
        console.log(response);
        if (response.success) {
            $('#success_message').addClass("success");
            $('#success_message').html("Successfully deleted post");
        } else {
            // add alert class and output error message
            $('#success_message').addClass("alert");
            $('#success_message').html("Error creating post");
        }
    }).always( function(){
        // run getPosts to refresh the posts.
        getPosts();
    }).fail(function(response) {
        // add alert class and output error message
        $('#success_message').addClass("alert");
        $('#success_message').html("Error creating post");
        console.log(response);
    });
}