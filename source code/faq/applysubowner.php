<?php

session_start();

require("config.php");
require("functions.php");

if(pf_check_number($_GET['subject']) == TRUE) {
	$validsubject = $_GET['subject'];
}
else {
	header("Location: " . $config_basedir);
}

require("header.php");


if($_POST['submit']) {
	$appsql = "SELECT * FROM mod_subowner WHERE sub_id = " . $validsubject . " AND user_id = '" . $_SESSION['SESS_USERID'] . "';";
//	$appresult = mysql_query($appsql);
	$appresult = $db->query($appsql);
//	if(mysql_num_rows($appresult) == 0) {
    if($appresult->rowCount() == 0){
		$inssql = "INSERT INTO mod_subowner(sub_id, user_id, reasons) VALUES(" . $_GET['subject'] . "," . $_SESSION['SESS_USERID'] . ",'" .  pf_fix_slashes($_POST['reasons']) . "');";
//		mysql_query($inssql);
		$db->query($inssql);
		echo "<h1>Application Submitted</h1>";
		echo "Your application has been submitted. You will be emailed with the decision.";
	}
	else {
		echo "<h1>Already Applied</h1>";
		echo "<p>You have already made an application for this subject.</p>";
	}
}
else {
	$subsql = "SELECT subject FROM subjects WHERE id = " . $validsubject . ";";
//	$subresult = mysql_query($subsql);
//	$subrow = mysql_fetch_assoc($subresult);
    $subresult = $db->query($subsql);
    $subrow = $subresult->fetchAll(PDO::FETCH_ASSOC);
?>
	<h1>Application for ownership of <i><?php echo $subrow[0]['subject']; ?></i></h1>
	<p>You have applied to maintain the subject <strong><?php echo $subrow[0]['subject']; ?></strong>.</p>
	<p>
	The procedure to apply to own a subject is as follows:
	<ul>
	<li>Fill in is Subject Ownership applion form.</li>
	<li>The contents of this form will be submitted to the site adminstrator approval.</li>
	<li>You will be emailed with the administrator's decision.</li>
	</ul>
	</p>
	<p>
	When you fill out the Reasons box below, it is advised that you indicate why you should be given
	the ownership of the subject. What can you bring to the subject in terms of time and knowledge? Can
	you ensure the subject questions are clear and well structured?
	</p>
	<form action="applysubowner.php?subject=<?php echo $validsubject; ?>" method="POST">
	<table cellpadding=5 cellspacing=5>
	<tr>
	<td>Reasons</td>
	<td><textarea name="reasons" cols="50" rows="10"></textarea></td>
	</tr>
	<tr>
	<td></td>
	<td><input type="submit" name="submit" value="Apply!"></td>
	</tr>
	</table>
	</form>
<?php
}

require("footer.php");

?>