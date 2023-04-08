
<?php
    //dessa gör det möjligt för mig att spara variabler efter att sidan "refrashar" svengelska
    $input_Firstname = isset($_POST['firstname']) ? $_POST['firstname'] : '';
    $input_Lastname = isset($_POST['lastname']) ? $_POST['lastname'] : '';
    $input_Username = isset($_POST['username']) ? $_POST['username'] : '';
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
        <div class="slide">
            <label for="firstname">* First Name:</label>
            <input type="text" id="firstname" name="firstname" value="<?php echo $input_Firstname; ?>" required><br><br>

            <label for="lastname">* Last Name:</label>
            <input type="text" id="lastname" name="lastname" value="<?php echo $input_Lastname; ?>" required><br><br>

            <label for="username">* Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $input_Username; ?>" required><br><br>

            <label for="email">* Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $input_Email; ?>" required><br><br>

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
    
    $user = $_POST["username"];
    $pass = $_POST["password"];
    $email = $_POST['email'];

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $confirmPass = $_POST["password_con"];
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_num_rows(mysqli_stmt_get_result($stmt));

    if (isset($pass, $confirmPass) && $pass == $confirmPass) {
        if (!inputTest($user)) {
            echo "Invalid username format!";
        } else {
            $username_lowercase = strtolower($user);
            $result = $conn->query("SELECT * FROM users WHERE LOWER(username)='$username_lowercase'");
            if ($result->num_rows == 0) {
                $send = "INSERT INTO users (username, password, email, firstname, lastname) VALUES ('$user', '$pass', '$email', '$firstname', '$lastname')";
                try {
                    if ($conn->query($send) === true) {
                        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
    
                        mysqli_stmt_bind_param($stmt, "s", $_POST["username"]);
                        
                        mysqli_stmt_execute($stmt);
                        
                        $result = mysqli_stmt_get_result($stmt);
    
                        $_SESSION['username'] = $_POST["username"];
                        $_SESSION['password'] = $_POST["password"];
                        $_SESSION['email'] = $_POST['email'];
                        $_SESSION['id'] = $row['id'];
                        $_SESSION['firstname'] = $_POST['firstname'];
                        $_SESSION['lastname'] = $_POST['lastname'];
                
                        header("Location: login.php");
                    } else {
                        echo "Error: " . $conn->error;
                    }
                } catch (\Throwable $th) {
                    echo "Error: " . $th->getMessage();
                }
            } else {
                echo "Username already in use!";
            }
        }
    } else {
        echo "Passwords do not match";
    }
    
    

    mysqli_close($conn);
}

?>
