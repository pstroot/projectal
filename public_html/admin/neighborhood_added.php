<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
include_once("includes/imageUploadFunctions.php");

$Query = "SELECT * FROM neighborhoods WHERE neighborhood_id = " . $_GET["id"];
$Results = $db->Execute($Query);
while (!$Results->EOF) {	
	  $neighborhood_id = $Results->fields['neighborhood_id'];
	  $neighborhood_name = $Results->fields['neighborhood_name'];
	  $city_id = $Results->fields['city_id'];
	  $isActive = $Results->fields['isActive'];
 	  $Results->MoveNext();
} 
        
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Admin - <?=$neighborhood_name?> Added</title>  	
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css" />
  	<script src="../scripts/modernizr-1.5.min.js"></script>
    <link href="includes/adminstyles.css" rel="stylesheet" type="text/css" />
    <script src="includes/adminScripts.js"></script>
	<script>
		var ID = <?=$_GET["id"];?>;
		var newRow = parent.createRow("theDataTable")
		newRow.id = "row_"+ID
		var newCell = parent.insertCell(newRow,'<div style="vertical-align:middle;text-align:right;white-space:nowrap;"><a onclick = "doEdit(<?=$neighborhood_id; ?>);" class="yellowBtn">edit</a><a onclick = "doDelete(<?=$neighborhood_id; ?>);" class="yellowBtn">delete</a></div>')
		newCell.className = "editButtons"
		var newCell = parent.insertCell(newRow,"<div id='name_<?=$neighborhood_id; ?>'><?=$neighborhood_name;?></div>")
		
		var newCell = parent.insertCell(newRow,"<input type='checkbox' value='<?=$neighborhood_id;?>' <? if($isActive == 1) print "checked"; ?> onChange='changeActiveState(this)'>")
		newCell.className = "centerContent"
		//parent.delayLyteboxClose();
	</script>
</head>
<body id="popup" style="background-color:#FFFFFF;">

<h1><?=$neighborhood_name?> Added</h1>
<div class="popupContent">

    Thank You! <b><?=$neighborhood_name?></b> has been added to the list of neighborhoods.<BR /><BR />
    
    <a href="javascript:parent.window.location = 'businesses.php?id=<?=$_GET["id"]?>';">Click here</a> to return to Businesses admin page<BR /><BR />
    
    <input type="button" value="Close Window" onclick="parent.delayLyteboxClose();" />
    
</div>
</body>
</html>
