<?php

require("header.php");

if(isset($_GET['date']) == TRUE) {
	$explodeddate = explode("-", $_GET['date']);
	$month = $explodeddate[0];
	$year = $explodeddate[1];
	$numdays = date("t", mktime(0, 0, 0, $month, 1, $year));
}
else {
	$month = date("n", mktime());
	$numdays = date("t", mktime());
	$year = date("Y", mktime());
}

$displaydate = date("F Y", mktime(0, 0, 0, $month, 1, $year));

if($month == 1) {
	$prevdate = "12-" .  ($year-1);
}
else {
	$prevdate = ($month-1) . "-" .  $year;
}

if($month == 12) {
	$nextdate = "1-" .  ($year+1);
}
else {
	$nextdate = ($month+1) . "-" . $year;
}

$cols = 5;
$numrows = ceil($numdays / $cols);
$counter = 1;
$newcounter = 1;

echo "<p>";
echo "<a href='$SCRIPT_NAME?date=" . $prevdate . "'><--</a> ";
echo $displaydate;
echo " <a href='$SCRIPT_NAME?date=" . $nextdate . "'>--></a> ";

echo "<table border=1>";

for($i=1;$i<=$numrows;$i++) {
	echo "<tr>";

	for($a=1;$a<=$cols;$a++) {
		echo "<td width='100' height='10'>";
		if($counter<=$numdays) {
			$display = date("D jS", mktime(0, 0, 0, $month, $counter, $year));
			echo $display;
		}
		echo "</td>";
		$counter++;
	}
	
	echo "</tr>";
	echo "<tr>";
	
	for($aa=1;$aa<=$cols;$aa++) {
	
		echo "<td width='100' height='100'>";
		if($newcounter <= $numdays) {
			echo "";
		}
		echo "</td>";
		$newcounter++;
	}

	echo "</tr>";

}

echo "</table>";




require("footer.php");

?>