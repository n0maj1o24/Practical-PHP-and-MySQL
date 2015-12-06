<?php

session_start();

require("config.php");
require("functions.php");

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
	$sql = "SELECT * FROM admins WHERE username = '" . $_POST['username'] . "' AND password = '" . $_POST['password'] . "';";
	
//	$result = mysql_query($sql);
//	$numrows = mysql_num_rows($result);
	$result = $db->query($sql);
	$numrows = $result->rowCount();

	if($numrows == 1) {	
//		$row = mysql_fetch_assoc($result);
		$row = $result->fetchAll(PDO::FETCH_ASSOC);
//		session_register("ADMIN");
		$_SESSION['ADMIN']=null;
		$_SESSION['ADMIN'] = $row[0]['username'];
		
		switch($_GET['ref']) {
			case "add":
				header("Location: " . $config_basedir . "/addforum.php");
			break;

			case "cat":
				header("Location: " . $config_basedir . "/addcat.php");
			break;

			case "del":
				header("Location: " . $config_basedir);
			break;
			
			default:
				header("Location: " . $config_basedir);
			break;
		}		
	}
	else {
		header("Location: " . $config_basedir . "/admin.php?error=1");
	}
}
else {

	require("header.php");

	echo "<h2>Admin login</h2>";
		
	if($_GET['error']) {
		echo "Incorrect login, please try again!";
	}

?>

<form action="<?php echo pf_script_with_get($_SERVER['SCRIPT_NAME']); ?>" method="post">

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