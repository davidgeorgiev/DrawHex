<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require "../../../wp-load.php";

function NewTaskInsert(){
	global $wpdb;
	$taskname = "";
	$description = "";
	$groupid = "";
	if(isset($_GET["groupid"])){
		$groupid = $_GET["groupid"];
	}
	if(isset($_GET["taskname"])){
		$taskname = $_GET["taskname"];
	}
	if(isset($_GET["description"])){
		$description = $_GET["description"];
	}
	$sql = "INSERT INTO DrawHexTableInfo(groupId,title,description,percentOfDone) VALUES(".$groupid.",'".$taskname."','".$description."',0);";
	$wpdb->get_results($sql);
	//echo $sql;
	echo "<script>";
	echo '$("#PutHexagonSvgHere").load("/wp-content/plugins/DrawHex/DrawHex.php?RefreshSvg=refreshit");';
	echo "</script>";
}
NewTaskInsert();
?>
