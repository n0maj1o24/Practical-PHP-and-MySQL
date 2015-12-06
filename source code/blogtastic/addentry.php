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

if(isset($_SESSION['USERNAME']) == FALSE) {
	header("Location: " . $config_basedir);
}

if($_POST['submit']) {
	$sql = "INSERT INTO entries(cat_id, dateposted, subject, body) VALUES(" . $_POST['cat'] . ", NOW(), '" . $_POST['subject'] . "', '" . $_POST['body'] . "');";
	//mysql_query($sql);
	$db->query($sql);
	header("Location: " . $config_basedir);

}
else {
	require("header.php");
?>

<h1>Add New Entry</h1>
<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="post">

<table>
<tr>
	<td>Category</td>
	<td>
	<select name="cat">
	<?php
		$catsql = "SELECT * FROM categories;";
		//$catres = mysql_query($catsql);
		$catres = $db->query($catsql);
		//while($catrow= mysql_fetch_assoc($catres)) {
		foreach($catres as $catrow){
				echo "<option value='" . $catrow['id']. "'>" . $catrow['cat'] . "</option>";
		}
	?>
	</select>
	</td>
</tr>
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
	<td><input type="submit" name="submit" value="Add Entry!"></td>
</tr>
</table>
</form>

<?php
}
require("footer.php");
?>