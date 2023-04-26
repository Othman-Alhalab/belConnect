
<?php
    //dessa gör det möjligt för mig att spara variabler efter att sidan "refrashar" svengelska
    $input_Firstname = isset($_POST['firstname']) ? $_POST['firstname'] : '';
    $input_Lastname = isset($_POST['lastname']) ? $_POST['lastname'] : '';
    $input_Username = isset($_POST['username']) ? $_POST['username'] : '';
    $input_age = isset($_POST['age']) ? $_POST['age'] : '';
    $input_phone = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';
    $input_Email = isset($_POST['email']) ? $_POST['email'] : '';

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Account BelConnect</title>
    <link rel="stylesheet" href="../assets/css/register.css">
 
</head>
<body>

    <form method="post" action="">
    <h2 id="regid">Register</h2>
    <input type="text" name="" id="" value="mamma100">
        <div class="slide">
            
            <label for="firstname">* First Name:</label>
            <input type="text" id="firstname" name="firstname" value="<?php echo $input_Firstname; ?>" required><br><br>

            <label for="lastname">* Last Name:</label>
            <input type="text" id="lastname" name="lastname" value="<?php echo $input_Lastname; ?>" required><br><br>

            <label for="username">* Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $input_Username; ?>" required><br><br>

            <label for="email">* Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $input_Email; ?>" required><br><br>

             
            <label for="phone_number">* phone number:</label>
            <input type="tel" id="email" name="phone_number" value="<?php echo $input_phone; ?>" required><br><br>


            <label for="age">* Age:</label>
            <input type="date" id="date" name="age" value="<?php echo $input_age; ?>" required><br><br>


            <button type="button" class="next">Next</button>
        </div>

        <div class="slide">
            <label for="password">* Password:</label>
            <input type="password" id="password" name="password" pattern=".{8,}" required><br><br>

            <label for="password_con">* Confirm Password:</label>
            <input type="password" id="password_con" name="password_con" pattern=".{8,}" required>
            <br>
            <p id="theMatch">Match</p>

            <button type="button" class="prev">Previous</button>
            <br><br>

            <input type="submit" value="Register">
            
        </div>
    </form>
<p><a href="./login.php">Already have an account?</a></p>
    <script>
        let en = false
        const slides = document.querySelectorAll('.slide');
        const prevBtn = document.querySelector('.prev');
        const nextBtn = document.querySelector('.next');
        let currentSlide = 0;

        function showSlide(n) {
            slides[currentSlide].style.display = "none";
            slides[n].style.display = "block";
            currentSlide = n;
        }

        function validatePassword() {
            const password = document.getElementById("password");
            const confirm_password = document.getElementById("confirm_password");
            if (password.value !== confirm_password.value) {
                confirm_password.setCustomValidity("Passwords do not match");
                document.getElementById("theMatch").innerHTML = "Not Match";
            } else {
                confirm_password.setCustomValidity("");
                document.getElementById("theMatch").innerHTML = "Match";
            }
        }

        function validateForm() {
            validatePassword();
            const form = document.querySelector('form');
            form.reportValidity();
        }

        nextBtn.addEventListener('click', () => {
            showSlide(currentSlide + 1);
        });

        prevBtn.addEventListener('click', () => {
            showSlide(currentSlide - 1);
        });

        const password = document.getElementById("password");
        const confirm_password = document.getElementById("confirm_password");
        password.addEventListener("input", validatePassword);
        confirm_password.addEventListener("input", validatePassword);
    </script>
</body>
</html>

<?php
require "metoder.php";
require "config.php";
session_start();

if (isset($_POST["username"]) && isset($_POST["password"])) {
    
    $username = $_POST["username"];
    $phone_number = $_POST['phone_number'];
    $age = $_POST['age'];
    $password = $_POST["password"];
    $confirmPass = $_POST["password_con"];
    $email = $_POST['email'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];

    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $user_reg_stmt = $conn->prepare("SELECT * FROM Accounts WHERE Username=?");
    $user_reg_stmt->bind_param('s', $username);
    $user_reg_stmt->execute();
    $result = mysqli_num_rows(mysqli_stmt_get_result($user_reg_stmt));

    if (isset($password, $confirmPass) && $password == $confirmPass) {
        if (!inputTest($username)) {
            echo "Invalid username format!";
        } else {
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);      
            $username_lowercase = strtolower($username);
            $email_lowercase = strtolower($email);
            $result_username = $conn->query("SELECT * FROM Accounts WHERE LOWER(Username)='$username_lowercase'");
            $result_email = $conn->query("SELECT * FROM Accounts WHERE LOWER(email)='$email_lowercase'");
            if ($result_username->num_rows == 0) {
                if($result_email->num_rows == 0){
                    
                //Koden under lägger in variablerna i tabellen "users" i databasen med hjälp av prepare statments 
                $register_users = $conn->prepare("INSERT INTO Users (Firstname, Lastname, Phone_number, age) VALUES (?, ?, ?, ?)");
                $register_users->bind_param('ssss', $firstname, $lastname, $phone_number, $age);
                $register_users->execute();

                $user_id = $register_users->insert_id;
                
                $register_account = $conn->prepare("INSERT INTO Accounts (username, password, email, UserID) VALUES (?, ?, ?, ?)");
                $register_account->bind_param("sssi", $username, $pass_hash, $email, $user_id);
                
                    if ($register_account->execute() === true) {
                        header("Location: login.php");
                    } else {
                            echo "Error: " . $conn->error;
                    }  

                }else{
                    echo "Email is already in use!";
                }
                
            } else {
                echo "Username is already in use!";
            }
        }
    } else {
        echo "Passwords do not match";
    }
    
    
edWo#IN
    mysqli_close($conn);
}

?>
