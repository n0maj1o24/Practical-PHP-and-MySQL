<?php

include("config.php");

//$db = mysql_connect($dbhost, $dbuser, $dbpassword);
//mysql_select_db($dbdatabase, $db);
try
{
	$db = new PDO("mysql:host=$dbhost;dbname=$dbdatabase",$dbuser,$dbpassword);
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
	echo 'database connection error: '.$e->getMessage();
}

if(isset($_GET['id']) == TRUE) {
	if(is_numeric($_GET['id']) == FALSE) {
		$error = 1;
	}

	if($error == 1) {
		header("Location: " . $config_basedir);
	}
	else {
		$validid = $_GET['id'];
	}
}
else {
	header("Location: " . $config_basedir);
}

switch($_GET['func']) {
	case "cat":
		$delsql = "DELETE FROM categories WHERE id = " . $validid . ";";
		//mysql_query($delsql);
		$db->query($delsql);
		header("Location: " . $config_basedir);
	break;

	case "forum":
		$delsql = "DELETE FROM forums WHERE id = " . $validid . ";";
		//mysql_query($delsql);
		$db->query($delsql);
		header("Location: " . $config_basedir);
	break;

	case "thread":
		$delsql = "DELETE FROM topics WHERE id = " . $validid . ";";
		//mysql_query($delsql);
		$db->query($delsql);
		header("Location: " . $config_basedir . "/viewforum.php?id=" . $_GET['forum']);
	break;
	
	default:
		header("Location: " . $config_basedir);
	break;

}

?>