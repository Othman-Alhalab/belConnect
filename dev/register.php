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
        <form method="post" action="">
            <label for="username">* Username:</label>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">* Password:</label>
            <input type="password" id="password" name="password" pattern=".{8,}" required><br><br>
            

            <label for="password_con">* Confirm Password:</label>
            <input type="password" id="password_con" name="password_con" pattern=".{8,}" required>
            <p id="theMatch">Match</p>
            
            
            <br>
            <label for="dob">* Age:</label>
            <input type="number" id="dob" name="dob" required min="12" max="999"><br><br>

            <input type="submit" value="Register">
            <p><a href="./login.php">Already have an account?</a></p>
        </form>

        <script>
            const pass = document.getElementById('password')
            const pass_con = document.getElementById('password_con')
            const theM = document.getElementById('theMatch')

            //när hemsidan laddar in är P taggen osynlig
            theM.innerText = ""

            

        function passwordmatch(){
            if(pass == pass_con){
                theM.innerText = "Inputs match!";
            }else{
                theM.innerText = "Does not match"
            }
        }
                    
            
            pass.addEventListener("input", function(event) {
                if (!pass.validity.valid) {
                    pass.setCustomValidity("");
                } else {
                    pass.setCustomValidity("Password must be at least 8 characters long.");
                }
            });

        document.getElementById("password_con").addEventListener("input", passwordmatch)
    </script>

</body>
</html>

<?php
require "metoder.php";
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BelConnectDB";

//Kollar om något finns i "$_POST['username']" och "$_POST['password']"
if (isset($_POST["username"]) && isset($_POST["password"])) {
    $user = $_POST["username"];
    $pass = $_POST["password"];
    $confirmPass = $_POST["password_con"];
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_num_rows(mysqli_stmt_get_result($stmt));

    if ($pass == $confirmPass) {
        if (!inputTest($user)) {
            echo "Invalid username format!";
        } elseif (!$result) {
            $send = "INSERT INTO users (username, password) VALUES ('$user', '$pass')";
        } else {
            echo "Username already in use!";
        }
    } else {
        echo "Passwords do not match";
    }

    try {
        if ($conn->query($send) === true) {
            header("Location: login.php");
        } else {
            //echo "err: " . $send . "<br>" . $conn->error;
        }
    } catch (\Throwable $th) {
        //throw $th;
    }

    mysqli_close($conn);
}

?>
