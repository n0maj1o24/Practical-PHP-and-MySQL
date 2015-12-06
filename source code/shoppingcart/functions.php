<?php
//require_once('db.php');
function pf_validate_number($value, $function, $redirect) {
	if(isset($value) == TRUE) {
		if(is_numeric($value) == FALSE) {
			$error = 1;
		}
	
		if($error == 1) {
			header("Location: " . $redirect);
		}
		else {
			$final = $value;
		}
	}
	else {
		if($function == 'redirect') {
			header("Location: " . $redirect);
		}
		
		if($function == "value") {
			$final = 0;
		}
	}
	
	return $final;
}

function showcart($db)
{
	global $config_basedir;
	static $total=0;
	if($_SESSION['SESS_ORDERNUM'])
	{
		if($_SESSION['SESS_LOGGEDIN'])
		{
			$custsql = "SELECT id, status from orders WHERE customer_id = " . $_SESSION['SESS_USERID'] . " AND status < 2;"; 
//			$custres = mysql_query($custsql);
//			$custrow = mysql_fetch_assoc($custres);
			//$oPDO = new PDO("mysql:host=$dbhost;dbname=$dbdatabase",$dbuser,$dbpassword);
			$custres = $db->query($custsql);
			$custrow = $custres->fetchAll(PDO::FETCH_ASSOC);
			$itemssql = "SELECT products.*, orderitems.*, orderitems.id AS itemid FROM products, orderitems WHERE orderitems.product_id = products.id AND order_id = " . $custrow['id'];
//			$itemsres = mysql_query($itemssql);
//			$itemnumrows = mysql_num_rows($itemsres);
			$itemsres = $db->query($itemssql);
			$itemnumrows = $itemsres->rowCount();
		}
		else
		{
			$custsql = "SELECT id, status from orders WHERE session = '" . session_id() . "' AND status < 2;"; 
//			$custres = mysql_query($custsql);
//			$custrow = mysql_fetch_assoc($custres);
			//$oPDO = new PDO("mysql:host=$dbhost;dbname=$dbdatabase",$dbuser,$dbpassword);
			$custres = $db->query($custsql);
			$custrow = $custres->fetchAll(PDO::FETCH_ASSOC);

			$itemssql = "SELECT products.*, orderitems.*, orderitems.id AS itemid FROM products, orderitems WHERE orderitems.product_id = products.id AND order_id = " . $custrow[0]['id'];
//			$itemsres = mysql_query($itemssql);
//			$itemnumrows = mysql_num_rows($itemsres);
			$itemsres = $db->query($itemssql);
			$itemnumrows = $itemsres->rowCount();
		}	
	}
	else
	{
		$itemnumrows = 0;
	}		

	if($itemnumrows == 0)
	{
		echo "You have not added anything to your shopping cart yet.";
		
	}
	else
	{			
		echo "<table cellpadding='10'>";
		echo "<tr>";
			echo "<td></td>";
			echo "<td><strong>Item</strong></td>";
			echo "<td><strong>Quantity</strong></td>";
			echo "<td><strong>Unit Price</strong></td>";
			echo "<td><strong>Total Price</strong></td>";
			echo "<td></td>";
		echo "</tr>";
			
		//while($itemsrow = mysql_fetch_assoc($itemsres))
		foreach($itemsres as $itemsrow)
		{	
				$quantitytotal = $itemsrow['price'] * $itemsrow['quantity'];
		echo "<tr>";

				if(empty($itemsrow['image'])) {
					echo "<td><img src='./productimages/dummy.jpg' width='50' alt='" . $itemsrow['name'] . "'></td>";
				}
				else {
					echo "<td><img src='./productimages/" . $itemsrow['image'] . "' width='50' alt='" . $itemsrow['name'] . "'></td>";
				}
		
//				echo "<td><img src='./productimages/" . $itemsrow['image'] . ".jpg' alt='" . $itemsrow['name'] . "' width='50'></td>";
				echo "<td>" . $itemsrow['name'] . "</td>";
				echo "<td>" . $itemsrow['quantity'] . "</td>";
				echo "<td><strong>&pound;" . sprintf('%.2f', $itemsrow['price']) . "</strong></td>";
				echo "<td><strong>&pound;" . sprintf('%.2f', $quantitytotal) . "</strong></td>";
				echo "<td>[<a href='" . $config_basedir . "delete.php?id=" . $itemsrow['itemid'] . "'>X</a>]</td>";
				echo "</tr>";
			
			//$total = $total + $quantitytotal;
			$total+=$quantitytotal;
			$totalsql = "UPDATE orders SET total = " . $total . " WHERE id = " . $_SESSION['SESS_ORDERNUM']; 
			//$totalres = mysql_query($totalsql);
			$totalres = $db->query($totalsql);
		}						

		echo "<tr>";
			echo "<td></td>";
			echo "<td></td>";
			echo "<td></td>";
			echo "<td>TOTAL</td>";
			echo "<td><strong>&pound;" . sprintf('%.2f', $total) . "</strong></td>";
			echo "<td></td>";
	echo "</tr>";

	echo "</table>";

	}
}


?>