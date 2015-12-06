<?php

require("header.php");

	$sql = "SELECT entries.*, categories.cat FROM entries, categories
		WHERE entries.cat_id = categories.id
		ORDER BY dateposted DESC
		LIMIT 1;";
	//$result =
//$result = mysql_query($sql);
try{
	$result = $db->query($sql);
	$row = $result->fetchAll(PDO::FETCH_ASSOC);
}catch (PDOException $e){
	echo $e->getMessage();
}
	//print_r($row);


//$row = mysql_fetch_assoc($result);

echo "<h2><a href='viewentry.php?id=" . $row[0]['id'] . "'>" . $row[0]['subject'] . "</a></h2><br />";
echo "<i>In <a href='viewcat.php?id=" . $row[0]['cat_id'] ."'>" . $row[0]['cat'] ."</a> - Posted on " . date("D jS F Y g.iA", strtotime($row[0]['dateposted'])) ."</i>";
	
if(isset($_SESSION['USERNAME']) == TRUE) {
	echo " [<a href='updateentry.php?id=" . $row[0]['id'] . "'>edit</a>]";
}

echo "<p>";
echo nl2br($row[0]['body']);
echo "</p>";


// comments on this blog entry:

echo "<p>";

$commsql = "SELECT name FROM comments WHERE blog_id = " . $row[0]['id'] ." ORDER BY dateposted;";
//$commresult = mysql_query($commsql);
try{
	$commresult = $db->query($commsql);
	$numrows_comm = $commresult->rowCount();
}catch (PDOException $e){
	echo $e->getMessage();
}
//$numrows_comm = mysql_num_rows($commresult);

if($numrows_comm == 0) {
	echo "<p>No comments.</p>";
}
else {
	echo "(<strong>" . $numrows_comm . "</strong>) comments : "; 
	
	$i = 1;		

	//while($commrow = mysql_fetch_assoc($commresult)) {
	//while($commrow = $commresult->fetchAll(PDO::FETCH_ASSOC)){
	foreach($commresult as $commrow){
		echo "<a href='viewentry.php?id=" . $row[0]['id'] ."#comment" . $i . "'>" . $commrow['name'] . "</a> ";
		$i++;
	}
}

echo "</p>";

// previous blog entries:


$prevsql = "SELECT entries.*, categories.cat FROM entries, categories
	WHERE entries.cat_id = categories.id
	ORDER BY dateposted DESC
	LIMIT 1, 5;";
try{
	$prevresult = $db->query($prevsql);
	$numrows_prev = $prevresult->rowCount();
}catch (PDOException $e){
	echo $e->getMessage();
}
//$prevresult = mysql_query($prevsql);
//$numrows_prev = mysql_num_rows($prevresult);

if($numrows_prev == 0) {
	echo "<p>No previous entries.</p>";
}
else {

	echo "<ul>";

	//while($prevrow = mysql_fetch_assoc($prevresult)) {
	//while($prevrow = $prevresult->fetchAll(PDO::FETCH_ASSOC)){
	foreach($prevresult as $prevrow){
		echo "<li><a href='viewentry.php?id=" . $prevrow['id'] . "'>" . $prevrow['subject'] . "</a></li>";
	}
}

echo "</ul>";

require("footer.php");

?>