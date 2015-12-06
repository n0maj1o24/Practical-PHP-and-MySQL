<?php

	require("phphomeprojectconfig.php");

	if(file_exists($config_headerfile)) {	
		include($config_headerfile);	
	}

	require("db.php");
	
	echo "<h1>Projects</h1>";
	echo "<p>I have created the following project(s):</p>";
	
	$projsql = "SELECT * FROM homeproject_projects;";
//	$projresult = mysql_query($projsql);
	$projresult = $db->query($projsql);
	
//	while($projrow = mysql_fetch_assoc($projresult)) {
	foreach($projresult as $projrow){
		echo "<h2>" . $projrow['name'] . "</h2>";
		echo "<p>" . $projrow['about'] . "</p>";
		echo "<p>&bull; <a href='" . $config_projecturl . "/" . $projrow['pathname'] . "'>View this project</a></p>";
	}

	if(file_exists($config_headerfile)) {	
		include($config_footerfile);	
	}

?>
