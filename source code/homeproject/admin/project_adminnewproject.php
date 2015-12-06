<?php
	require_once("../project_functions.php");
	pf_protect_admin_page();

	if($_POST['submit']) {
		$inssql = "INSERT INTO homeproject_projects(name, about, pathname) VALUES("
			. "'" . pf_fix_slashes($_POST['name'])
			. "', '" . pf_fix_slashes($_POST['about'])
			. "', '" . pf_fix_slashes($_POST['pathname'])
			. "');";
//		mysql_query($inssql);
		$db->query($inssql);
		header("Location: " . $config_projectadminbasedir . basename($_SERVER['SCRIPT_NAME']));
	}
	else {
?>
		<h1>New Project</h1>
		<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>?func=newproject" method="POST">
		<table>
		<tr>
			<td>Project Name</td>
			<td><input type="text" name="name" value="<?php echo $row['name'] ?>"></td>
		</tr>
		<tr>
			<td>Path Name</td>
			<td><input type="text" name="pathname" value="<?php echo $row['pathname'] ?>"></td>
		</tr>
		<tr>
			<td>Description</td>
			<td><textarea name="about" rows="10" cols="50"><?php echo $row['about'] ?></textarea></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" name="submit" value="Modify details"></td>
		</tr>
		
		</table>
		</form>

<?php
	}
?>