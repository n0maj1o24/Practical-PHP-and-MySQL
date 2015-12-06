<?php

	require("config.php");
	require("db.php");
	require("functions.php");

	$validid = pf_validate_number($_GET['id'], "redirect", $config_basedir . "showcart.php");
	
	$itemsql = "SELECT * FROM orderitems WHERE id = " . $_GET['id'] . ";";
//	$itemres = mysql_query($itemsql);
//	$numrows = mysql_num_rows($itemres);

	$itemres = $db->query($itemsql);
	$numrows = $itemres->rowCount();
	
	if($numrows == 0) {
		header("Location: " . $config_basedir . "showcart.php");
	}
	
	//$itemrow = mysql_fetch_assoc($itemres);
	$itemrow = $itemres->fetchAll(PDO::FETCH_ASSOC);

	$prodsql = "SELECT price FROM products WHERE id = " . $itemrow[0]['product_id'] . ";";
//	$prodres = mysql_query($prodsql);
//	$prodrow = mysql_fetch_assoc($prodres);
	$prodres = $db->query($prodsql);
	$prodrow = $prodres->fetchAll(PDO::FETCH_ASSOC);

	$sql = "DELETE FROM orderitems WHERE id = " . $_GET['id'];
	//mysql_query($sql);
	$db->query($sql);

	$totalprice = $prodrow[0]['price'] * $itemrow[0]['quantity'] ;

	$updsql = "UPDATE orders SET total = total - " . $totalprice . " WHERE id = " . $_SESSION['SESS_ORDERNUM'] . ";";
	//mysql_query($updres);
	$db->query($updres);
	header("Location: " . $config_basedir . "/showcart.php");

?>
	
