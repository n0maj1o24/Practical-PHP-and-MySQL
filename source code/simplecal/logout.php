<?php


session_start();

require("config.php");

session_destroy();

header("Location: " . $config_basedir);

?>
