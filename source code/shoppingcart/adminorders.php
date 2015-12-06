<?php
	session_start();

	require("config.php");
	require("db.php");
	require("functions.php");
		
	if(isset($_SESSION['SESS_ADMINLOGGEDIN']) == FALSE) {
		header("Location: " . $config_basedir);
	}

	if(isset($_GET['func']) == TRUE) {

		if($_GET['func'] != "conf") {
			header("Location: " . $config_basedir);
		}
	
		$validid = pf_validate_number($_GET['id'], "redirect", $config_basedir);
	
		$funcsql = "UPDATE orders SET status = 10 WHERE id = " . $_GET['id'];
		//mysql_query($funcsql);
		$db->query($funcsql);

		header("Location: " . $config_basedir . "adminorders.php");
	}
	else {
		require("header.php");
		echo "<h1>Outstanding orders</h1>";
		$orderssql = "SELECT * FROM orders WHERE status = 2";
//		$ordersres = mysql_query($orderssql);
//		$numrows = mysql_num_rows($ordersres);

		$ordersres = $db->query($orderssql);
		$numrows = $ordersres->rowCount();
		
		if($numrows == 0)
		{
			echo "<strong>No orders</strong>";
		}
		else
		{				
			echo "<table cellspacing=10>";
		
			//while($row = mysql_fetch_assoc($ordersres))
			foreach($ordersres as $row)
			{
				echo "<tr>";
					echo "<td>[<a href='adminorderdetails.php?id=" . $row['id'] . "'>View</a>]</td>";
					echo "<td>" . date("D jS F Y g.iA", strtotime($row['date'])) . "</td>";
					echo "<td>";
					
					if($row['registered'] == 1)
					{
						echo "Registered Customer";
					}
					else
					{
						echo "Non-Registered Customer";
					}
					
					echo "</td>";
		
					echo "<td>&pound;" . sprintf('%.2f', $row['total']) . "</td>";
	
					echo "<td>";
					
					if($row['payment_type'] == 1)
					{
						echo "PayPal";
					}
					else
					{
						echo "Cheque";
					}
				
					echo "</td>";
			
					echo "<td><a href='adminorders.php?func=conf&id=" . $row['id'] . "'>Confirm Payment</a></td>";
				echo "</tr>";	
			}

			echo "</table>";
		}
	}

	require("footer.php");
?>
	
