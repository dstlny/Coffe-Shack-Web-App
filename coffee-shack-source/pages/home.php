<?php
    require_once 'header.php';

    $user_object = unserialize($_SESSION['user']);

    if($user_object->_admin == FALSE){
       require_once '../php/login-form.php';
    } elseif($user_object->_admin == TRUE){
       header('Location: ../pages/staffPage.php');
    } else {
       require_once '../php/login-form.php';
    }
    
    require_once 'footer.php';
?>