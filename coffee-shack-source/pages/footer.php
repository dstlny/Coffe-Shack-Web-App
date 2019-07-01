<!--footer start-->
</div>
</div>
<div class="bottomnav">
  <?php
    $user_object = unserialize($_SESSION['user']);
    if($user_object->_logged_in && !$user_object->_admin){
        echo '<a href="../pages/menu.php">Menu</a>';
        echo '<a href="#stores">Stores</a>';
        if(isset($_SESSION['ordered'])){
            echo '<a href="../pages/payment.php">Current Order</a>';
        }
        echo '<a href="../php/userAccount.php">Account</a>';
    } elseif($user_object->_logged_in && $user_object->_admin){ 
        echo '<a href="../pages/menu.php">Menu</a>';
        echo '<a href="../pages/staffPage.php">Customer Orders</a>';
        if(isset($_SESSION['ordered'])){
            echo '<a href="../pages/payment.php">Current Order</a>';
        }
        echo '<a href="../php/userAccount.php">Account</a>';
    } else{
        echo '<a href="../pages/home.php">Home</a>';
    }
    ?>
</div> 
</body>
</html>