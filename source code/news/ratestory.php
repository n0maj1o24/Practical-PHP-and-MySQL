<?php

require("db.php");
require("functions.php");

if(pf_check_number($_GET['id']) == TRUE) {
	$validid = $_GET['id'];
}
else {
	header("Location: " . $config_basedir);
}

if(pf_check_number($_GET['rating']) == TRUE) {
	$validid = $_GET['rating'];
}
else {
	header("Location: " . $config_basedir);
}

require("header.php");

$checksql = "SELECT * FROM ratings WHERE user_id = " . $_SESSION['SESS_USERID'] . " AND story_id = " . $validid . ";";
//$checkresult = mysql_query($checksql);
//$checknumrows = mysql_num_rows($checkresult);
$checkresult = $db->query($checksql);
$checknumrows = $checkresult->rowCount();

if($checknumrows == 1) {
	echo "<h1>Already voted</h1>";
	echo "<p>You have already voted for this story.</p>";
}
else {
	$inssql = "INSERT INTO ratings(user_id, story_id, rating) VALUES(" . $_SESSION['SESS_USERID']. "," . $validid . "," . $validrating . ");";
//	mysql_query($inssql);
	$db->query($inssql);

	echo "<h1>Thankyou!</h1>";
	echo "<p>Thankyou for your vote.</p>";
}
?>