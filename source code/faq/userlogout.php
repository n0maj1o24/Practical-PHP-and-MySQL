<?php

   session_start();
	require("config.php");
	
//   session_unregister('SESS_USERNAME');
//   session_unregister('SESS_USERID');
   unset($_SESSION['SESS_USERNAME']);
   unset($_SESSION['SESS_USERID']);

	header("Location: " . $config_basedir . "index.php");
?>