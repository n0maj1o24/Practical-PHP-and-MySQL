<?php

	session_start();

	require("config.php");
	
//	$db = mysql_connect($dbhost, $dbuser, $dbpassword);
//	mysql_select_db($dbdatabase, $db);
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
	<title><?php echo $config_forumsname; ?></title>
	<link rel="stylesheet" href="stylesheet.css" type="text/css" />
</head>
<body>
<div id="header">
<h1>BidTastic Auctions</h1>
</div>
<div id="menu">
<a href="index.php">Home</a> &bull;
<?php

if(isset($_SESSION['USERNAME']) == TRUE) {
	echo "<a href='logout.php'>Logout</a> &bull;";
}
else {
	echo "<a href='login.php'>Login</a> &bull;";
}

?>

<a href="newitem.php">New Item</a>
</div>
<div id="container">
<div id="bar">
	<?php require("bar.php"); ?>
</div>
<div id="main">
