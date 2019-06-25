<?php
include '../pages/header.php';
?>
<!--Simple timer in JS.-->
<script>
    var sec = 0;
    function pad ( val ) { return val > 9 ? val : "0" + val; }
    setInterval( function(){
        document.getElementById("seconds").innerHTML=pad(++sec%60);
        document.getElementById("minutes").innerHTML=pad(parseInt(sec/60,10));
        }, 1000);
</script>
<?php              
if(!empty($_SESSION['mainOrder']) || !empty($_SESSION['sideOrder'])){
    echo "<br><div id=\"#basket-accordion\"><h3 style=\"font-size:15px;\">Order ID: {$_SESSION['orderID']}</h3><div>";
    //calling a function inside of the myFiunctions class 
    $user_object = unserialize($_SESSION['user']);
    $obj->printFinal();

    if(!isset($_SESSION['ordered'])){
        $total = $_SESSION['orderTotal'];
        $_SESSION['tblNo'] = $_POST['tblNo'];
        $current_timestamp = date('Y-m-d H:i:s');

        $obj->insertOrders($total,$_SESSION['tblNo'],$current_timestamp,$user_object->_userID);

        for($i = 0; $i < count($_SESSION['mainOrder']); $i++){
            foreach($_SESSION['mainOrder'][$i] as $key=>$value){
                $obj->insertOrderItems($value,$key,$_SESSION['orderID']);
            }
        }

        for($i = 0; $i < count($_SESSION['sideOrder']); $i++){
            foreach($_SESSION['sideOrder'][$i] as $key=>$value){
                $obj->insertOrderItems($value,$key,$_SESSION['orderID']);
            }
        }

        //here to make sure the order doesn't go through more than once.
		$_SESSION['ordered'] = '1';
    }

    if($obj->getOrderStatus($_SESSION['orderID']) == 'N' || $obj->getOrderStatus($_SESSION['orderID']) == NULL){
        echo '<div><center><b><p style="font-size: 15px;">Order placed <span style="font-size: 15px;" id="minutes">00</span>:<span style="font-size: 15px;" id="seconds">00</span> ago</p></b></center></div>';
        echo '<div><center><b><p style="font-size: 15px;">This page will refresh every 30 seconds.</p></b></center></div></div><br><br>';
        echo '<meta http-equiv="Refresh" content="30">';
     } else{
        echo "<div><center><b><p style=\"font-size: 15px; color: green;\">Your order is completed! Please collect your order from the till, citing your Order Number (<b>{$_SESSION['orderID']}</b>)!</p></b><br><p style=\"font-size:12px;\" class=\"loading\">Redirecting back to menu, thanks for your order!<span>.</span><span>.</span><span>.</span></p></center></div></div>";
        //tell the customer their order is complete, redirect back to menu and clear their current order by appending ?ClearOrder to the URL.
        echo "<meta http-equiv='Refresh' content='15; URL=../pages/menu.php?ClearAll'>";
     }

} else{

    header('Location: ../pages/menu.php');
    
}
include '../pages/footer.php';
?>
