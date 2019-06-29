<?php
   require_once 'header.php';

   $user_object = unserialize($_SESSION['user']);

   if($user_object->_admin == TRUE && $user_object->_logged_in == TRUE){

      echo '<meta http-equiv="Refresh" content="30">';
      $answer = $obj->checkOrders();
      
      if($answer == TRUE){
         $obj->printCustomerOrders();
      } else {
         echo '<p>Currently no orders to processs....</p>';
      }

   } else {

      echo '<meta http-equiv="refresh" content="0;URL="../home.php"/>';

   }
   require_once 'footer.php';
?>