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

//Make sure that the order arrays are set and are occupied, else redirect back to the appropriate page.
if(!empty($_SESSION['mainOrder']) || !empty($_SESSION['sideOrder'])){
    echo '<br><div id="#basket-accordion"><h3 style="font-size:15px;">Your Order, Order ID: '.$_SESSION['orderID'].'</h3><div>';
    //Prints the users final, confirmed order.
    $obj->printFinal();

    if(!isset($_SESSION['ordered'])){
	$userID = $_SESSION['userID'];
	$total = $_SESSION['orderTotal'];
	$_SESSION['tblNo'] = $_POST['tblNo'];
	$current_timestamp = date('Y-m-d H:i:s'); 
	    
	//Get insert users order to the database.
	$obj->step1($id,$total,$current_timestamp,'N',$_SESSION['tblNo'],$userID,NULL);
	
	//Get the next OrderID.
	$answer = $obj->returnNextID();
	    
	//Loop through the order-items of the mainOrder session array
	//Inserting them into order-items table.
	for($i = 0; $i < count($_SESSION['mainOrder']); $i++){
	    foreach($_SESSION['mainOrder'][$i] as $key=>$value){
	            $obj->step2($id,$value,$key,$answer);
	    }
	}

	//Loop through the order-items of the sideOrder session array
	//Inserting them into order-items table.
	for($i = 0; $i < count($_SESSION['sideOrder']); $i++){
	    foreach($_SESSION['sideOrder'][$i] as $key=>$value){
	            $obj->step2($id,$value,$key,$answer);
	    }
	}

	//here to make sure the order doesn't go through more than once.
	$_SESSION['ordered'] = '1';
    }

    if($obj->getOrderStatus($_SESSION['orderID']) == 'N' || $obj->getOrderStatus($_SESSION['orderID']) == NULL){
       echo '<div><center><b><p style="font-size: 15px;">Order placed <span style="font-size: 15px;" id="minutes">00</span>:<span style="font-size: 15px;" id="seconds">00</span> ago</p></b></center></div></div></div>';
       echo '<div><center><b><p style="font-size: 15px;">This page will refresh every 30 seconds.</p></b></center></div>';
       echo '<meta http-equiv="Refresh" content="30">';
    } else{
       echo '<div><center><b><p style="font-size: 15px; color: green;">Your order is completed! Please collect your order from the till, citing your Order Number (<b>'.$_SESSION['orderID'].'</b>)!</p></b><br><p style="font-size:12px;" class="loading">Redirecting back to menu, thanks for your order!<span>.</span><span>.</span><span>.</span></p></center></div></div></div>';
       //tell the customer their order is complete, redirect back to menU and clear their current order by appending ?ClearOrder to the URL.
       echo "<meta http-equiv='Refresh' content='4; URL=../pages/menu.php?ClearAll'>";
    }

} else {
    header('Location: ../pages/menu.php');
}

include '../pages/footer.php';

?>
