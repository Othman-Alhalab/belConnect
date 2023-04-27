
<?php

    //fixa error managment

    session_start();
    require 'metoder.php';
    require "config.php";
    
    $errormsg = "";
    $Input_email = isset($_POST['email']) ? $_POST['email'] : "" ;
    $Input_answer = isset($_POST['answer']) ? $_POST['answer'] : "" ;
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
       
        if($_SESSION['page'] === "finduser"){ 
            $_SESSION['email'] = $_POST['email'];
            $question = $_POST['question'];
            $answer = $_POST['answer'];

            if(!empty($_POST['email']) && isset($_POST['question']) && !empty($_POST['answer'])){
                $getmail = $conn->prepare('SELECT * FROM Users where Email=?');
                $getmail->bind_param('s',$_POST['email']);
                $getmail->execute();
                $res = $getmail ->get_result();
                $USER = $res->fetch_assoc();


                $info_stamt = $conn->prepare("SELECT * FROM user_secret_questions WHERE UserID=?");	
                $info_stamt->bind_param("s", $USER['UserID']);
                $info_stamt->execute();
                $results = $info_stamt->get_result();
    
                while($table = $results->fetch_assoc()){
                    if(strtolower($table['Question']) === strtolower($question) && strtolower($table['Answer']) === strtolower($answer)){
                        $_SESSION['page'] = "founduser";
                    }else{
                        $errormsg = "incorrect info";
                    }
                }
           }else{
                $errormsg = "fill out all forms";
            }
        

        }else if($_SESSION['page'] === "founduser"){
            $password = $_POST['password'];
            $confirm_password = $_POST['Confirm_password'];
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            if(strlen($password) >= 6){
                if(isset($_POST['password']) && isset($_POST['Confirm_password'])){
                    if($password === $confirm_password){
                        $getmail = $conn->prepare('SELECT * FROM Users where Email=?');
                        $getmail->bind_param('s', $_SESSION['email']);
                        $getmail->execute();
                        $res = $getmail ->get_result();
                        $USER = $res->fetch_assoc();
    
                        $reset_pass_call = $conn ->prepare('SELECT * FROM Users WHERE UserID=?');
                        $reset_pass_call->bind_param('s', $USER['UserID']);
                        $reset_pass_call->execute();
            
                        $results = $reset_pass_call->get_result();
                        while($table = $results->fetch_assoc()){
                            $set_pass = $conn->prepare('UPDATE Users SET password=? WHERE UserID=?');
                            $set_pass->bind_param('ss', $pass_hash, $USER['UserID']);
                            $set_pass->execute();
                            session_destroy();
                            header('location: login.php');
                        }
                    }else{
                        $errormsg = "The passwords do not match";
                    }
                }else{
                    $errormsg = "fill out all forms";
                }
            }else{
                $errormsg = "Your password has to be 6 characters or longer";
            }
        
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
    <?php if($_SESSION['page'] == "finduser"):?>
            <form method="post" action="" name="getInfo">
            <label for="username">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $Input_email?>"><br><br>
            <label for="question">Secret question</label>
        
                <select id="question" name="question">
                <option value="show">What is your favorite movie or TV show?</option>
                <option value="born">In what city or town were you born?</option>
                <option value="pet">What was the name of your first pet?</option>
                </select>
                <br>
                <br>
                <label for="answer">Answer:</label>
                <input type="text" id="answer" name="answer" value="<?php echo $Input_answer?>"><br><br>
        
            <p style="color:red;"><?php echo $errormsg?></p>
            <p><a href="./login.php">Back</a></p>
            <input type="submit" value="Login">
            </form>
            

    <?php elseif ($_SESSION['page'] == "founduser"):?>
            
            <form method="post" action="" name="setInfo">

                <label for="password">New password:</label>
                <input type="password" id="password" name="password"><br><br>

                <label for="Confirm_password">Confirm password:</label>
                <input type="password" id="password" name="Confirm_password"><br><br>
                <p style="color:red;"><?php echo $errormsg?></p>
                <p><a href="./login.php">Back</a></p>
                <input type="submit" value="change">

            </form>
        
    <?php endif;?>
</body>
</html>