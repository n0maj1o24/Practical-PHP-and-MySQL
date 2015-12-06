<?php

include("config.php");

if(isset($_GET['id']) == TRUE) {
	if(is_numeric($_GET['id']) == FALSE) {
		$error = 1;
	}

	if($error == 1) {
		header("Location: " . $config_basedir);
	}
	else {
		$validforum = $_GET['id'];
	}
}
else {
	header("Location: " . $config_basedir);
}

require("header.php");

$forumsql = "SELECT * FROM forums WHERE id = " . $validforum . ";";
//$forumresult = mysql_query($forumsql);
$forumresult = $db->query($forumsql);
//$forumrow = mysql_fetch_assoc($forumresult);
$forumrow = $forumresult->fetchAll(PDO::FETCH_ASSOC);
echo "<h2>" . $forumrow[0]['name'] . "</h2>";

echo "<a href='index.php'>" . $config_forumsname . " forums</a><br /><br />";

echo "[<a href='newtopic.php?id=" . $validforum . "'>New Topic</a>]";
echo "<br /><br />";

$topicsql = "SELECT MAX( messages.date ) AS maxdate, topics.id AS topicid, topics.*, users.* FROM messages, topics, users WHERE messages.topic_id = topics.id AND topics.user_id = users.id  AND topics.forum_id = " . $validforum . " GROUP BY messages.topic_id ORDER BY maxdate DESC;";
//$topicresult = mysql_query($topicsql);
//$topicnumrows = mysql_num_rows($topicresult);
$topicresult = $db->query($topicsql);
$topicnumrows = $topicresult->rowCount();
if($topicnumrows == 0) {
	echo "<table width='300px'><tr><td>No topics!</td></tr></table>";
}
else {

	echo "<table>";
	
	echo "<tr>";
	echo "<th>Topic</th>";
	echo "<th>Replies</th>";
	echo "<th>Author</th>";
	echo "<th>Date Posted</th>";
	echo "</tr>";
	//while($topicrow = mysql_fetch_assoc($topicresult)) {
	foreach($topicresult as $topicrow){
		$msgsql = "SELECT id FROM messages WHERE topic_id = " . $topicrow['topicid'];
//		$msgresult = mysql_query($msgsql);
//		$msgnumrows = mysql_num_rows($msgresult);
		$msgresult = $db->query($msgsql);
		$msgnumrows = $msgresult->rowCount();
		echo "<tr>";
		echo "<td>";
		
		if($_SESSION['ADMIN']) {
			echo "[<a href='delete.php?func=thread&id=" . $topicrow['topicid'] . "&forum=" . $validforum . "'>X</a>] - ";
		}
		
		echo "<strong><a href='viewmessages.php?id=" . $topicrow['topicid'] . "'>" . $topicrow['subject'] . "</a></td></strong>";
		echo "<td>" . $msgnumrows . "</td>";
		echo "<td>" . $topicrow['username'] . "</td>";
		echo "<td>" . date("D jS F Y g.iA", strtotime($topicrow['date'])) . "</td>";
		echo "<tr>";
	}
	
	echo "</table>";

}

require("footer.php");

?>