<?php
/*
Plugin Name: DrawHex
Description: DrawingHexagonGraph
Author: David Georgiev
Version: 1.0
*/
$r = 0;
$g = 0;
$b = 0;
$MyGlobalHistory = array();
$rightDown = array(45,26);
$down = array(0,52);
$up = array(0,-52);
$leftDown = array(-45,26);
$rightUp = array(45,-26);
$leftUp = array(-45,-26);

if(isset($_GET["RefreshSvg"])){
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	require "../../../wp-load.php";
}

function CreateTables(){
	global $wpdb;
	$wpdb->get_results("DROP TABLE IF EXISTS DrawHexTableInfo;");
	$wpdb->get_results("CREATE TABLE DrawHexTableInfo(id int NOT NULL AUTO_INCREMENT,groupId int,title varchar(255),description varchar(255),percentOfDone int,PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;");
	$wpdb->get_results("DROP TABLE IF EXISTS DrawHexTableGroups;");
	$wpdb->get_results("CREATE TABLE DrawHexTableGroups(id int NOT NULL AUTO_INCREMENT,title varchar(255),color varchar(32),PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;");
}

function ReturnRandomDir(){
	$AllDirs = array("up","right-up","right-down","down","left-down","left-up");
	return $AllDirs[array_rand($AllDirs)];
}

function CountNeightbours($HexParams){
	global $MyGlobalHistory;
	$counter = 0;
	global $rightDown;
	global $down;
	global $up;
	global $leftDown;
	global $rightUp;
	global $leftUp;

	if(in_array(SumHexParameterWith($HexParams,$up), $MyGlobalHistory)){
		$counter++;
	}
	if(in_array(SumHexParameterWith($HexParams,$rightDown), $MyGlobalHistory)){
		$counter++;
	}
	if(in_array(SumHexParameterWith($HexParams,$down), $MyGlobalHistory)){
		$counter++;
	}
	if(in_array(SumHexParameterWith($HexParams,$leftDown), $MyGlobalHistory)){
		$counter++;
	}
	if(in_array(SumHexParameterWith($HexParams,$rightUp), $MyGlobalHistory)){
		$counter++;
	}
	if(in_array(SumHexParameterWith($HexParams,$leftUp), $MyGlobalHistory)){
		$counter++;
	}
	return $counter;
}

function SumHexParameterWith($HexParams,$NewDirection){
	$NewParams = array();
	$i = 0;
	foreach($HexParams as $digit) {
		$i++;
		if($i%2){
			array_push($NewParams, $digit+$NewDirection[0]);
		}else{
			array_push($NewParams, $digit+$NewDirection[1]);
		}
	}
	return $NewParams;
}

function MoveHexagon($MaxRes,$HexParams,$moveDirParam="none",$RandomDir=0,$UniquePos=0){
	global $HexagonsOnScreen;
	global $MyGlobalHistory;
	array_push($MyGlobalHistory, $HexParams);
	global $rightDown;
	global $down;
	global $up;
	global $leftDown;
	global $rightUp;
	global $leftUp;
	
	$DirTestingIndex = 1;
	$CurrentMaxNeighbours = 6;
	$LoopsCounter = 0;
	do{
		if($DirTestingIndex==7){
			$DirTestingIndex = 1;
		}
		if($DirTestingIndex==1){
			$CurrentMaxNeighbours--;
		}
		$LoopsCounter++;
		if($LoopsCounter>1000){
			return -1;
		}
		if($RandomDir){
			switch($DirTestingIndex){
			case 1: $moveDirParam = "right-down";
			break;
			case 2: $moveDirParam = "down";
			break;
			case 3: $moveDirParam = "left-down";
			break;
			case 4: $moveDirParam = "right-up";
			break;
			case 5: $moveDirParam = "left-up";
			break;
			case 6: $moveDirParam = "up";
			break;
			}
			$moveDirParam = ReturnRandomDir();
		}
		switch($moveDirParam){
			case "right-down": $NewDirection = $rightDown;
			break;
			case "down": $NewDirection = $down;
			break;
			case "left-down": $NewDirection = $leftDown;
			break;
			case "right-up": $NewDirection = $rightUp;
			break;
			case "left-up": $NewDirection = $leftUp;
			break;
			case "up": $NewDirection = $up;
			break;
			case "none": $NewDirection = array(0,0);
			break;
		}
		$NewParams = SumHexParameterWith($HexParams,$NewDirection);
		$DirTestingIndex++;
	}while(($RandomDir==1)&&($UniquePos==1)&&(in_array($NewParams, $MyGlobalHistory))||((CountNeightbours($NewParams)<$CurrentMaxNeighbours)&&(count($MyGlobalHistory)>=2)));
	
	return $NewParams;
	
}

function CenterHexagon($HexParams,$MaxRes){
	$NewParams = array();
	foreach($HexParams as $digit) {
		array_push($NewParams, $digit+($MaxRes/2));
	}
	return $NewParams;
}

function GiveMeARandomHexColor($min,$max){
	global $r;
	global $g;
	global $b;
	$r = rand($min,$max);
	$g = rand($min,$max);
	$b = rand($min,$max);
	return '#'.dechex($r).dechex($g).dechex($b);
}
function GardientColor(){
	global $r;
	global $g;
	global $b;
	if(($r+0.1<=15)&&($g+0.1<=15)&&($b+0.1<=15)){
		$r+=0.1;
		$g+=0.1;
		$b+=0.1;
	}
	return '#'.dechex($r).dechex($g).dechex($b);
}

function ReturnDeformedHexagon($Hexagon,$TypeOfDeformation,$ResizeParameter=0){
	$ResizeParameter = -$ResizeParameter;
	$DeformedHexagon = array();
	if($TypeOfDeformation == 1){
		for ($i = 0; $i < count($Hexagon); $i++) {
			array_push($DeformedHexagon, $Hexagon[$i]+rand(-10,10));
		}
	}
	if($TypeOfDeformation == 2){//RESIZING
		array_push($DeformedHexagon, $Hexagon[0]-$ResizeParameter/1.2);
		array_push($DeformedHexagon, $Hexagon[1]);
		array_push($DeformedHexagon, $Hexagon[2]-($ResizeParameter/2));
		array_push($DeformedHexagon, $Hexagon[3]-($ResizeParameter/1.5));
		array_push($DeformedHexagon, $Hexagon[4]+($ResizeParameter/2));
		array_push($DeformedHexagon, $Hexagon[5]-($ResizeParameter/1.5));
		array_push($DeformedHexagon, $Hexagon[6]+$ResizeParameter/1.2);
		array_push($DeformedHexagon, $Hexagon[7]);
		array_push($DeformedHexagon, $Hexagon[8]+($ResizeParameter/2));
		array_push($DeformedHexagon, $Hexagon[9]+($ResizeParameter/1.5));
		array_push($DeformedHexagon, $Hexagon[10]-($ResizeParameter/2));
		array_push($DeformedHexagon, $Hexagon[11]+($ResizeParameter/1.5));
	}
	return $DeformedHexagon;
}
function TryToMakeHexagonsFitTheScreen($Hexagons,$MaxRes,$PaddingFromTheBorders){
	$divider = 0;
	$step = 0.05;
	$TryAgain = 0;
	//$SmalestXAndSmallestY = ReturnSmalestXAndSmallestY($Hexagons,$MaxRes,$widthRect);
	do{
		$TryAgain = 0;
		$divider+=$step;
		
		//$PaddingX = $Hexagons[0][0]-($Hexagons[0][0]/$divider);
		//$PaddingY = $Hexagons[0][1]-($Hexagons[0][1]/$divider);
		$FixedSize = array();
		$FixedSizes = array();
		foreach($Hexagons as $Hexagon) {
			for ($i = 0; $i < count($Hexagon); $i++) {
				if($i%2==0){
					array_push($FixedSize, $Hexagon[$i]/$divider);
				}else{
					array_push($FixedSize, $Hexagon[$i]/$divider);
				}
			}
		
			array_push($FixedSizes, $FixedSize);
			$FixedSize = array();
		}
		/*foreach($FixedSizes as $Current){
			for ($i = 0; $i < count($Current); $i++) {
				if((($i%2==0)&&($Current[$i]>$MaxRes+$widthRect-$PaddingFromTheBorders))||(($i%2==1)&&($Current[$i]>$MaxRes-$PaddingFromTheBorders))||($Current[$i]<$PaddingFromTheBorders)||(($i%2==0)&&($Current[$i]<($widthRect)))){
					$TryAgain = 1;
				}
			}
		}*/
		$CurrentHexagonesSumedResolution = ReturnCurrentResolutionOfAllHexagons($FixedSizes,$MaxRes);
	}while($CurrentHexagonesSumedResolution[0]>$MaxRes-$PaddingFromTheBorders||$CurrentHexagonesSumedResolution[1]>$MaxRes-$PaddingFromTheBorders);
	
	
	return $FixedSizes;
}

function cmp($a, $b)
{
    return strcmp($a[0], $b[0]);
}

function ReturnCurrentResolutionOfAllHexagons($Hexagons,$MaxRes){
	$SmallestX = 2*$MaxRes;
	$SmallestY = 2*$MaxRes;
	foreach($Hexagons as $Hexagon) {
		for ($i = 0; $i < count($Hexagon); $i++) {
			if($i%2==0){
				if($SmallestX>$Hexagon[$i]){
					$SmallestX = $Hexagon[$i];
				}
			}else{
				if($SmallestY>$Hexagon[$i]){
					$SmallestY = $Hexagon[$i];
				}
			}
		}
	}
	$BiggestX = 0;
	$BiggestY = 0;
	foreach($Hexagons as $Hexagon) {
		for ($i = 0; $i < count($Hexagon); $i++) {
			if($i%2==0){
				if($BiggestX<$Hexagon[$i]){
					$BiggestX = $Hexagon[$i];
				}
			}else{
				if($BiggestY<$Hexagon[$i]){
					$BiggestY = $Hexagon[$i];
				}
			}
		}
	}
	return array($BiggestX-$SmallestX,$BiggestY-$SmallestY);
}

function PrintAllHexagonsInArray($Hexagons,$MaxRes,$widthRect,$heightRect){
	global $wpdb;
	$myBGColor = GiveMeARandomHexColor(2,4);
	$HexagonCounter=0;
	$MyGroups = $wpdb->get_results("SELECT * FROM DrawHexTableGroups WHERE id IN (SELECT groupId FROM DrawHexTableInfo);");
	echo '<meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" /><svg style="background-color:'.$myBGColor.';"  height="'.$MaxRes.'" width="'.($MaxRes+$widthRect).'">';
	
	foreach($MyGroups as $CurrentGroup){
		$MyInfo = $wpdb->get_results("SELECT * FROM DrawHexTableInfo WHERE groupId = ".$CurrentGroup->id.";");
		foreach($MyInfo as $MyCurrentInfo){
			echo '<polygon class="hex" points="';
			for ($i = 0; $i < count($Hexagons[$HexagonCounter]); $i++) {
				echo $Hexagons[$HexagonCounter][$i].',';
			}
		
		
		
			//$HexColor = GardientColor();
			echo '" fill="#'.$CurrentGroup->color.'" stroke="white" stroke-width="4" title="'.$MyCurrentInfo->title.'" ></polygon>';
			DrawTriangels($Hexagon,$myBGColor,$MyCurrentInfo->percentOfDone);
			$HexagonCounter++;
		}
	}
	//foreach($Hexagons as $Hexagon) {
		
		
		
		//$Hexagon = ReturnDeformedHexagon($Hexagon,2,$Info[$HexagonCounter][2]);
		
		
	//}
	/*$HexagonCounter=0;
	foreach($Hexagons as $Hexagon) {
		$HexagonCounter++;
		//echo '<text x='.($Hexagon[0]-45).' y='.($Hexagon[1]+5).'>'.$HexagonCounter.'</text>';
	}*/
	
	$xRect = -$widthRect;
	$yRect = -$heightRect;
	foreach($MyGroups as $Current){
			$xRect = 0;
			$yRect+=$heightRect;
		
		echo '<rect x="'.$xRect.'" y="'.$yRect.'" width="'.$widthRect.'" height="'.$heightRect.'" style="fill:#'.$Current->color.';stroke-width:1;stroke:rgb(0,0,0)" />';
		echo '<text x='.($xRect+15).' y='.($yRect+($heightRect/2)+5).'>'.$Current->title.'</text>';
	}
	
	echo '</svg>';
	echo '<script>document.body.style.backgroundColor = "'.$myBGColor.'"</script>';; 
	//print_r($GroupsAndColors);
}

function DrawHexagons($MaxRes){
	global $MyGlobalHistory;
	global $wpdb;
	$MyInfo = $wpdb->get_results("SELECT * FROM DrawHexTableInfo;");
	$HexParams = array(60,30,45,56,15,56,0,30,15,4,45,4);
	$HexParams = CenterHexagon($HexParams,$MaxRes);
	
	
	for($k = 1; $k <= count($MyInfo); $k++){
		$TempHexagon = MoveHexagon($MaxRes,$HexParams,"",1,1);
		if($TempHexagon!=-1){
			$HexParams = $TempHexagon;
		}else{
			$HexParams = array(60,30,45,56,15,56,0,30,15,4,45,4);
			$HexParams = CenterHexagon($HexParams,$MaxRes);
			$k = 0;
			$MyGlobalHistory = array();
		}
	}
	
	$widthRect = ($MaxRes/5);
	$heightRect = 40;
	$MyGlobalHistory = TryToMakeHexagonsFitTheScreen($MyGlobalHistory,$MaxRes,$MaxRes/6);
	$CenteredArray = CenterAllHexagons($MyGlobalHistory,$MaxRes,$widthRect);
	$MyGlobalHistory = $CenteredArray[2];
	
	PrintAllHexagonsInArray($MyGlobalHistory,$MaxRes,$widthRect,$heightRect);
	
	
	/*foreach($MyGlobalHistory as $Point) {
		echo '<p>'.CountNeightbours($Point).'</p>';
	}*/
	
}

function DrawTriangels($Hexagon,$BGcolor,$Percent){
	$Hexagon = ReturnDeformedHexagon($Hexagon,2,-10);
	$ToSixPercent = ($Percent*6)/100;
	$CenterOfTheHexagon = array($Hexagon[6]+(($Hexagon[0]-$Hexagon[6])/2),$Hexagon[11]+(($Hexagon[3]-$Hexagon[11])/2));
	$openPoly = '<polygon class="hex" points="';
	//$TriangelColor = hexToHsl(str_replace("#", "", $BGcolor));
	/*echo '<text x=100 y=100>'.$TriangelColor[0].' '.$TriangelColor[1].' '.$TriangelColor[2].'</text>';
	$TriangelColor[2]+=10;
	$TriangelColorHex = "#".hslToHex($TriangelColor);
	echo '<text x=100 y=200>'.$TriangelColorHex.'</text>';*/
	$closePoly = '" fill="'.$BGcolor.'"  title="" ></polygon>';
	
	if($ToSixPercent>=1){
		echo $openPoly.$Hexagon[8].','.$Hexagon[9].','.$Hexagon[10].','.$Hexagon[11].','.$CenterOfTheHexagon[0].','.$CenterOfTheHexagon[1].$closePoly;
	}
	if($ToSixPercent>=2){
		echo $openPoly.$Hexagon[10].','.$Hexagon[11].','.$Hexagon[0].','.$Hexagon[1].','.$CenterOfTheHexagon[0].','.$CenterOfTheHexagon[1].$closePoly;
	}
	if($ToSixPercent>=3){
		echo $openPoly.$Hexagon[0].','.$Hexagon[1].','.$Hexagon[2].','.$Hexagon[3].','.$CenterOfTheHexagon[0].','.$CenterOfTheHexagon[1].$closePoly;
	}
	if($ToSixPercent>=4){
		echo $openPoly.$Hexagon[2].','.$Hexagon[3].','.$Hexagon[4].','.$Hexagon[5].','.$CenterOfTheHexagon[0].','.$CenterOfTheHexagon[1].$closePoly;
	}
	if($ToSixPercent>=5){
		echo $openPoly.$Hexagon[4].','.$Hexagon[5].','.$Hexagon[6].','.$Hexagon[7].','.$CenterOfTheHexagon[0].','.$CenterOfTheHexagon[1].$closePoly;
	}
	if($ToSixPercent>=6){
		echo $openPoly.$Hexagon[6].','.$Hexagon[7].','.$Hexagon[8].','.$Hexagon[9].','.$CenterOfTheHexagon[0].','.$CenterOfTheHexagon[1].$closePoly;
	}
	
	
}

function CenterAllHexagons($Hexagons,$MaxRes,$widthRect){
	$countDigitsX = 0;
	$countDigitsY = 0;
	$SumDigitsX = 0;
	$SumDigitsY = 0;
	$CenteredHexagons = array();
	$CenteredHexagon = array();
	foreach($Hexagons as $Hexagon) {
		for ($i = 0; $i < count($Hexagon); $i++) {
			if($i%2==0){
				$countDigitsX++;
				$SumDigitsX+=$Hexagon[$i];
			}else{
				$countDigitsY++;
				$SumDigitsY+=$Hexagon[$i];
			}
		}
	}
	$AVGX = $SumDigitsX/$countDigitsX;
	$AVGY = $SumDigitsY/$countDigitsY;
	$PaddingX = (($MaxRes/2)+$widthRect)-$AVGX;
	$PaddingY = ($MaxRes/2)-$AVGY;
	foreach($Hexagons as $Hexagon) {
		for ($i = 0; $i < count($Hexagon); $i++) {
			if($i%2==0){
				array_push($CenteredHexagon, $Hexagon[$i]+$PaddingX);
			}else{
				array_push($CenteredHexagon, $Hexagon[$i]+$PaddingY);
			}
		}
	
		array_push($CenteredHexagons, $CenteredHexagon);
		$CenteredHexagon = array();
	}
	return array($AVGX,$AVGY,$CenteredHexagons);
}

// EXAMPLE USE
function mainDrawHexagons(){
	global $wpdb;
	echo '<script src="/wp-content/plugins/DrawHex/jquery.min.js"></script><script src="/wp-content/plugins/DrawHex/jscolor-2.0.4/jscolor.js"></script>';
	echo '<div id="PutHexagonSvgHere" style="float: left;">';
	$ifTableExistsInfo = $wpdb->get_results("SELECT * FROM DrawHexTableInfo;");
	$ifTableExistsGroups = $wpdb->get_results("SELECT * FROM DrawHexTableGroups;");
	if(empty($ifTableExistsInfo)&&empty($ifTableExistsGroups)){
		CreateTables();
	}else{
		DrawHexagons(768);
	}
	echo '</div>';
}

function ShowAddGroupMenu(){
	echo '<div id="AddNewGroupDiv" style="padding-left:25px;float: left;">';
	echo '<h2 style="color:white;">Add new group</h2>';
	echo '<div><p><input id="inputGroupName" type="text" placeholder="enter new group name"></p><p><input class="jscolor" value="ab2567"></p>';
	echo '<p><button type="submit" id="CreateGroupButton">Create the group</button><p></div>';
	echo '<div id="statusDiv"></div>';
	echo '</div>';
	
	echo "<script>";
	echo "$(document).ready(function(){";
	echo '$("#CreateGroupButton").click(function(){';
	//echo 'alert("Hello! I am an alert box!!");';
	echo '$("#statusDiv").load("/wp-content/plugins/DrawHex/AddNewGroup.php?groupName="+String($("#inputGroupName").val()).split(" ").join("+")+"&color="+String($(".jscolor").val()).split(" ").join("+"));';
	echo '});';
	echo '});';
	echo "</script>";
}

function ShowTaskAdder(){
	echo '<div id="TaskAdder" style="padding-left:25px;float: left;"></div>';
	echo '<script src="/wp-content/plugins/DrawHex/TaskAdderLoader.js"></script>';
}

if(isset($_GET["RefreshSvg"])){
	DrawHexagons(768);
}

?>
