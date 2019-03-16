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
if(!empty($_SESSION['mainOrder']) || !empty($_SESSION['siderOrder'])){
    echo '<div id="outer-accordion"><h3>Your order</h3><div>';
    //calling a function inside of the myFiunctions class 
    $obj->printFinal();
    echo '<div><center><b><p style="font-size: 15px;">Order placed <span style="font-size: 15px;" id="minutes"></span>:<span style="font-size: 15px;" id="seconds"></span> ago</p></b></center></div></div></div>';
} else{
    header('Location: ../pages/menu.php');
}
include '../pages/footer.php';
?>
<?php
$id = ' ';
$userID = $_SESSION['userID'];
$total = $_SESSION['orderTotal'];
$current_timestamp = date('Y-m-d H:i:s'); 
$obj->step1($id,$total,$current_timestamp,'N',$userID,NULL);
$result = mysqli_query($connection, "SELECT MAX(Order_ID) FROM ORDERS");
while($row = mysqli_fetch_assoc($result)) {
      $answer = $row['MAX(Order_ID)'];
}

for($i = 0; $i < count($_SESSION['mainOrder']); $i++){
    foreach($_SESSION['mainOrder'][$i] as $key=>$value){
            $obj->step2($id,$value,$key,$answer);
    }
}

for($i = 0; $i < count($_SESSION['sideOrder']); $i++){
    foreach($_SESSION['sideOrder'][$i] as $key=>$value){
            $obj->step2($id,$value,$key,$answer);
    }
}
?>
