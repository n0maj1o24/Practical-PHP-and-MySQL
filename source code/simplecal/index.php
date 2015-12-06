<?php
	session_start();

	require("db.php");

	if(isset($_SESSION['LOGGEDIN']) == TRUE) {
		header("Location: " . $config_basedir . "view.php");
	}
	
	if($_POST['submit'])
	{
		$loginsql = "SELECT * FROM users WHERE username = '" . $_POST['userBox'] . "' AND password = '" . $_POST['passBox'] . "'";
//		$loginres = mysql_query($loginsql);
//		$numrows = mysql_num_rows($loginres);
		$loginres = $db->query($loginsql);
		$numrows = $loginres->rowCount();

		
		if($numrows == 1)
		{
			//$loginrow = mysql_fetch_assoc($loginres);
			$loginrow = $loginres->fetchAll(PDO::FETCH_ASSOC);

			//session_register("LOGGEDIN");
			
			$_SESSION['LOGGEDIN'] = 1;
			
			header("Location: " . $config_basedir . "view.php");
		}
		else
		{
			//header("Location: http://" . $HTTP_HOST . $SCRIPT_NAME . "?error=1");
			header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?error=1");
		}
	}
	else
	{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title></title>
	<link href="stylesheet.css" rel="stylesheet">
</head>
<body>
	<div id="login">

	<h1>Calendar Login</h1>
	Please enter your username and password to log on.
	<p>
	
	<?php
		if($_GET['error']) {
			echo "<strong>Incorrect username/password</strong>";
		}
	?>
	
	<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST">
	<table>
		<tr>
			<td>Username</td>
			<td><input type="text" name="userBox">
		</tr>
		<tr>
			<td>Password</td>
			<td><input type="password" name="passBox">
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" name="submit" value="Log in">
		</tr>		
	</table>
	</form>
</div>	
<?php
	}
	
?>