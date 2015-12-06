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

if($_POST['submit']) {
$sql = "SELECT * FROM logins WHERE username = '" . $_POST['username'] . "' AND password = '" . $_POST['password'] . "';";
	
//	$result = mysql_query($sql);
//	$numrows = mysql_num_rows($result);
	$result = $db->query($sql);
	$numrows = $result->rowCount();
		
	if($numrows == 1) {
	
	
//		$row = mysql_fetch_assoc($result);
//		session_register("USERNAME");
//		session_register("USERID");
		//$_SESSION['USERNAME']=null;
		//$_SESSION['USERID']=null;
		$row = $result->fetchAll(PDO::FETCH_ASSOC);
		
		$_SESSION['USERNAME'] = $row[0]['username'];
		$_SESSION['USERID'] = $row[0]['id'];
		
		header("Location: " . $config_basedir);
	}
	else {
		header("Location: " . $config_basedir . "/login.php?error=1");
	}
}
else {
	require("header.php");
	
	if(@$_GET['error']) {
		echo "Incorrect login, please try again!";
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