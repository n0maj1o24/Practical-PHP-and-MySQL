<?php
	session_start();

	require("db.php");
	
	if(isset($_SESSION['SESS_ADMINLOGGEDIN']) == TRUE) {
		header("Location: " . $config_basedir);
	}
	
	if($_POST['submit'])
	{
		$loginsql = "SELECT * FROM admins WHERE username = '" . $_POST['userBox'] . "' AND password = '" . $_POST['passBox'] . "'";
//		$loginres = mysql_query($loginsql);
//		$numrows = mysql_num_rows($loginres);
		$loginres = $db->query($loginsql);
		$numrows = $loginres->rowCount();
		
		if($numrows == 1)
		{
			//$loginrow = mysql_fetch_assoc($loginres);
			$loginrow = $loginres->fetchAll(PDO::FETCH_ASSOC);

			//session_register("SESS_ADMINLOGGEDIN");
			
			$_SESSION['SESS_ADMINLOGGEDIN'] = 1;

			header("Location: " . $config_basedir  . "adminorders.php");

		}
		else
		{
			header("Location: " . $config_basedir  . "adminlogin.php?error=1");
		}
	}
	else
	{

	require("header.php");
		
	echo "<h1>Admin Login</h1>";
	
	if($_GET['error'] == 1) {
		echo "<strong>Incorrect username/password!</strong>";
	}
?>
	<p>
	<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST">
	<table>
		<tr>
			<td>Username</td>
			<td><input type="textbox" name="userBox">
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
	
<?php
	}
	
	require("footer.php");
?>
	
