
<?php
    require "config.php";
    session_start();
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
        <p><?php echo $error_msg ?></p>
        <br>
        <input type="submit" value="Submit">
        
        <?php echo "USER: " . $_SESSION['Username']?>
    </form>
    
    <h1>My posts</h1>


<?php
    //Till att lägga upp inlägg
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //kollar så att ingen tom data hamnar i databasen
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
                
            
                //om taggen inte är null och inte "Select" (dvs att den kollar om någon tagg blev vald)
            if($tag != "Select" && $tag != null){
                $tagV = setTag($tag); 
                $stmt = $conn->prepare("INSERT INTO Posts (UserID, Title, Content, Anonymous, TagID) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param('issii', $user_id ,$Title, $Content, $Anonymous, $tagV);
                    if($stmt->execute()){
                        header('Location: post.php');
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

            if($delete->execute()) {
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


