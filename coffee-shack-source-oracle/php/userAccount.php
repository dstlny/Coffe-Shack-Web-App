<?php
include '../pages/header.php';


//Print either the customers information or the staff-members information if they are in the Account page.
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
