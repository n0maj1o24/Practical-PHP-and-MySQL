<?php
	require("db.php");

	if(empty($_POST['name'])) {
		$error = 1;
	}

	if(empty($_POST['description'])) {
		$error = 1;
	}
	
	if($_POST['starthour'] > $_POST['endhour']) {
		$error = 1;
	}

	if($_POST['starthour'] == $_POST['endhour']) {
		$error = 1;
	}

	if($error == 1) {
		header("Location: " . $config_basedir . "view.php?error=1&eventdate=" . $_GET['date']);

		exit;
	}

	$elements = explode("-", $_POST['date']);
	$redirectdate = $elements[1] . "-" . $elements[0];

	$finalstart = $_POST['starthour'] . ":" . $_POST['startminute'] . ":00";
	$finalend = $_POST['endhour'] . ":" . $_POST['endminute'] . ":00";
		
	$inssql = "INSERT INTO events(date, starttime, endtime, name, description) VALUES("
	. "'" . $_POST['date']
	. "', '" . $finalstart
	. "', '" . $finalend
	. "', '" . addslashes($_POST['name'])
	. "', '" . addslashes($_POST['description'])
	. "');";

	//mysql_query($inssql);
	$db->query($inssql);

	header("Location: " . $config_basedir . "view.php?date=" . $redirectdate);		

?>