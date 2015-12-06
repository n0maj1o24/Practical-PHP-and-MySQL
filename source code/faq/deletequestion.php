<?php

session_start();

require("db.php");
require("functions.php");

if($_SESSION['SESS_ADMIN']) {
	header("Location: " . $config_basedir);
}


if(pf_check_number($_GET['topic']) == TRUE) {
	$validtopic = $_GET['topic'];
}
else {
	header("Location: " . $config_basedir);
}

if(pf_check_number($_GET['subject']) == TRUE) {
	$validsubject = $_GET['subject'];
}
else {
	header("Location: " . $config_basedir);
}

if(pf_check_number($_GET['questionid']) == TRUE) {
	$validquestionid = $_GET['questionid'];
}
else {
	header("Location: " . $config_basedir);
}


if($_GET['conf']) {
	$delsql = "DELETE FROM questions WHERE id = " . $validquestionid . ";";
	mysql_query($delsql);
	
	header("Location: " . $config_basedir . "questions.php?subject=" . $validsubject . "&topic=" . $validtopic);
}
else {
	require("header.php");
	echo "<h1>Are you sure you want to delete this question?</h1>";
	echo "<p>[<a href='" . $SCRIPT_NAME . "?conf=1&subject=" . $validsubject . "&topic=" . $validtopic . "&questionid=" . $validquestionid . "'>Yes</a>] [<a href='questions.php?subject=" . $validsubject . "&topic=" . $validtopic . "'>No</a>]</p>";
}

require("footer.php");

?>
