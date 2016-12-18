<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require "../../../wp-load.php";

function PrintTaskInfo(){
	global $wpdb;
	$taskid="";
	if(isset($_GET["taskid"])){
		$taskid .= $_GET["taskid"];
	}
	$myResult = $wpdb->get_results("SELECT * FROM DrawHexTableInfo WHERE id = ".$taskid.";");
	echo '<h1>Selected Task info:</h1>';
	foreach($myResult as $row){
		echo '<p>'.$row->title.'</p>';
		echo '<p>'.$row->description.'</p>';
	}
}
PrintTaskInfo();
?>
