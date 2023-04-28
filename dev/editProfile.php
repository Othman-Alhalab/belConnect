<?php 
    session_start();
	$error_pass = "";
	$error_change_profile_picture = "";
	$security_and_privacy = "";
	$error_personal_info = "";
	$error_2fa = "";
    if(isset($_SESSION['Username'])):?>

            
<?php
	require "config.php";
	require "./metoder.php";
		
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		//$msg = "sds";

		$tabname = $_COOKIE["tabname"];
		if($tabname == "personal_info"){
			$firstname = $_POST['first_name'];
			$lastname = $_POST['last_name'];
			$username = $_POST['username'];
			$email = $_POST['email'];
			$Phone_number = $_POST['Phone_number'];

			if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['Phone_number'])){
				$username_lowercase = strtolower($username);
				
				if($_POST['username'] != $_SESSION['Username']){
					$result = $conn->prepare("SELECT * FROM Users WHERE LOWER(Username)=?");
					$result->bind_param('s', $username_lowercase);
					$result->execute();
					$result->store_result();

					//alot of checks
					if(!empty(trim($Phone_number)) && strlen(trim($Phone_number)) >= 6 && checkPhoneNumber(trim($Phone_number))){
						if(inputTest($firstname) && inputTest($lastname)){
							if(filter_var($email, FILTER_VALIDATE_EMAIL)){
								if(testUsername($username) && strlen($username) >= 3){
									if ($result->num_rows == 0) {			
										$stmt_users = $conn ->prepare("UPDATE Users SET Username=? WHERE UserID=?");
										$stmt_users->bind_param('si',$_POST['username'], $_SESSION['UserID']);
				
										
										if ($stmt_users->execute()) {
											$_SESSION['Username'] = $_POST['username'];
											$error_personal_info = "saved changes!";
											
										} else {
											$error_personal_info = "Error: " . $conn->error;
										}
						
									} else {
										//$msg = "Username already in use!";
										$error_personal_info = "Username already in use!";
									}
								}else{
									$error_personal_info = "username is to short (atleast 3 characters) or inclueds speical characters";
								}
							}else{
								$error_personal_info = "please enter a vaild email";
							}
						}else{
							$error_personal_info = "no speical characters (* | / | + | - | 1-9) etc in (first name, Last name, tel and username)";
						}
					}else{
						$error_personal_info = "enter a vaild phone number (has to be longer then 6 numbers)";
					}
					
					
					
				}else{
					//$user_id = $_SESSION['id'];//isset($_SESSION['id']) ? $_SESSION['id'] : getid($conn);
					$stmt_users = $conn -> prepare("UPDATE Users SET Username=?, Email=?, Firstname=?, Lastname=?, Phone_number=? WHERE UserID=?");
					$stmt_users->bind_param('ssssii', $username, $email, $firstname, $lastname, $Phone_number, $_SESSION['UserID']);

					if ($stmt_users->execute()) {
						$_SESSION['Username'] = $_POST['username'];
						$_SESSION['Email'] = $_POST['email'];
						$_SESSION['Firstname'] = $_POST['first_name'];
						$_SESSION['Lastname'] = $_POST['last_name'];
						$_SESSION['Phone_number'] = $_POST['Phone_number'];
						//$msg = "saved changes!";
						$error_personal_info = "saved changes!";
							$stmt_users ->close();
					} else {
						$error_personal_info = "Error: " . $conn->error;
						$stmt_account->close();
							$stmt_users ->close();
					}
				}
				
	
			}else{
				//$msg =  "fill in all fields";
				$error_personal_info = "fill in all fields";
			}
		}elseif($tabname == "change_password"){
			$getCureentPass = $conn->prepare('SELECT Password FROM Users where UserID =?');
			$getCureentPass->bind_param('s', $_SESSION['UserID']);
			$getCureentPass->execute();
			$res =$getCureentPass->get_result();
            $UserPass = $res->fetch_assoc();

			//lössenords byte (kollar först vilken sida(Tabname) användaren är på och sedan kollar på alla inputs är ifyllda).
			if(isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])){
				$oldPass = $_POST['current_password'];
				$userDbPass = $UserPass['Password'];
				$newPass = $_POST['new_password'];
				$confirmPass = $_POST['confirm_password'];
				$user_id = $_SESSION['UserID']; 

				$hashed_pass = password_hash($newPass, PASSWORD_DEFAULT);
				//Kollar att det gammla lösenordet inte är samma som det nya.    $oldPass == $userDbPass
				if(strlen($newPass) >= 6){
					if($confirmPass === $newPass){
						if(password_verify($oldPass, $userDbPass)){
							if(!password_verify($newPass, $userDbPass)){
								$pass_stamt = $conn->prepare("UPDATE Users SET Password=? WHERE UserID=?");
								$pass_stamt->bind_param("si", $hashed_pass, $_SESSION['UserID']);
								if ($pass_stamt->execute()) {
	
									$error_pass =  "The password was successfully changed!";	
								}else{
									echo "Error: " . $conn->error;
								}
							}else{
								$error_pass = "You cannot have the same password as the current!";
								
							}
						}else{
							$error_pass = "Wrong password!";
						}
	
					}else{
						$error_pass =  "The passwords do not match!";
					}
				}else{
					$error_pass = "Your password has to be 6 characters or longer";
				}
			}else{
				$error_pass =  "fill in all fields";
			}

		}elseif($tabname == "change_profile_picture"){

			$allowed = array("image/jpeg", "image/png");
			try {
				
				if($_FILES['my_image']['size'] < 1000000){
					if(in_array($_FILES['my_image']['type'], $allowed)) {
						if(isset($_FILES['my_image'])){
							$img_name = $_FILES['my_image']['name'];
							$img_size = $_FILES['my_image']['size'];
							$tmp_name = $_FILES['my_image']['tmp_name'];
			
							$img_data = file_get_contents($tmp_name);
							$img_type = $_FILES['my_image']['type'];
							
							$stmt = $conn->prepare("UPDATE Profile_pic SET image_data = ?, image_type = ? WHERE UserID = ?");
							$stmt->bind_param("sss", $img_data, $img_type, $_SESSION['UserID']);	
							$stmt->execute();
							$error_change_profile_picture = "Your profile picture has successfully been updated!";
			
						}else{
							$error_change_profile_picture = "No image has been uploaded!";
						}
	
					}else{
						$error_change_profile_picture = 'Only jpg and png files are allowed.';
					}
				}else{
					$error_change_profile_picture = 'No files larger then 1MB';
				}

				
			} catch (\Throwable $th) {
				$error_change_profile_picture = "No image was selected";
			}
		
			
		}elseif($tabname == "security_and_privacy"){
			$stmt = $conn->prepare("SELECT * FROM user_secret_questions");
			$stmt->execute();
			$stmt->store_result();

			if ($stmt->num_rows < 0) {
				$error_2fa = "You already have a secret question set!";
			} else {
				if (!empty($_POST['answer'])) {
					$register_answer = $conn->prepare("INSERT INTO user_secret_questions (UserID, Question, Answer) VALUES (?, ?, ?)");
					$register_answer->bind_param("sss", $_SESSION['UserID'], $_POST['question'], $_POST['answer']);
					if ($register_answer->execute() === true) {
						$error_2fa = "A secret question has been set!";
						$_SESSION['secret'] = true;
					}
				} else {
					$error_2fa = "Enter your answer and press submit";
				}
			}
		}


	}
	?>
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
				<input type="text" id="first_name" name="first_name" value="<?php echo $_SESSION['Firstname'] ?>" required><br><br>
				<label for="last_name">Last Name:</label>
				<input type="text" id="last_name" name="last_name" value="<?php echo $_SESSION['Lastname'] ?>" required><br><br>
				
				<label for="username">Username:</label>
				<input type="text" id="username" name="username" value="<?php echo $_SESSION['Username'] ?>" required><br><br>
				
				<label for="email">Email:</label>
				<input type="email" id="email" name="email" value="<?php echo $_SESSION['Email'] ?>" required><br><br>
				
				<label for="Phone_number">Phone number:</label>
				<input type="tel" id="Phone_number" name="Phone_number" value="<?php echo $_SESSION['Phone_number'] ?>" required><br><br>
				
				<p> <?php echo $error_personal_info ?></p>
			
			<input type="submit" value="Save changes"> 
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
				<p> <?php echo $error_pass ?></p>
			<input type="submit" value="Save changes"> 
		</div>
	</form>


	<form action="" method="post" id="change_profile_picture" enctype = "multipart/form-data">

		<div id="change_profile_picture">
			<?php 
				$getPic = $conn->prepare('SELECT * from Profile_pic where UserID=?');
				$getPic->bind_param("s", $_SESSION['UserID']);
				$getPic->execute();
				$tempV = $getPic->get_result();
				$res = $tempV->fetch_assoc();
				if (!empty($res['image_data'])) {
					$img_data = $res['image_data'];
					$img_type = $res['image_type'];
					$base64_image = base64_encode($img_data);
					$img_src = "data:$img_type;base64,$base64_image";
				} else {
					$img_src = "../assets/default-profile-photo.jpg";
				}
				
				echo '<img src="' . $img_src . '" width="180" height="180" style="margin-top: 63px; margin-left: 192px;">';
				

			?>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<input type = "file" name = "my_image">
			<p><?php echo $error_change_profile_picture ?></p>
			<input type="submit" name="submit" value="Submit">
			<img src="" alt="">
			
			
			
		</div>
	</form>

	
	<form action="" method="post" id="security_and_privacy">
	<?php 
		$info_stamt = $conn->prepare("SELECT * FROM user_secret_questions WHERE UserID = ?");
		$info_stamt->bind_param('s', $_SESSION['UserID']);
		$info_stamt->execute();
		$info_stamt->store_result();
		
		if($info_stamt->num_rows == 0):?>
			
			<label for="question"></label>

			<select id="question" name="question">
			<option value="show">What is your favorite movie or TV show?</option>
			<option value="born">In what city or town were you born?</option>
			<option value="pet">What was the name of your first pet?</option>
			</select>
			<br>
			<br>
			
			<input type="text" name="answer" id="answer" placeholder="dog name efe">
			<p><?php echo $error_2fa?></p>
			<input type="submit" value="Set security question" id="question_submit">
			<br>
			<br>
	<?php else:?>
			<p>2FA</p>
				A secret question has been set
				<br>
			<button type="button" class="btn btn-outline-danger"><a href="./deleteAccount.php" style="text-decoration: none; color: black;" onclick="<?php $_SESSION["delAccount"] = "finduser"?>">Delete account</a></button>
	<?php endif?>



	</form>


	<script>
		//Kollar om det finns någon cookie (allmänt) vilket det alltid gör. jag säger då att om det finns en cookie då ta cookien
		// "tabname" och ändra den lite så att det blir enklare att hantera den om det inte finns något (det funkade ej att köra replace)
		//då sätt "tabname_in" till "personal_info"
		//regex från stackoverflow
		const tabname_in = document.cookie ? document.cookie.replace(/(?:(?:^|.*;\s*)tabname\s*\=\s*([^;]*).*$)|^.*$/, "$1") : "personal_info"
		
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
	</script>
	
	
	
  </body>
  </html>

<?php else:?>
    <h1>no access</h1>
    <a href="./logout.php">Back</a>
<?php endif; ?>