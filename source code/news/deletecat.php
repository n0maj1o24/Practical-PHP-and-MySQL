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
	$parentsql = "SELECT parent FROM categories WHERE id = " . $validid . ";";
//	$parentresult = mysql_query($parentsql);
//	$parentrow = mysql_fetch_assoc($parentresult);
	$parentresult = $db->query($parentsql);
	$parentrow = $parentresult->fetchAll(PDO::FETCH_ASSOC);
	
	if($parentrow[0]['parent'] == 1) {
		$delparentsql = "DELETE FROM categories WHERE id = " . $validid . ";";
//		mysql_query($delparentsql);
		$db->query($delparentsql);

		$delchildsql = "DELETE categories.* FROM categories INNER JOIN cat_relate ON  cat_relate.child_id = categories.id WHERE cat_relate.parent_id = " . $validid . ";";
//		mysql_query($delchildsql);
		$db->query($delchildsql);
		
		$delrelsql = "DELETE FROM cat_relate WHERE parent_id = " . $validid . ";";
//		mysql_query($delrelsql);
		$db->query($delrelsql);
	}
	else {
		$delsql = "DELETE FROM categories WHERE id = " . $validid . ";";
//		mysql_query($delsql);
		$db->query($delsql);

		$relsql = "DELETE FROM cat_relate WHERE child_id = " . $validid . ";";
//		mysql_query($relsql);
		$db->query($relsql);
	}	

	header("Location: " . $config_basedir);
}
else {
	require("header.php");
	echo "<h1>Are you sure you want to delete this question?</h1>";
	echo "<p>[<a href='" . $_SERVER['SCRIPT_NAME'] . "?conf=1&id=" . $validid . "'>Yes</a>] [<a href='index.php'>No</a>]</p>";
}

require("footer.php");

?>
