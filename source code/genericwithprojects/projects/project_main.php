<?php
	require("../db.php");
	$projsql = "SELECT * FROM homeproject_projects WHERE pathname = '" . $project . "';";
//	$projresult = mysql_query($projsql);
//	$projrow = mysql_fetch_assoc($projresult);
	$projresult = $db->query($projsql);
	$projrow = $projresult->fetchAll(PDO::FETCH_ASSOC);

	$project_id = $projrow[0]['id'];
	$project_name = $projrow[0]['name'];

	switch($_GET['func']) {
		case "download":
			require("download.php");
		break;

		case "screenshots":
			require("screenshots.php");
		break;

		default:
			$sql = "SELECT * FROM homeproject_projects WHERE pathname = '" . $project . "';";
//			$result = mysql_query($sql);
//			$row = mysql_fetch_assoc($result);
			$result = $db->query($sql);
			$row = $result->fetchAll(PDO::FETCH_ASSOC);

			echo "<h1>" . $row[0]['name'] . "</h1>";
			echo "<p>" . $row[0]['about'] . "</p>";
		break;
	}
	
?>