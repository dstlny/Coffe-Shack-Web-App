<?php
	require_once '../pages/header.php';
	
	if(isset($_SESSION['adminLoggedIn'])){
		echo '<meta http-equiv="Refresh" content="15">';
		$obj->printCustomerOrders();
	} else {
		echo '<center><p style="color: red; font-size: 20px" class="loading">UNAUTHORIZED USER DETECTED<span>.</span><span>.</span><span>.</span></p></center>';
		echo "<meta http-equiv='Refresh' content='2; URL=../pages/menu.php'>";
	}

	require_once '../pages/footer.php';
?>