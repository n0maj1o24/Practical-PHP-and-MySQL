<?php


session_start();
//session_unregister("USERID");
//session_unregister("USERNAME");
unset($_SESSION['USERID']);
unset($_SESSION['USERNAME']);
require("config.php");

header("Location: " . $config_basedir);

?>
