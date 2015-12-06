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

if(isset($_GET['id']) == TRUE) {
	if(is_numeric($_GET['id']) == FALSE) {
		$error = 1;
	}

	if($error == 1) {
		header("Location: " . $config_basedir);
	}
	else {
		$validtopic = $_GET['id'];
	}
}
else {
	header("Location: " . $config_basedir);
}

if(isset($_SESSION['USERNAME']) == FALSE) {
	header("Location: " . $config_basedir . "/login.php?ref=reply&id=" . $validtopic);
}

if($_POST['submit']) {
	$messagesql = "INSERT INTO messages(date, user_id, topic_id, subject, body) VALUES(NOW()
		, " . $_SESSION['USERID']
		. ", " . $validtopic
		. ", '" . $_POST['subject']
		. "', '" . $_POST['body']	
		. "');";

	//mysql_query($messagesql);
	$db->query($messagesql);
	header("Location: " . $config_basedir . "/viewmessages.php?id=" . $validtopic);
}
else {
	require("header.php");
?>
	<form action="<?php echo pf_script_with_get($_SERVER['SCRIPT_NAME']); ?>" method="post">
	<table>
	<tr>
		<td>Subject</td>
		<td><input type="text" name="subject"></td>
	</tr>
	<tr>
		<td>Body</td>
		<td><textarea name="body" rows="10" cols="50"></textarea></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" name="submit" value="Post!"></td>
	</tr>
	</table>
	</form>

<?php
}

require("footer.php");

?>