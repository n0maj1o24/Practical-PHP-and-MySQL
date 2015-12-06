<?php

require_once("../project_functions.php");
pf_protect_admin_page();

if(pf_check_number($_GET['relid']) == TRUE) {
	$validrelid = $_GET['relid'];
}
else {
	header("Location: " . $config_projectadminbasedir);
}


if($_GET['conf']) {
	$uploaddir = $config_projectdir . $_SESSION['SESS_PROJECTPATH'] . "/releases/";
	
	$filesql = "SELECT filename FROM homeproject_releasefiles WHERE id = " . $validrelid . ";";
//	$fileresult = mysql_query($filesql);
//	$filerow = mysql_fetch_assoc($fileresult);
	$fileresult = $db->query($filesql);
	$filerow = $fileresult->fetchAll(PDO::FETCH_ASSOC);

	$fullfile = $uploaddir . $filerow['filename'];

	if(file_exists($fullfile) == TRUE) {
		unlink($fullfile);

		$delsql = "DELETE FROM homeproject_releasefiles WHERE id = " . $validrelid . ";";
//		mysql_query($delsql);
		$db->query($delsql);
		
		header("Location: " . $config_projectadminbasedir . basename($_SERVER['SCRIPT_NAME']) . "?func=downloads");
	}
	else {
		echo "<h1>File does not exist</h1>";
		echo "The file you tried to delete does not exist.";
	}
	
}
else {

	echo "<h1>Are you sure you want to delete this release?</h1>";
	echo "<p>[<a href='" . $_SERVER['SCRIPT_NAME'] . "?func=deleterelease&conf=1&relid=" . $validrelid . "'>Yes</a>] [<a href='" . $_SERVER['SCRIPT_NAME'] . "?func=main'>No</a>]";
}

?>
