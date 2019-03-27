<?php
include '../dbcon/init.php';
if(isset($_POST['subOrder'])){
    $OID=$_POST['orderID'];
    $SID=$_POST['staffID'];
    $sql = "UPDATE ORDERS SET Order_Complete = 'Y', fk2_Staff_ID = '$SID' WHERE ORDER_ID = '$OID';";
    mysqli_query($connection, $sql);
    header('Location: ../pages/home.php');
    exit();
}
?>