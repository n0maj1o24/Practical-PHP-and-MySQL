<?php

function pf_script_with_get($script) {
	$page = $script;
	$page = $page . "?";
	
	foreach($_GET as $key => $val) {
		$page = $page . $key . "=" . $val . "&";  
	}
	
	return substr($page, 0, strlen($page)-1);
}

?>