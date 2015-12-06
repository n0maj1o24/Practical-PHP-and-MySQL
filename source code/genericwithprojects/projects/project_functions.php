<?php

require("phphomeprojectconfig.php");

function pf_protect_nonadmin_page() {
	if(basename($_SERVER['SCRIPT_NAME']) != "index.php") {
		echo "<h1>Error</h1>";
		echo "You cannot access this page directly. Please go to the main project pages directly.";
		exit;
	}
}

function pf_protect_admin_page() {
	global $config_projectadminfilename;

	if(basename($_SERVER['SCRIPT_NAME']) != $config_projectadminfilename) {
		echo "<h1>Error</h1>";
		echo "You cannot access this page directly. Please go to the admin pages directly.";
		exit;
	}
}

//function pf_fix_slashes($string) {
//	if (get_magic_quotes_gpc() == 1) {
//		return($string);
//	}
//	else {
//		return(addslashes($string));
//	}
//}
function pf_fix_slashes($string){
	return (addslashes($string));
}

function pf_check_number($value) {
	if(isset($value) == FALSE) {
		$error = 1;
	}
	
	if(is_numeric($value) == FALSE) {
		$error = 1;
	}

	if($error == 1) {
		return FALSE;
	}
	else {
		return TRUE;
	}
}

?>