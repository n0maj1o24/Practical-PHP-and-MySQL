<?php

	require("db.php");

	$sql = "DELETE FROM events WHERE id = " . $_GET['id'];
	//mysql_query($sql);
	$db->query($sql);

	echo "<script>javascript: history.go(-1)</script>";

?>