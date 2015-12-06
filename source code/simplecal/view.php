<?php

session_start();

require("config.php");

if(isset($_SESSION['LOGGEDIN']) == FALSE) {
	header("Location: " . $config_basedir);
}

function short_event($name) {
	$final = "";
	$final = (substr($name, 0, 12) . "...");

	return $final;
}

require("header.php");

if($_GET['error']) {
	echo "<script>newEvent('" . $_GET['eventdate'] . "', 1)</script>";
}

$cols = 7;
$weekday = date("w", mktime(0, 0, 0, $month, 1, $year));

$numrows = ceil(($numdays + $weekday) / $cols);

echo "<br />";
echo "<table class='cal' cellspacing=0 cellpadding=5 border=1>";
echo "<tr>";
echo "<th class='cal'>Sunday</th>";
echo "<th class='cal'>Monday</th>";
echo "<th class='cal'>Tuesday</th>";
echo "<th class='cal'>Wednesday</th>";
echo "<th class='cal'>Thursday</th>";
echo "<th class='cal'>Friday</th>";
echo "<th class='cal'>Saturday</th>";
echo "</tr>";
$counter = 1;
$newcounter = 1;

echo "<tr>";

$daysleft = 6 - $weekday--;

for($f=0;$f<=$weekday;$f++) {
	echo "<td class='cal_date' width='110' height='10'>";
	echo "</td>";		
}

for($f=0;$f<=$daysleft;$f++) {
	echo "<td class='cal_date' width='100' height='10'>";

	$display = date("jS", mktime(0, 0, 0, $month, $counter, $year));
	
	$todayday = date("d");
	$todaymonth = date("n");
	$todayyear = date("Y");
	
	if($counter == $todayday AND $month == $todaymonth AND $year == $todayyear) {
		echo "<strong>TODAY " . $display . "</strong>";
	}
	else {				
		echo $display;	
	}

	echo "</td>";		
	$counter++;
}

echo "</tr>";

echo "<tr>";

for($f=0;$f<=$weekday;$f++) {
	echo "<td class='cal' width='110' height='10'>";
	if($newcounter <= $numdays) {
	}

	echo "</td>";		
}

for($f=0;$f<=$daysleft;$f++) {
	echo "<td class='cal' width='110' height='40'>";
	
			$date = $year . "-" . $month . "-" . $newcounter;
			echo "<a class='cal' href='#' onclick=\"newEvent('" . $date . "')\"></a>";
			
			$eventsql = "SELECT * FROM events WHERE date = '" . $date . "';"; 
			//$eventres = mysql_query($eventsql);
			$eventres = $db->query($eventsql);

			//while($eventrow = mysql_fetch_assoc($eventres)) {
			foreach($eventres as $eventrow){
				echo "<a class='deleteevent' href='delete.php?id=" . $eventrow['id'] . "' onclick=\"return confirm('Are you sure you want to delete `" . $eventrow['name'] ."`?');\">X</a>";	
				echo "<a class='event' href='#' onclick='getEvent(" . $eventrow['id'] . ")'>" . short_event($eventrow['name']) . "</a><br />";
			}
	echo "</td>";		
	$newcounter++;
}

echo "</tr>";

for($i=1;$i<=($numrows-1);$i++) {

	echo "<tr>";
	
	for($a=0;$a<=($cols-1);$a++) {
	
			echo "<td class='cal_date' width='110' height='10'>";

			$display = date("jS", mktime(0, 0, 0, $month, $counter, $year));
			
			$todayday = date("d");
			$todaymonth = date("n");
			$todayyear = date("Y");
			
			if($counter == $todayday AND $month == $todaymonth AND $year == $todayyear) {
				echo "<strong>TODAY " . $display . "</strong>";
			}
			else {				
				echo $display;	
			}
			echo "</td>";

		$counter++;
	}
	
	echo "</tr>";

	echo "<tr>";
	
	for($aa=1;$aa<=$cols;$aa++) {
		echo "<td class='cal' width='110' height='40'>";
		if($newcounter <= $numdays) {
			$date = $year . "-" . $month . "-" . $newcounter;
			echo "<a class='cal' href='#' onclick=\"newEvent('" . $date . "')\"></a>";
			
			$eventsql = "SELECT * FROM events WHERE date = '" . $date . "';"; 
			//$eventres = mysql_query($eventsql);
			$eventres = $db->query($eventsql);

			//while($eventrow = mysql_fetch_assoc($eventres)) {
			foreach($eventres as $eventrow){
				echo "<a class='deleteevent' href='delete.php?id=" . $eventrow['id'] . "' onclick=\"return confirm('Are you sure you want to delete `" . $eventrow['name'] ."`?');\">X</a>";	
				echo "<a class='event' href='#' onclick='getEvent(" . $eventrow['id'] . ")'>" . short_event($eventrow['name']) . "</a><br />";
			}
			
		}
		echo "</td>";
		$newcounter++;
	}

	echo "</tr>";

}

echo "</table>";

require("footer.php");

?>