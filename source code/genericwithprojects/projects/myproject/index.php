<?php

	require("../../header.php");
	$project = substr(dirname($_SERVER['SCRIPT_NAME']), strrpos(dirname($_SERVER['SCRIPT_NAME']), "/") + 1);
	chdir(dirname(__FILE__));
	require("../phphomeprojectconfig.php");
	require("../project_bar.php");
	
	require("../project_main.php");

?>
