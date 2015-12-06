<?php

require("config.php");
require("header.php");

$itemssql = "SELECT users.username, users.email, items.id, items.name FROM items, users WHERE dateends < NOW() AND items.user_id = users.id AND endnotified = 0;";
//$itemsresult = mysql_query($itemssql);
$itemsresult = $db->query($itemssql);

//while($itemsrow = mysql_fetch_assoc($itemsresult)) {
foreach($itemsresult as $itemsrow){
	$bidssql = "SELECT bids.amount, users.username, users.email FROM bids, users WHERE bids.user_id = users.id AND item_id = " . $itemsrow['id'] . " ORDER BY amount DESC LIMIT 1;";
//	$bidsresult = mysql_query($bidssql);
//	$bidsnumrows = mysql_num_rows($bidsresult);
	$bidsresult = $db->query($bidssql);
	$bidsnumrows = $bidsresult->rowCount();

	$own_owner = $itemsrow['username'];	
	$own_email = $itemsrow['email'];	
	$own_name = $itemsrow['name'];	
	
	if($bidsnumrows == 0) {
echo "ping";
$owner_body=<<<_OWNER_

Hi $own_owner,

Sorry, but your item '$own_name', did not have any bids placed with it.

_OWNER_;

		mail($own_email, "Your item '" . $own_name . "' did not sell", $owner_body);
	}
	else {
	echo "item with bids" . $itemsrow['id'];
		//$bidsrow = mysql_fetch_assoc($bidsresult);
		$bidsrow = $bidsresult->fetchAll(PDO::FETCH_ASSOC);

		$own_highestbid = $bidsrow[0]['amount'];
	
		$win_winner = $bidsrow[0]['username'];
		$win_email = $bidsrow[0]['email'];
		
		
$owner_body=<<<_OWNER_

Hi $own_owner,

Congratulations! The auction for your item '$own_name', has completed with a winning bid
of $config_currency$own_highestbid bidded by $win_winner!

Bid details:

Item: $own_name
Amount: $config_currency$own_highestbid
Winning bidder: $win_winner ($win_email)

It is recommended that you contact the winning bidder within 3 days.

_OWNER_;
		
		$winner_body=<<<_WINNER_
		
Hi $win_winner,

Congratulations! Your bid of $config_currency$own_highestbid for
the item '$own_name' was the highest bid!

Bid details:

Item: $own_name
Amount: $config_currency$own_highestbid
Owner: $own_owner ($own_email)

It is recommended that you contact the owner of the item within 3 days.

_WINNER_;
		
		mail($own_email, "Your item '" . $own_name . "' has sold", $owner_body);
		mail($win_email, "You won item '" . $own_name . "'!", $winner_body);
	}
	
	$updsql = "UPDATE items SET endnotified = 1 WHERE id = " . $itemsrow['id'];
	echo $updsql;
	//mysql_query($updsql);
	$db->query($updsql);
}

require("footer.php");

?>