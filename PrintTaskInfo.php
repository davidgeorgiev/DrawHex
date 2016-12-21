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
	$myResult2 = $wpdb->get_results("SELECT title,color FROM DrawHexTableGroups WHERE id = ".$myResult[0]->groupId.";");
	echo '<h1>Selected Task info:</h1>';
	foreach($myResult as $row){
		echo '<h3>'.$row->title.'</h3>';
		echo '<p style="padding:10px;color:white;background-color:#'.$myResult2[0]->color.';">&nbsp;&nbsp;Group: '.$myResult2[0]->title.'</p>';
		echo '<p>'.$row->description.'</p>';
		echo '<p>Dead line: '.$row->taskDate.'</p>';
		echo '<p><button type="submit" id="IncrementPercent">'.$row->percentOfDone.'/6 task done</button><p>';
		echo '<p><button type="submit" id="DeleteTask">Delete task</button><p>';
		echo '<div id="StatTask"></div>';
		echo '<script>';
		echo '$("#IncrementPercent").click(function(){';
			//echo 'alert("Hello! I am an alert box!!");';
			echo '$("#IncrementPercent").load("/wp-content/plugins/DrawHex/IncrementPercentOfDone.php?taskid='.$row->id.'");';
			echo '});';
		echo '$("#DeleteTask").click(function(){';
			//echo 'alert("Hello! I am an alert box!!");';
			echo '$("#TaskInfoDiv").load("/wp-content/plugins/DrawHex/DeleteTask.php?taskid='.$row->id.'");';
			echo '});';
		echo '</script>';
	}
}
PrintTaskInfo();
?>
