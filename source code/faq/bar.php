<?php

if($_SESSION['SESS_USERNAME']) {
	echo "<table class='visible' width='100%'cellspacing=0 cellpadding=5>";
	echo "<tr><th class='visible'>Login details</th></tr>";
	echo "<tr><td>";
	echo "Logged in as <strong>" . $_SESSION['SESS_USERNAME'] . "</strong>";
	echo "<ul>";
	echo "<li>View my <a href='userhome.php'>Control Panel</a></li>";
	echo "<li><a href='userlogout.php'>Logout</a></li>";
	echo "</ul>";
	echo "</td></tr>";
	echo "</table>";
}

if($_SESSION['SESS_ADMINUSER']) {
	echo "<table class='visible' width='100%'cellspacing=0 cellpadding=5>";
	echo "<tr><th class='visible'>Login details</th></tr>";
	echo "<tr><td>";
	echo "Logged in as <strong>" . $_SESSION['SESS_ADMINUSER'] . "</strong>";
	echo "<ul>";
	echo "<li>View my <a href='adminhome.php'>Admin Panel</a></li>";
	echo "<li><a href='adminlogout.php'>Logout</a></li>";
	echo "</ul>";
	echo "</td></tr>";
	echo "</table>";
}

if(basename($_SERVER['SCRIPT_NAME']) == "answer.php") {

	echo "<h1>Other questions</h1>";
	$subsql = "SELECT topic_id FROM questions WHERE id = " . $_GET['id'] . ";";
//	$subresult = mysql_query($subsql);
//	$subrow = mysql_fetch_assoc($subresult);
	$subresult = $db->query($subsql);
	$subrow = $subresult->fetchAll(PDO::FETCH_ASSOC);

	
	$othersql = "SELECT id, question FROM questions WHERE topic_id = " . $subrow[0]['topic_id'] . " AND active = 1;";
	//$otherresult = mysql_query($othersql);
	$otherresult = $db->query($othersql);

	echo "<table width='100%'>";

	//while($otherrow = mysql_fetch_assoc($otherresult)) {
	foreach($otherresult as $otherrow){
		echo "<tr>";
		echo "<td width='5%'>";
	
		if($otherrow['id'] == $_GET['id']) {
			echo "&bull;";
		}
	
		echo "<td><a href='answer.php?id=" . $otherrow['id'] . "'>" . $otherrow['question']  . "</a></td>";
		echo "</tr>";
	}

	echo "</table>";
}
else {
	$subsql = "SELECT * FROM subjects";
	//$subres = mysql_query($subsql);
	$subres = $db->query($subsql);

	echo "<h1>Subjects</h1>";
	
	echo "<table>";

	//while($subrow = mysql_fetch_assoc($subres)) {
	foreach($subres as $subrow){
		echo "<tr>";
		echo "<td width='5%'>";
		
		if($subrow['id'] == $_GET['subject']) {
			echo "&bull;";
		}
		
		echo "</td>";
		echo "<td><a href='index.php?subject=" . $subrow['id'] . "'>" . $subrow['subject'] . "</a></td>";
		
		if($_SESSION['SESS_ADMINUSER']) {
			echo "<td>[<a href='deletesubject.php?subject=" . $subrow['id'] . "'>X</a>]</td>";
		}
	
		echo "</tr>";
	}

	echo "</table>";	

	if(isset($_GET['subject'])) {
		$topsql = "SELECT * FROM topics WHERE subject_id = " . $_GET['subject'] . ";";
		//$topres = mysql_query($topsql);
		$topres = $db->query($topsql);

		echo "<h1>Topics</h1>";
		
		//if(mysql_num_rows($topres) == 0) {
		if($topres->rowCount()==0){
			echo "No topics!";
		}

		echo "<table width='100%'>";

		//while($toprow = mysql_fetch_assoc($topres)) {
		foreach($topres as $toprow){
			echo "<tr>";
			echo "<td width='5%'>";
			
			if($toprow['id'] == $_GET['topic']) {
				echo "&bull;";
			}
			
			echo "</td>";
			
			echo "<td><a href='questions.php?subject=" . $subject . "&amp;topic=" . $toprow['id'] . "'>" . $toprow['name'] . "</a></td>";
			
			if($_SESSION['SESS_ADMINUSER']) {
				echo "<td>[<a href='deletetopic.php?subject=" . $toprow['subject_id'] . "&amp;topic=" . $toprow['id'] . "'>X</a>]</td>";
			}
			
			echo "</tr>";
		}
	
	echo "</table>";
	}
}

?>