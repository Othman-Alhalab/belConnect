
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
    <form method="post" action="" id="pub">
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
            <input type="checkbox" name="tags[]" value="programming" id="programming-tag">
            <label for="programming-tag">Programming</label>

            <input type="checkbox" name="tags[]" value="food" id="food-tag">
            <label for="food-tag">Food</label>

            <input type="checkbox" name="tags[]" value="art" id="art-tag">
            <label for="art-tag">Art</label>

            <input type="checkbox" name="tags[]" value="music" id="music-tag">
            <label for="music-tag">Music</label>

            <input type="checkbox" name="tags[]" value="other" id="other-tag">
            <label for="other-tag">Other</label>
        </div>
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
            $Author = isset($_POST['anonymous']) ? "Anonymous" : $_SESSION['Username'];
            $tags = implode(',', $_POST['tags']);
            $user_id = $_SESSION['UserID'];
            
            $stmt_tag = $conn->prepare("INSERT INTO Tags (Tags) VALUES (?)");
            $stmt_tag->bind_param('s', $tags);
            $stmt_tag->execute();
            $tag_id = $conn->insert_id;
            
            $stmt = $conn->prepare("INSERT INTO Posts (UserID, Author, Username, Title, Content, TagID) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('issssi', $user_id, $Author, $username ,$Title, $Content, $tag_id);
            
            if($stmt->execute() === TRUE){
                echo "POST UPLOADED";
                header('Location: post.php');
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
            //nytt

            /*
            $getPost_stamt = $conn -> prepare("SELECT * FROM Tags WHERE TagID =?");
            $getPost_stamt->bind_param('s', $row['TagID']);
            $getPost_stamt->execute();
            $res =$getPost_stamt->get_result();
            $TagDATA = $res->fetch_assoc();
            */
            //////////////////////////////////

            $delete = $conn->prepare('DELETE FROM Posts WHERE PostId=? AND Username=?');
            $delete->bind_param('ss', $_POST['post_id'], $_SESSION['Username']);

            //nytt
            $delete_tag = $conn->prepare('DELETE FROM Tags WHERE TagID=?');
            $delete_tag->bind_param('i', $_POST['post_id']);


            if($delete->execute() && $delete_tag->execute()) {
                echo '<div class="success-message">Post deleted successfully.</div>';
            } else {
                echo '<div class="error-message">Error deleting post.</div>';
            }
        }

        //visar posts som finns
        $getPost_stamt = $conn -> prepare("SELECT * FROM Posts WHERE Username =? ORDER BY created_at DESC");
        $getPost_stamt->bind_param('s', $_SESSION['Username']);
        $getPost_stamt->execute();
        $results = $getPost_stamt->get_result();
        while ($row = $results->fetch_assoc()) {
            $getPost_stamt = $conn -> prepare("SELECT * FROM Tags WHERE TagID =?");
            $getPost_stamt->bind_param('s', $row['TagID']);
            $getPost_stamt->execute();
            $res =$getPost_stamt->get_result();
            $TagDATA = $res->fetch_assoc();
            echo '<div class="post-container">';
            echo '<div class="post-header">' . $row['Title'] . '</div>';
            echo '<div class="post-meta">By ' . $row['Author'] . " (you)". ' on ' . $row['Created_at'] . '</div>';
            echo '<div class="post-content">' . $row['Content'] . '</div>';

            // Display the tags for the post
            $tags = explode(',', $TagDATA['Tags']);
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


