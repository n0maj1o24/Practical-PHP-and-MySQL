<?php

session_start();

require("db.php");
require("functions.php");

if(!$_SESSION['SESS_USERNAME']) {
	header("Location: " . $config_basedir . "login.php");
}

if(pf_check_number($_GET['subject']) == TRUE) {
	$validsubject = $_GET['subject'];
}
else {
	header("Location: " . $config_basedir);
}
 
if($_GET['conf']) {
	$updsql = "UPDATE subjects SET owner_id = 0 WHERE id = " . $validsubject . ";";
//	mysql_query($updsql);
	$db->query($updsql);

	header("Location: " . $config_basedir . "userhome.php");
}
else {
	require("header.php");

	echo "<h1>Are you sure that you want to drop this subject?</h1>";
	echo "<p>[<a href='removesubown.php?conf=1&subject=" . $validsubject . "'>Yes</a>] [<a href='userhome.php'>No</a>]";
}

require("footer.php");

?>
