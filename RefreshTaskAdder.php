<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require "../../../wp-load.php";

function RefreshTaskAdder(){
	global $wpdb;
	echo '<h2 style="color:white;">Add new task</h2>';
	echo '<select id="SelectedGroup" style="width: 250px;">';
	$sql = "SELECT id,title,color FROM DrawHexTableGroups;";
	$MyGroups = $wpdb->get_results($sql);
	foreach($MyGroups as $CurrGroup){
		echo '<option style="background-color: #'.$CurrGroup->color.';color:white;" value="'.$CurrGroup->id.'">'.$CurrGroup->title.'</option>';
	}
	echo '</select>';
	echo '<div><p><input id="inputTaskName" type="text" placeholder="Task title"></p>';
	echo '<textarea id="inputDescription" rows="4" cols="30"></textarea>';
	echo '<p><button type="submit" id="AddNewTaskButton">Add new task</button><p></div>';
	echo '<div id="TAStat"></div>';
	echo '<script>';
	echo '
		$(document).ready(
			function(){
				$("#AddNewTaskButton").click(
					function(){
						$("#TAStat").load("/wp-content/plugins/DrawHex/NewTaskInsert.php?taskname="+String($("#inputTaskName").val()).split(" ").join("+")+"&description="+String($("#inputDescription").val()).split(" ").join("+")+"&groupid="+String($("#SelectedGroup").val()).split(" ").join("+"));
					}
				);
			}
		);
	';
	echo '</script>';
}

RefreshTaskAdder();
