<?php 
    require "./config.php";


     //Kollar om strängen har siffror

    function siffror_test($str){
        return preg_match('/\d/', $str) > 0;
    };

    //En function som kollar at strängen som jag lägger in bara inhelåller bokstäver (stora och små från A-Z)
    //samt mellan rum (whitespaces)
    function inputTest($str){
        return preg_match('/\S/', $str) && preg_match('/[a-zA-Z]/', $str);
    };

   //en function som tar fram "user-id" om det behövs. 
    function getid($conn){
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ? AND password = ?");
        mysqli_stmt_bind_param($stmt, "ss", $user, $pass);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        return $row['id'];
    };

    //en funktion till att göra session id's
    function generate_session_id() {
        $prefix = "SESSION_"; // Add a prefix to make the session ID more unique
        return $prefix . uniqid();
    }
?>