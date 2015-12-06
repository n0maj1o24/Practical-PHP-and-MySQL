<?php

require("db.php");

//session_register("SESS_PARENT");
//session_register("SESS_CHILD");


if(isset($_GET['parentcat']) && isset($_GET['childcat'])) {
	if(is_numeric($_GET['parentcat'])) {
		$_SESSION['SESS_PARENT'] = $_GET['parentcat'];
	}
	
	if(is_numeric($_GET['childcat'])) {
		$currentcat = $_GET['childcat'];

		$_SESSION['SESS_CHILD'] = $_GET['childcat'];
	}
}
else if(isset($_GET['parentcat'])) {
	if(is_numeric($_GET['parentcat'])) {
		$currentcat = $_GET['parentcat'];

		$_SESSION['SESS_PARENT'] = $_GET['parentcat'];
		$_SESSION['SESS_CHILD'] = 0;
	}
}
else {
	$currentcat = 0;
}


require("header.php");

if($currentcat == 0) {
	$sql = "SELECT * FROM stories ORDER BY dateposted DESC LIMIT 5;";
}
else {
	$parentsql = "SELECT parent FROM categories WHERE id = " . $currentcat . ";";
//	$parentres = mysql_query($parentsql);
//	$parentrow = mysql_fetch_assoc($parentres);
	$parentres = $db->query($parentsql);
	$parentrow = $parentres->fetchAll(PDO::FETCH_ASSOC);

	if($parentrow[0]['parent'] == 1) {
		$sql = sprintf("SELECT stories.* FROM stories INNER JOIN cat_relate ON stories.cat_id = cat_relate.child_id WHERE cat_relate.parent_id = %d UNION SELECT stories.* FROM stories WHERE stories.cat_id = %d;" , $currentcat, $currentcat);
	}
	else {
		$sql = "SELECT * FROM stories WHERE cat_id = " . $currentcat . ";";
	}
}

//$result = mysql_query($sql);
//$numrows = mysql_num_rows($result);
$result = $db->query($sql);
$numrows = $result->rowCount();

if($numrows == 0) {
	echo "<h1>No Stories</h1>";
	echo "<p>There are currently no stories in this category.	</p>";
}
else {
//	while($row = mysql_fetch_assoc($result)) {
	foreach($result as $row){
		if($_SESSION['SESS_USERLEVEL'] == 10) {
			echo "<a href='deletestory.php?id=" . $row['id'] . "'>[X]</a> ";
		}
	
		echo "<strong><a href='viewstory.php?id=" . $row['id']
			. "'>"
			. $row['subject']
			. "</a></strong><br />";
		echo date("D jS F Y g.iA", strtotime($row['dateposted']));
		
		if($_SESSION['POSTERUSERNAME']) {
			echo " [<a href='updatestory.php?id=" . $row['id']
				. "&parentcat=" . $_GET['parentcat']
				. "&subcat=" . $currentcat
				. "'>update</a>]<br />";
		}
		
		echo "<p>" . $row['body'] . "</p>";
	}
}
require("footer.php");

?>