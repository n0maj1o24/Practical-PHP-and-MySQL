<?php
	require_once("../project_functions.php");
	pf_protect_admin_page();

	$uploaddir = $config_projectdir . $_SESSION['SESS_PROJECTPATH'] . "/releases/";
	
	if($_POST['versubmit']) {
		$addsql = "INSERT INTO homeproject_releaseversions(project_id, version) VALUES("
			. $_SESSION['SESS_PROJECTID']
			. ", '" . $_POST['version'] . "')";
//		mysql_query($addsql);
		$db->query($addsql);
		header("Location: " . $config_projectadminbasedir . basename($_SERVER['SCRIPT_NAME']) . "?func=downloads");
	}
	elseif($_POST['relsubmit']) {
		$uploadfile = $uploaddir . basename($_FILES['releasefile']['name']);

		if(move_uploaded_file($_FILES['releasefile']['tmp_name'], $uploadfile)) {
			$addsql = "INSERT INTO homeproject_releasefiles(type_id, date, version_id, filename) VALUES("
				. $_POST['type']
				. ", NOW()"
				. ", " . $_GET['ver']
				. ", '" . $_FILES['releasefile']['name']
				. "')";
//			mysql_query($addsql);
			$db->query($addsql);
			header("Location: " . $config_projectadminbasedir . basename($_SERVER['SCRIPT_NAME']) . "?func=downloads");
		}
		else {
		   echo "Possible file upload attack!\n";
		}	
		
	}
	else {
		$versql = "SELECT * FROM homeproject_releaseversions WHERE project_id = " . $_SESSION['SESS_PROJECTID'] . " ORDER BY id DESC;";
//		$verresult = mysql_query($versql);
//		$vernumrows = mysql_num_rows($verresult);
		$verresult = $db->query($versql);
		$vernumrows = $verresult->rowCount();

		echo "<h1>Manage Downloads</h1>";

		echo "<table border=1 cellpadding=5>";
		echo "<tr><td colspan=3>";

		if($_GET['addver']) {
			echo "<form action='" . $_SERVER['SCRIPT_NAME'] . "?func=downloads' method='POST'>";
			echo "<strong>New Release Number: ";
			echo "<input type='text' name='version'>";
			echo "<input type='submit' value='Add' name='versubmit'>";
			echo "</form>";
		}
		else {
			echo "<a href='" . $_SERVER['SCRIPT_NAME'] . "?func=downloads&addver=1'>Add a New Version</a>";
		}

		echo "</td></tr>";

		if($vernumrows == 0) {
			echo "<tr><td colspan=2>This project has no versions or releases.</td></tr>";
		}
		else {	
//			while($verrow = mysql_fetch_assoc($verresult)) {
			foreach($verresult as $verrow){
				echo "<tr>";
				echo "<td><strong>" . $verrow['version'] . "</strong></td>";
				echo "<td>";
		
				$relsql = "SELECT homeproject_releasefiles.id, homeproject_releasefiles.filename, homeproject_releasefiles.date, homeproject_releasetypes.type FROM homeproject_releaseversions INNER JOIN homeproject_releasefiles ON homeproject_releasefiles.version_id = homeproject_releaseversions.id INNER JOIN homeproject_releasetypes ON homeproject_releasefiles.type_id = homeproject_releasetypes.id WHERE homeproject_releaseversions.id = " . $verrow['id'];
//				$relresult = mysql_query($relsql);
//				$relnumrows = mysql_num_rows($relresult);
				$relresult = $db->query($relsql);
				$relnumrows = $relresult->rowCount();
			
				if($relnumrows == 0) {
					echo "No releases!";
				}
				else {				
//					while($relrow = mysql_fetch_assoc($relresult)) {
					foreach($relresult as $relrow){
						echo "[<a href='" . $_SERVER['SCRIPT_NAME'] . "?func=deleterelease&relid=" . $relrow['id'] . "'>X</a>] <a href='releases/" . $relrow['filename'] . "'>" . $relrow['type'] . "</a><br>";
					}
				}
	
				echo "</td>";
				echo "<td>";
	
				if($_GET['addrelver'] == $verrow['id']) {
					$typessql = "SELECT * FROM homeproject_releasetypes;";
//					$typesresult = mysql_query($typessql);
					$typesresult = $db->query($typessql);
				
					echo "<form action='" . $_SERVER['SCRIPT_NAME'] . "?func=downloads&ver=" . $verrow['id'] . "' method='POST' enctype='multipart/form-data'>";
					echo "<select name='type'>";
		
//					while($typesrow = mysql_fetch_assoc($typesresult)) {
					foreach($typesresult as $typesrow){
						echo "<option value=" . $typesrow['id'] . ">" . $typesrow['type'] . "</option>";
					}
	
					echo "</select>";
					echo "<input type='file' name='releasefile'>";
					echo "<input type='submit' value='Add' name='relsubmit'>";
					echo "</form>";
				}
				else {
					echo "<a href='" . $_SERVER['SCRIPT_NAME'] . "?func=downloads&addrelver=" . $verrow['id'] . "'>Add a New Release</a>";
				}
	
				echo "</td>";
				echo "</tr>";
			}
		echo "<table>";
		}	
	}
?>