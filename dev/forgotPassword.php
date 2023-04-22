
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
       if($page === "finduser"){ 
            $email = $_POST['email'];
            $question = $_POST['question'];
            $answer = $_POST['answer'];

            if(isset($_POST['email']) && isset($_POST['question']) && isset($_POST['answer'])){
                $info_stamt = $conn->prepare("SELECT * FROM user_secret_questions, users WHERE email=?");	
                $info_stamt->bind_param("s", $email);
                $info_stamt->execute();
                $results = $info_stamt->get_result();
    
                while($table = $results->fetch_assoc()){
                    if(strtolower($table['question']) === strtolower($question) && strtolower($table['answer']) === strtolower($answer)){
                        $page = "founduser";
                        $_SESSION['temp_store_mail'] = $table['email'];
                    }
                }
                
            }else{
                $errormsg = "fill out all forms";
            }
        

       }else if($page === "founduser"){
        $password = $_POST['password'];
        $confirm_password = $_POST['Confirm_password'];

            if(isset($_SESSION['temp_store_email'])){
                if(isset($password) && isset($Confirm_password)){
                    if($password === $Confirm_password){
                        $reset_pass_call = $conn ->prepare('SELECT * FROM users WHERE email=?');
                        $reset_pass_call->bind_param('s', $email);
                        $reset_pass_call->execute();
        
                        $results = $reset_pass_call->get_result();
                        while($table = $results->fetch_assoc()){
                           
                        }
                    }else{
                        $errormsg = "The passwords do not match";
                    }
                }else{
                    $errormsg = "fill out all forms";
                }
            }else{
                header('location forgotPassword.php');
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
    
    <?php if($page == "finduser"):?>
            <form method="post" action="" name="getInfo">
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
            
            <form method="post" action="" name="setInfo">

                <label for="password">New password:</label>
                <input type="password" id="password" name="password"><br><br>

                <label for="Confirm_password">Confirm password:</label>
                <input type="password" id="password" name="Confirm_password"><br><br>

                <p><a href="./login.php">Back</a></p>
                <input type="submit" value="change">

            </form>
        
    <?php endif;?>
</body>
</html>