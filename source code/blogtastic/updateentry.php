<?php

session_start();
require("config.php");
if(isset($_SESSION['USERNAME']) == FALSE) {
	header("Location: " . $config_basedir);
}

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
		$validentry = $_GET['id'];
	}
}
else {
	$validentry = 0;
}

if($_POST['submit']) {
	$sql = "UPDATE entries SET cat_id = " . $_POST['cat'] . ", subject = '" . $_POST['subject'] ."', body = '" . $_POST['body'] . "' WHERE id = " . $validentry . ";";
	//mysql_query($sql);
	$db->query($sql);
	header("Location: " . $config_basedir . "/viewentry.php?id=" . $validentry);
	
}
else {

	require("header.php");
	$fillsql = "SELECT * FROM entries WHERE id = " . $validentry . ";";	
//	$fillres = mysql_query($fillsql);
//	$fillrow = mysql_fetch_assoc($fillres);
	$fillres = $db->query($fillsql);
	$fillrow = $fillres->fetchAll(PDO::FETCH_ASSOC);

?>
<h1>Update Entry</h1>
<form action="<?php echo $_SERVER['SCRIPT_NAME'] . "?id=" . $validentry; ?>" method="post">

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
			echo "<option value='" . $catrow['id'] . "'";
			
			if($catrow['id'] == $fillrow[0]['cat_id']) {
				echo " selected";
			}
			
			echo ">" . $catrow['cat'] . "</option>";
		}
	?>
	</select>
	</td>
</tr>
<tr>
	<td>Subject</td>
	<td><input type="text" name="subject" value="<?php echo $fillrow[0]['subject']; ?>"></td>
</tr>
<tr>
	<td>Body</td>
	<td><textarea name="body" rows="10" cols="50"><?php echo $fillrow[0]['body']; ?></textarea></td>
</tr>
<tr>
	<td></td>
	<td><input type="submit" name="submit" value="Update Entry!"></td>
</tr>
</table>
</form>

<?php
}
require("footer.php");
?>