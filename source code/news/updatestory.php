<?php
session_start();

/*if(!$_SESSION['POSTERUSERNAME']) {
	header("Location: index.php");
}*/

if(!$_GET['id']) {
//	header("Location: index.php");
}
else {
	$storyid = $_GET['id'];
}

require("header.php");
// Load the main class
require_once 'HTML/QuickForm.php';


echo "<h1>Update story</h1>";

// Instantiate the HTML_QuickForm object
$form = new HTML_QuickForm('firstForm');

// Set defaults for the form elements
//$form->setDefaults(array('name' => 'Joe User'));

// Add some elements to the form

$catsql = "SELECT id, category FROM news_cat ORDER BY category;";
//$catres = mysql_query($catsql);
$catres = $db->query($catsql);
//while($catrow = mysql_fetch_assoc($catres)) {
foreach($catres as $catrow){
	$catarr[$catrow['id']] = $catrow['category'];
}

$s =& $form->createElement('select','cat_id','Category ');
$s->loadArray($catarr,'cat');

$form->addElement($s);
$form->addElement('hidden', 'story_id');
$form->addElement('text', 'subject', 'Subject', array('size' => 50, 'maxlength' => 255));
$form->addElement('textarea', 'body', 'Password', array('size' => 50, 'maxlength' => 1000));
$form->addElement('submit', null, 'Update Story!');

// Set defaults for the form elements

$storysql = "SELECT * FROM story WHERE id = " . $storyid . ";";
//$storyres = mysql_query($storysql);
//$storyrow = mysql_fetch_assoc($storyres);
$storyres = $db->query($storysql);
$storyrow = $storyres->fetchAll(PDO::FETCH_ASSOC);

$form->setDefaults(array('story_id' => $storyid));
$form->setDefaults(array('cat_id' => $storyrow[0]['cat_id']));
$form->setDefaults(array('subject' => $storyrow[0]['subject']));
$form->setDefaults(array('body' => $storyrow[0]['body']));

// Define filters and validation rules
$form->applyFilter('subject', 'trim');
$form->addRule('subject', 'Please enter your username', 'required', null, 'client');

$form->applyFilter('body', 'trim');
$form->addRule('body', 'Please enter your username', 'required', null, 'client');


// if form validates, freeze the data and process it
if ($form->validate()) {
    $form->freeze();
    $form->process("process_data", false);
}
else {
    $form->display();
}

// process form data ($values = name/value pairs
function process_data ($values) {
	global $db;
	$sql = "UPDATE story SET cat_id = ". $values['cat_id']
		. ", poster_id = " . $_SESSION['POSTERID']
		. ", subject = '" . $values['subject'] . "'"
		. ", body = '" . $values['body']
		. "' WHERE id = " . $values['story_id'] . ";";
		
//	mysql_query($sql);
	$db->query($sql);
	echo $sql;
}

require("footer.php");

?>
