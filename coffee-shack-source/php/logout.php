<?php
    session_start();
    $_SESSION = array(); // Clears the $_SESSION variable
    unset($_SESSION['user']);
    session_destroy();
    header('location:../pages/home.php');
?>
