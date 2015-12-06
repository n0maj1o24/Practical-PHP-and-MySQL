<?php


session_start();
session_unregister("ADMIN");

require("config.php");

header("Location: " . $config_basedir);

?>
