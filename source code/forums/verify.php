<?php

require("header.php");

$verifystring = urldecode($_GET['verify']);
$verifyemail = urldecode($_GET['email']);

$sql = "SELECT id FROM users WHERE verifystring = '" . $verifystring . "' AND email = '" . $verifyemail . "';";
//$result = mysql_query($sql);
//$numrows = mysql_num_rows($result);
$result = $db->query($sql);
$numrows = $result->rowCount();

if($numrows == 1) {
//	$row = mysql_fetch_assoc($result);
	$row = $result->fetchAll(PDO::FETCH_ASSOC);
	$sql = "UPDATE users SET active = 1 WHERE id = " . $row[0]['id'];
//	$result = mysql_query($sql);
	$result = $db->query($sql);
	echo "Your account has been verified. You can now <a href='login.php'>log in</a>";
}
else {
	echo "This account could not be verified.";
}

echo $verifystring;

require("footer.php");

?>

