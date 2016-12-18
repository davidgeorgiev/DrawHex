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
		array_push($DeformedHexagon, $Hexagon[0]-$ResizeParameter);
		array_push($DeformedHexagon, $Hexagon[1]);
		array_push($DeformedHexagon, $Hexagon[2]-($ResizeParameter/2));
		array_push($DeformedHexagon, $Hexagon[3]-($ResizeParameter/2));
		array_push($DeformedHexagon, $Hexagon[4]+($ResizeParameter/2));
		array_push($DeformedHexagon, $Hexagon[5]-($ResizeParameter/2));
		array_push($DeformedHexagon, $Hexagon[6]+$ResizeParameter);
		array_push($DeformedHexagon, $Hexagon[7]);
		array_push($DeformedHexagon, $Hexagon[8]+($ResizeParameter/2));
		array_push($DeformedHexagon, $Hexagon[9]+($ResizeParameter/2));
		array_push($DeformedHexagon, $Hexagon[10]-($ResizeParameter/2));
		array_push($DeformedHexagon, $Hexagon[11]+($ResizeParameter/2));
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

function PrintAllHexagonsInArray($Info,$Hexagons,$MaxRes,$widthRect,$heightRect){
	$myBGColor = GiveMeARandomHexColor(2,4);
	$HexColor = GiveMeARandomHexColor(5,15);
	$HexagonCounter = 0;
	$prevGroup = "";
	$GroupsAndColors = array();
	usort($Info,"cmp");
	echo '<svg style="background-color:'.$myBGColor.';"  height="'.$MaxRes.'" width="'.($MaxRes+$widthRect).'">';
	foreach($Hexagons as $Hexagon) {
		
		//$Hexagon = ReturnDeformedHexagon($Hexagon,2,$Info[$HexagonCounter][2]);
		
		echo '<polygon class="hex" points="';
		for ($i = 0; $i < count($Hexagon); $i++) {
			echo $Hexagon[$i].',';
		}
		if($Info[$HexagonCounter][0]!=$prevGroup){
			$prevGroup = $Info[$HexagonCounter][0];
			$HexColor = GiveMeARandomHexColor(5,14);
			array_push($GroupsAndColors, array($Info[$HexagonCounter][0],$HexColor));
		}
		//$HexColor = GardientColor();
		echo '" fill="'.$HexColor.'" stroke="black" stroke-width="1" title="'.$Info[$HexagonCounter][1].'" ></polygon>';
		$HexagonCounter++;
	}
	$HexagonCounter=0;
	foreach($Hexagons as $Hexagon) {
		$HexagonCounter++;
		//echo '<text x='.($Hexagon[0]-45).' y='.($Hexagon[1]+5).'>'.$HexagonCounter.'</text>';
	}
	
	$xRect = -$widthRect;
	$yRect = -$heightRect;
	foreach($GroupsAndColors as $Current){
			$xRect = 0;
			$yRect+=$heightRect;
		
		echo '<rect x="'.$xRect.'" y="'.$yRect.'" width="'.$widthRect.'" height="'.$heightRect.'" style="fill:'.$Current[1].';stroke-width:1;stroke:rgb(0,0,0)" />';
		echo '<text x='.($xRect+15).' y='.($yRect+($heightRect/2)+5).'>'.$Current[0].'</text>';
	}
	
	echo '</svg>';
	//print_r($GroupsAndColors);
}

function DrawHexagons($MaxRes,$Info){
	global $MyGlobalHistory;
	$HexParams = array(60,30,45,56,15,56,0,30,15,4,45,4);
	$HexParams = CenterHexagon($HexParams,$MaxRes);
	
	
	for($k = 1; $k <= count($Info); $k++){
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
	
	PrintAllHexagonsInArray($Info,$MyGlobalHistory,$MaxRes,$widthRect,$heightRect);
	
	
	/*foreach($MyGlobalHistory as $Point) {
		echo '<p>'.CountNeightbours($Point).'</p>';
	}*/
	
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
	$Info = array(array("Res","Title 1",-3),array("group1","Title 2",-6),array("group1","Title 3",0),array("group1","Title 4",-3),
	array("group2","Title 1",-3),array("group2","Title 2",0),array("group2","Title 3",-2),array("group2","Title 4",-3),array("group1","Title 1",-3),array("group1","Title 1",-3),array("group3","Title 1",-3),array("group3","Title 1",-3),array("group4","Title 1",-3),array("group5","Title 1",0),array("group1","Title 1",-3),array("group1","Title 2",-6),array("group1","Title 3",0),
	array("group1","Title 4",-3),array("group2","Title 4",-3),array("group1","Title 1",-3),array("group1","Title 1",-3),array("group1","Title 1",-3),array("group1","Title 2",-6),array("group1","Title 3",0),
	array("group1","Title 4",-3),array("group2","Title 4",-3),array("group1","Title 1",-3),array("group1","Title 1",-3),array("group3","Title 1",-3),array("group3","Title 1",-3),array("group4","Title 1",-3),array("group5","Title 1",0),array("group1","Title 1",-3),array("group1","Title 2",-6),array("group1","Title 3",0),array("group1","Title 4",-3),
	array("group2","Title 1",-3),array("group2","Title 2",0),array("group2","Title 3",-2),array("group2","Title 4",-3),array("group1","Title 1",-3),array("group1","Title 1",-3),array("group3","Title 1",-3),array("group3","Title 1",-3),array("group4","Title 1",-3),array("group5","Title 1",0),array("group1","Title 1",-3),array("group1","Title 2",-6),array("group1","Title 3",0),
	array("group1","Title 4",-3),array("group2","Title 1",-3),array("group2","Title 2",0),array("group2","Title 3",-2),array("group2","Title 4",-3),array("group1","Title 1",-3),array("group1","Title 1",-3),array("group3","Title 1",-3),array("group3","Title 1",-3),array("group4","Title 1",-3),array("group5","Title 1",0),array("group1","Title 1",-3),array("group1","Title 2",-6),
	array("group1","Title 3",0),array("group1","Title 4",-3),array("group2","Title 1",-3),array("group2","Title 2",0),
	array("group2","Title 3",-2),array("group3","Title 1",-3),array("group4","Title 1",-3),array("group5","Title 1",0),array("group1","Title 1",-3),array("group1","Title 2",-6),array("group1","Title 3",0),array("group1","Title 4",-3),
	array("group2","Title 1",-3),array("group2","Title 2",0),array("group2","Title 3",-2),array("group2","Title 4",-3),array("group1","Title 1",-3),array("group1","Title 1",-3),array("group3","Title 1",-3),array("group3","Title 1",-3),array("group4","Title 1",-3),array("group5","Title 1",0),array("group1","Title 1",-3),array("group1","Title 2",-6),array("group1","Title 3",0),
	array("group1","Title 4",-3),array("group2","Title 1",-3),array("group2","Title 2",0),array("group2","Title 3",-2),array("group2","Title 4",-3),array("group1","Title 1",-3),array("group1","Title 1",-3),array("group3","Title 1",-3),array("group3","Title 1",-3),array("group4","Title 1",-3),array("group5","Title 1",0),array("group1","Title 1",-3),array("group1","Title 2",-6),
	array("group1","Title 3",0),array("group1","Title 4",-3),array("group3","Title 1",-3),array("group4","Title 1",-3),array("group5","Title 1",0),array("group1","Title 1",-3),array("group1","Title 2",-6),array("group1","Title 3",0),array("group1","Title 4",-3),
	array("group2","Title 1",-3),array("group2","Title 2",0),array("group2","Title 3",-2),array("group2","Title 4",-3),array("group1","Title 1",-3),array("group1","Title 1",-3),array("group3","Title 1",-3),array("group3","Title 1",-3),array("group4","Title 1",-3),array("group5","Title 1",0),array("group1","Title 1",-3),array("group1","Title 2",-6),array("group1","Title 3",0),
	array("group1","Title 4",-3),array("group2","Title 1",-3),array("group2","Title 2",0),array("group2","Title 3",-2),array("group2","Title 4",-3),array("group1","Title 1",-3),array("group1","Title 1",-3),array("group3","Title 1",-3),array("group3","Title 1",-3),array("group4","Title 1",-3),array("group5","Title 1",0),array("group1","Title 1",-3),array("group1","Title 2",-6),
	array("group1","Title 3",0),array("group1","Title 4",-3),array("group3","Title 1",-3),array("group4","Title 1",-3),array("group5","Title 1",0),array("group1","Title 1",-3),array("group1","Title 2",-6),array("group1","Title 3",0),array("group1","Title 4",-3),
	array("group2","Title 1",-3),array("group2","Title 2",0),array("group2","Title 3",-2),array("group2","Title 4",-3),array("group1","Title 1",-3),array("group1","Title 1",-3),array("group3","Title 1",-3),array("group3","Title 1",-3),array("group4","Title 1",-3),array("group5","Title 1",0),array("group1","Title 1",-3),array("group1","Title 2",-6),array("group1","Title 3",0),
	array("group1","Title 4",-3),array("group2","Title 1",-3),array("group2","Title 2",0),array("group2","Title 3",-2),array("group2","Title 4",-3),array("group1","Title 1",-3),array("group1","Title 1",-3),array("group3","Title 1",-3),array("group3","Title 1",-3),array("group4","Title 1",-3),array("group5","Title 1",0),array("group1","Title 1",-3),array("group1","Title 2",-6),
	array("group1","Title 3",0),array("group1","Title 4",-3),array("group3","Title 1",-3),array("group4","Title 1",-3),array("group5","Title 1",0),array("group1","Title 1",-3),array("group1","Title 2",-6),array("group1","Title 3",0),array("group1","Title 4",-3),
	array("group2","Title 1",-3),array("group2","Title 2",0),array("group2","Title 3",-2),array("group2","Title 4",-3),array("group1","Title 1",-3),array("group1","Title 1",-3),array("group3","Title 1",-3),array("group3","Title 1",-3),array("group4","Title 1",-3),array("group5","Title 1",0),array("group1","Title 1",-3),array("group1","Title 2",-6),array("group1","Title 3",0),
	array("group1","Title 4",-3));
	$MaxRes = 768;
	DrawHexagons($MaxRes,$Info);
}


?>