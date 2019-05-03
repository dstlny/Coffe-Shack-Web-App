<?php
include '../dbcon/init.php';
if(isset($_POST['subOrder'])){
    $OID=$_POST['orderID'];
	
	if(empty($OID)){
		echo '<center><p>Currently no orders have been placed</p></center>';
	} else {
	    $SID=$_POST['staffID'];
	    $stid = oci_parse($connection, "UPDATE ORDERS SET ORDER_COMPLETE = 'Y', FK2_STAFF_ID = '$SID' WHERE ORDER_ID = '$OID'");
	    oci_execute($stid);
	    header('Location: ../pages/staffPage.php');
	    exit();
	}
}
?>