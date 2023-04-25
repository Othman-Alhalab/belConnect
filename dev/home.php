<!DOCTYPE html>
<html>
<head>
    <title>BelConnect - Home</title>
    <link rel="stylesheet" href="../assets/css/home.css">
</head>
<body>
        <?php 
        require "config.php";

        session_start();
        if(isset($_SESSION['Username'])) :?>
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

            <?php
                $usr = $_SESSION['Username'];
                $tags = array("Other", "Food", "Art", "Programming", "Music");
                // Check if the form was submitted

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $selected_tags = $_POST['tags'];
                } else {
                    //gör så att alla tags är selected by default 
                    $selected_tags = $tags;
                };

                // Build the WHERE clause for the tags
                $where_clause = "";
                foreach ($selected_tags as $tag) {
                    if ($where_clause == "") {
                        $where_clause = "WHERE Tagname = '$tag'";
                    } else {
                        $where_clause .= " OR Tagname = '$tag'";
                    }
                }
                //$stmt = $conn->prepare("SELECT * FROM Posts $where_clause ORDER BY created_at DESC");
                
                

                echo '
                <h1 style="text-align:center; margin-top:50px;">Welcome, ' . $usr . "!" .'</h1>
                <form method="POST" id="filter-form" style="text-align:center">
                    <h3>Filter posts by tag:</h3>
                    <select id="tags-container" name="tags[]">';
    
            //skriver ut alla tags
            foreach ($tags as $tag) {
                $selected = in_array($tag, $selected_tags) ? "selected" : "";
                echo "<option value=\"$tag\" $selected>$tag</option>";
            }
            echo '
                    </select>  
                    <button type="submit">Filter</button>
                </form>
                <div id="feed">';
            echo '<h2>All posts</h2>';
                   

                $profile_picture_query = "SELECT * FROM Accounts";
                $pic_res = $conn->query($profile_picture_query);
                $profile_pictures = [];

                //Denna while loop gör så att alla bilder hamnar i "profile_pictures" Arrayn
                while ($picID = $pic_res->fetch_assoc()) {
                    if(!empty($picID['image_data'])){
                        $img_data = $picID['image_data']; 
                        $img_type = $picID['image_type'];
                        $base64_image = base64_encode($img_data);
                        $img_src = "data:$img_type;base64,$base64_image";
                        $profile_pictures[$picID['UserID']] = $img_src;
                    }else{
                        $profile_pictures[$picID['UserID']] = "../assets/default-profile-photo.jpg";
                    }
                }

                //$stmt = $conn->prepare("SELECT * FROM Posts $where_clause ORDER BY created_at DESC");
                echo $where_clause;
                echo "<br>";
                $stmt = $conn->prepare("SELECT * FROM posts JOIN Tags ON posts.TagID = Tags.TagID $where_clause ORDER BY Created_at DESC");
                $stmt->execute();
                $result = $stmt->get_result();
                //$sql = "SELECT * FROM posts $where_clause ORDER BY created_at DESC";
                //$result = $conn->query($sql);
                
                // Loopar igenom posts
                $getPost_stamt = $conn -> prepare("SELECT * FROM posts JOIN Tags ON posts.TagID = Tags.TagID $where_clause ORDER BY Created_at DESC");
                //$getPost_stamt->bind_param('s', $_SESSION['UserID']);
                $getPost_stamt->execute();
                $results = $getPost_stamt->get_result();
        
        //skriver ut alla inlägg
        while ($row = $results->fetch_assoc()) {
            
            /*
            $getPost_stamt = $conn -> prepare("SELECT * FROM Tags WHERE TagID =?");
            $getPost_stamt->bind_param('s', $row['TagID']);
            $getPost_stamt->execute();
            $res =$getPost_stamt->get_result();
            $TagDATA = $res->fetch_assoc();
*/
            
            echo '<div class="post-container">';
            echo '<div class="post-header">' . $row['Title'] . '</div>';
            echo '<div class="post-meta">By ' . $row['Author'] . " (you)". ' on ' . $row['Created_at'] . '</div>';
            echo '<div class="post-content">' . $row['Content'] . '</div>';

            if($row['Author'] === "Anonymous"){
                echo '<img src="../assets/default-profile-photo.jpg" width="80" height="80" style="float: right; margin-top: -70px;">';
            }else{

                //från gpt fattar ej själv hur skiten funkar
                if (isset($profile_pictures[$row['UserID']])) {
                    $img_src = $profile_pictures[$row['UserID']];
                    echo '<img src="' . $img_src . '" width="80" height="80" style="float: right; margin-top: -70px;">';
                }
            }
            
            $getPost_stamt = $conn -> prepare("SELECT * FROM Tags WHERE TagID =?");
            $getPost_stamt->bind_param('s', $row['TagID']);
            $getPost_stamt->execute();
            $res =$getPost_stamt->get_result();
            $TagDATA = $res->fetch_assoc();
            // Display the tags for the post
            $tags = explode(',', $TagDATA['Tagname']);
            echo '<div class="post-tags">';
            foreach($tags as $tag) {
                echo '<span class="tag ' . $tag . '">' . ucfirst($tag) . '</span>';
            }
            echo '</div>';


            echo '</div>';
            
        }
                
            ?>
    <?php else:?>
        <h1>no access</h1>
        <a href="./logout.php">Back</a>
    <?php endif; ?>
</body>
</html>