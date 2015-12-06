<?php
	session_start();
	require("config.php");
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">	
<head>
	<title><?php echo $config_sitename; ?></title>
	<link href="stylesheet.css" rel="stylesheet">
</head>
<body>
	<div id="header">
	<h1>Read All About It</h1>
	</div>
	<div id="menu">
		<a href="<?php echo $config_basedir; ?>">Home</a>
	</div>
	<div id="container">
		<div id="bar">
			<?php
				
				require("bar.php");
			?>
		</div>

		<div id="main">
