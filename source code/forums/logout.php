<?php


session_start();
//session_unregister("USERNAME");
unset($_SESSION['USERNAME']);
require("config.php");

header("Location: " . $config_basedir);

?>
