<?php
include '../pages/header.php';
//If this isn't set we know the user isn't signed in, thus we can gracefully redirect them to the home page.
//Else, user is gained access to the page.
if(!isset($_SESSION['loggedIn'])){
   header('Location: ../pages/home.php');
}
        
//If this session array isn't set then set it.    
if(!isset($_SESSION['mainOrder'])){    
   $_SESSION['mainOrder'] = array();
}
    
 //if this two GET variables are set, add them onto the session array
if(isset($_GET['id']) && isset($_GET['qty'])){
   //array_push($_SESSION['mainOrder'], array($_GET['id'] => $_GET['qty'])); -- this is slower as it involves calling the array_push function each time.
   $_SESSION['mainOrder'][] = array($_GET['id'] => $_GET['qty']); //-- faster - since we haven't got the overhead of calling a function, we're just appending the ID/QTY to the end.
   header('Location: ../pages/menu.php');
} 
        
//Restricting the amount of cofee orders the user can submit to 4 
//Relay a simple message stating this.
if(count($_SESSION['mainOrder']) > 4){
   echo '<script>alert("You cannot add more than 3 items at a time!");</script>';
   $index = array_search($_GET['id'], $_SESSION['mainOrder']);
   array_splice($_SESSION['mainOrder'], $index, 1);
   header('Location: ../pages/menu.php');
}
    
 //If this session array isn't set then set it.  
if(!isset($_SESSION['sideOrder'])){    
   $_SESSION['sideOrder'] = array();
   header('Location: ../pages/menu.php');
}
    
//if this two GET variables are set, add them onto the session array
if(isset($_GET['bkID']) && isset($_GET['bkQty'])){
    //array_push($_SESSION['sideOrder'], array($_GET['bkID'] => $_GET['bkQty'])); -- this is slower as it involves calling the array_push function each time.
    $_SESSION['sideOrder'][] = array($_GET['bkID'] => $_GET['bkQty']); // faster - since we haven't got the overhead of calling a function, we're just appending the bkID/bkQty to the end.
    header('Location: ../pages/menu.php');
}
    
//Restricting the amount of orders from the bakery the user can submit to 4.
//Relay a simple message stating this.
if(count($_SESSION['sideOrder']) > 4){
   echo '<script>alert("You cannot add more than 3 items at a time!");</script>';
   $index = array_search($_GET['bkID'], $_SESSION['sideOrder']);
   array_splice($_SESSION['sideOrder'], $index, 1);
   header('Location: ../pages/menu.php');
}

//Clears all favourites, redirects to Index page.
if(isset($_GET['ClearAll'])){
   $_SESSION['mainOrder'] = array();
   $_SESSION['sideOrder'] = array();
   echo '<meta http-equiv="refresh" content="0;url=../pages/menu.php">';
}
?>
<!--JQuery accordion-->
<div id="outer-accordion">
    <h3>Coffee Selection</h3>
    <div>
        <!--JQuery accordion-->
        <div id="inner-accordion">
            <h3>Latte</h3>
                <div>
                    <?php
                        ///calling a function inside of the myFiunctions class
                        $obj->printItems('Coffee_Latte');
                    ?> 
                </div>
            <h3>Americano</h3>
                <div>
                    <?php
                        ///calling a function inside of the myFiunctions class
                        $obj->printItems('Coffee_Americano');
                     ?> 
                </div>
            <h3>Espresso</h3>
                <div>
                    <?php
                        //calling a function inside of the myFiunctions class
                        $obj->printItems('Coffee_Espresso');
                    ?> 
                </div>
            <h3>Macchiato</h3>
                <div>
                   <?php
                        //calling a function inside of the myFiunctions class
                        $obj->printItems('Coffee_Macchiato');
                    ?> 
               </div>
            <h3>Cappuccino</h3>
                <div>
                   <?php
                        //calling a function inside of the myFiunctions class
                        $obj->printItems('Coffee_Cappuccino');
                    ?>
               </div>
            <h3>Mocha</h3>
                <div>
                   <?php
                        //calling a function inside of the myFiunctions class
                        $obj->printItems('Coffee_Mocha');
                    ?>
               </div>
        </div>
    </div>
    <h3>Bakery items</h3>
    <div>
        <!--JQuery accordion-->
        <div id="inner-accordion-bakery">
            <h3>On the side..</h3>
                <div>
                    <?php
                        //calling a function inside of the myFunctions class
                        $obj->printItems('Bakery');
                    ?> 
                </div>
        </div>
    </div>
        <?php              
        if(!empty($_SESSION['mainOrder']) || !empty($_SESSION['siderOrder'])){
            echo '<h3>Current Basket</h3><div>';
            //calling a function inside of the myFiunctions class 
            $obj->printBasket();
        }
            echo '</div>';
        ?>
</div>
<?php 
include '../pages/footer.php';
?>