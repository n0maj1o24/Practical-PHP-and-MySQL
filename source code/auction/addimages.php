<?php

session_start();

include("config.php");
include("functions.php");

//$db = mysql_connect($dbhost, $dbuser, $dbpassword);
//mysql_select_db($dbdatabase, $db);
try
{
	$db = new PDO("mysql:host=$dbhost;dbname=$dbdatabase",$dbuser,$dbpassword);
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
	echo 'database connection error: '.$e->getMessage();
}


$validid = pf_validate_number($_GET['id'], "redirect", "index.php");

if(isset($_SESSION['USERNAME']) == FALSE) {
	//header("Location: " . $HOST_NAME . "login.php?ref=images&id=" . $validid);
	header("Location: " . $_SERVER['HTTP_HOST'] . "login.php?ref=images&id=" . $validid);
}

$theitemsql = "SELECT user_id FROM items WHERE id = " . $validid . ";";
//$theitemresult = mysql_query($theitemsql);
//$theitemrow = mysql_fetch_assoc($theitemresult);
$theitemresult = $db->query($theitemsql);
$theitemrow = $theitemresult->fetchAll(PDO::FETCH_ASSOC);

if($theitemrow[0]['user_id'] != $_SESSION['USERID']) {
	header("Location: " . $config_basedir);	
}

if($_POST['submit']) {
	if($_FILES['userfile']['name'] == '') {
//		header("Location: " . $HOST_NAME . $SCRIPT_NAME . "?error=nophoto");
		header("Location: " . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?error=nophoto");
	}
	elseif($_FILES['userfile']['size'] == 0) {
//		header("Location: " . $HOST_NAME . $SCRIPT_NAME . "?error=photoprob");
		header("Location: " .  $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?error=photoprob");
	}
	elseif($_FILES['userfile']['size'] > $MAX_FILE_SIZE) {
//		header("Location: " . $HOST_NAME . $SCRIPT_NAME . "?error=large");
		header("Location: " . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?error=large");
	}
	elseif(!getimagesize($_FILES['userfile']['tmp_name'])) {
//		header("Location: " . $HOST_NAME . $SCRIPT_NAME . "?error=invalid");
		header("Location: " . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?error=invalid");
	}
	else {
		$uploaddir = "/xampplite/htdocs/sites/auction/images/";
	   $uploadfile = $uploaddir . $_FILES['userfile']['name'];

	   if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
			
			$inssql = "INSERT INTO images(item_id, name) VALUES(" . $validid . ", '" . $_FILES['userfile']['name'] . "')";
			//mysql_query($inssql);
			$db->query($inssql);

//			header("Location: " . $HOST_NAME . $SCRIPT_NAME . "?id=" . $validid);
		   header("Location: " . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?id=" . $validid);
	   }
	   else {
	       echo 'There was a problem uploading your file.<br />';
	   }
	} 	
}
else {
	require("header.php");

	$imagessql = "SELECT * FROM images WHERE item_id = " . $validid . ";";
//	$imagesresult = mysql_query($imagessql);
//	$imagesnumrows = mysql_num_rows($imagesresult);
	$imagesresult = $db->query($imagessql);
	$imagesnumrows = $imagesresult->rowCount();

	echo "<h1>Current images</h1>";

	if($imagesnumrows == 0) {
		echo "No images.";
	}
	else {
		echo "<table>";
		//while($imagesrow = mysql_fetch_assoc($imagesresult)) {
		foreach($imagesresult as $imagesrow){
			echo "<tr>";
			echo "<td><img src='" . $config_basedir . "/images/" . $imagesrow['name'] . "' width='100'></td>";
			echo "<td>[<a href='deleteimage.php?image_id=" . $imagesrow['id'] . "&item_id=" . $validid . "'>delete</a>]</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
	switch($_GET['error']) {
		case "empty":
			echo 'You did not select anything.';
		break;

		case "nophoto":
			echo 'You did not select a photo to upload.';
		break;

		case "photoprob":
			echo 'There appears to be a problem with the photo your are uploading';
		break;

		case "large":
			echo 'The photo you selected is too large';
		break;
		
		case "invalid":
			echo 'The photo you selected is not a valid image file';
		break;
	}

?>

<form enctype="multipart/form-data" action="<?php pf_script_with_get($_SERVER['SCRIPT_NAME']); ?>" method="POST">
<input type="hidden" name="MAX_FILE_SIZE" value="3000000">
<table>
<tr>
   <td>Image to upload</td>
   <td><input name="userfile" type="file"></td>
</tr>
<tr>
	<td><input type="submit" name="submit" value="Upload File"></td>
</tr>
</table>
</form>

When you have finished adding photos, go and <a href="<?php echo "itemdetails.php?id=" . $validid; ?>">see your item</a>!
<?php
}

require("footer.php");
?>
