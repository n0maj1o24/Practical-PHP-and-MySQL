<?php

require("config.php");

session_start();

//session_unregister('SESS_ADMIN');
//session_unregister('SESS_ADMINUSER');
//session_unregister('SESS_ADMINID');
unset($_SESSION['SESS_ADMINUSER']);
unset($_SESSION['SESS_ADMINID']);

header("Location: " . $config_basedir);

?>