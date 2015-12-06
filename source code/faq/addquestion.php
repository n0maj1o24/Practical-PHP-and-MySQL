<?php

session_start();

require("db.php");
require("functions.php");

if(pf_check_number($_GET['subject']) == TRUE) {
	$validsubject = $_GET['subject'];
}
else {
	header("Location: " . $config_basedir);
}

if(isset($_GET['topic']) == TRUE) {
	if(is_numeric($_GET['topic']) == TRUE) {
		$validtopic = $_GET['topic'];
	}
	else {
		header("Location: " . $config_basedir);
	}
}
 
if(!$_SESSION['SESS_USERNAME']) {
	header("Location: " . $config_basedir . "login.php");
}
 
if($_POST['submit']) {
	$authsql = "SELECT * FROM subjects WHERE id = " . $validsubject . " AND owner_id = " . $_SESSION['SESS_USERID'] . ";";
//	$authresult = mysql_query($authsql);
//	$authnumrows = mysql_num_rows($authresult);
	$authresult = $db->query($authsql);
	$authnumrows = $authresult->rowCount();

	if($authnumrows == 1) {
		$qsql = "INSERT INTO questions(topic_id, question, answer, addedby_id, dateadded, active) VALUES("
		. $_POST['topic']
		. ", '" . pf_fix_slashes($_POST['question'])
		. "', '" . pf_fix_slashes($_POST['answer'])
		  	. "', " . $_SESSION['SESS_USERID']
		. ", NOW()"
		. ", 1);";
		//$qresult = mysql_query($qsql);
		$qresult = $db->query($qsql);
		
		header("Location: " . $config_basedir . "answer.php?id=" . mysql_insert_id());
		header("Location: " . $config_basedir . "answer.php?id=" . $db->lastInsertId());
	}
	else {            
		$qsql = "INSERT INTO questions(topic_id, question, answer, addedby_id, dateadded, active) VALUES("
		. $_POST['topic']
		. ", '" . pf_fix_slashes($_POST['question'])
		. "', '" . pf_fix_slashes($_POST['answer'])
		  	. "', " . $_SESSION['SESS_USERID']
		. ", NOW()"
		. ", 0);";
		
//		$qresult = mysql_query($qsql);
		$qresult = $db->query($qsql);

		require("header.php");
		
		echo "<h1>Awaiting moderation</h1>";
		echo "Your question requires moderator approval before it is posted.";
	}

}
else {
	require("header.php");
	
	$subsql = "SELECT * FROM subjects WHERE id = " . $validsubject . ";";
//	$subq = mysql_query($subsql);
//	$subrow = mysql_fetch_assoc($subq);
	$subq = $db->query($subsql);
	$subrow = $subq->fetchAll(PDO::FETCH_ASSOC);

	$toplistsql = "SELECT * FROM topics WHERE subject_id = " . $validsubject . " ORDER BY name ASC;";
//	$toplistresult = mysql_query($toplistsql);
//	$toplistnumrows = mysql_num_rows($toplistresult);
	$toplistresult = $db->query($toplistsql);
	$toplistnumrows = $toplistresult->rowCount();
	
	echo "<h1>Add a new question</h1>";

	if($_SESSION['SESS_USERID'] == $subrow[0]['owner_id']) {
		if($toplistnumrows == 0) {
			$notopics = TRUE;
		}
	}

	if($notopics == TRUE) {
		echo "No topics have been created. Click <a href='addtopic.php'>here</a> to create one!";
	}
	else {
		echo "<p>";
		echo "<form action='addquestion.php?subject=" . $validsubject . "' method='POST'>";
		echo "<table cellpadding=5>";
		echo "<tr>";            
		echo "<td>Subject</td>";
		echo "<td><strong>" . $subrow[0]['subject'] . "</strong></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>Topic</td>";
		echo "<td>";
		
		if(!$validtopic) {				
			echo "<select name='topic'>";
			
			//while($toplistrow = mysql_fetch_assoc($toplistresult)) {
			foreach($toplistresult as $toplistrow){
				echo "<option value='" . $toplistrow['id'] . "'>" . $toplistrow['name'] . "</option>";
			}
	
			echo "</select>";
		}
		else {
			$topsql = "SELECT * FROM topics WHERE id = " . $validtopic . ";";
//			$topq = mysql_query($topsql);
//			$toprow = mysql_fetch_assoc($topq);
			$topq = $db->query($topsql);
			$toprow = $topq->fetchAll(PDO::FETCH_ASSOC);
			
			echo "<strong>" . $toprow[0]['name'] . "</strong>";
			echo "<input type='hidden' name='topic' value='" . $toprow[0]['id'] . "'>";
		}				
	
		echo "</td>";    
		echo "</tr>";
		echo "<tr>";
		echo "<td>Question</td>";
		echo "<td><input type='text' name='question'></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>Answer</td>";
		echo "<td><textarea name='answer' rows=10 cols=50></textarea></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td colspan=2><input type='submit' name='submit' value='Add Question'></td>";
		echo "</tr>";
		echo "</table>";
		echo "</form>";
	}

}

require("footer.php");

?>