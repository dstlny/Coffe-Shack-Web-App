<?php
    require_once 'header.php';

    $user_object = unserialize($_SESSION['user']);

    if(!$user_object->_admin){
      require_once '../php/login-form.php';
    } elseif($user_object->_admin){
      header('Location: ../pages/staffPage.php');
    } else {
       require_once '../php/login-form.php';
    }
    
    require_once 'footer.php';
?>