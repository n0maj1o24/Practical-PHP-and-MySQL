<?php

session_start();

require("config.php");
require("db.php");
require("functions.php");

if($_SESSION['SESS_USERLEVEL'] != 10) {
	header("Location: " . $config_basedir);
}

if(pf_check_number($_GET['id']) == TRUE) {
	$validid = $_GET['id'];
}
else {
	header("Location: " . $config_basedir);
}


if($_GET['conf']) {
	$delsql = "DELETE FROM stories WHERE id = " . $validid . ";";
//	mysql_query($delsql);
	$db->query($delsql);
	
	header("Location: " . $config_basedir);
}
else {
	require("header.php");
	echo "<h1>Are you sure you want to delete this question?</h1>";
	echo "<p>[<a href='" . $_SERVER['SCRIPT_NAME'] . "?conf=1&id=" . $validid . "'>Yes</a>] [<a href='index.php'>No</a>]</p>";
}

require("footer.php");

?>
