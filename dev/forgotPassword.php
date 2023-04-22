
<?php
    session_start();
    require 'metoder.php';
    require "config.php";
    $page = "finduser";
    
    $errormsg = "";
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $question = $_POST['question'];
        $answer = $_POST['answer'];

       if($page === "finduser"){ 
            $info_stamt = $conn->prepare("SELECT * FROM user_secret_questions, users");		
            $info_stamt->execute();
            $results = $stmt->get_result();
            while($row = $results->fetch_assoc()){
                if($row['answer'] === $answer){
                    if($row['question'] === $question){
                    
                    }
                }
            }
            $page = "founduser";

       }else if($page === "founduser"){

       }
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
    
    <?php if($page == "finduser"):?>
            <form method="post" action="">
            <label for="username">Email:</label>
            <input type="email" id="email" name="email"><br><br>
            <label for="question">Secret question</label>
        
                <select id="question" name="question">
                <option value="show">What is your favorite movie or TV show?</option>
                <option value="born">In what city or town were you born?</option>
                <option value="pet">What was the name of your first pet?</option>
                </select>
                <br>
                <br>
                <label for="answer">Answer:</label>
                <input type="text" id="answer" name="answer"><br><br>
        
            <p style="color:red;"><?php echo $errormsg?></p>
            <p><a href="./login.php">Back</a></p>
            <input type="submit" value="Login">
            </form>
            

    <?php elseif ($page == "founduser"):?>
            
            <form method="post" action="">

                <label for="password">New password:</label>
                <input type="password" id="password" name="password"><br><br>

                <label for="password">Confirm password:</label>
                <input type="password" id="password" name="password"><br><br>

                <p><a href="./login.php">Back</a></p>
                <input type="submit" value="change">

            </form>
        
    <?php endif;?>
</body>
</html>