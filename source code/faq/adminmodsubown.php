<?php

session_start();

if(!$_SESSION['SESS_ADMINUSER']) {
	header("Location: " . $config_basedir);
}

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

switch($_GET['func']) {
	case "main":
		require("header.php");

		$subssql = "SELECT subjects.subject, subjects.id FROM subjects INNER JOIN mod_subowner ON subjects.id = mod_subowner.sub_id GROUP BY subjects.id;";
//		$subsresult = mysql_query($subssql);
//		$subsnumrows = mysql_num_rows($subsresult);
		$subsresult = $db->query($subssql);
		$subsnumrows = $subsresult->rowCount();
		
		echo "<h1>Subjects and Ownership</h1>";            
		
		if($subsnumrows == 0) {
			echo "No requests have been made.";
		}
		else {
//			while($subsrow = mysql_fetch_assoc($subsresult)) {
			foreach($subsresult as $subsrow){
				$reqsql = "SELECT users.id AS userid, users.username, mod_subowner.* FROM users INNER JOIN mod_subowner ON mod_subowner.user_id = users.id WHERE mod_subowner.sub_id = " . $subsrow['id'] . ";";
//				$reqresult = mysql_query($reqsql);
				$reqresult = $db->query($reqsql);

				echo "<table class='visible' cellpadding=10 cellspacing=0>";
				echo "<tr><th class'visible' colspan='4'>Ownership requests for <i>" . $subsrow['subject'] . "</i></th></tr>";
				
//				while($reqrow = mysql_fetch_assoc($reqresult)) {
				foreach($reqresult as $reqrow){
					echo "<tr>";
					echo "<td>Requested by 	<strong>" . $reqrow['username'] . "</strong></td>";
					echo "<td>" . $reqrow['reasons'] . "</td>";
					echo "<td><a href='" . $_SERVER['SCRIPT_NAME'] . "?func=accept&id=" . $reqrow['id'] . "'>Accept</a></td>";
					echo "<td><a href='" . $_SERVER['SCRIPT_NAME'] . "?func=deny&id=" . $reqrow['id'] . "'>Deny</a></td>";
					echo "</tr>";											
				}
				
				echo "</table>";
				echo "<br/>";
			}
		}
	break;
	
	case "accept":
		$validid = set_validid();
				
		$sql = "SELECT mod_subowner.sub_id, subjects.subject, users.id AS userid, users.username, users.email FROM mod_subowner INNER JOIN subjects ON mod_subowner.sub_id = subjects.id LEFT JOIN users ON mod_subowner.user_id = users.id WHERE mod_subowner.id = " . $validid . ";";
//		$result = mysql_query($sql);
//		$row = mysql_fetch_assoc($result);
//		$numrows = mysql_num_rows($result);

		$result = $db->query($sql);
		$row = $result->fetchAll(PDO::FETCH_ASSOC);
		$numrows = $result->rowCount();
	
		$mail_username = $row[0]['username'];
		$mail_email = $row[0]['email'];
		$mail_subject = $row[0]['subject'];
			
$mail_body=<<<_MESSAGE_

Hi $mail_username,

I am pleased to inform you that you have been accepted as the new owner of the '$mail_subject' subject.

When you next log into '$config_sitename' you will see the subject in your Control Panel.

Kind regards,

	$config_sitename Administrator

_MESSAGE_;

		mail($mail_email, "Ownership request for " . $mail_subject . " accepted!", $mail_body);

		$addsql = "UPDATE subjects SET owner_id = " . $row['userid'] . " WHERE id = " . $row['sub_id'] . ";";
//		mysql_query($addsql);
		$db->query($addsql);
		$delsql = "DELETE FROM mod_subowner WHERE sub_id = " . $row['sub_id'] . ";";
//		mysql_query($delsql);
		$db->query($delsql);
		header("Location: " . $config_basedir . "adminmodsubown.php?func=main");
	break;
	
	case "deny":
		$validid = set_validid();		
		
		require("header.php");
		echo "<h1>Are you sure that you want to deny this request?</h1>";
		echo "<p>[<a href='adminmodsubown.php?func=denyconf&id=" . $validid . "'>Yes</a>] [<a href='adminmodsubown.php?func=main'>No</a>]";
	break;
	
	case "denyconf":
		$validid = set_validid();
				
		$sql = "SELECT mod_subowner.sub_id, subjects.subject, users.id AS userid, users.username, users.email FROM mod_subowner INNER JOIN subjects ON mod_subowner.sub_id = subjects.id LEFT JOIN users ON mod_subowner.user_id = users.id WHERE mod_subowner.id = " . $validid . ";";
//		$result = mysql_query($sql);
//		$row = mysql_fetch_assoc($result);
//		$numrows = mysql_num_rows($result);
		$result = $db->query($sql);
		$row = $result->fetchAll(PDO::FETCH_ASSOC);
		$numrows = $result->rowCount();
	
		$mail_username = $row[0]['username'];
		$mail_email = $row[0]['email'];
		$mail_subject = $row[0]['subject'];
			
$mail_body=<<<_MESSAGE_

Hi $mail_username,

I am writing to inform you that your request for ownership of the '$mail_subject' subject has been declined.

Better luck next time!

Kind regards,

	$config_sitename Administrator

_MESSAGE_;

		mail($mail_email, "Ownership request for " . $mail_subject . " denied!", $mail_body);

		$delsql = "DELETE FROM mod_subowner WHERE id = " . $validid . ";";
//		mysql_query($delsql);
		$db->query($delsql);

		header("Location: " . $config_basedir . "adminmodsubown.php?func=main");
	break;
}

require("footer.php");

?>