<?php 
session_start();
$hostname = 'EROS'; 
$password = 'C7148666'; 
$databaseName = 'C7148666'; 
$connection = oci_connect($databaseName, $password, $hostname) or exit("Unable to connect to database!");
?>