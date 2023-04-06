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
    <!DOCTYPE html>
<html>

<body>
  <nav>
  <a href="./home.php">Home</a>
  <a href="./post.php">Create Post</a>
  <a href="./editProfile.php">Edit Profile</a>
  <a href="./logout.php">Logout</a>
  </nav>
	

  <h1>Edit Profile</h1>


	<div class="tab">
		<button class="tablinks" onclick="openTab(event, 'personal_info')">Personal Info</button>
		<button class="tablinks" onclick="openTab(event, 'change_password')">Change Password</button>
		<button class="tablinks" onclick="openTab(event, 'change_profile_picture')">Change Profile Picture</button>
	</div>

	<form action="" method="post">
		<div id="personal_info" class="tabcontent">
			<fieldset>
				<label for="first_name">First Name:</label>
				<input type="text" id="first_name" name="first_name" value="<?php echo $_SESSION['firstname'] ?>" required><br><br>
				<label for="last_name">Last Name:</label>
				<input type="text" id="last_name" name="last_name" value="<?php echo $_SESSION['lastname'] ?>" required><br><br>
				<label for="username">Username:</label>
				<input type="text" id="username" name="username" value="<?php echo $_SESSION['username'] ?>" required><br><br>
				<label for="email">Email:</label>
				<input type="email" id="email" name="email" value="<?php echo $_SESSION['email'] ?>" required><br><br>
			</fieldset>
			<input type="submit" value="Save changes"> 
		</div>
	</form>

	<form action="" method="post">
		<div id="change_password" class="tabcontent">
			<fieldset>
				<label for="current_password">Current Password:</label>
				<input type="password" id="current_password" name="current_password" required><br><br>
				<label for="new_password">New Password:</label>
				<input type="password" id="new_password" name="new_password" required><br><br>
				<label for="confirm_password">Confirm Password:</label>
				<input type="password" id="confirm_password" name="confirm_password" required><br><br>
			</fieldset>
			<input type="submit" value="Save changes"> 
		</div>
	</form>

	<form action="" method="post">
		<div id="change_profile_picture" class="tabcontent">
			<fieldset>
				<label for="profile_picture">Choose Picture:</label>
				<input type="file" id="profile_picture" name="profile_picture">
			</fieldset>
			<input type="submit" value="Upload">
		</div>
	</form>
	<script>
		function openTab(evt, tabName) {
			// Declare all variables
			let i, tabcontent, tablinks;

			// Get all elements with class="tabcontent" and hide them
			tabcontent = document.getElementsByClassName("tabcontent");
			for (i = 0; i < tabcontent.length; i++) {
				tabcontent[i].style.display = "none";
			}

			// Get all elements with class="tablinks" and remove the class "active"
			tablinks = document.getElementsByClassName("tablinks");
			for (i = 0; i < tablinks.length; i++) {
				tablinks[i].className = tablinks[i].className.replace(" active", "");
			}

			// Show the current tab, and add an "active" class to the button that opened the tab
			document.getElementById(tabName).style.display = "block";
			evt.currentTarget.className += " active";
			document.cookie = "tabName=" + tabName;
		}

		openTab(event, 'personal_info');
	</script>
	
	<?php
		require "./config.php";


	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$firstname = $_POST['first_name'];
		$lastname = $_POST['last_name'];
		$username = $_POST['username'];
		$email = $_POST['email'];

		$oldPass = $_POST['current_password'];
		$newPass = $_POST['new_password'];
		$confirmPass = $_POST['confirm_password'];

		$tabname = $_COOKIE["tabName"];
		$user_id = $_SESSION['id'];
		if($tabname == "personal_info"){
			if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['username']) && isset($_POST['email'])){
				$username_lowercase = strtolower($username);
				$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
				if($_POST['username'] != $_SESSION['username']){
					$result = $conn->query("SELECT * FROM users WHERE LOWER(username)='$username_lowercase'");
					if ($result->num_rows == 0) {
						$send = "UPDATE users SET username='$username', email='$email', firstname='$firstname', lastname='$lastname' WHERE id=$user_id";
						
						if ($conn->query($send) === true) {
							$_SESSION['username'] = $_POST['username'];
							$_SESSION['email'] = $_POST['email'];
							$_SESSION['firstname'] = $_POST['first_name'];
							$_SESSION['lastname'] = $_POST['last_name'];
							echo "saved changes!";
						} else {
							echo "Error: " . $conn->error;
						}
		
					} else {
						echo "Username already in use!";
					}
				}else{
					$send = "UPDATE users SET firstname='$firstname', lastname='$lastname', email='$email' WHERE id='$user_id'";
					if ($conn->query($send) === true) {
						$_SESSION['email'] = $_POST['email'];
						$_SESSION['firstname'] = $_POST['first_name'];
						$_SESSION['lastname'] = $_POST['last_name'];
						echo "saved changes!";
					} else {
						echo "Error: " . $conn->error;
					}
				}
				
	
			}else{
				echo "fill in all fields";
			}
		}elseif($tabname == "change_password"){
			if(isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])){
				//reset password system here
			}else{
				echo "fill in all fields";
			}
		}elseif($tabname == "profile_picture"){
			//profile picture sysytem here
		}


	}
	?>
  </body>
  </html>

<?php else:?>
    <h1>no access</h1>
    <a href="./logout.php">Back</a>
<?php endif; ?>