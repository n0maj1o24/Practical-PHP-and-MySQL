<?php

session_start();

require("config.php");
require("functions.php");

if(isset($_SESSION['ADMIN']) == FALSE) {
	header("Location: " . $config_basedir . "/admin.php?ref=cat");
}

if($_POST['submit']) {
//	$db = mysql_connect($dbhost, $dbuser, $dbpassword);
//	mysql_select_db($dbdatabase, $db);

	try
	{
		$db = new PDO("mysql:host=$dbhost;dbname=$dbdatabase",$dbuser,$dbpassword);
		$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	}catch(PDOException $e){
		echo 'database connection error: '.$e->getMessage();
	}

	$catsql = "INSERT INTO categories(name) VALUES('" . $_POST['cat'] . "');";	
	//mysql_query($catsql);
	$db->query($catsql);
	header("Location: " . $config_basedir);
}
else {
	require("header.php");

?>
	<h2>Add a new category</h2>
	
	<form action="<?php echo pf_script_with_get($SCRIPT_NAME); ?>" method="post">
	<table>
	<tr>
		<td>Category</td>
		<td><input type="text" name="cat"></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" name="submit" value="Add Category!"></td>
	</tr>
	</table>
	</form>

<?php
}

require("footer.php");

?>