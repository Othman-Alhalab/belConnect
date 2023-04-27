


<?php
    session_start();
    require 'metoder.php';
    require "config.php";

    
    $errormsg = "";
    if(isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
    
        
        //Kollar för med databasen om det finns en användare med "username input"
        $stmt = $conn->prepare("SELECT * FROM Users WHERE Username = ?");
        $stmt->bind_param("s", $_POST['username']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['Password'];
            
            if (password_verify($password, $hashed_password)) {


                //Lägger in dessa variabler i vår Session så att det kan användas senare
                $_SESSION['Username'] = $row['Username'];
                $_SESSION['Password'] = $row['Password'];
                $_SESSION['Email'] = $row['Email'];
                $_SESSION['UserID'] = $row['UserID'];
                $_SESSION['Firstname'] = $row['Firstname'];
                $_SESSION['Lastname'] = $row['Lastname'];
                $_SESSION['Phone_number'] = $row['Phone_number'];
                
                header("Location: home.php");
                echo $row['Firstname'];
                echo $row['UserID'];
            } else {
                $errormsg = "Invalid username or password";
            }
        } else {
            $errormsg = "No user with this name was found";
        }
    
        // Close connection
        mysqli_close($conn);
    }
    
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../assets/css/login.css">
    <title>Login BelConnect</title>
</head>
<body>
    <h2>Login</h2>
	<form method="post" action="">
		<label for="username">Username:</label>
		<input type="text" id="username" name="username"><br><br>
		<label for="password">Password:</label>
		<input type="password" id="password" name="password"><br><br>
        <p style="color:red;"><?php echo $errormsg?></p>
		<p><a href="./register.php" onclick="<?php $_SESSION['err'] = "n" ?>">Register Account</a></p>
        <p><a href="./forgotPassword.php" onclick="<?php $_SESSION['page'] = 'finduser'; ?>">Forgot pasword</a></p>
		<input type="submit" value="Login">
	</form>
</body>
</html>