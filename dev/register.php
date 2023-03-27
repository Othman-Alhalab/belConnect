<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Account BelConnect</title>
    <link rel="stylesheet" href="../assets/css/register.css">
</head>
<body>
    <h2>Register</h2>
        <form method="post" action="register.php">
            <label for="username">* Username:</label>
            <input type="text" id="username" name="username" required><br><br>
            <label for="password">* Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <label for="dob">* Date of Birth:</label>
            <input type="date" id="dob" name="dob" required><br><br>
            <input type="submit" value="Register">
            <p><a href="./login.php">Already have an account?</a></p>
        </form>
</body>
</html>

<?php
    require 'metoder.php';
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "BelConnectDB";

    if(isset($_POST['username']) && isset($_POST['password'])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $user);
        mysqli_stmt_execute($stmt);
        $result = mysqli_num_rows(mysqli_stmt_get_result($stmt));
       
        

        if (!inputTest($user)) {
            echo "Invalid username format!";
        } else if (!$result) {
            $send = "INSERT INTO users (username, password) VALUES ('$user', '$pass')";
        } else {
            echo "Username already in use!";
        }

        try {
            if ($conn->query($send) === TRUE) {
                
                echo "User was successfully registerd";
    
            } else {
                //echo "err: " . $send . "<br>" . $conn->error;
            }
        } catch (\Throwable $th) {
            //throw $th;
        };

        mysqli_close($conn);
    }
?>