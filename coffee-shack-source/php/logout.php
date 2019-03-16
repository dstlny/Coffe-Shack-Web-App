<?php
    session_start();
    $_SESSION = array(); // Clears the $_SESSION variable
    session_destroy();
    header('location:../pages/home.php');
?>
