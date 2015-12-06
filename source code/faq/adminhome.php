<?php

session_start();


if(isset($_SESSION['SESS_ADMINUSER']) == FALSE) {
	header("Location: " . $config_basedir . "adminlogin.php");
}

require("header.php");
	echo "<h1>Admin Panel</h1>";
	echo "Welcome <strong>" . $_SESSION['SESS_ADMINUSER'] . "</strong> [<a href='adminlogout.php'>logout</a>]";
?>
<table border="0" cellspacing="5" cellpadding="5">
  <tr>
    <td><a href="addsubject.php">Add a subject</a></td>
    <td>Add a new subject</td>
  </tr>
  <tr>
    <td><a href="addtopic.php">Add a topic</a></td>
    <td>Add a new topic</td>
  </tr>
  <tr>
    <td><a href="adminmodquestions.php?func=main">Moderated Questions</a></td>
    <td>Authorise or Reject submitted questions</td>
  </tr>
  <tr>
    <td><a href="adminmodsubown.php?func=main">Subject Ownership Requests</a></td>
    <td>Authorise or Reject submitted questions</td>
  </tr>
</table>

<?php
	require("footer.php");
?>