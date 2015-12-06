<?php
session_start();

require("db.php");
require("functions.php");

if($_SESSION['SESS_ADMIN']) {
	header("Location: " . $config_basedir);
}

if(pf_check_number($_GET['subject']) == TRUE) {
	$validsubject = $_GET['subject'];
}
else {
	header("Location: " . $config_basedir);
}


if($_GET['conf']) {
	$delsql = "DELETE FROM subjects WHERE id = " . $validsubject . ";";
//	mysql_query($delsql);
	$db->query($delsql);
	header("Location: " . $config_basedir);
}
else {
	require("header.php");
	echo "<h1>Are you sure you want to delete this subject?</h1>";
	echo "<p>[<a href='" . $_SERVER['SCRIPT_NAME'] . "?conf=1&subject=" . $validsubject . "'>Yes</a>] [<a href='" . $config_basedir . "'>No</a>]";
}

require("footer.php");

?>
