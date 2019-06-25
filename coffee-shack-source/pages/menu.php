<?php
include '../pages/header.php';
//If this isn't set we know the user isn't signed in, thus we can gracefully redirect them to the home page.
//Else, user is gained access to the page.
if(isset($_SESSION['loggedIn']) || isset($_SESSION['adminLoggedIn'])){
   
    //If this session array isn't set then set it.    
    if(!isset($_SESSION['mainOrder'])){    
       $_SESSION['mainOrder'] = array();
    } elseif(!isset($_SESSION['sideOrder'])){    
       $_SESSION['sideOrder'] = array();
    }
        
     //if this two GET variables are set, add them onto the session array
    if(isset($_GET['id']) || isset($_GET['bkID'])){
      
        if(isset($_GET['qty'])){
           //array_push($_SESSION['mainOrder'], array($_GET['id'] => $_GET['qty'])); -- this is slower as it involvescalling the array_push function each time.
           $_SESSION['mainOrder'][] = array($_GET['id'] => $_GET['qty']); 
           //-- faster - since we haven't got the overhead of calling a function, we're just appending the ID/QTY to the end.
        } elseif(isset($_GET['bkQty'])){
           //array_push($_SESSION['mainOrder'], array($_GET['id'] => $_GET['qty'])); -- this is slower as it involvescalling the array_push function each time.
           $_SESSION['sideOrder'][] = array($_GET['bkID'] => $_GET['bkQty']);
           //-- faster - since we haven't got the overhead of calling a function, we're just appending the ID/QTY to the end.
        }

        header('Location: ../pages/menu.php');
    } 
            
    //Restricting the amount of cofee orders the user can submit to 4 
    //Relay a simple message stating this.
    if(isset($_SESSION['mainOrder']) && count($_SESSION['mainOrder']) > 4){
       echo '<script>alert("You cannot add more than 4 items at a time from the coffee section!");</script>';
       $index = array_search($_GET['id'], $_SESSION['mainOrder']);
       array_splice($_SESSION['mainOrder'], $index, 1);
       header('Location: ../pages/menu.php');
    }

    //Restricting the amount of orders from the bakery the user can submit to 4.
    //Relay a simple message stating this.
    if(isset($_SESSION['sideOrder']) && count($_SESSION['sideOrder']) > 4){
       echo '<script>alert("You cannot add more than 4 items at a time from the bakery section!");</script>';
       $index = array_search($_GET['bkID'], $_SESSION['sideOrder']);
       array_splice($_SESSION['sideOrder'], $index, 1);
       header('Location: ../pages/menu.php');
    }
    
    //Clears necccessary session variables
    if(isset($_GET['ClearAll'])){
       $_SESSION['mainOrder'] = array();
       $_SESSION['sideOrder'] = array();
       unset($_SESSION['ordered']);
       unset($_SESSION['orderID']);
       unset($_SESSION['orderTotal']);
       unset($_SESSION['mainOrder']);
       unset($_SESSION['sideOrder']);
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
    </div>
    <?php              
            if(!empty($_SESSION['mainOrder']) || !empty($_SESSION['sideOrder'])){
                echo '<div id="basket-accordion">';
                echo '  <h3>Current Basket</h3><div>';
                $obj->printBasket();
            }
                echo '</div>';
            ?>
    <?php 
} else{
    header('Location: ../pages/home.php');
}
include '../pages/footer.php';
?>