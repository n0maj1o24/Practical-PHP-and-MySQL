
<?php
	echo "<p><i>All content on this site is &copy; " . $config_sitename . "</i></p>"; 

	if($_SESSION['SESS_ADMINLOGGEDIN'] == 1)
	{
		echo "[<a href='" . $config_basedir . "adminorders.php'>admin</a>] [<a href='" . $config_basedir . "adminlogout.php'>admin logout</a>]";
	}
?>

</div>
	</div>
</body>
</html>
