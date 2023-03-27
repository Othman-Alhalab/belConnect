<?php
    session_start(); // start the session
    session_destroy(); // clear the session data
    header("Location: login.php"); 
?>