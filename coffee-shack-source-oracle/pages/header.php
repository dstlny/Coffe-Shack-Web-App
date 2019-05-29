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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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

   
<!--This script only triggers if the user scrolls up and content hits the header.
Only here to stop a nasty bug with JQuery accordions ovelapping the header--> 
<script>
  // Hide Header on on scroll down
  var didScroll;
  var lastScrollTop = 0;
  var delta = 5;
  var navbarHeight = $('nav').outerHeight();
  
  $(window).scroll(function(event){
      didScroll = true;
  });
  
  setInterval(function() {
      if (didScroll) {
          hasScrolled();
          didScroll = false;
      }
  }, 250);
  
  function hasScrolled() {
      var st = $(this).scrollTop();
      
      // Make sure they scroll more than delta
      if(Math.abs(lastScrollTop - st) <= delta)
          return;
      
      // If they scrolled down and are past the navbar, add class .nav-up.
      // This is necessary so you never see what is "behind" the navbar.
      if (st > lastScrollTop && st > navbarHeight){
          // Scroll Down
          $('nav').removeClass('nav-down').addClass('nav-up');
      } else {
          // Scroll Up
          if(st + $(window).height() < $(document).height()) {
              $('nav').removeClass('nav-up').addClass('nav-down');
          }
      }
      
      lastScrollTop = st;
  }
</script>
</head>
<!--Header navbar-->
<nav class="nav-down">
    <a style="float:left; background:#333; color:#fff;pointer-events: none; cursor: default;">COFFEE SHACK</a>
    <?php
       $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
       $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
      
       if(strpos($url, 'home.php') !== false && isset($_SESSION['adminLoggedIn'])){
          echo '<a href="../pages/staffPage.php">STAFF PAGE</a>';
       } elseif(strpos($url, 'home.php') !== false){
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