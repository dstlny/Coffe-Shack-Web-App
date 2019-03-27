<?php
include '../pages/header.php';

if(isset($_SESSION['loggedIn']) || isset($_SESSION['adminLoggedIn'])){
   
   if(isset($_SESSION['userName'])){
      $obj->getUserDetails($_SESSION['userName']);
   }

   if(isset($_SESSION['adminUserName'])){
      $obj->getUserDetails($_SESSION['adminUserName']);
   }
   
} else {
    header('Location: ../pages/home.php');
}

include '../pages/footer.php';
?>