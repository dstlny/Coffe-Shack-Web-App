<?php
    require_once 'header.php';

    if(isset($_SESSION['loggedIn'])){
       require_once '../php/login-form.php';
    } elseif(isset($_SESSION['adminLoggedIn']) && !empty($_POST['orderID'])){
       require_once '../pages/staffPage.php';
    } else {
       require_once '../php/login-form.php';
    }
    
    require_once 'footer.php';
?>