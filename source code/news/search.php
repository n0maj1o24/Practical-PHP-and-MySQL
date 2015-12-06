<?php
require("db.php");
require("header.php");

function short_description($des) {
	$final = "";
	$final = (substr($des, 0, 200) . "...");

	echo "<p>" . strip_tags($final) . "</p>";
}


$terms = explode(" ", urldecode($_GET['searchterms']));

$query = "SELECT id, subject, body FROM stories WHERE body LIKE '%" . $terms[0] . "%'";

for($i=1; $i<count($terms); $i++) {
	$query = $query." AND body LIKE '%". $terms[$i] . "%'";
}

//$searchresult = mysql_query($query);
//$searchnumrows = mysql_num_rows($searchresult);
$searchresult = $db->query($query);
$searchnumrows = $searchresult->rowCount();

$pagesize = 2;
$numpages = ceil($searchnumrows / $pagesize);

if(!$_GET['page']) {
	$validpage = 1;
}
else {
	if(is_numeric($_GET['page']) == TRUE) {
		$validpage = $_GET['page'];
	}
	else {
		$validpage = 1;
	}
}

echo "<h1>Search Results</h1>";
echo "<p>Search for ";

foreach($terms as $key) {
	echo "<u>" . $key . "</u> ";
}

echo " has <strong>" . $searchnumrows . "</strong> results</p>";

if($searchnumrows == 0) {
	echo "<h2>No Results</h2>";
}
else {
	echo "Page " . $validpage . " of " . $numpages;
	echo "<p>";
	
	// figure out which records to display
	
	$offset = ($validpage - 1) * $pagesize;
	
	$pagesql = $query  . " ORDER BY dateposted DESC LIMIT " . $offset . ", " . $pagesize . ";";
	echo $pagesql;
//	$pageres = mysql_query($pagesql);
//	$pagenumrows = mysql_num_rows($pageres);
	$pageres = $db->query($pagesql);
	$pagenumrows = $pageres->rowCount();
	
//	while($pagerow = mysql_fetch_assoc($pageres)) {
	foreach($pageres as $pagerow){
		echo "<h2><a href='viewstory.php?id=" . $pagerow['id'] . "'>" . $pagerow['subject'] . "</a></h2>";
		echo "Posted on " . date('D jS F Y', strtotime($pagerow['date']));
		short_description($pagerow['body']);
	}
	
	echo "<p>";
	echo "<strong>Pages: </strong>";
	
	for($i=1; $i <= $numpages; $i++) {
		if($i == $validpage) {
			echo "<strong>&bull;" . $i . "&bull;</strong> ";
		}
		else {
		echo "<a href='search.php?term=" . $_GET['term'] . "&page=" . $i . "'>" . $i . "</a>" . " ";
		}
	}
}
require("footer.php");
?>