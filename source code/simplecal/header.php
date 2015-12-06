<?php

session_start();

require("config.php");

//$db = mysql_connect($dbhost, $dbuser, $dbpassword);
//mysql_select_db($dbdatabase, $db);
try
{
	$db = new PDO("mysql:host=$dbhost;dbname=$dbdatabase",$dbuser,$dbpassword);
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
	echo 'database connection error: '.$e->getMessage();
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title><?php echo $config_blogname; ?></title>
	<script language="javascript" type="text/javascript" 
			src="./internal_request.js">
	</script>
	<link href="stylesheet.css" rel="stylesheet">
</head>
<body>
<div id="header">
<h1><?php echo $config_name; ?></h1>
</div>
	<div id="menu">
		&bull;
		<a href="<?php echo $config_basedir; ?>">This month</a>
		&bull;
		<a href="<?php echo $config_basedir; ?>/logout.php">Logout</a>
		&bull;
	</div>
<div id="container">
	<div id="bar">
		<?php require("bar.php"); ?>
	</div>