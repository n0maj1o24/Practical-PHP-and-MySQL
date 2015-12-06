<?php

function pf_fix_slashes($string) {
//	if (get_magic_quotes_gpc() == 1) {
//		return($string);
//	}
//	else {
		return(addslashes($string));
//	}
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