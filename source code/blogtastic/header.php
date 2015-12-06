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
	<link href="stylesheet.css" rel="stylesheet">
</head>
<body>
<div id="header">
<h1><?php echo $config_blogname; ?></h1>
</div>
<div id="menu">
<a href="index.php">home</a>
<a href="viewcat.php">categories</a>

<?php

if(isset($_SESSION['USERNAME']) == TRUE) {
	echo "<a href='logout.php'>logout</a>";
}
else {
	echo "<a href='login.php'>login</a>";
}

if(isset($_SESSION['USERNAME']) == TRUE) {
	echo " - ";
	echo "[<a href='addentry.php'>add entry</a>]";
	echo "[<a href='addcat.php'>add category</a>]";
}

?>
</div>

<daptiv id="container">

<div id="main">
