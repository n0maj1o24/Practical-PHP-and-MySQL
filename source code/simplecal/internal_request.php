<?php

session_start();

require("db.php");
require("config.php");

if(isset($_SESSION['LOGGEDIN']) == FALSE) {
	header("Location: " . $config_basedir);
}

if($_GET['action'] == 'getevent'){
	$sql = "SELECT * FROM events WHERE id = " . $_GET['id'] . ";";
//	$result = mysql_query($sql);
//	$row = mysql_fetch_assoc($result);
	$result= $db->query($sql);
	$row = $result->fetchAll(PDO::FETCH_ASSOC);

	echo "<h1>Event Details</h1>";
	
	echo $row[0]['name'];
	echo "<p>" . $row[0]['description'] . "</p>";
	echo "<p><strong>Date:</strong> " . date("D jS F Y", strtotime($row[0]['date'])) . "<br />";
	echo "<strong>Time:</strong> " . $row[0]['starttime'] . " - " . $row[0]['endtime'] . "</p>";
}


?>



