<?php

session_start();

require("config.php");
require("functions.php");

//$db = mysql_connect($dbhost, $dbuser, $dbpassword);
//mysql_select_db($dbdatabase, $db);
try
{
	$db = new PDO("mysql:host=$dbhost;dbname=$dbdatabase",$dbuser,$dbpassword);
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
	echo 'database connection error: '.$e->getMessage();
}


if(isset($_SESSION['USERNAME']) == FALSE) {
	header("Location: " . $config_basedir . "/login.php?ref=newitem");
}

if($_POST['submit']) {
	$validdate = checkdate($_POST['month'], $_POST['day'], $_POST['year']);

	if($validdate == TRUE) {
		$concatdate = $_POST['year']
			. "-" . sprintf("%02d", $_POST['month'])
			. "-" . sprintf("%02d", $_POST['day'])
			. " " . $_POST['hour']
			. ":" . $_POST['minute']
			. ":00";
			
		$itemsql = "INSERT INTO items(user_id, cat_id, name, startingprice, description, dateends) VALUES("
			. $_SESSION['USERID']
			. ", " . $_POST['cat']
			. ", '" . addslashes($_POST['name'])
			. "', " . addslashes($_POST['price'])
			. ", '" . addslashes($_POST['description'])
			. "', '" . $concatdate
			. "');";
	
//		mysql_query($itemsql);
//		$itemid = mysql_insert_id();
		$db->query($itemsql);
		$itemid = $db->lastInsertId();
	
		header("Location: " . $config_basedir . "/addimages.php?id=" . $itemid);
	}
	else {
		header("Location: " . $config_basedir . "/newitem.php?error=date");		
	}
}
else {
	require("header.php");
?>
	<h1>Add a new item</h1>
	<strong>Step 1</strong> - Add your item details.
	<p>
	<?php
		switch($_GET['error']) {
			case "date":
				echo "<strong>Invalid date - please choose another!</strong>";
			break;
		}
	?>
	</p>	
	<form action="<?php echo pf_script_with_get($_SERVER['SCRIPT_NAME']); ?>" method="post">
	<table>
	<?php
		$catsql = "SELECT * FROM categories ORDER BY category;";
		//$catresult = mysql_query($catsql);
		$catresult=$db->query($catsql);
	?>
		<tr>
			<td>Category</td>
			<td>
			<select name="cat">
			<?php
			//while($catrow = mysql_fetch_assoc($catresult)) {
			foreach($catresult as $catrow){
				echo "<option value='" . $catrow['id'] . "'>" . $catrow['category'] . "</option>";
			}
			?>
			</select>
			</td>
		</tr>
	<tr>
		<td>Item name</td>
		<td><input type="text" name="name"></td>
	</tr>
	<tr>
		<td>Item description</td>
		<td><textarea name="description" rows="10" cols="50"></textarea></td>
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
						echo "<option>" . $i . "</option>";
					}
				?>
				</select>
				</td>
				<td>
				<select name="month">
				<?php
					for($i=1;$i<=12;$i++) {
						echo "<option>" . $i . "</option>";
					}
				?>
				</select>
				</td>
				<td>
				<select name="year">
				<?php
					for($i=2005;$i<=2008;$i++) {
						echo "<option>" . $i . "</option>";
					}
				?>
				</select>
				</td>
				<td>
				<select name="hour">
				<?php
					for($i=0;$i<=23;$i++) {
						echo "<option>" . sprintf("%02d",$i) . "</option>";
					}
				?>
				</select>
				</td>
				<td>
				<select name="minute">
				<?php
					for($i=0;$i<=60;$i++) {
						echo "<option>" . sprintf("%02d",$i)  . "</option>";
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
		<td><?php echo $config_currency; ?><input type="text" name="price"></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" name="submit" value="Post!"></td>
	</tr>
	</table>
	</form>

<?php
}

require("footer.php");

?>
