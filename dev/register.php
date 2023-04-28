
<?php
    //dessa gör det möjligt för mig att spara variabler efter att sidan "refrashar" svengelska
    $input_Firstname = isset($_POST['firstname']) ? $_POST['firstname'] : '';
    $input_Lastname = isset($_POST['lastname']) ? $_POST['lastname'] : '';
    $input_Username = isset($_POST['username']) ? $_POST['username'] : '';
    $input_age = isset($_POST['age']) ? $_POST['age'] : '';
    $input_phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $input_Email = isset($_POST['email']) ? $_POST['email'] : '';
?>


<!DOCTYPE html>
<html>
  <head>
    <title>Login Page</title>
    <link rel="stylesheet" href="../assets/css/registerNEW.css">
  </head>
  <body>
  <form action="" method="POST">
    <div id="form1">
        <h2>Enter your personal information:</h2>
        <label for="firstname">First Name: (requied)</label>
        <input type="text" id="firstname" name="firstname" value="<?php echo $input_Firstname ?>"><br>

        <label for="lastname">Last Name: (requied)</label>
        <input type="text" id="lastname" name="lastname" value="<?php echo $input_Lastname ?>"><br>

        <label for="username">Username: (requied)</label>
        <input type="text" id="username" name="username" value="<?php echo $input_Username ?>"><br>

        <label for="email">Email: <br> (requied)</label>
        <input type="text" id="email" name="email" value="<?php echo $input_Email ?>"><br>

        <label for="phone">Phone Number: (requied)</label>
        <input type="tel" id="phone" name="phone" value="<?php echo $input_phone ?>"><br>

        <label for="age">Age: <br> (requied) [12+] </label>
        <input type="number" id="age" name="age" value="<?php echo $input_age ?>"><br>
        <p id="err" name="err" style="color:red;"></p>
        <button type="button" onclick="checkForm1()">Next</button>
        <p><a href="./login.php">already have an account</a></p>
    </div>

    <div id="form2">
        <h2>Create a password:</h2>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br>

        <label for="password_con">Confirm Password:</label>
        <input type="password" id="password_con" name="password_con"><br>

        
        <button type="submit" onclick="checkForm2()">Submit</button>
        <button type="button" onclick="back()">Back</button>
    </div>
</form>


    <script>
      function checkForm1() {
        // Get all the input elements in the first form
        const inputs = document.querySelectorAll("#form1 input");

        // Check if all input elements have a value
        for (let i = 0; i < inputs.length; i++) {
          if (!inputs[i].value) {
            document.getElementById('err').innerText = "Please fill out all fields!";
            return false;
          }
        }

        // Check if age is a number
        const ageInput = document.querySelector("#age");
        if (isNaN(ageInput.value)) {
            document.getElementById('err').innerText = "Please enter a valid age!"
          
          return false;
        }

        // If all checks pass, hide form 1 and show form 2
        document.getElementById("form1").style.display = "none";
        document.getElementById("form2").style.display = "block";
      }

      function checkForm2() {
        // Get the password and confirm password input elements
        const passwordInput = document.querySelector("#password");
        const password_conInput = document.querySelector("#password_con");

        // Check if password and confirm password match
        if (passwordInput.value !== password_conInput.value) {
          document.getElementById('err').innerText = "Passwords do not match!";
          return false;
        }
      }

      function back(){
        document.getElementById("form1").style.display = "block";
        document.getElementById("form2").style.display = "none";
      }
      document.getElementById("form1").style.display = "block";
      document.getElementById("form2").style.display = "none";
    </script>
  </body>
</html>
<?php
require "metoder.php";
require "config.php";
session_start();
   
      if($_SERVER['REQUEST_METHOD'] == "POST"){
        if (isset($_POST["username"]) && isset($_POST["password"])) {
    
            $username = $_POST["username"];
            $phone_number = $_POST['phone'];
            $age = $_POST['age'];
            $password = $_POST["password"];
            $confirmPass = $_POST["password_con"];
            $email = $_POST['email'];
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
        
            $user_reg_stmt = $conn->prepare("SELECT * FROM Users WHERE Username=?");
            $user_reg_stmt->bind_param('s', $username);
            $user_reg_stmt->execute();
            $result = mysqli_num_rows(mysqli_stmt_get_result($user_reg_stmt));

            if(strlen($password) >= 6){
              if(testUsername($username) && strlen($username) >= 3){
                if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                  if(inputTest($firstname) && inputTest($lastname) && inputTest($username)){
                    if($age >= 12){
                      if(isset($password, $confirmPass) && $password == $confirmPass) {
                            $pass_hash = password_hash($password, PASSWORD_DEFAULT);      
                            $username_lowercase = strtolower($username);
                            $email_lowercase = strtolower($email);
                            $result_username = $conn->query("SELECT * FROM Users WHERE LOWER(Username)='$username_lowercase'");
                            $result_email = $conn->query("SELECT * FROM Users WHERE LOWER(email)='$email_lowercase'");
                            if ($result_username->num_rows == 0) {
                                if($result_email->num_rows == 0){
                                    
                                //Koden under lägger in variablerna i tabellen "users" i databasen med hjälp av prepare statments 
                                $register_users = $conn->prepare("INSERT INTO Users (Firstname, Lastname, Phone_number, age, Username, Password, Email) VALUES (?, ?, ?, ?, ?, ?, ?)");
                                $register_users->bind_param('sssssss', $firstname, $lastname, $phone_number, $age, $username, $pass_hash, $email);
                                $register_users->execute();
                
                                $user_id = $register_users->insert_id;
                                
                                $create_pic = $conn->prepare("INSERT INTO Profile_pic (UserID) VALUES (?)");
                                $create_pic->bind_param('i',$user_id);
                                
                                    if ($create_pic->execute()) {
                                        $_SESSION['Username'] = $_POST["username"];
                                        $_SESSION['Phone_number'] = $_POST['phone'];
                                        $_SESSION['Password'] = $_POST["password"];
                                        $_SESSION['Email'] = $_POST['email'];
                                        $_SESSION['Firstname'] = $_POST['firstname'];
                                        $_SESSION['Lastname'] = $_POST['lastname'];
                                        $_SESSION['UserID'] = $user_id;
                                        header("Location: home.php");
               
                                    } else {
                                      echo "Error: " . $conn->error;
                                    }  
                
                                }else{
                                  echo "<script>document.getElementById('err').innerText = 'Email is already in use!'</script>";
                                }
                                
                            } else {
                              echo "<script>document.getElementById('err').innerText = 'Username is already in use!'</script>";
                            }
                            
                          }
                      } else {
                        echo "<script>document.getElementById('err').innerText = 'You have to be older then 13'</script>";
                      }
                    }else{
                      echo "<script>document.getElementById('err').innerText = 'no speical characters (* | / | + | - | 1-9) etc in (first name, Last name, tel and username)'</script>";
                    }
                }else{
                  echo "<script>document.getElementById('err').innerText = 'please enter a vaild email'</script>";
                }
              }else{
                echo "<script>document.getElementById('err').innerText = 'username is to short (atleast 3 characters) or inclueds speical characters'</script>";
              }
            }else{
              echo "<script>document.getElementById('err').innerText = 'The password has to be atleast 6 characters long!'</script>";
            }
            
            }
            mysqli_close($conn);
        }
      

?>