<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "BelConnectDB";

    // Get form data
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if user exists and password is correct
    $sql = "SELECT * FROM users WHERE username = '$user' AND password = '$pass'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Login successful
        echo "Login successful!";
    } else {
        // Login failed
        echo "Invalid username or password";
    }

    // Close connection
    mysqli_close($conn);
?>
