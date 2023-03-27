<?php
    session_start(); // start the session

  
    if(isset($_SESSION['username'])) {

        echo "Welcome, " . $_SESSION['username'] . "!<br>";
        echo "<a href='logout.php'>Logout</a>";
        echo '<img src="https://thumbs.dreamstime.com/b/welcome-word-14571465.jpg" alt="">';
    } else {
        echo '<img src="https://t3.ftcdn.net/jpg/02/78/64/40/360_F_278644083_BroS8fi7rLZ0ve8UzowgyLhjavNo9cNA.jpg" alt="">';
        echo "<a href='logout.php'>back to login</a>";
    }
?>
