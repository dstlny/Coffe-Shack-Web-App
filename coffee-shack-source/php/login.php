<?php
/*
  Include our connection to the database.
*/
include '../dbcon/init.php';
include '../php/userUtilities.php';

if (isset($_POST['subLogin'])) {
    $user = new user();
    $user->setEmail($_POST['txtLogEmail']);
    $user->setPass($_POST['txtLogPass']);

    $_SESSION['errors'] = array();

    if($user->checkIfDataNotEmpty($user->getEmail(), $user->getPass())){

        if($user->checkUser($user->getEmail(), $user->getPass()) || $user->checkAdmin($user->getEmail(), $user->getPass())){
            
            $_SESSION['errors']['success'] = "<br><b style=\"color: green; font-size: 12px;\">Succesfully logged in!</b>";
            $_SESSION['user'] = serialize($user->getUserObject());
            header("location: ../pages/home.php");
            exit();
            
        } else {

            //Customer nor Staff exists with this email
            $_SESSION['errors']['noRecog'] = "<b style=\"color: red; font-size: 12px;\">Account not recognised!</b>";
            header("location: ../pages/home.php");
            exit();

        }

    }

} else {

    header("location: ../pages/home.php");
    exit();
    
}
?>