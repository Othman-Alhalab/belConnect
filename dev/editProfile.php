<?php 
    session_start();
    if(isset($_SESSION['username'])):?>

            
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../assets/css/editProfile.css">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <title>Document</title>
    </head>
    <body>
    <!DOCTYPE html>
<html>

<body>
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
	

  <h1>Edit Profile</h1>


	<div class="tab">
		<button class="tablinks" onclick="selectTab('personal_info')">Personal Info</button>
		<button class="tablinks" onclick="selectTab('change_password')">Change Password</button>
		<button class="tablinks" onclick="selectTab('change_profile_picture')">Change Profile Picture</button>
		<button class="tablinks" onclick="selectTab('security_and_privacy')">Security and Privacy</button>
	</div>

	<form action="" method="post" id="personal_info">
		<div id="personal_info" >
				<label for="first_name">First Name:</label>
				<input type="text" id="first_name" name="first_name" value="<?php echo $_SESSION['firstname'] ?>" required><br><br>
				<label for="last_name">Last Name:</label>
				<input type="text" id="last_name" name="last_name" value="<?php echo $_SESSION['lastname'] ?>" required><br><br>
				<label for="username">Username:</label>
				<input type="text" id="username" name="username" value="<?php echo $_SESSION['username'] ?>" required><br><br>
				<label for="email">Email:</label>
				<input type="email" id="email" name="email" value="<?php echo $_SESSION['email'] ?>" required><br><br>
				
				
			
			<input type="submit" value="Save changes"> 
		</div>
	
	</form>

	<form action="" method="post" id="security_and_privacy">
		<div id="security_and_privacy" >
				<div class="form-check form-switch">
					<input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" onclick="window.location='?2fa=true'">
					<label class="form-check-label" for="flexSwitchCheckDefault">2FA</label>
				</div>
			<br>
			<button type="button" class="btn btn-outline-danger"><a href="./deleteAccount.php" style="text-decoration: none; color: black;">Delete account</a></button>
			
		</div>
	</form>

	<form action="" method="post" id="change_password">
		<div id="change_password" >
			
				<label for="current_password">Current Password:</label>
				<input type="password" id="current_password" name="current_password" required><br><br>
				<label for="new_password">New Password:</label>
				<input type="password" id="new_password" name="new_password" required><br><br>
				<label for="confirm_password">Confirm Password:</label>
				<input type="password" id="confirm_password" name="confirm_password" required><br><br>
			
			<input type="submit" value="Save changes"> 
		</div>
	</form>


	<form action="" method="post" id="change_profile_picture" enctype = "multipart/form-data">
		<div id="change_profile_picture">
			<input type = "file" name = "my_image">
			<input type="submit" name="submit" value="Submit">

		</div>
	</form>

	<script>
		//Kollar om det finns någon cookie (allmänt) vilket det alltid gör. jag säger då att om det finns en cookie då ta cookien
		// "tabname" och ändra den lite så att det blir enklare att hantera den om det inte finns något (det funkade ej att köra replace)
		//då sätt "tabname_in" till "personal_info"
		//regex från stackoverflow
		const tabname_in = document.cookie ? document.cookie.replace(/(?:(?:^|.*;\s*)tabname\s*\=\s*([^;]*).*$)|^.*$/, "$1") : "personal_info"
		
		function check_2fa_btn(){
			if(document.getElementById("flexSwitchCheckDefault").checked){
				document.cookie = "TwoFA=true";
			}else{
				document.cookie = "TwoFA=false";
			}
		}

		
		
		function selectTab(tabname){
			switch (tabname) {
				case "change_password":
					document.getElementById('change_password').style.display = "block";
					document.getElementById('change_profile_picture').style.display = "none";
					document.getElementById('personal_info').style.display = "none";
					document.getElementById('security_and_privacy').style.display = "none";
					document.cookie = "tabname=change_password"
				break;

				case "change_profile_picture":
					document.getElementById('change_profile_picture').style.display = "block";
					document.getElementById('change_password').style.display = "none"
					document.getElementById('personal_info').style.display = "none"
					document.getElementById('security_and_privacy').style.display = "none";
					document.cookie = "tabname=change_profile_picture"
				break;

				case "personal_info":
					document.getElementById('personal_info').style.display = "block";
					document.getElementById('change_profile_picture').style.display = "none"
					document.getElementById('change_password').style.display = "none";
					document.getElementById('security_and_privacy').style.display = "none";
					document.cookie = "tabname=personal_info"
				break;

				case "security_and_privacy":
					document.getElementById('security_and_privacy').style.display = "block";
					document.getElementById('personal_info').style.display = "none";
					document.getElementById('change_profile_picture').style.display = "none"
					document.getElementById('change_password').style.display = "none";
					document.cookie = "tabname=security_and_privacy"
					break;
				
				default:
					document.getElementById('personal_info').style.display = "block";
					document.getElementById('change_profile_picture').style.display = "none"
					document.getElementById('change_password').style.display = "none";
					document.getElementById('security_and_privacy').style.display = "none";
					document.cookie = "tabname=personal_info"
					break;
			}
			
		}
		
		selectTab(tabname_in)
		/*
		document.cookie = "tabname=personal_info"
		const curr = document.cookie ? "personal_info" : "x"
		
		selectTab('personal_info')
		*/
	</script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	
	<?php
		require "./config.php";

		
		if(isset($_COOKIE["TwoFA"])) {
			if($_COOKIE["TwoFA"] == "true"){
				
			}elseif($_COOKIE["TwoFA"] == "false"){

			}
		}
		
	
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		//$msg = "sds";

		$tabname = $_COOKIE["tabname"];
		$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
		if($tabname == "personal_info"){
			$firstname = $_POST['first_name'];
			$lastname = $_POST['last_name'];
			$username = $_POST['username'];
			$email = $_POST['email'];

			if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['username']) && isset($_POST['email'])){
				$username_lowercase = strtolower($username);
				
				if($_POST['username'] != $_SESSION['username']){
					$result = $conn->query("SELECT * FROM users WHERE LOWER(username)='$username_lowercase'");
					$user_id = $_SESSION['id'];
					if ($result->num_rows == 0) {
						$send = "UPDATE users SET username='$username', email='$email', firstname='$firstname', lastname='$lastname' WHERE id=$user_id";
						
						if ($conn->query($send) === true) {
							$_SESSION['username'] = $_POST['username'];
							$_SESSION['email'] = $_POST['email'];
							$_SESSION['firstname'] = $_POST['first_name'];
							$_SESSION['lastname'] = $_POST['last_name'];
							//$msg = "saved changes!";
							echo "saved changes!";
						} else {
							echo "Error: " . $conn->error;
						}
		
					} else {
						//$msg = "Username already in use!";
						echo "Username already in use!";
					}
				}else{
					$user_id = $_SESSION['id'];//isset($_SESSION['id']) ? $_SESSION['id'] : getid($conn);
					$send = "UPDATE users SET firstname='$firstname', lastname='$lastname', email='$email' WHERE id='$user_id'";
					if ($conn->query($send) === true) {
						$_SESSION['email'] = $_POST['email'];
						$_SESSION['firstname'] = $_POST['first_name'];
						$_SESSION['lastname'] = $_POST['last_name'];
						//$msg = "saved changes!";
						echo "saved changes!";
					} else {
						echo "Error: " . $conn->error;
					}
				}
				
	
			}else{
				//$msg =  "fill in all fields";
				echo "fill in all fields";
			}
		}elseif($tabname == "change_password"){
			//lössenords byte (kollar först vilken sida(Tabname) användaren är på och sedan kollar på alla inputs är ifyllda).
			if(isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])){
				$oldPass = $_POST['current_password'];
				$userDbPass = $_SESSION['password'];
				$newPass = $_POST['new_password'];
				$confirmPass = $_POST['confirm_password'];
				$user_id = $_SESSION['id'];//isset($_SESSION['id']) ? $_SESSION['id'] : getid($conn);

				//Kollar att det gammla lösenordet inte är samma som det nya.
				if($confirmPass == $newPass){
					if($oldPass !== $userDbPass){
						$send = "UPDATE users SET password='$newPass' WHERE id=$user_id";
						if ($conn->query($send) === true) {
							echo "The password was successfully changed!";	
						}else{
							echo "Error: " . $conn->error;
						}
					}else{
						echo "incorrect password";
						
					}
					
				}else{
					echo "The passwords do not match!";
				}
			}else{
				echo "fill in all fields";
			}

		}elseif($tabname == "change_profile_picture"){
			if(isset($_FILES['my_image'])){
				
				$img_name = $_FILES['my_image']['name'];
				$img_size = $_FILES['my_image']['size'];
				$tmp_name = $_FILES['my_image']['tmp_name'];

					$img_data = file_get_contents($tmp_name);
					$img_type = $_FILES['my_image']['type'];
				
					$stmt = $conn->prepare("UPDATE users SET image_data = ?, image_type = ? WHERE id = ?");
            		$stmt->bind_param("sss", $img_data, $img_type, $_SESSION['id']);	
					$stmt->execute();
					echo "Your profile picture has successfully been updated!";

			}else{
				echo "No image has been uploaded!";
			}
		
			
				

		}elseif($tabname == "security_and_privacy"){
			$fa2 = $_POST['flexSwitchCheckDefault'];

			
				

		}


	}
	?>
  </body>
  </html>

<?php else:?>
    <h1>no access</h1>
    <a href="./logout.php">Back</a>
<?php endif; ?>