<?php 
    session_start();

    if(isset($_SESSION['username'])):?>

            
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../assets/css/editpro.css">
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
  
  <form id="form-one" action="submit-one.php" method="post">
    <div class="section-container one">

      <div class="form-group one">
        <label for="firstname">First Name</label>
        <input type="text" id="firstname" name="firstname" placeholder="Enter your first name" value="Jon">
      </div>

      <div class="form-group one">
        <label for="lastname">Last Name</label>
        <input type="text" id="lastname" name="lastname" placeholder="Enter your last name" value="Doe">
      </div>

      <div class="form-group one">
        <label for="email">Email</label>
        <input type="text" id="email" name="email" placeholder="Enter your email" value="admin@site.net">
      </div>
    
      <button type="button" onclick="showSection('two')">Reset Password</button>
      <input type="submit" value="Save Changes">
    </div>
  </form>

  <form id="form-two" action="submit-two.php" method="post">
    <div class="section-container two" style="display: none">
      <div class="form-group two">
        <label for="oldpassword">Old Password</label>
        <input type="password" id="oldpassword" name="oldpassword" placeholder="Enter your old password">
      </div>

      <div class="form-group two">
        <label for="newpassword">New Password</label>
        <input type="password" id="newpassword" name="newpassword" placeholder="Enter your new password">
      </div>

      <div class="form-group two">
        <label for="confirmpassword">Confirm New Password</label>
        <input type="password" id="confirmpassword" name="confirmpassword" placeholder="Confirm your new password">
      </div>

      <button type="button" onclick="showSection('one')">Cancel</button>
      <input type="submit" value="Save Changes">
    </div>
  </form>
</div>

<script>
  function showSection(section) {
    if (section === 'one') {
      document.querySelector('#form-one .section-container.one').style.display = 'block';
      document.querySelector('#form-two .section-container.two').style.display = 'none';
    } else {
      document.querySelector('#form-one .section-container.one').style.display = 'none';
      document.querySelector('#form-two .section-container.two').style.display = 'block';
    }
  }
</script>

    </body>
    </html>
<?php else:?>
    <h1>no access</h1>
    <a href="./logout.php">Back</a>
<?php endif; ?>