<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require "../../../wp-load.php";

function PrintMyDatePicker(){
	echo '<div id="DataSelector">';
	echo '<select id="Year" style="width: 80px;">';
	for($i=(int)date("Y");$i<=(int)date("Y")+20;$i++){
		echo '<option value="'.$i.'">'.$i.'</option>';
	}
	echo '</select>';
	echo '<select id="Month" style="width: 80px;">';
	for($i=1;$i<=12;$i++){
		echo '<option value="'.$i.'">'.$i.'</option>';
	}
	echo '</select>';
	echo '<select id="Day" style="width: 80px;">';
	for($i=1;$i<=31;$i++){
		echo '<option value="'.$i.'">'.$i.'</option>';
	}
	echo '</select>';
	echo '</div>';
}

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
	PrintMyDatePicker();
	echo '<p><button type="submit" id="AddNewTaskButton">Add new task</button><p></div>';
	echo '<div id="TAStat"></div>';
	echo '<script>';
	echo '
		$(document).ready(
			function(){
				$("#AddNewTaskButton").click(
					function(){
						$("#TAStat").load("/wp-content/plugins/DrawHex/NewTaskInsert.php?taskname="+String($("#inputTaskName").val()).split(" ").join("+")+"&description="+String($("#inputDescription").val()).split(" ").join("+")+"&groupid="+String($("#SelectedGroup").val()).split(" ").join("+")+"&taskDate="+String($("#Year").val())+"-"+String($("#Month").val())+"-"+String($("#Day").val()));
					}
				);
			}
		);
	';
	echo '</script>';
}

RefreshTaskAdder();
