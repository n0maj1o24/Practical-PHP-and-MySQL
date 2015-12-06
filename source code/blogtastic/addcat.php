<?php

session_start();

require("config.php");

try
{
	$db = new PDO("mysql:host=$dbhost;dbname=$dbdatabase",$dbuser,$dbpassword);
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
	echo 'database connection error: '.$e->getMessage();
}
//$db = mysql_connect($dbhost, $dbuser, $dbpassword);
//mysql_select_db($dbdatabase, $db);

if(isset($_SESSION['USERNAME']) == FALSE) {
	header("Location: " . $config_basedir);
}

if($_POST['submit']) {
	$sql = "INSERT INTO categories(cat) VALUES('" . $_POST['cat'] . "');";
	$db->query($sql);
//	mysql_query($sql);
	header("Location: " . $config_basedir . "viewcat.php");
}
else {
	
	require("header.php");

?>

<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="post">

<table>
<tr>
	<td>Category</td>
	<td><input type="text" name="cat"></td>
</tr>
<tr>
	<td></td>
	<td><input type="submit" name="submit" value="Add Entry!"></td>
</tr>
</table>
</form>

<?php
}
require("footer.php");
?>