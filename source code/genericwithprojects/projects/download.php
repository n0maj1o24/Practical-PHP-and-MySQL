<?php
	require("project_functions.php");
	pf_protect_nonadmin_page();

	$versql = "SELECT * FROM homeproject_releaseversions WHERE project_id = " . $project_id . " ORDER BY id DESC;";
//	$verresult = mysql_query($versql);
	$verresult = $db->query($versql);
	
	echo "<h1>Download</h1>";

//	while($verrow = mysql_fetch_assoc($verresult)) {
	foreach($verresult as $verrow){
		echo "<h2>" . $verrow['version'] . "</h2>";

		$relsql = "SELECT homeproject_releasefiles.filename, homeproject_releasefiles.date, homeproject_releasetypes.type FROM homeproject_releaseversions INNER JOIN homeproject_releasefiles ON homeproject_releasefiles.version_id = homeproject_releaseversions.id INNER JOIN homeproject_releasetypes ON homeproject_releasefiles.type_id = homeproject_releasetypes.id WHERE homeproject_releaseversions.id = " . $verrow['id'];
//		$relresult = mysql_query($relsql);
//		$relnumrows = mysql_num_rows($relresult);
		$relresult = $db->query($relsql);
		$relnumrows = $relresult->rowCount();

		echo "<ul>";
		
		if($relnumrows == 0) {
			echo "<li><strong>No releases!</strong></li>";
		}
		else {
//			while($relrow = mysql_fetch_assoc($relresult)) {
			foreach($relresult as $relrow){
				echo "<li><a href='releases/" . $relrow['filename'] . "'>Download the " . $relrow['type'] . "</a> (<i>Released " . date("D jS F Y g.iA", strtotime($relrow['date'])) . "</i>)</li>";
			}
		}

		echo "</ul>";
	}

?>
