<?php 
    require "config.php";
    session_start();

    if(isset($_SESSION['username'])):?>
        <?php 

            $errormsg = "";

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                if($_SESSION['delAccount'] == "finduser"){
                    if(isset($_POST['question']) && $_POST['answer']){
                        $info_stamt = $conn->prepare("SELECT * FROM user_secret_questions WHERE user_id=?");	
                        $info_stamt->bind_param('i', $_SESSION['id']);
                        $info_stamt->execute();
                        $results = $info_stamt->get_result();
                        while($table = $results->fetch_assoc()){
                            if(strtolower($table['question']) === strtolower($_POST['question']) && strtolower($table['answer']) === strtolower($_POST['answer'])){
                                $_SESSION['delAccount'] = "founduser";
                                $errormsg = "dsa";
                            }else{
                                $errormsg = "wrong";
                            }
                        }
                    }else{
                        $errormsg = "fill out all feilds";
                    }
                }else if($_SESSION['delAccount'] == "founduser"){
                    if(isset($_POST['password']) && isset($_POST['username'])){
                        $findInfo = $conn->prepare('SELECT * FROM users WHERE username=?');
                        $findInfo->bind_param('s', $_POST['username']);
                        $findInfo->execute();
                        $results = $findInfo->get_result();
                        while($table = $results->fetch_assoc()){
                          if(password_verify($_POST['password'], $table['password']) && $_SESSION['id'] == $table['id'] && strtolower($_POST['username']) === strtolower($table['username'])){
                            
                            //ej klar
                            $delAcc = $conn->prepare('DELETE FROM users WHERE id=?');
                            $delAcc->bind_param('i', $table['id']);
                            $delAcc->execute();
                            
                          }else{
                            echo "wrong password";
                          }
                        }

                    }
                }
            }

        
    ?>



    <!DOCTYPE html>
    <html>
    <head>
        <title>Delete Account</title>
        <link rel="stylesheet" href="../assets/css/deleteAccount.css">
    </head>
    <body>
         
            <?php if($_SESSION['delAccount'] === "finduser"):?>
                <div class="confirm">
                <h1>Verification</h1>
                    <form method="post" action="">
                    <label for="question"></label>

                        <select id="question" name="question">
                        <option value="show">What is your favorite movie or TV show?</option>
                        <option value="born">In what city or town were you born?</option>
                        <option value="pet">What was the name of your first pet?</option>
                        </select>
                        <br>
                        
                        <br>
                        <input type="text" name="answer" id="answer" placeholder="dog name efe">
                        <input type="submit" value="verify">
                    </form>
                    <p><?php echo $errormsg?></p>
                    <button><a href="./editProfile.php">go back</a></button>
                </div>

            <?php elseif($_SESSION['delAccount'] == "founduser"):?>
              
                <div class="container">
                    <h1>Delete Account</h1>
                    <p>Are you sure you want to delete your account?</p>
                    <form method="post" action="">
                        <p>nej, du kan inte radera andra konton 💀</p>
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" required>
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                        <button type="submit">Confirm</button>
                    </form>
                    <a href="./editProfile.php">go back</a>
                </div>
                
            <?php endif?>
        

    </body>
    </html>






<?php else:?>
    <?php echo "Acess denied!". "<br>" .'<a href="./logout.php">back</a>';?>
    
<?php endif ?>
