<?php 
session_start();
$hostname = 'EROS'; 
$password = '******'; 
$databaseName = '******'; 
$connection = oci_connect($databaseName, $password, $hostname) or exit("Unable to connect to database!");
?>
