<?php

session_start();

require("db.php");
require("functions.php");

function set_validid() {
	global $config_basedir;
	if(pf_check_number($_GET['id']) == TRUE) {
		return $_GET['id'];
	}
	else {
		header("Location: " . $config_basedir);
	}
}

switch($_GET['func'])
{
	case "allow":
		$validid = set_validid();
		
		$modqsql = "UPDATE questions SET active = 1 WHERE id = " . $validid . ";";
//		$modqq = mysql_query($modqsql);
		$modqq = $db->query($modqsql);

		header("Location: " . $config_basedir . "adminmodquestions.php?func=main");					
	break;
	
	case "details":
		require("header.php");
		$validid = set_validid();
		
		$sql = "SELECT questions.*, topics.name, subjects.subject FROM questions INNER JOIN topics ON questions.topic_id = topics.id INNER JOIN subjects ON topics.subject_id = subjects.id WHERE questions.id = " . $validid . ";";
//		$result = mysql_query($sql);
//		$row = mysql_fetch_assoc($result);
		$result = $db->query($sql);
		$row = $result->fetchAll(PDO::FETCH_ASSOC);
		
		echo "<h1>Submitted question details</h1>";
		echo "<table border='0' cellspacing='0' cellpadding='5'>";
		echo "<tr>";
			echo "<td><b>Subject</b></td>";
			echo "<td>" . $row[0]['subject'] . "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td><b>Topic</b></td>";
			echo "<td>" . $row[0]['name'] . "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td><b>Question</b></td>";
			echo "<td>" . $row[0]['question'] . "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td><b>Answer</b></td>";
			echo "<td>" . $row[0]['answer'] . "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan=2>";
			echo "<a href='adminmodquestions.php?func=main'>&lArr; Back to questions</a>";
			echo " &bull; ";
			echo "<a href='adminmodquestions.php?func=allow&id=" . $row[0]['id'] . "'>Allow</a> ";
			echo " &bull; ";
			echo " <a href='adminmodquestions.php?func=deny&id=" . $row[0]['id'] . "'>Deny</a>";
			echo "</td>";
		echo "</tr>";
		echo "</table>";
	break;
	
	case "main":
		require("header.php");

		if($_SESSION['SESS_ADMINUSER']) {
			$modqsql = "SELECT questions.*, users.username FROM users inner join questions on questions.addedby_id=users.id inner join topics on questions.topic_id=topics.id inner join subjects on topics.subject_id=subjects.id WHERE questions.active = 0;";
		}
		else {
			$modqsql = "SELECT questions.*, users.username FROM users inner join questions on questions.addedby_id=users.id inner join topics on questions.topic_id=topics.id inner join subjects on topics.subject_id=subjects.id WHERE questions.active = 0 AND subjects.owner_id = " . $_SESSION['SESS_USERID'] . ";";
		}
		
//		$modresult = mysql_query($modqsql);
		$modresult = $db->query($modqsql);
		
		echo "<h1>Questions submitted for moderation</h1>";
		echo "<table cellspacing='0' cellpadding='5'>";
		echo "<tr>";
		echo "<th>Subject</th>";
		echo "<th>Topic</th>";
		echo "<th>Question</th>";
		echo "<th>Submitted By</th>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "</tr>";
		
//		if(mysql_num_rows($modresult) == 0) {
		if($modresult->rowCount() == 0){
			echo "<tr>";
			echo "<td colspan=7>No questions to moderate</td>";
			echo "</tr>";
		}
		
//		while($row = mysql_fetch_assoc($modresult)) {
		foreach($modresult as $row){
			$subsql = "SELECT topics.name, subjects.subject FROM topics, subjects WHERE topics.subject_id = subjects.id AND topics.id = " . $row['topic_id'] . ";";
//			$subresult = mysql_query($subsql);
//			$subrow = mysql_fetch_assoc($subresult);
			$subresult = $db->query($subsql);
			$subrow = $subresult->fetchAll(PDO::FETCH_ASSOC);
			  						  
			echo "<tr>";
			echo "<td>" . $subrow[0]['subject'] . "</td>";
			echo "<td>" . $subrow[0]['name'] . "</td>";
			echo "<td>" . $row['question'] . "</td>";
			echo "<td>" . $row['username'] . "</td>";
			echo "<td><a href='adminmodquestions.php?func=details&id=" . $row['id'] . "'>Details</a></td>";			
			echo "<td><a href='adminmodquestions.php?func=allow&id=" . $row['id'] . "'>Allow</a></td>";
			echo "<td><a href='adminmodquestions.php?func=deny&id=" . $row['id'] . "'>Deny</a></td>";
			
			echo "</tr>";
		}
		
		echo "</table>";
	break;
	
	case "deny":
		require("header.php");
		
		$validid = set_validid();
				
		echo "<h1>Are you sure that you want to reject this question?</h1>";
		echo "<p>[<a href='" . $_SERVER['SCRIPT_NAME'] . "?func=denyconf&id=" . $validid . "'>Yes</a>] [<a href='" . $_SERVER['SCRIPT_NAME'] . "?func=main'>No</a>]";
	break;
	
	case "denyconf":
		$validid = set_validid();
							
		$delsql = "DELETE FROM questions WHERE id = " . $_GET['id'] . ";";
//		$delq = mysql_query($delsql);
		$delq = $db->query($delsql);
		header("Location: " . $config_basedir . "adminmodquestions.php?func=main");										
	break;
}

require("footer.php");
?>