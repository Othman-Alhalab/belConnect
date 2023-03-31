<?php
    require 'metoder.php';
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "BelConnectDB";

    session_start();

    if(isset($_POST['username']) && isset($_POST['password'])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ? AND password = ?");

        mysqli_stmt_bind_param($stmt, "ss", $user, $pass);

        mysqli_stmt_execute($stmt);

        $result = mysqli_num_rows(mysqli_stmt_get_result($stmt));

        if ($result) {
            // Login successful
            $_SESSION['username'] = $user;
            header("Location: profile.php");
        } else {
            // Login failed
            echo "Invalid username or password";
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
		<p><a href="./register.php">Register Account</a></p>
		<input type="submit" value="Login">
	</form>

    <button><a href="./profile.php">hack?</a></button>
</body>
</html>
