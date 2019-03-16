<?php 
session_start();
$hostname = 'localhost'; 
$username = 'root'; 
$password = ''; 
$databaseName = 'c3518706'; 
$connection = mysqli_connect($hostname, $username, $password, $databaseName) or exit("Unable to connect to database!");
?>