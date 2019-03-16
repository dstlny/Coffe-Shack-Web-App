<?php
include '../pages/header.php';

if(!isset($_SESSION['loggedIn'])){
    header('Location: ../pages/home.php');
}

$obj->getUserDetails($_SESSION['userName']);

include '../pages/footer.php';
?>