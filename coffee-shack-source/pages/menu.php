<?php
require '../pages/header.php';
?>
<script type="text/javascript">
    function duplicateItem(){
            window.alert("This product is already in your basket, you cannot enter duplicates!");
    }
</script>
<?php
//If this isn't set we know the user isn't signed in, thus we can gracefully redirect them to the home page.
//Else, user is gained access to the page.
$user_object = unserialize($_SESSION['user']);

if($user_object->_logged_in){
   
    //If this session array isn't set then set it.    
    if(!isset($_SESSION['mainOrder'])){    
       $_SESSION['mainOrder'] = array();
    } elseif(!isset($_SESSION['sideOrder'])){    
       $_SESSION['sideOrder'] = array();
    }

     /**
      * Feel like crashing the Apache instance?
      * I fucking dare you change this shit.
      */
    if(isset($_GET['id']) || isset($_GET['bkID'])){
      
        if(isset($_GET['qty'])){

            if(empty($_SESSION['mainOrder'])){

                $_SESSION['mainOrder'][] = array('_product_id' => (int)$_GET['id'],  '_product_qty' => (int)$_GET['qty']); 

            } else{

                $needle_is_in_haystack = FALSE;

                if(count($_SESSION['mainOrder']) < 4){

                    foreach($_SESSION['mainOrder'] as $key => $item){
                        if($item['_product_id'] == $_GET['id']){
                            $needle_is_in_haystack = TRUE;
                        }
                    }
                    
                    if(!$needle_is_in_haystack){
                        $_SESSION['mainOrder'][] = array('_product_id' => (int)$_GET['id'],  '_product_qty' => (int)$_GET['qty']); 
                    } else{
                        echo'<script type="text/javascript">duplicateItem();</script>';
                    }

                }

            }

        } elseif(isset($_GET['bkQty'])){

                if(empty($_SESSION['sideOrder'])){

                    $_SESSION['sideOrder'][] = array('_product_id' => (int)$_GET['bkID'], '_product_qty' => (int)$_GET['bkQty']); 

                } else{

                    $needle_is_in_haystack = FALSE;

                    if(count($_SESSION['sideOrder']) < 4){

                        foreach($_SESSION['sideOrder'] as $key => $item){
                            if($item['_product_id'] == $_GET['bkID']){
                                $needle_is_in_haystack = TRUE;
                            }
                        }
                        
                        if(!$needle_is_in_haystack){
                            $_SESSION['sideOrder'][] = array('_product_id' => (int)$_GET['bkID'],  '_product_qty' => (int)$_GET['bkQty']); 
                        } else{
                            echo'<script type="text/javascript">duplicateItem();</script>';
                        }
                    }
                }

            }

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
        if(!isset($_SESSION['ordered'])){            
            if(!empty($_SESSION['mainOrder']) || !empty($_SESSION['sideOrder'])){
                echo "<div id=\"basket-accordion\"><h3>Current Basket</h3><div>";
                $obj->printBasket();
            }
                echo '</div><br>';
        } else{
            ?>
            <script>
            $(function() {
                $("#basket-accordion").accordion({
                collapsible: false,
                heightStyle: 'content',
                active: true
                });
            } );
            </script>
            <?=
            "<div id=\"basket-accordion\"><h3>Current Basket</h3><div>You have already ordered!<br>To access your current order please click \"Current Order\" at the bottom. Or alternatively, click <a href='../php/payment.php'>this link</a></div></div>";
        }
            ?>   
    <?php 
} else{

    header('Location: ../pages/home.php');

}

require '../pages/footer.php';
?>