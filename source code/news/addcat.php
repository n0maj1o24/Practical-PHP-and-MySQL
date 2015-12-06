<?php
session_start();
require("config.php");
require("functions.php");
require("db.php");
require_once 'HTML/QuickForm.php';

if($_SESSION['SESS_USERLEVEL'] != 10) {
	header("Location:" . $config_basedir);
}

	$form = new HTML_QuickForm('catform');
	
	$catsql = "SELECT id, category FROM categories WHERE parent = 1 ORDER BY category;";
//	$catres = mysql_query($catsql);
	$catres = $db->query($catsql);
	
	$catarr[0] = "-- No Parent --";
	
//	while($catrow = mysql_fetch_assoc($catres)) {
	foreach($catres as $catrow){
		$catarr[$catrow['id']] = $catrow['category'];
	}
	
	$s =& $form->createElement('select','cat_id','Parent Category ');
	$s->loadArray($catarr,'cat');
	
	$form->addElement($s);
	$form->addElement('text', 'category', 'Category', array('size' => 20, 'maxlength' => 100));
	$form->addElement('submit', null, 'Add Story!');
	
	$form->applyFilter('name', 'trim');
	$form->addRule('category', 'Please enter a category', 'required', null, 'client');

	if ($form->validate()) {
		$form->freeze();
		$form->process("process_data", false);
		header("Location: " . $config_basedir);
	}
	else {
		require("header.php");
		echo "<h1>Add a category</h1>";
		echo "<p>Select the parent category that the new category is part of. If you want to create a new parent category, use the <tt>-- No Parent --</tt> option.</p>";
		
		$form->display();
	}
	

function process_data ($values) {	
	require("db.php");
	global $db;
	if($values['cat_id'] == 0) {
		$sql = "INSERT INTO categories(category, parent) VALUES('" . pf_fix_slashes($values['category']) . "', 1);";
//		$result = mysql_query($sql);
		$result = $db->query($sql);
	}
	else {
		$sql = "INSERT INTO categories(category, parent) VALUES('" . pf_fix_slashes($values['category']) . "', 0);";
//		$result = mysql_query($sql);
//		$insertid = mysql_insert_id();
		$result = $db->query($sql);
		$insertid = $db->lastInsertId();
		
		$relatesql = "INSERT INTO cat_relate(parent_id, child_id) VALUES(" . $values['cat_id'] . ", " . $insertid . ");";
//		$relateresult = mysql_query($relatesql);
		$relatesql = $db->query($relatesql);
	}
}
	
require("footer.php");

?>
