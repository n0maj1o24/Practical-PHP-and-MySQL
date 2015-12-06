<?php

	require_once("../project_functions.php");
	pf_protect_admin_page();
	
if($_POST['submit']) {
	if($_FILES['userfile']['name'] == '') {
		//header("Location: " . $HOST_NAME . $SCRIPT_NAME . "?func=screenshots&error=nophoto");
		header("Location: " . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?func=screenshots&error=nophoto");
	}
	elseif($_FILES['userfile']['size'] == 0) {
		header("Location: " . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?func=screenshots&error=photoprob");
	}
	elseif(!getimagesize($_FILES['userfile']['tmp_name'])) {
		header("Location: " . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?func=screenshots&error=invalid");
	}
	else {
		$uploaddir = $config_projectdir . $_SESSION['SESS_PROJECTPATH'] . "/screenshots/";
	   $uploadfile = $uploaddir . $_FILES['userfile']['name'];

	   if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {			
			$inssql = "INSERT INTO homeproject_screenshots(project_id, name) VALUES(" . $_SESSION['SESS_PROJECTID'] . ", '" . $_FILES['userfile']['name'] . "')";
//			mysql_query($inssql);
			$db->query($inssql);
			header("Location: " . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?func=screenshots");
	   }
	   else {
	       echo 'There was a problem uploading your file.<br />';
	   }
	} 	
}
else {
	$imagessql = "SELECT * FROM homeproject_screenshots WHERE project_id = " . $_SESSION['SESS_PROJECTID'] . ";";
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
//		while($imagesrow = mysql_fetch_assoc($imagesresult)) {
		foreach($imagesresult as $imagesrow){
			echo "<tr>";
			echo "<td><img src='" . $config_projecturl . $_SESSION['SESS_PROJECTPATH'] . "/screenshots/" . $imagesrow['name'] . "' width='100'></td>";
			echo "<td>[<a href='" . basename($_SERVER['SCRIPT_NAME']) . "?func=deletescreenshot&imageid=" . $imagesrow['id'] . "'>delete</a>]</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
	
	switch($_GET['error']) {
		case "empty":
			echo '<p>You did not select anything.</p>';
		break;

		case "nophoto":
			echo '<p>You did not select a photo to upload.</p>';
		break;

		case "photoprob":
			echo '<p>There appears to be a problem with the photo your are uploading</p>';
		break;

		case "large":
			echo '<p>The photo you selected is too large</p>';
		break;
		
		case "invalid":
			echo '<p>The photo you selected is not a valid image file</p>';
		break;
	}

?>

<form enctype="multipart/form-data" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>?func=screenshots" method="POST">
<table>
<tr>
   <td>Image to upload</td>
   <td><input name="userfile" type="file"></td>
</tr>
<tr>
	<td colspan=2><input type="submit" name="submit" value="Upload File"></td>
</tr>
</table>
</form>

<?php
}

?>