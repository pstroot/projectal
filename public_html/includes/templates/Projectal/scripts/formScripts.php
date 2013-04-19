<?
$errorTemplate = "<div class='errorMsg'>::ERROR::</div>";

function required($var){
	global $errorTemplate;
	
	print "<input type='hidden' name='required[]' value='$var'>";
	print "<span class='formRequired'>*</span>";
	if (isset($_SESSION["formErrors"][$var])) {
		
		print "<script>showError('$var');</script>";
		print str_replace("::ERROR::",$_SESSION['formErrors'][$var],$errorTemplate);
	}
}

function addDateForm($dateSelected=0, $monthName='month', $dayName='day', $yearName='year'){
	if ($dateSelected==0) $dateSelected = strtotime("now");
	$thisYear = date("Y",strtotime("now"));
	$y = date("Y",$dateSelected);
	$m = date("m",$dateSelected);
	$d = date("d",$dateSelected);
	
	print "<select name='$monthName' style='width:80px;'>\n";
	for ($x=1;$x<=12;$x++){
		$tempdate = (mktime(1,1,1,$x,1,1));
		$displayThis = date("M",$tempdate);
		$valueThis = date("m",$tempdate);
		
		print "\t<option value='$x'";
		if ($valueThis == $m) print " selected";
		print ">" . $displayThis . "</option>\n";
	}
	print "</select>\n\n";
	
	print "<select name='$dayName' style='width:60px;'>\n";
	for ($x=1;$x<=31;$x++){
		$tempdate = (mktime(1,1,1,1,$x,1));
		$displayThis = date("d",$tempdate);
		$valueThis = date("d",$tempdate);
		
		print "\t<option value='$x'";
		if ($valueThis == $d) print " selected";
		print ">" . date("d",$tempdate) . "</option>\n";
	}
	print "</select>\n\n";
	
	
	print "<select name='$yearName' style='width:60px;'>\n";
	for ($x = ($thisYear-8); $x <= ($thisYear+2); $x++){
		$tempdate = (mktime(1,1,1,1,1,$x));
		$displayThis = date("Y",$tempdate);
		$valueThis = date("Y",$tempdate);
		
		print "\t<option value='$x'";
		if ($valueThis == $y) print " selected";
		print ">" . $displayThis . "</option>\n";
	}
	print "</select>\n\n";
}
// FOR TESTING
// addDateForm(strtotime("14/5/2007"));



function addState($stateSelected="MN", $fieldName='state'){
	$states = "AK,AL,AR,AZ,CA,CO,CT,DC,DE,FL,GA,GU,HI,IA,ID,IL,IN,KS,KY,LA,MA,MD,ME,MI,MN,MO,MP,MS,MT,NC,ND,NE,NH,NJ,NM,NV,NY,OH,OK,OR,PA,PR,RI,SC,SD,TN,TX,UT,VA,VI,VT,WA,WI,WV,WY";
	$statesArray = explode(",",$states);
	
	print "\n<SELECT name='$fieldName' class='pulldown' style='width:50px;'>\n";
	for ($x = 0; $x < count($statesArray); $x++){
		if (strtoupper($stateSelected) != $statesArray[$x]) {
			print "\t<OPTION value='{$statesArray[$x]}'>{$statesArray[$x]}</OPTION>\n";
		} else {
			print "\t<OPTION value='{$statesArray[$x]}' selected>{$statesArray[$x]}</OPTION>\n";
		}
	}
	print "</select>\n\n";


}





function addTime($unixTimestamp='0',$hourName='hour', $minuteName='minute', $ampmName='ampm'){
	if ($unixTimestamp==0) $unixTimestamp = strtotime("now");
	
	$hour = date("h",$unixTimestamp);
	$minutes = date("i",$unixTimestamp);
	$ampm = date("a",$unixTimestamp);
	
	
	print "\n<SELECT name='$hourName' style='width:auto;'>\n";
	for ($x = 0; $x <= 11; $x++){
		if ($hour == ($x+1)) $selected = "selected";
		print "\t<OPTION $selected>" . ($x+1) . "</OPTION>\n";
		$selected = "";
	}
	print "</select>\n\n";
	
	print " : ";
	print "\n<SELECT name='$minuteName' style='width:auto;'>\n";
	for ($x = 0; $x <= 3; $x++){
		$theMin = ($x*15);
		if ($minutes == $theMin) $selected = "selected";
		if ($theMin == 0) $theMin = "00";
		print "\t<OPTION $selected>" . $theMin . "</OPTION>\n";
		$selected = "";
	}
	print "</select>\n\n";
	
	
	print "\n<SELECT name='$ampmName' style='width:auto;'>\n";
	if (strtoupper($ampm) == "AM"){
		print "\t<OPTION selected>am</OPTION>\n";
		print "\t<OPTION>pm</OPTION>\n";
	} else {
		print "\t<OPTION>am</OPTION>\n";
		print "\t<OPTION selected>pm</OPTION>\n";
	}
	print "</select>\n\n";


}

function createColumnHeader($label,$sortColumn){
	global $sortDir,$sortby;
	$sortDirection = "";
	$cellClass = "";
	
	if ($sortby == $sortColumn){
		$cellClass = "class='active'";
		if ($sortDir == "ASC"){
			$imageTag = "<img src='images/icon_down.gif'>";
			$sortDirection = "&dir=1";
		} else {
			$imageTag = "<img src='images/icon_up.gif'>";
		}
	}
				
	print  "<TH $cellClass><a href='?sortby=$sortColumn" . "$sortDirection'>$label</a>" . @$imageTag . "</TH>";
	
}
?>