<?php
session_start();

require("db.php");

echo "<table class='visible' width='100%'cellspacing=0 cellpadding=5>";
echo "<tr><th class='visible'>Login details</th></tr>";
echo "<tr><td>";

if($_SESSION['SESS_USERNAME']) {
	echo "Logged in as <strong>" . $_SESSION['SESS_USERNAME'] . "</strong> - <a href='userlogout.php'>Logout</a>";

	echo "<p>";

	if($_SESSION['SESS_USERLEVEL'] > 1) {
		echo "<a href='addstory.php'>Post a new story</a><br />";
	}

	if($_SESSION['SESS_USERLEVEL'] == 10) {
		echo "<a href='addcat.php'>Add a new Category</a><br />";
	}

	echo "<p>";
}
else {
	echo "<a href='userlogin.php'>Login</a>";
}

echo "</td></tr>";
echo "</table>";

echo "<h1>Topics</h1>";

$sql = "SELECT * FROM categories WHERE parent = 1;";
//$result = mysql_query($sql);
//$numrows = mysql_num_rows($result);
$result = $db->query($sql);
$numrows = $result->rowCount();

if($numrows == 0) {
	echo "<p>No categories</p>";
}
else {
//	while($row = mysql_fetch_assoc($result)) {
	foreach($result as $row){
		if($_SESSION['SESS_USERLEVEL'] == 10) {
			echo "<a href='deletecat.php?id=" . $row['id'] . "'>[X]</a> ";
		}
	
		echo "<a href='index.php?parentcat=" . $row['id'] . "'>" . $row['category'] . "</a><br>";
		
		if($row['id'] == $_SESSION['SESS_PARENT']) {
			$childsql = "SELECT categories.id, categories.category FROM categories INNER JOIN cat_relate ON categories.id = cat_relate.child_id  WHERE cat_relate.parent_id = " . $_SESSION['SESS_PARENT'] . ";";
//			$childresult = mysql_query($childsql);
			$childresult = $db->query($childsql);
			
//			while($childrow = mysql_fetch_assoc($childresult)) {
			foreach($childresult as $childrow){
				if($_SESSION['SESS_USERLEVEL'] == 10) {
					echo "<a href='deletecat.php?id=" . $childrow['id'] . "'>[X]</a> ";
				}
	
				echo " &bull; <a href='index.php?parentcat=" . $row['id'] . "&amp;childcat=" . $childrow['id'] . "'>" . $childrow['category'] . "</a><br>";
			}
		}
	}
}


require_once 'HTML/QuickForm.php';
echo "<h1>Search</h1>";

$searchform = new HTML_QuickForm('searchform', 'get', 'search.php');

// Add some elements to the form
$searchform->addElement('text', 'searchterms', 'Search', array('size' => 20, 'maxlength' => 50));
$searchform->addElement('submit', null, 'Search!');

// Define filters and validation rules
$searchform->applyFilter('name', 'trim');
$searchform->addRule('searchterms', 'Enter a search term', 'required', null, 'client');

//$searchform->validate();
$searchform->display();

?>