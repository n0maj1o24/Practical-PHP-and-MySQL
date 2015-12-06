<?php

require_once("../project_functions.php");
pf_protect_admin_page();


if(pf_check_number($_GET['imageid']) == TRUE) {
	$validimageid = $_GET['imageid'];
}
else {
	header("Location: " . $config_projectadminbasedir);
}

if($_GET['conf']) {

	$imagesql = "SELECT * FROM homeproject_screenshots WHERE id = " . $validimageid;
//	$imageresult = mysql_query($imagesql);
//	$imagerow = mysql_fetch_assoc($imageresult);
	$imageresult = $db->query($imagesql);
	$imagerow = $imageresult->fetchAll(PDO::FETCH_ASSOC);
		
	unlink($config_projectdir . $_SESSION['SESS_PROJECTPATH'] . "/screenshots/" . $imagerow[0]['name']);

	$delsql = "DELETE FROM homeproject_screenshots WHERE id = " . $validimageid;
//	mysql_query($delsql);
	$db->query($delsql);
	header("Location: " . $config_projectadminbaseurl . basename($_SERVER['SCRIPT_NAME']) . "?func=screenshots");

}
else {


	echo "<h2>Delete image?</h2>";
	echo "<form action=" . $_SERVER['SCRIPT_NAME'] . "?func=deletescreenshot' method='post'>";
	echo "<p>Are you sure you want to delete this image?</p>";
	echo "<p>";
	echo "<a href=" . $_SERVER['SCRIPT_NAME'] . "?func=deletescreenshot&conf=1&imageid=" . $validimageid . ">Yes</a> / <a href=" . $_SERVER['SCRIPT_NAME'] . "?func=screenshots>No</a>";

	echo "</p>";
	echo "</form>";

}

?>


