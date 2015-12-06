<?php
session_start();
require("functions.php");

if(pf_check_number($_GET['topic']) == TRUE) {
	$validtopic = $_GET['topic'];
}
else {
	header("Location: " . $config_basedir);
}

if(pf_check_number($_GET['subject']) == TRUE) {
	$validsubject = $_GET['subject'];
}
else {
	header("Location: " . $config_basedir);
}

function question_summary($question) {
	$final = "";
	$final = (substr($question, 0, 80) . "...");
	
	return $final;
}

require("header.php");

echo "<h1>Questions</h1>";

$qsql = "SELECT * FROM questions WHERE topic_id = " . $validtopic . " AND active = 1;";
//$qresult = mysql_query($qsql);
//$numrows = mysql_num_rows($qresult);
$qresult = $db->query($qsql);
$numrows = $qresult->rowCount();

if($numrows == 0) {
	echo "No Questions";
}
else {
	echo "<table cellspacing=0 cellpadding=5>";

	//while($qrow = mysql_fetch_assoc($qresult)) {
	foreach($qresult as $qrow){
		echo "<tr>";
		
		echo "<td><a href='answer.php?id=" . $qrow['id'] . "'>" . $qrow['question'] . "</a></td>";
		echo "<td><i>" . question_summary($qrow['answer']) . "</i></td>";
		
		if($_SESSION['SESS_ADMINUSER'] AND $numrows >= 1) {
			echo "<td><a href='deletequestion.php?topic=" . $validtopic . "&subject=" . $validsubject . "&questionid=" . $qrow['id'] . "'>Delete Question</a></td>";
		}
	
		echo "</tr>";
	}

	echo "</table>";
}


if($_SESSION['SESS_USERNAME'])
{
	echo "<h2>Options</h2>";
	echo "<a href='addquestion.php?subject=$subject&topic=$topic'>Add a question</a>";
}

require("footer.php");

?>