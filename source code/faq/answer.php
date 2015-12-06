<?php

session_start();

require("db.php");
require("functions.php");

if(pf_check_number($_GET['id']) == TRUE) {
	$validid = $_GET['id'];
}
else {
	header("Location: " . $config_basedir);
}


if($_POST['submit']) {
	$qsql = "INSERT INTO comments(question_id, title, comment, user_id) VALUES('"
		. $validid
		. "','" . pf_fix_slashes($_POST['titleBox'])
		. "','" . pf_fix_slashes($_POST['commentBox'])
		. "', '" . $SESS_USERID
		. "')";
	 
	//mysql_query($qsql);
	$db->query($qsql);
	header("Location: " . $config_basedir . "answer.php?id=" . $validid);
}
else {
	require("header.php");
	
	$qsql = "SELECT questions.question, questions.dateadded, questions.answer, users.username  FROM questions, users WHERE addedby_id = users.id AND questions.id = " . $_GET['id'] . " AND active = 1;";
//	$qresult = mysql_query($qsql);
//	$qrow = mysql_fetch_assoc($qresult);
	$qresult = $db->query($qsql);
	$qrow = $qresult->fetchAll(PDO::FETCH_ASSOC);
	
	//if(mysql_num_rows($qresult) == 0) {
	if($qresult->rowCount()==0){
	echo "No Questions";
	}
	else {

		echo "<h1>" . $qrow[0]['question'] . "</h1>";
		
		echo "Added by <strong>" . $qrow[0]['username'] . "</strong> on " . date("D jS F Y g.iA", strtotime($qrow[0]['dateadded']));
		
		echo "<p>";
		echo $qrow[0]['answer'];
		echo "</p>";
		
		$csql = "SELECT comments.title, comments.comment, users.username FROM comments, users WHERE comments.user_id = users.id AND question_id = " . $validid . ";";
		//$cresult = mysql_query($csql);
		$cresult = $db->query($csql);

		echo "<table class='visible' width='100%' cellspacing=0 cellpadding=5>";
		echo "<tr><th class='visible' colspan=2>Comments about this question</th></tr>";
	
		//if(mysql_num_rows($cresult) == 0) {
		if($cresult->fetchAll(PDO::FETCH_ASSOC)==0){
			echo "<tr><td colspan=2><strong>No comments!</strong></td></tr>";
		}
		else {
			//while($crow = mysql_fetch_assoc($cresult))
			foreach($cresult as $crow)
			{     
				echo "<tr>";
				echo "<td width='15%'><strong>" . $crow['title'] . "</strong> by <i>" . $crow['username'] . "</i></td>";
				echo "<td>" . $crow['comment'] . "</td>";
				echo "</tr>";
			}
		}
	
		echo "</table>";
		
		if($_SESSION['SESS_USERNAME']) {
			echo "<h2>Add a comment</h2>";
			echo "<form action='answer.php?id=" . $_GET['id'] . "' method='POST'>";
			echo "<table width='100%'>";
			echo "<tr>";
			echo "<td>Title</td>";
			echo "<td><input type='text' name='titleBox'></td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td>Comment</td>";
			echo "<td><textarea rows=10 cols=50 name='commentBox'></textarea></td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td></td>";
			echo "<td><input type='submit' name='submit'value='Post Comment'></td>";
			echo "</tr>";
			echo "</table>";
			echo "</form>";
		}
		else {
			echo "<p>&bull; You cannot post as your are <strong>Anonymous</strong>. Please <a href='login.php'>login</a></p>";
		}
	}
}

require("footer.php");

?>