<?php
	require("project_functions.php");
	pf_protect_nonadmin_page();

	$sql = "SELECT * FROM homeproject_screenshots WHERE project_id = " . $project_id . ";";
//	$result = mysql_query($sql);
//	$numrows = mysql_num_rows($result);
	$result = $db->query($sql);
	$numrows = $result->rowCount();

	echo "<h1>" . $project_name . " Screenshots</h1>";
	if($numrows == 0) {
		echo "No screenshots!";	
	}
	else {
//		while($row = mysql_fetch_assoc($result)) {
		foreach($result as $row){
			echo "<a href='screenshots/" . $row['name'] . "'><img src='./screenshots/" . $row['name'] . "' width='" . $config_projectscreenshotthumbsize . "'></a>";
			echo "<br><br>";
		}
	}

?>