<?php


    if (!defined('DB_SERVER')) {
        define('DB_SERVER', 'localhost');
    }
    if (!defined('DB_USERNAME')) {
        define('DB_USERNAME', 'root');
    }
    if (!defined('DB_PASSWORD')) {
        define('DB_PASSWORD', '');
    }
    if (!defined('DB_NAME')) {
        define('DB_NAME', 'BelConnectDB');
    }

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>