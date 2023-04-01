<?php 
    session_start();

    if(isset($_SESSION['username'])):?>

            
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../assets/css/editProfile.css">
        <title>Document</title>
    </head>
    <body>
        <nav>
            <a href="./home.php">Home</a>
            <a href="./post.php">Create Post</a>
            <a href="./editProfile.php">Edit Profile</a>
            <a href="./logout.php">Logout</a>
        </nav>
                    <div class="form-container">
            <h2>Account Info</h2>
            <form>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="JohnDoe" placeholder="Enter username">
                
                <label for="password">Old Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter old password">
                
                <label for="newpassword1">New Password:</label>
                <input type="password" id="newpassword1" name="newpassword1" placeholder="Enter new password">
                
                <label for="newpassword2">Confirm New Password:</label>
                <input type="password" id="newpassword2" name="newpassword2" placeholder="Confirm new password">
                
                <label for="firstname">First Name:</label>
                <input type="text" id="firstname" name="firstname" value="John" placeholder="Enter first name">
                
                <label for="lastname">Last Name:</label>
                <input type="text" id="lastname" name="lastname" value="Doe" placeholder="Enter last name">
                
                <input type="submit" value="Save Changes">
            </form>
            </div>

    </body>
    </html>
<?php else:?>
    <h1>no access</h1>
    <a href="./logout.php">Back</a>
<?php endif; ?>