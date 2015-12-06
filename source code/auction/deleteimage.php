<?php

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


$validimageid = pf_validate_number($_GET['image_id'], "redirect", $config_basedir);
$validitemid = pf_validate_number($_GET['item_id'], "redirect", $config_basedir);

if($_POST['submityes']) {

	$imagesql = "SELECT * FROM images WHERE id = " . $validimageid;
//	$imageresult = mysql_query($imagesql);
//	$imagerow = mysql_fetch_assoc($imageresult);
	$imageresult = $db->query($imagesql);
	$imagerow = $imageresult->fetchAll(PDO::FETCH_ASSOC);
		
	unlink("./images/" . $imagerow[0]['name']);

	$delsql = "DELETE FROM images WHERE id = " . $validimageid;
	//mysql_query($delsql);
	$db->query($delsql);

	header("Location: " . $config_basedir . "addimages.php?id=" . $validitemid);

}
elseif($_POST['submitno']) {
	header("Location: " . $config_basedir . "addimages.php?id=" . $validitemid);
}
else {

//	$sql = "SELECT * FROM images WHERE id = " . $validimageid;
//	$result = mysql_query($sql);
	
	require("header.php");
?>

	<h2>Delete image?</h2>
	<form action="<?php echo pf_script_with_get($_SERVER['SCRIPT_NAME']); ?>" method="post">
	Are you sure you want to delete this image?
	<p>
	<input type="submit" name="submityes" value="Yes"> <input type="submit" name="submitno" value="No">
	</p>
	</form>

<?php
}
require("footer.php");
?>


