<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require "../../../wp-load.php";

function DeleteTask($InfoId){
	global $wpdb;
	$sql = "DELETE FROM DrawHexTableInfo WHERE id=".$InfoId.";";
	$wpdb->get_results($sql);
	$sql = "SELECT * FROM DrawHexTableInfo;";
	$MyResult = $wpdb->get_results($sql);
	if(count($MyResult)>0){
		echo "<script>";
		echo '$("#PutHexagonSvgHere").load("/wp-content/plugins/DrawHex/DrawHex.php?RefreshSvg=refreshit");';
		echo "</script>";
	}else{
		echo "<script>";
		echo '$("#PutHexagonSvgHere").load("/wp-content/plugins/DrawHex/Nothing.php");';
		echo "</script>";
	}
	
}
$taskId = 0;
if(isset($_GET["taskid"])){
	$taskId = $_GET["taskid"];
}
if($taskId>0){
	DeleteTask($taskId);
}

?>
