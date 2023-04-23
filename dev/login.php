


<?php
    session_start();
    require 'metoder.php';
    require "config.php";

    
    $errormsg = "";
    if(isset($_POST['username']) && isset($_POST['password'])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        //Kollar för med databasen om det finns en användare med "username input"
        $stmt = $conn->prepare("SELECT id, username, password, email, firstname, lastname FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $user);
        mysqli_stmt_execute($stmt);
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];
            if (password_verify($pass, $hashed_password)) {

                //Lägger in dessa variabler i vår Session så att det kan användas senare
                $_SESSION['username'] = $row['username'];
                $_SESSION['password'] = $row['password'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['firstname'] = $row['firstname'];
                $_SESSION['lastname'] = $row['lastname'];

                header("Location: home.php");
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
		<p><a href="./register.php">Register Account</a></p>
        <p><a href="./forgotPassword.php" onclick="<?php $_SESSION['page'] = 'finduser'; ?>">Forgot pasword</a></p>
		<input type="submit" value="Login">
	</form>
</body>
</html>