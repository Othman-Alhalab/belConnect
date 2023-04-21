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
        if(isset($_SESSION['username'])) :?>
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


            //1. måste lösa så att det finns en koppling till id i users så at det kan användas i profilbild skiten

                $tags = array("other", "food", "art", "programming", "music");
                // Check if the form was submitted
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $selected_tags = isset($_POST['tags']) ? $_POST['tags'] : array();
                } else {
                    // By default, all tags are selected
                    $selected_tags = $tags;
                };

            
                $usr = $_SESSION['username'];
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "BelConnectDB";
                $conn = mysqli_connect($servername, $username, $password, $dbname);

                // Build the WHERE clause for the tags
                $where_clause = "";
                foreach ($selected_tags as $tag) {
                    if ($where_clause == "") {
                        $where_clause = "WHERE tags LIKE '%$tag%'";
                    } else {
                        $where_clause .= " OR tags LIKE '%$tag%'";
                    }
                }

                $sql = "SELECT * FROM posts $where_clause ORDER BY created_at DESC";
                $result = $conn->query($sql);
                

                echo '
                    <h1 style="text-align:center; margin-top:50px;">Welcome, ' . $usr . "!" .'</h1>
                    <form method="POST" id="filter-form" style="text-align:center">
                        <h3>Filter posts by tag:</h3>
                        <div id="tags-container">';
                foreach ($tags as $tag) {
                    $checked = in_array($tag, $selected_tags) ? "checked" : "";
                    echo '<label><input type="checkbox" name="tags[]" value="' . $tag . '" ' . $checked . '> ' . ucfirst($tag) . '</label><br>';
                }
                echo '
                        </div>
                        <button type="submit">Filter</button>
                        </form>
                        <div id="feed">';
                echo '<h2>All posts</h2>';
                   

                $profile_picture_query = "SELECT * FROM users";
                $pic_res = $conn->query($profile_picture_query);
                
                $profile_pictures = [];

                //Denna while loop gör så att alla bilder hamnar i "profile_pictures" Arrayn
                while ($picID = $pic_res->fetch_assoc()) {
                    if(!empty($picID['image_data'])){
                        $img_data = $picID['image_data']; 
                        $img_type = $picID['image_type'];
                        $base64_image = base64_encode($img_data);
                        $img_src = "data:$img_type;base64,$base64_image";
                        $profile_pictures[$picID['id']] = $img_src;
                    }else{
                        $profile_pictures[$picID['id']] = "../assets/default-profile-photo.jpg";
                    }
                }
                
                // Loop through the posts
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="post-container">';
                    echo '<div class="post-header">' . $row['post_name'] . '</div>';
                    echo '<div class="post-meta">By ' . $row['author'] . ' on ' . $row['created_at'] . '</div>';
                    echo '<div class="post-content">' . $row['post_data'] . '</div>';
                    
                    // Display the profile picture for the author
                    if($row['author'] === "Anonymous"){
                        echo '<img src="../assets/default-profile-photo.jpg" width="80" height="80" style="float: right; margin-top: -70px;">';
                    }else{
                        if (isset($profile_pictures[$row['user_id']])) {
                            $img_src = $profile_pictures[$row['user_id']];
                            echo '<img src="' . $img_src . '" width="80" height="80" style="float: right; margin-top: -70px;">';
                        }
                    }
                    
                    //Visar vilka tags varje post har
                    $post_tags = explode(',', $row['tags']);
                    $post_tags_filtered = array_intersect($tags, $post_tags);
                    if (!empty($post_tags_filtered)) {
                        echo '<div class="post-tags">';
                        foreach($post_tags_filtered as $tag) {
                            echo '<span class="tag ' . $tag . '">' . ucfirst($tag) . '</span>';
                        }
                        echo '</div>';
                    }
                
                    echo '</div>';
                }
            ?>
    <?php else:?>
        <h1>no access</h1>
        <a href="./logout.php">Back</a>
    <?php endif; ?>
</body>
</html>
