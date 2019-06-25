<?php
include '../pages/header.php';

$user_object = unserialize($_SESSION['user']);

if($user_object->_logged_in == TRUE){
   
   $obj->getUserDetails($user_object->_mailuid, $user_object->_admin);

} else {

    header('Location: ../pages/home.php');

}

include '../pages/footer.php';
?>