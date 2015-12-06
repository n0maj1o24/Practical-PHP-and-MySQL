<?php

session_start();

require("config.php");

//session_unregister("SESS_USERNAME");
//session_unregister("SESS_USERID");
//session_unregister("SESS_USERLEVEL");
unset($_SESSION['SESS_USERNAME']);
unset($_SESSION['SESS_USERID']);
unset($_SESSION['SESS_USERLEVEL']);

header("Location: " . $config_basedir);

?>