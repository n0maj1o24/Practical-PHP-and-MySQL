<?php
	session_start();

	require("header.php");	
	require("functions.php");

	echo "<h1>Your shopping cart</h1>";
	showcart($db);

	if(isset($_SESSION['SESS_ORDERNUM']) == TRUE) {
		$sql = "SELECT * FROM orderitems WHERE order_id = " . $_SESSION['SESS_ORDERNUM'] . ";";
//		$result = mysql_query($sql);
//		$numrows = mysql_num_rows($result);
		$result = $db->query($sql);
		$numrows = $result->rowCount();

		if($numrows >= 1) {
			echo "<h2><a href='checkout-address.php'>Go to the checkout</a></h2>";
		}
	}
	
	
	require("footer.php");
?>
	
