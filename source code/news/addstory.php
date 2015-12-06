<?php
session_start();

require("config.php");
require("functions.php");
require("db.php");
require_once 'HTML/QuickForm.php';

if($_SESSION['SESS_USERLEVEL'] < 1) {
	header("Location:" . $config_basedir);
}

$form = new HTML_QuickForm('firstForm');

$catsql = "SELECT id, category FROM categories ORDER BY category;";
//$catres = mysql_query($catsql);
$catres = $db->query($catsql);

//while($catrow = mysql_fetch_assoc($catres)) {
foreach($catres as $catrow){
	$catarr[$catrow['id']] = $catrow['category'];
}

$s =& $form->createElement('select','cat_id','Category ');
$s->loadArray($catarr);

$form->addElement($s);
$form->addElement('text', 'subject', 'Subject', array('size' => 50, 'maxlength' => 255));
$form->addElement('textarea', 'body', 'Comment:', array('size' => 50, 'maxlength' => 255, 'rows' => 20, 'cols' => 80));
$form->addElement('submit', null, 'Add Story!');

$form->addRule('subject', 'Please enter a subject', 'required', null, 'client');
$form->addRule('body', 'Add some body text', 'required', null, 'client');

if($form->validate()) {
	$form->freeze();
	$form->process("process_data", false);
	
//	$insertid = mysql_insert_id();
	$insertid = $db->lastInsertId();
	
	header("Location: " . $config_basedir . "viewstory.php?id=" . $insertid);
}
else {
	require("header.php");
	echo "<h1>Add story</h1>";

	$form->display();
}

function process_data ($values,$db) {
//	global $db;
	$sql = "INSERT INTO stories(cat_id, poster_id, dateposted, subject, body) VALUES("
		. $values['cat_id']
		. ", " . $_SESSION['SESS_USERID']
		. ", NOW()"
		. ", '" . pf_fix_slashes($values['subject']) . "'"
		. ", '" . pf_fix_slashes($values['body'])
		. "');";
	$result = $db->query($sql);
//	$result = mysql_query($sql);

}

require("footer.php");

?>

