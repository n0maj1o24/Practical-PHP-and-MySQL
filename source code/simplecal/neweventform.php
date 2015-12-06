<?php

	if($_GET['error'] == 1) {
		echo "<p><strong>There is an error in the form. Please correct it and re-submit.</strong></p>";
	}

?>
<h1>Add a new event</h1>
<form action="processnewevent.php?date=<?php echo $_GET['date']; ?>" method="POST">
<table>
<tr>
	<td>Date</td>
	<td>
	<?php echo "<strong>" . date("D jS F Y", strtotime($_GET['date'])) . "</strong>"; ?>
	<input type="hidden" name="date" value="<?php echo $_GET['date']; ?>">
	</td>
</tr>
<tr>
	<td>Name</td>
	<td><input type="text" name="name" size="15"></td>
</tr>
<tr>
	<td>Start Time</td>
	<td>
	<select name="starthour">
	<?php
		for($i=0;$i<=23;$i++) {
			echo "<option value=" . sprintf("%02d", $i) . ">" . sprintf("%02d", $i) . "</option>";
		}
	?>
	</select>

	<select name="startminute">
	<?php
		for($i=0;$i<=60;$i++) {
			echo "<option value=" . sprintf("%02d", $i) . ">" . sprintf("%02d", $i) . "</option>";
		}
	?>
	</select>

	</td>
</tr>
<tr>
	<td>End Time</td>
	<td>
	<select name="endhour">
	<?php
		for($i=0;$i<=23;$i++) {
			echo "<option value=" . sprintf("%02d", $i) . ">" . sprintf("%02d", $i) . "</option>";
		}
	?>
	</select>

	<select name="endminute">
	<?php
		for($i=0;$i<=60;$i++) {
			echo "<option value=" . sprintf("%02d", $i) . ">" . sprintf("%02d", $i) . "</option>";
		}
	?>
	</select>

	</td>
</tr>
<tr>
	<td>Description</td>
	<td><textarea cols="15" rows="10" name="description"></textarea></td>
</tr>
<tr>
	<td></td>
	<td><input type="submit" name="submit" value="Add Event"></td>
</tr>
</table>
</form>