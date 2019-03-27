<?php 
session_start();
$hostname = ''; 
$password = ''; 
$databaseName = ''; 
$connection = oci_connect($databaseName, $password, $hostname) or exit("Unable to connect to database!");
?>