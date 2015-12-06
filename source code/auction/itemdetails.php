<?php

session_start();

include("config.php");
include("functions.php");

//$db = mysql_connect($dbhost, $dbuser, $dbpassword);
//mysql_select_db($dbdatabase, $db);
try
{
	$db = new PDO("mysql:host=$dbhost;dbname=$dbdatabase",$dbuser,$dbpassword);
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
	echo 'database connection error: '.$e->getMessage();
}

$validid = pf_validate_number($_GET['id'], "redirect", $config_basedir);

if($_POST['submit']) {

	if(is_numeric($_POST['bid']) == FALSE) {
		header("Location: " . $config_basedir . "itemdetails.php?id=" . $validid . "&error=letter");
	}

	$theitemsql = "SELECT * FROM items WHERE id = " . $validid . ";";
//	$theitemresult = mysql_query($theitemsql);
//	$theitemrow = mysql_fetch_assoc($theitemresult);
	$theitemresult = $db->query($theitemsql);
	$theitemrow = $theitemresult->fetchAll(PDO::FETCH_ASSOC);

	$checkbidsql = "SELECT item_id, max(amount) AS highestbid, count(id) AS number_of_bids FROM bids WHERE item_id=" . $validid . " GROUP BY item_id;";
//	$checkbidresult = mysql_query($checkbidsql);
//	$checkbidnumrows = mysql_num_rows($checkbidresult);
	$checkbidresult = $db->query($checkbidsql);
	$checkbidnumrows = $checkbidresult->rowCount();


	if($checkbidnumrows == 0) {
		if($theitemrow['startingprice'] > $_POST['bid']) {
			header("Location: " . $config_basedir . "itemdetails.php?id=" . $validid . "&error=lowprice#bidbox");
		}
	}
	else {
		//$checkbidrow = mysql_fetch_assoc($checkbidresult);
		$checkbidrow = $checkbidresult->fetchAll(PDO::FETCH_ASSOC);
		
		if($checkbidrow[0]['highestbid'] > $_POST['bid']) {
			header("Location: " . $config_basedir . "itemdetails.php?id=" . $validid . "&error=lowprice#bidbox");
		}
	}

	$inssql = "INSERT INTO bids(item_id, amount, user_id) VALUES("
		. $validid
		. ", " . $_POST['bid']
		. ", " . $_SESSION['USERID']
		. ");";
	//mysql_query($inssql);
	$db->query($inssql);
	header("Location: " . $config_basedir . "itemdetails.php?id=" . $validid);
}
else {

	require("header.php");
	
	$itemsql = "SELECT UNIX_TIMESTAMP(dateends) AS dateepoch, items.* FROM items WHERE id = " . $validid . ";";
	//$itemresult = mysql_query($itemsql);
	$itemresult = $db->query($itemsql);
	//$itemrow = mysql_fetch_assoc($itemresult);
	$itemrow = $itemresult->fetchAll(PDO::FETCH_ASSOC);
	$nowepoch = mktime();
	$rowepoch = $itemrow[0]['dateepoch'];
	
	if($rowepoch > $nowepoch) {
		$VALIDAUCTION = 1;
	}
	
	echo "<h1>" . $itemrow[0]['name'] . "</h1>";
	
	$imagesql = "SELECT * FROM images WHERE item_id = " . $validid . ";";
//	$imageresult = mysql_query($imagesql);
//	$imagenumrows = mysql_num_rows($imageresult);
	$imageresult = $db->query($imagesql);
	$imagenumrows = $imageresult->rowCount();
	
	$bidsql = "SELECT item_id, MAX(amount) AS highestbid, COUNT(id) AS number_of_bids FROM bids WHERE item_id=" . $validid . " GROUP BY item_id;";
//	$bidresult = mysql_query($bidsql);
//	$bidnumrows = mysql_num_rows($bidresult);
	$bidresult = $db->query($bidsql);
	$bidnumrows = $bidresult->rowCount();
	
	echo "<p>";
	
	if($bidnumrows == 0) {
		echo "<strong>This item has had no bids</strong> - <strong>Starting Price</strong>: " . $config_currency . sprintf('%.2f', $itemrow[0]['startingprice']);
	}
	else {
		//$bidrow = mysql_fetch_assoc($bidresult);
		$bidrow=$bidresult->fetchAll(PDO::FETCH_ASSOC);
		echo "<strong>Number Of Bids</strong>: " . $bidrow[0]['number_of_bids'] . " - <strong>Current Price</strong>: " . $config_currency . sprintf('%.2f', $bidrow[0]['highestbid']);
	}
	
	echo " - <strong>Auction ends</strong>: " . date("D jS F Y g.iA", $rowepoch);
	
	echo "</p>";
	
	if($imagenumrows == 0) {
		echo "No images.";
	}
	else {
		//while($imagerow = mysql_fetch_assoc($imageresult)) {
		foreach($imageresult as $imagerow){
			echo "<img src='./images/" . $imagerow['name'] ."' width='200'>";
		}
	}
	
	echo "<p>" . nl2br($itemrow['description']) . "</p>";
	
	echo "<a name='bidbox'></a>";
	echo "<h2>Bid for this item</h2>";
	
	if(isset($_SESSION['USERNAME']) == FALSE) {
		echo "To bid, you need to log in. Login <a href='login.php?id=" . $validid . "&ref=addbid'>here</a>.";
	}
	else {
		if($VALIDAUCTION == 1) {
			echo "Enter the bid amount into the box below.";
			echo "<p>";
		
			switch($_GET['error']) {
				case "lowprice":
					echo "The bid entered is too low. Please enter another price.";
				break;
		
				case "letter":
					echo "The value entered is not a number.";
				break;
			}

?>
		
			<form action="<?php echo pf_script_with_get($_SERVER['SCRIPT_NAME']); ?>" method="post">
			<table>
			<tr>
				<td><input type="text" name="bid"></td>
				<td><input type="submit" name="submit" value="Bid!"></td>
			</tr>
			</table>
			</form>

<?php
		}
		else {
			echo "This auction has now ended.";
		}
		
		$historysql = "SELECT bids.amount, users.username FROM bids, users WHERE bids.user_id = users.id AND item_id = " . $validid . " ORDER BY amount DESC";
//		$historyresult = mysql_query($historysql);
//		$historynumrows = mysql_num_rows($historyresult);
		$historyresult = $db->query($historysql);
		$historynumrows = $historyresult->rowCount();
	
		if($historynumrows >= 1) {
			echo "<h2>Bid History</h2>";
			echo "<ul>";
	
			//while($historyrow = mysql_fetch_assoc($historyresult)) {
			foreach($historyresult as $historyrow){
				echo "<li>" . $historyrow['username'] . " - " . $config_currency . sprintf('%.2f', $historyrow['amount']) . "</li>";
			}
	
		echo "</ul>";
		}
	}
}

require("footer.php");

?>