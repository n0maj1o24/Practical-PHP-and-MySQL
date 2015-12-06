<?php

session_start();

require("config.php");
require("functions.php");

$db = mysql_connect($dbhost, $dbuser, $dbpassword);
mysql_select_db($dbdatabase, $db);

if(isset($_SESSION['USERNAME']) == FALSE) {
	header("Location: " . $config_basedir . "/login.php?ref=newitem");
}

$validid = pf_validate_number($_GET['id'], "redirect", $config_basedir);

if($_POST['submit']) {
	$validdate = checkdate($_POST['month'], $_POST['day'], $_POST['year']);

	if($validdate == TRUE) {
		$concatdate = $_POST['year']
			. "-" . sprintf("%02d", $_POST['month'])
			. "-" . sprintf("%02d", $_POST['day'])
			. " " . $_POST['hour']
			. ":" . $_POST['minute']
			. ":00";
			
		$itemsql = "UPDATE items SET"
			. " user_id = " . $_SESSION['USERID']
			. ", cat_id = " . $_POST['cat']
			. ", name = '" . $_POST['name']
			. "', startingprice = " . $_POST['price']
			. ", description = '" . $_POST['description']
			. "', dateends = '" . $concatdate
			. "' WHERE id = " . $validid . ";";
	
		mysql_query($itemsql);
		$itemid = mysql_insert_id();
	
		header("Location: " . $config_basedir . "/itemdetails.php?id=" . $validid);
	}
	else {
		header("Location: " . $config_basedir . "/newitem.php?error=date");		
	}
}
else {
	require("header.php");

	$datasql = "SELECT EXTRACT(YEAR FROM dateends) AS year, EXTRACT(MONTH FROM dateends) AS month, EXTRACT(DAY FROM dateends) AS day, HOUR(dateends) AS hour, MINUTE(dateends) AS minute, items.* FROM items WHERE id = " . $validid . ";";
	$dataresult = mysql_query($datasql);
	$datanumrows = mysql_num_rows($dataresult);
	
	if($datanumrows == 0) {
		header("Location: " . $config_basedir);
	}
	else {
		$datarow = mysql_fetch_assoc($dataresult);
?>
		<h2>Edit an item</h2>
		<p>
		<?php
			switch($_GET['error']) {
				case "date":
					echo "<strong>Invalid date - please choose another!</strong>";
				break;
			}
		?>
		</p>	
		<form action="<?php echo pf_script_with_get($SCRIPT_NAME); ?>" method="post">
		<table>
		<?php
			$catsql = "SELECT * FROM categories ORDER BY category;";
			$catresult = mysql_query($catsql);
		?>
			<tr>
				<td>Category</td>
				<td>
				<select name="cat">
				<?php
				while($catrow = mysql_fetch_assoc($catresult)) {
					echo "<option value='" . $catrow['id'] . "'";
					
					if($catrow['id'] == $datarow['cat_id']) {
						echo "selected";
					}
					
					echo ">" . $catrow['category'] . "</option>";
				}
				?>
				</select>
				</td>
			</tr>
		<tr>
			<td>Item name</td>
			<td><input type="text" name="name" value="<?php echo $datarow['name']; ?>"></td>
		</tr>
		<tr>
			<td>Item description</td>
			<td><textarea name="description" rows="10" cols="50"><?php echo $datarow['description']; ?></textarea></td>
		</tr>
		<tr>
			<td>Ending date</td>
			<td>
			<table>
				<tr>
					<td>Day</td>
					<td>Month</td>
					<td>Year</td>
					<td>Hour</td>
					<td>Minute</td>
				</tr>
				<tr>
					<td>
					<select name="day">
					<?php
						for($i=1;$i<=31;$i++) {
							echo "<option";
							
							if($datarow['day'] == $i) {
								echo " selected";
							}
							
							echo ">" . $i . "</option>";
						}
					?>
					</select>
					</td>
					<td>
					<select name="month">
					<?php
						for($i=1;$i<=12;$i++) {
							echo "<option";

							if($datarow['month'] == $i) {
								echo " selected";
							}
							
							echo ">" . $i . "</option>";
						}
					?>
					</select>
					</td>
					<td>
					<select name="year">
					<?php
						for($i=2005;$i<=2008;$i++) {
							echo "<option";

							if($datarow['year'] == $i) {
								echo " selected";
							}

							echo ">" . $i . "</option>";
						}
					?>
					</select>
					</td>
					<td>
					<select name="hour">
					<?php
						for($i=0;$i<=23;$i++) {
							echo "<option";

							if($datarow['hour'] == $i) {
								echo " selected";
							}

							echo ">" . sprintf("%02d",$i) . "</option>";
						}
					?>
					</select>
					</td>
					<td>
					<select name="minute">
					<?php
						for($i=0;$i<=60;$i++) {
							echo "<option";

							if($datarow['minute'] == $i) {
								echo " selected";
							}

							echo ">" . sprintf("%02d",$i)  . "</option>";
						}
					?>
					</select>
					</td>
				</tr>
			</table>		
			</td>
		</tr>
		<tr>
			<td>Price</td>
			<td><?php echo $config_currency; ?><input type="text" name="price" value="<?php echo $datarow['startingprice']; ?>"></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" name="submit" value="Post!"></td>
		</tr>
		</table>
		</form>
<?php
	}
}

require("footer.php");

?>
