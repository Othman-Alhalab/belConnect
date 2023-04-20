<?php session_start();

if(isset($_SESSION['username'])):?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Delete Account</title>
        <link rel="stylesheet" href="../assets/css/deleteAccount.css">
    </head>
    <body>
        <div class="container">
            <h1>Delete Account</h1>
            <p>Are you sure you want to delete your account?</p>
            <form method="post" action="delete_account.php">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Confirm</button>
            </form>
        </div>

        <div class="confirm">
        <h1>Verification</h1>
            <form method="post" action="delete_account.php">
            <label for="question"></label>

                <select id="question">
                <option value="show">What is your favorite movie or TV show?</option>
                <option value="born">In what city or town were you born?</option>
                <option value="pet">What was the name of your first pet?</option>
                </select>
                <br>
                <br>
                <input type="text" name="answer" id="answer" placeholder="woofels? idk?💀">
                <input type="submit" value="verify">
            </form>
            <button><a href="./editProfile.php">go back</a></button>
        </div>

        <script>
            document.getElementsByClassName('container')[0].style.display = 'none';
            //document.getElementsByClassName('confirm')[0].style.display = 'none';

            let twoFA = "active" // cookie
            let mode = false; //cookie?

            function checkFA(cookie){
                switch (cookie) {
                    case "active":
                        if(!mode){
                            document.getElementsByClassName('container')[0].style.display = 'none';
                            document.getElementsByClassName('confirm')[0].style.display = 'block';
                            document.cookie = "fa=" + mode;
                        }else{
                            document.getElementsByClassName('container')[0].style.display = 'block';
                            document.getElementsByClassName('confirm')[0].style.display = 'none';
                            document.cookie = "fa=" + mode;
                        }
                        break;
                
                    case "disabled":
                        document.getElementsByClassName('container')[0].style.display = 'block';
                        document.getElementsByClassName('confirm')[0].style.display = 'none';
                    break;
                }
            }
        </script>
    </body>
    </html>






<?php else:?>
    <?php echo "Acess denied!". "<br>" .'<a href="./logout.php">back</a>';?>
    
<?php endif ?>
