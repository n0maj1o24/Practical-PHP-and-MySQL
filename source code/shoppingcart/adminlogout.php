<?php

	session_start();

	require("config.php");

	//session_unregister("SESS_ADMINLOGGEDIN");
	unset($_SESSION['SESS_ADMINLOGGEDIN']);
	
	header("Location: " . $config_basedir);
?>
	
