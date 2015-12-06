<?php

session_start();

require("db.php");

if($_SESSION['SESS_ADMINUSER']) {
	header("Location: " . $config_basedir . "adminhome.php");
}

if($_POST['submit']) {

	$sql = "SELECT * FROM admins WHERE username = '" . $_POST['username'] . "' AND password = '" . $_POST['password'] . "';";
//	$result = mysql_query($sql);
//	$numrows = mysql_num_rows($result);
	$result = $db->query($sql);
	$numrows = $result->rowCount();
	
	if($numrows == 1) {
//		$row = mysql_fetch_assoc($result);
		$row = $result->fetchAll(PDO::FETCH_ASSOC);
//		session_register("SESS_ADMIN");
//		session_register("SESS_ADMINUSER");
//		session_register("SESS_ADMINID");
		
		$_SESSION['SESS_ADMINUSER'] = $_POST['username'];
		$_SESSION['SESS_ADMINID'] = $row[0]['id'];
		
		header("Location: " . $config_basedir . "adminhome.php");
	}
	else {
		header("Location: " . $config_basedir . "/adminlogin.php?error=1");
	}
}
else {

	require("header.php");

	echo "<h1>Admin Login</h1>";
	
	if($_GET['error']) {
	echo "<p>Incorrect login, please try again!</p>";
	}

?>
	<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="post">
	
	<table>
	<tr>
	<td>Username</td>
	<td><input type="text" name="username"></td>
	</tr>
	<tr>
	<td>Password</td>
	<td><input type="password" name="password"></td>
	</tr>
	<tr>
	<td></td>
	<td><input type="submit" name="submit" value="Login!"></td>
	</tr>
	</table>
	</form>

<?php
}

require("footer.php");

?>