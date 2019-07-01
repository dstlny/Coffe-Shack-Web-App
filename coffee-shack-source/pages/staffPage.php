<?php
   require_once 'header.php';

   $user_object = unserialize($_SESSION['user']);

   if($user_object->_admin && $user_object->_logged_in){

      echo '<meta http-equiv="Refresh" content="30">';
      $answer = $obj->checkOrders();
      
      if($answer){
         $obj->printCustomerOrders();
      } else {
         echo '<p style="font-size:20px;" class="loading">Currently no orders to process<span>.</span><span>.</span><span>.</span></p>';
      }

   } else {

      echo '<meta http-equiv="refresh" content="0;URL="../home.php"/>';

   }

   require_once 'footer.php';
?>