<?php

require("config.php");
if(isset($_GET['id']) == TRUE) {
	if(is_numeric($_GET['id']) == FALSE) {
		$error = 1;
	}

	if($error == 1) {
		header("Location: " . $config_basedir . "/viewcat.php");
	}
	else {
		$validcat = $_GET['id'];
	}
}
else {
	$validcat = 0;
}

require("header.php");

$sql = "SELECT * FROM categories";

//$result = mysql_query($sql);
try{
	$result = $db->query($sql);
	//$row = $result->fetchAll(PDO::FETCH_ASSOC);
}catch (PDOException $e){
	echo $e->getMessage();
}
//while($row = mysql_fetch_assoc($result)) {
	foreach($result as $row){
		//print_r($validcat);
	if($validcat == $row['id']) {
		echo "<strong><h1>" . $row['cat'] . "</h1></strong><br />";

		$entriessql = "SELECT * FROM entries WHERE cat_id = " . $validcat . " ORDER BY dateposted DESC;";
		//$entriesres = mysql_query($entriessql);
		//$numrows_entries = mysql_num_rows($entriesres);
		try{
			$entriesres = $db->query($entriessql);
			$numrows_entries = $entriesres->rowCount();
		}catch (PDOException $e){
			echo $e->getMessage();
		}
		echo "<ul>";

		if($numrows_entries == 0) {
			echo "<li>No entries!</li>";
		}
		else {
			//while($entriesrow = mysql_fetch_assoc($entriesres)) {
			foreach($entriesres as $entriesrow){
				echo "<li>" . date("D jS F Y g.iA", strtotime($entriesrow['dateposted'])) . " - <a href='viewentry.php?id=" . $entriesrow['id'] . "'>" . $entriesrow['subject'] ."</a></li>";
			}
		}

		echo "</ul>";

	}
	else {
		echo "<a href='viewcat.php?id=" . $row['id'] . "'>" . $row['cat'] . "</a><br />";
	}
}


require("footer.php");

?>