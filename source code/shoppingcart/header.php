<?php
	//global $db;
	session_start();

	if(isset($_SESSION['SESS_CHANGEID']) == TRUE)
	{
		session_unset();
		session_regenerate_id();
	}

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
<head>
	<title><?php echo $config_sitename; ?></title>
	<link href="stylesheet.css" rel="stylesheet">
</head>
<body>
	<div id="header">
	<h1><?php echo $config_sitename; ?></h1>
	</div>
	<div id="menu">
		<a href="<?php echo $config_basedir; ?>">Home</a> -
		<a href="<?php echo $config_basedir; ?>showcart.php">View Basket/Checkout</a>
	</div>
	<div id="container">
		<div id="bar">
			<?php
				
				require("bar.php");
				echo "<hr>";
				
				if(isset($_SESSION['SESS_LOGGEDIN']) == TRUE)
				{
					echo "Logged in as <strong>" . $_SESSION['SESS_USERNAME'] . "</strong> [<a href='" . $config_basedir . "logout.php'>logout</a>]";
				}
				else
				{
					echo "<a href='" . $config_basedir . "login.php'>Login</a>";
				}
			?>
			
		</div>

		<div id="main">
