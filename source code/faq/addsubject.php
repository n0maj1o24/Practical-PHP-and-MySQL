<?php

session_start();

require("db.php");
require("functions.php");

if(isset($_SESSION['SESS_ADMINUSER']) == FALSE) {
	header("Location: " . $config_basedir . "adminlogin.php");
}

if($_POST['submit']) {
	$subsql = "INSERT INTO subjects(subject, blurb, owner_id) VALUES("
		. "'" . pf_fix_slashes($_POST['subject'])
		. "', '" . pf_fix_slashes($_POST['blurb'])
		. "'," . $_POST['owner']
		. ");";
//	mysql_query($subsql);
	$db->query($subsql);
	header("Location: " . $config_basedir);
}
else {
	require("header.php");

?>
	<h1>Add a new subject</h1>
	
	<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post">
	<table cellpadding="5">
	<tr>
		<td>Subject</td>
		<td><input type="text" name="subject"></td>
	</tr>
	<tr>
		<td>Owner</td>
		<td>
		<select name="owner">
			<option value="0">--- No Owner ---</option>
		<?php
			$sql = "SELECT * FROM users ORDER BY username ASC;";
//			$result = mysql_query($sql);
			$result = $db->query($sql);
//			while($row = mysql_fetch_assoc($result)) {
			foreach($result as $row){
					echo "<option value='" . $row['id'] . "'>" . $row['username'] . "</option>";
			}
		?>
		</select>
		</td>
	</tr>
	<tr>
		<td>Description Blurb</td>
		<td><textarea name="blurb" cols=50 rows=10></textarea></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" name="submit" value="Add Subject!"></td>
	</tr>
	</table>
	</form>

<?php
}

require("footer.php");

?>