<?php
require_once '../dbcon/init.php';
require_once '../php/myFunctions.php';
$obj = new myFunctions();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Assignment</title>
<meta charset="utf-8">

<!--makes sure the website is responsive-->
<meta name="viewport" content="width=device-width, initial-scale=1">

<!--Style sheets ans well as scripts for the JQuery Accordions, for them to work-->
<link rel="stylesheet" type="text/css" href="../css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>

<!--Simple JQuery accordion script.-->
<script>
  $(function() {
    $("#inner-accordion, #outer-accordion, #accordion, #inner-accordion-bakery, #staff-accordion, #basket-accordion").accordion({
      collapsible: true,
      heightStyle: 'content',
      active: false
    });
  } );
</script>
</head>
<!--Header navbar-->
<nav class="nav-down" style="z-index: 2147483647">
    <a style="float:left; background:#333; color:#fff;pointer-events: none; cursor: default;">COFFEE SHACK</a>
    <?php
       $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
       $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
      
       if(strpos($url, 'home.php') !== false){
          echo '<a href="../pages/home.php">HOMEPAGE</a>';
       } elseif(strpos($url, 'menu.php') !== false){
          echo '<a href="../pages/menu.php">MENU</a>';
       } elseif(strpos($url, 'payment.php') !== false){
          echo '<a href="../pages/payment.php">ORDER PAYMENT</a>';
       } elseif(strpos($url, 'register-form.php') !== false){
          echo '<a href="../php/register-form.php">USER REGISTRATION</a>';
       } elseif(strpos($url, 'userAccount.php') !== false){
          echo '<a href="../php/userAccount.php">ACCOUNT DETAILS</a>';
       } elseif(strpos($url, 'staffPage.php') !== false){
          echo '<a href="../pages/staffPage.php">STAFF PAGE</a>';
       }
    ?>
</nav>
<body>
<div class="main">