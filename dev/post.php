
<?php
    require "config.php";
    session_start(); // start the session
    $error_msg = "";
    if(isset($_SESSION['Username'])) :?>

    <?php
        //sparar alla input värden efter refresh
         $Input_postName = isset($_POST['post-name']) ? $_POST['post-name'] : "" ;
         $Input_postdata = isset($_POST['post-data']) ? $_POST['post-data'] : "" ;
    ?>


        <nav>
            <div class="navbar-left">
                <a href="./home.php">Home</a>
                <a href="./post.php">Create Post</a>
                <a href="./editProfile.php">Edit Profile</a>
            </div>
            <div class="navbar-right">
                <a href="./logout.php">Logout</a>
            </div>
        </nav>

    <link rel="stylesheet" href="../assets/css/post.css">
    <h1>Create Post</h1>
    <form method="post" action="" id="pub" enctype="multipart/form-data">
        <label for="post-name">Title:</label>
        <input type="text" name="post-name" id="post-name" value="<?php echo $Input_postName;?>">

        <label for="post-data">Content:</label>
        <textarea name="post-data" id="post-data"><?php echo $Input_postdata;?></textarea>
        
        <div class="checkbox-container">
            <input type="checkbox" name="anonymous" id="anonymous">
        <span id="anonymous-label">Anonymous Post</span>
        
        </div>

        <br>
        <br>
        <label>Tags:</label>
        <div class="checkbox-container">

            <select name="tags" id="tags">  
                <option value="Select" name="select_tag">select tag</option> 
                <option value="Food" name="Food">food</option>
                <option value="Art" name="Art">art</option>
                <option value="Music" name="Music">music</option>
                <option value="Other" name="Other">other</optison>
            </select>

        </div>
        <br>
        <label for="my_image">Upload a picture</label>
        <input type = "file" name="my_image">
        <br>
        <p><?php echo $error_msg ?></p>
        <br>
        <input type="submit" value="Submit">
        
        <?php echo "USER: " . $_SESSION['Username']?>
    </form>
    
    <h1>My posts</h1>


<?php
    //Till att lägga upp inlägg
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
       if(isset($_POST['post-name']) && isset($_POST['tags'])){
         if(!empty($_POST['post-data'] && !empty($_POST['post-name']))){
            
            $username = $_SESSION['Username'];
            $Title = strip_tags($_POST['post-name']);
            $Content = strip_tags($_POST['post-data']);
            $Anonymous = isset($_POST['anonymous']) ? 1 : 0;
            $tag = $_POST['tags'];
            $user_id = $_SESSION['UserID'];
            
            
            
                function setTag($tag){
                    switch ($tag) {
                    case 'Programing':
                        return 1;
    
                    case 'Food':
                        return 2;
    
                    case 'Art':
                        return 3;
    
                    case 'Music':
                        return 4;
    
                    case 'Other':
                        return 5;
    
                    }
                }
                
            

            if($tag != "Select"){
               


                $tagV = setTag($tag);
                if($tag != null){
                    
                        if(isset($_FILES['my_image']) && $_FILES['my_image']['error'] === UPLOAD_ERR_OK){
                            $img_name = $_FILES['my_image']['name'];
                            $img_size = $_FILES['my_image']['size'];
                            $tmp_name = $_FILES['my_image']['tmp_name'];
                            $img_data = file_get_contents($tmp_name);
                            $img_type = $_FILES['my_image']['type'];
                    
                            $stmt = $conn->prepare("INSERT INTO Posts (UserID, Title, Content, Anonymous, TagID) VALUES (?, ?, ?, ?, ?)");
                            $stmt->bind_param('issii', $user_id ,$Title, $Content, $Anonymous, $tagV);
                            $stmt->execute();
                            $post_id = $stmt->insert_id;
                    
                            $stmtV = $conn->prepare("INSERT INTO Post_Pic (image_type, image_data, PostID) VALUES (?, ?, ?)");
                            $stmtV->bind_param('ssi', $img_type, $img_data, $post_id);
                    
                            if($stmtV->execute()){
                                header('Location: post.php');
                            }

                        }else{
                            $stmt = $conn->prepare("INSERT INTO Posts (UserID, Title, Content, Anonymous, TagID) VALUES (?, ?, ?, ?, ?)");
                            $stmt->bind_param('issii', $user_id ,$Title, $Content, $Anonymous, $tagV);
                            
                            if($stmt->execute()){
                                header('Location: post.php');
                            }
                        }
                        
                    
                }else{
                    echo "<script>console.log('Debug Objects: " . "issue" . "' );</script>";
                }

                

                
            }else{
                $error_msg = "Please select a tag!";
            }
         }else{
            $error_msg = "Please fill out all fileds.";
        }
            
       }
    
    }
    
?>



<?php
//Tar bort inlägg
    //Till att skriva ut "Mina" posts (inloggade användarens posts)
    if(isset($_SESSION['Username'])) {
        
        $usr = $_SESSION['Username'];

        //Används till att radera inlägg genom att hitta idt på inlägget och användarnamnet på användaren
        if(isset($_POST['delete_post'])) {

            $delete = $conn->prepare('DELETE FROM Posts WHERE PostId=?');
            $delete->bind_param('i', $_POST['post_id']);

            $delete_picture = $conn->prepare('DELETE FROM Post_Pic WHERE PostId=?');
            $delete_picture->bind_param('i', $_POST['post_id']);

            if($delete->execute() && $delete_picture->execute()) {
                echo '<div class="success-message">Post deleted successfully.</div>';
            } else {
                echo '<div class="error-message">Error deleting post.</div>';
            }
        }

        //visar posts som finns
        $getPost_stamt = $conn -> prepare("SELECT * FROM Posts WHERE UserID =? ORDER BY created_at DESC");
        $getPost_stamt->bind_param('s', $_SESSION['UserID']);
        $getPost_stamt->execute();
        $results = $getPost_stamt->get_result();

        while ($row = $results->fetch_assoc()) {

            $getPost_stamt = $conn->prepare("SELECT * FROM Post_Pic WHERE PostID = ?");
            $getPost_stamt->bind_param('i', $row['PostId']);
            $getPost_stamt->execute();
            $res = $getPost_stamt->get_result();
            $TagDATA = $res->fetch_assoc();

            $profile_pictures = [];


            while ($TagDATA) {
                if (!empty($TagDATA['image_data'])) {
                    $img_data = $TagDATA['image_data'];
                    $img_type = $TagDATA['image_type'];
                    $base64_image = base64_encode($img_data);
                    $img_src = "data:$img_type;base64,$base64_image";
                    $profile_pictures[$TagDATA['Post_picID']] = $img_src;
                }
                $TagDATA = $res->fetch_assoc();
            }

            if (isset($profile_pictures[$row['PostId']])) {
                $img_src = $profile_pictures[$row['PostId']];
                echo '<img src="' . $img_src . '" width="80" height="80" style="float: right; margin-top: 33px;">';
            }
            //Denna while loop gör så att alla bilder hamnar i "profile_pictures" Arrayn
         


            $Author = $row['Anonymous'] ? "Anonymous" : $_SESSION['Username'];
            echo '<div class="post-container">';
            echo '<div class="post-header">' . $row['Title'] . '</div>';
            echo '<div class="post-meta">By ' . $Author . " (you)". ' on ' . $row['Created_at'] . '</div>';
            echo '<div class="post-content">' . $row['Content'] . '</div>';

            $getPost_stamt = $conn -> prepare("SELECT * FROM Tags WHERE TagID =?");
            $getPost_stamt->bind_param('s', $row['TagID']);
            $getPost_stamt->execute();
            $res =$getPost_stamt->get_result();
            $TagDATA = $res->fetch_assoc();
            // Display the tags for the post
            $tags = explode(',', $TagDATA['Tagname']);
            echo '<div class="post-tags">';
            foreach($tags as $tag) {
                echo '<span class="tag ' . $tag . '">' . $tag . '</span>';
            }
            echo '</div>';

            echo '<form method="POST" id="delG">';
            echo '<input type="hidden" name="post_id" value="' . $row['PostId'] . '">';
            echo '<button type="submit" name="delete_post">Delete Post</button>';
            echo '</form>';

            echo '</div>';
            
        }
        

    }

?>


<?php else : ?>
    <h1>no access</h1>
    <a href="./logout.php">Back</a>
<?php endif; ?>


