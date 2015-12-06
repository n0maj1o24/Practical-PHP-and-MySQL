<?php
	session_start();
	require("db.php");

	$statussql = "SELECT status FROM orders WHERE id = " . $_SESSION['SESS_ORDERNUM'];
//	$statusres = mysql_query($statussql);
//	$statusrow = mysql_fetch_assoc($statusres);
	$statusres = $db->query($statussql);
	$statusrow = $statusres->fetchAll(PDO::FETCH_ASSOC);
	$status = $statusrow[0]['status'];

	if($status == 1) {	
		header("Location: " . $config_basedir . "checkout-pay.php");
	}

	if($status >= 2) {	
		header("Location: " . $config_basedir);
	}

	if($_POST['submit'])
	{
		if($_SESSION['SESS_LOGGEDIN'])
		{
			if($_POST['addselecBox'] == 2)
			{
				if(empty($_POST['forenameBox']) ||
					empty($_POST['surnameBox']) ||
					empty($_POST['add1Box']) ||
					empty($_POST['add2Box']) ||
					empty($_POST['add3Box']) ||
					empty($_POST['postcodeBox']) ||
					empty($_POST['phoneBox']) ||
					empty($_POST['emailBox']))
				{
					header("Location: " . $basedir . "checkout-address.php?error=1");
					exit;
				}
			
				$addsql = "INSERT INTO delivery_addresses(forename, surname, add1, add2, add3, postcode, phone, email)
							VALUES('"
							. strip_tags(addslashes($_POST['forenameBox'])) . "', '"
							. strip_tags(addslashes($_POST['surnameBox'])) . "', '"
							. strip_tags(addslashes($_POST['add1Box'])) . "', '"
							. strip_tags(addslashes($_POST['add2Box'])) . "', '"
							. strip_tags(addslashes($_POST['add3Box'])) . "', '"
							. strip_tags(addslashes($_POST['postcodeBox'])) . "', '"
							. strip_tags(addslashes($_POST['phoneBox'])) . "', '"
							. strip_tags(addslashes($_POST['emailBox'])) . "')";
				
				//mysql_query($addsql);
				$db->query($addsql);

				//$setaddsql = "UPDATE orders SET delivery_add_id = " . mysql_insert_id() . ", status = 1 WHERE id = " . $_SESSION['SESS_ORDERNUM'];
				$setaddsql = "UPDATE orders SET delivery_add_id = " . $db->lastInsertId() . ", status = 1 WHERE id = " . $_SESSION['SESS_ORDERNUM'];

				//mysql_query($setaddsql);
				$db->query($setaddsql);

				header("Location: " . $config_basedir . "checkout-pay.php");
			}
			else
			{
				$custsql = "UPDATE orders SET delivery_add_id = 0, status = 1 WHERE id = " . $_SESSION['SESS_ORDERNUM'];
				//mysql_query($custsql);
				$db->query($custsql);
				header("Location: " . $config_basedir . "checkout-pay.php");
			}
		}
		else
		{
			if(empty($_POST['forenameBox']) ||
				empty($_POST['surnameBox']) ||
				empty($_POST['add1Box']) ||
				empty($_POST['add2Box']) ||
				empty($_POST['add3Box']) ||
				empty($_POST['postcodeBox']) ||
				empty($_POST['phoneBox']) ||
				empty($_POST['emailBox']))
			{
				header("Location: " . "checkout-address.php?error=1");
				exit;
			}

			$addsql = "INSERT INTO delivery_addresses(forename, surname, add1, add2, add3, postcode, phone, email)
					VALUES('"
					. $_POST['forenameBox'] . "', '"
					. $_POST['surnameBox'] . "', '"
					. $_POST['add1Box'] . "', '"
					. $_POST['add2Box'] . "', '"
					. $_POST['add3Box'] . "', '"
					. $_POST['postcodeBox'] . "', '"
					. $_POST['phoneBox'] . "', '"
					. $_POST['emailBox'] . "')";
		
				//mysql_query($addsql);
				$db->query($addsql);

			$setaddsql = "UPDATE orders SET delivery_add_id = " . $db->lastInsertId() . ", status = 1 WHERE session = '" . session_id() . "'";
			//mysql_query($setaddsql);
			$db->query($setaddsql);

			header("Location: " . $config_basedir . "checkout-pay.php");		
		}
	}
	else
	{
	
	require("header.php");
	echo "<h1>Add a delivery address</h1>";
	
	if(isset($_GET['error']) == TRUE) {
		echo "<strong>Please fill in the missing information from the form</strong>";
	}
	
	echo "<form action='" . $_SERVER['SCRIPT_NAME'] . "' method='POST'>";
	
	if($_SESSION['SESS_LOGGEDIN'])
	{
	?>
	<input type="radio" name="addselecBox" value="1" checked>Use the address from my account</input><br>
	<input type="radio" name="addselecBox" value="2">Use the address below:</input>
	<?php
	}
	
	?>
		<table>
		<tr>
			<td>Forename</td>
			<td><input type="text" name="forenameBox"></td>
		</tr>
		<tr>
			<td>Surname</td>
			<td><input type="text" name="surnameBox"></td>
		</tr>
		<tr>
			<td>House Number, Street</td>
			<td><input type="text" name="add1Box"></td>
		</tr>
		<tr>
			<td>Town/City</td>
			<td><input type="text" name="add2Box"></td>
		</tr>
		<tr>
			<td>County</td>
			<td><input type="text" name="add3Box"></td>
		</tr>
		<tr>
			<td>Postcode</td>
			<td><input type="text" name="postcodeBox"></td>
		</tr>
		<tr>
			<td>Phone</td>
			<td><input type="text" name="phoneBox"></td>
		</tr>
		<tr>
			<td>Email</td>
			<td><input type="text" name="emailBox"></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" name="submit" value="Add Address (press only once)"></td>
		</tr>
		</table>
	</form>
	
<?php
	}		
	require("footer.php");
?>
	
