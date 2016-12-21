<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require "../../../wp-load.php";

function IncrementPercentOfDone($InfoId){
	global $wpdb;
	$sql = "SELECT percentOfDone FROM DrawHexTableInfo WHERE id=".$InfoId.";";
	$MyGroups = $wpdb->get_results($sql);
	$Percent = $MyGroups[0]->percentOfDone;
	if($Percent>=6){
		$Percent = -1;
	}
	$Percent+=1;
	$sql = "UPDATE DrawHexTableInfo SET percentOfDone=".$Percent." WHERE id=".$InfoId.";";
	$MyGroups = $wpdb->get_results($sql);
	echo $Percent.'/6 task done';
	echo "<script>";
	echo '$("#PutHexagonSvgHere").load("/wp-content/plugins/DrawHex/DrawHex.php?RefreshSvg=refreshit");';
	echo "</script>";
	return $Percent;
}
$taskId = 0;
if(isset($_GET["taskid"])){
	$taskId = $_GET["taskid"];
}
if($taskId>0){
	IncrementPercentOfDone($taskId);
}

?>
