<?php
    include '../dbcon/init.php';

    if(isset($_POST['subOrder'])){

        $OID=$_POST['orderID'];
        $SID=$_POST['staffID'];
        $stid = oci_parse($connection, "UPDATE ORDERS SET ORDER_COMPLETE = 'Y', FK2_STAFF_ID = '$SID' WHERE ORDER_ID = '$OID'");
        oci_execute($stid);
        oci_free_statement($stid);
        oci_close($connection);
        header('Location: ../pages/staffPage.php');
        exit();

    }
?>