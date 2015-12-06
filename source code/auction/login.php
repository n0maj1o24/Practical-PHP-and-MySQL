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
	$sql = "SELECT * FROM users WHERE username = '" . $_POST['username'] . "' AND password = '" . $_POST['password'] . "';";
	
//	$result = mysql_query($sql);
//	$numrows = mysql_num_rows($result);
	$result = $db->query($sql);
	$numrows = $result->rowCount();
		
	if($numrows == 1) {
		//$row = mysql_fetch_assoc($result);
		$row = $result->fetchAll(PDO::FETCH_ASSOC);

		if($row[0]['active'] == 1) {
//			session_register("USERNAME");
//			session_register("USERID");
		
			$_SESSION['USERNAME'] = $row[0]['username'];
			$_SESSION['USERID'] = $row[0]['id'];

			switch($_GET['ref']) {
				case "addbid":
					header("Location: " . $config_basedir . "/itemdetails.php?id=" . $_GET['id'] . "#bidbox");
				break;

				case "newitem":
					header("Location: " . $config_basedir . "/newitem.php");
				break;

				case "images":
					header("Location: " . $config_basedir . "/addimages.php?id=" . $_GET['id']);
				break;
					
				default:
					header("Location: " . $config_basedir);
				break;
			}
		}
		else {
			require("header.php");
			echo "This account is not verified yet. You were emailed a link to verify the account. Please click on the link in the email to continue.";
		}			
	}
	else {
		header("Location: " . $config_basedir . "/login.php?error=1");
	}
}
else {

	require("header.php");

	echo "<h1>Login</h1>";

	
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
Don't have an account? Go and <a href="register.php">Register</a>!
<?php
}

require("footer.php");
?>