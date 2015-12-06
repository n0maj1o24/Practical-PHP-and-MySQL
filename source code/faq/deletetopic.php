<?php

session_start();

require("db.php");
require("functions.php");

if(isset($_SESSION['SESS_ADMINUSER']) == FALSE) {
	header("Location: " . $config_basedir);
}


if(pf_check_number($_GET['topic']) == TRUE) {
	$validtopic = $_GET['topic'];
}
else {
	header("Location: " . $config_basedir);
}

if($_GET['conf']) {
	$delsql = "DELETE FROM topics WHERE id = " . $validtopic . ";";
//	mysql_query($delsql);
	$db->query($delsql);
	
	header("Location: " . $config_basedir);
}
else {
	require("header.php");
	echo "<h1>Are you sure you want to delete this topic?</h1>";
	echo "<p>[<a href='" . $_SERVER['SCRIPT_NAME'] . "?conf=1&topic=" . $validtopic . "'>Yes</a>] [<a href='" . $config_basedir . "'>No</a>]";
}

require("footer.php");

?>
