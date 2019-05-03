</div>
</div>
<div class="bottomnav">
  <?php
    if(isset($_SESSION['loggedIn'])){
        echo '<a href="../pages/menu.php">Menu</a>';
        echo '<a href="#stores">Stores</a>';
        echo '<a href="../php/userAccount.php">Account</a>';
    } elseif(isset($_SESSION['adminLoggedIn'])){ 
        echo '<a href="../pages/menu.php">Menu</a>';
        echo '<a href="../pages/staffPage.php">Customer Orders</a>';
        echo '<a href="../php/userAccount.php">Account</a>';
    } else{
        echo '<a href="../pages/home.php">Home</a>';
    }
    ?>
</div> 
</body>
</html>