<?php

session_start();

require("config.php");

if(!$_SESSION['SESS_USERNAME']) {
	header("Location: " . $config_basedir . "login.php");
}

require("header.php");

echo "<h1>Control Panel</h1>";
echo "Welcome <strong>" . $_SESSION['SESS_USERNAME'] . "</strong> [<a href='userlogout.php'>logout</a>]"; 

echo "<h2>Subjects Owned</h2>";

$ownsql = "SELECT * FROM subjects WHERE owner_id =" . $_SESSION['SESS_USERID'] . ";";
//$ownres = mysql_query($ownsql);
$ownres = $db->query($ownsql);

//if(mysql_num_rows($ownres) >= 1)
if($ownres->rowCount()>=1)
{
	echo "<ul>";

	//while($ownrow = mysql_fetch_assoc($ownres)) {
	foreach($ownres as $ownrow){
		echo "<li><strong><a href='index.php?subject=" . $ownrow['id'] . "'>" . $ownrow['subject'] . "</a></strong> - <a href='addquestion.php?subject=" . $ownrow['id'] . "'>Add a question</a> &bull; <a href='removesubown.php?subject=" . $ownrow['id'] . "'>Remove ownership</a></li>";
	}
	
	echo "</ul>";

	echo "<a href='addtopic.php'>Add a topic</a>";
	echo " &bull; ";
	echo "<a href='adminmodquestions.php?func=main'>Moderate submitted questions</a>";
}
else
{
	echo "No subjects are owned";
}

require("footer.php");

?>
