
<?php
    session_start(); // start the session

    if(isset($_SESSION['username'])) :?>

    <?php 
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

        

        <input type="submit" value="Submit">
        
        <?php echo "USER: " . $_SESSION['username']?>
    </form>
    
    <h1>My posts</h1>


<?php
    require "config.php";
    //Till att skriva ut "Mina" posts (inloggade användarens posts)
    if(isset($_SESSION['username'])) {
        
        $usr = $_SESSION['username'];
        $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

        //Används till att radera inlägg genom att hitta idt på inlägget och användarnamnet på användaren
        if(isset($_POST['delete_post'])) {
            $post_id = $_POST['post_id'];
            $sql = "DELETE FROM posts WHERE id = '$post_id' AND username = '$usr'";
            $result = $conn->query($sql);
            if($result) {
                echo '<div class="success-message">Post deleted successfully.</div>';
            } else {
                echo '<div class="error-message">Error deleting post.</div>';
            }
        }

        $sql = "SELECT * FROM posts WHERE username = '$usr' ORDER BY created_at DESC";
        $result = $conn->query($sql);
        //echo mysqli_num_fields($result);
        while ($row = $result->fetch_assoc()) {
            echo '<div class="post-container">';
            echo '<div class="post-header">' . $row['post_name'] . '</div>';
            echo '<div class="post-meta">By ' . $row['author'] . " (you)". ' on ' . $row['created_at'] . '</div>';
            echo '<div class="post-content">' . $row['post_data'] . '</div>';

            // Display the tags for the post
            $tags = explode(',', $row['tags']);
            echo '<div class="post-tags">';
            foreach($tags as $tag) {
                echo '<span class="tag ' . $tag . '">' . $tag . '</span>';
            }
            echo '</div>';

            echo '<form method="POST" id="delG">';
            echo '<input type="hidden" name="post_id" value="' . $row['id'] . '">';
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


<?php
    //Till att lägga upp inlägg

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
       if(isset($_POST['post-name']) && isset($_POST['tags'])){
         if(!empty($_POST['post-data'] && !empty($_POST['post-name']))){
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "BelConnectDB";
            $conn = mysqli_connect($servername, $username, $password, $dbname);
            
            $username = $_SESSION['username'];
            $postName = strip_tags($_POST['post-name']);
            $postData = strip_tags($_POST['post-data']);
            $author = isset($_POST['anonymous']) ? "Anonymous" : $_SESSION['username'];
            $tags = implode(',', $_POST['tags']);
            
            $sql = "INSERT INTO posts (author, username, post_name, post_data, tags) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssss', $author ,$username, $postName, $postData, $tags);
            $stmt->execute();
            header("Location: post.php");
         }else{
            echo "<script>alert('Please fill out all fileds.')</script>";
        }
            
       }
    
    }
    
?>

