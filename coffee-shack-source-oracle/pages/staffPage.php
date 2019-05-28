<?php
	require_once '../pages/header.php';

        $answer = $obj->checkOrders();
	
	if(isset($_SESSION['adminLoggedIn']) && $answer == true){
	    echo '<meta http-equiv="Refresh" content="15">';
	    $obj->printCustomerOrders();
	} elseif($_SESSION['adminLoggedIn']) && $answer == false){
	    echo '<center><p style="color: red; font-size: 20px" class="loading">Currently no orders to process<span>.</span><span>.</span><span>.</span></p></center>';
	    echo '<meta http-equiv="Refresh" content="15">';
	} elseif(!isset($_SESSION['adminLoggedIn'])) {
	    echo '<center><p style="color: red; font-size: 20px" class="loading">UNAUTHORIZED USER DETECTED<span>.</span><span>.</span><span>.</span></p></center>';
	    echo "<meta http-equiv='Refresh' content='2; URL=../pages/menu.php'>";
	}

	require_once '../pages/footer.php';
?>
