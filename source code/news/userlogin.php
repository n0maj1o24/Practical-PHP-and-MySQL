<?php

session_start();

require("config.php");
require("db.php");
require("functions.php");

if($_SESSION['SESS_USERNAME']) {
	header("Location: " . $config_basedir . "userhome.php");
}

if($_POST['submit']) {

	$sql = "SELECT * FROM users WHERE username = '" . pf_fix_slashes($_POST['username']) . "' AND password = '" . md5(pf_fix_slashes($_POST['password'])) . "'";
	
//	$result = mysql_query($sql);
//	$numrows = mysql_num_rows($result);
	$result = $db->query($sql);
	$numrows = $result->rowCount();
	
	if($numrows == 1) {
//		$row = mysql_fetch_assoc($result);
		$row = $result->fetchAll(PDO::FETCH_ASSOC);
//		session_register("SESS_USERNAME");
//		session_register("SESS_USERID");
//		session_register("SESS_USERLEVEL");
		
		$_SESSION['SESS_USERNAME'] = $row[0]['username'];
		$_SESSION['SESS_USERID'] = $row[0]['id'];
		$_SESSION['SESS_USERLEVEL'] = $row[0]['level'];
		
		header("Location: " . $config_basedir);
	}
	else {
		header("Location: " . $config_basedir . "/userlogin.php?error=1");
	}
}
else {
	require("header.php");

	echo "<h1>Login</h1>";
	
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