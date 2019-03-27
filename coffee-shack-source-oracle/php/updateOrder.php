<?php
include '../dbcon/init.php';
if(isset($_POST['subOrder'])){
    $OID=$_POST['orderID'];
    $SID=$_POST['staffID'];
    $stid = oci_parse($conn, "UPDATE ORDERS SET Order_Complete = 'Y', fk2_Staff_ID = '$SID' WHERE ORDER_ID = '$OID'");
    oci_execute($stid);
    oci_free_statement($stid);
	oci_close($connection);
    header('Location: ../pages/home.php');
    exit();
}
?>