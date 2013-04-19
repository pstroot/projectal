<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
include_once("includes/imageUploadFunctions.php");

$Query = "SELECT * FROM business_types WHERE type_id = " . $_GET["id"];
$Results = $db->Execute($Query);
while (!$Results->EOF) {	
	  $id = $Results->fields['type_id'];
	  $name = $Results->fields['type_name'];
	  $isActive = $Results->fields['isActive'];
 	  $Results->MoveNext();
} 
        
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Admin - <?=$name?> Added</title>  	
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css" />
  	<script src="../scripts/modernizr-1.5.min.js"></script>
    <link href="includes/adminstyles.css" rel="stylesheet" type="text/css" />
    <script src="includes/adminScripts.js"></script>
	<script>
		var ID = <?=$_GET["id"];?>;
		var newRow = parent.createRow("theDataTable")
		var newCell = parent.insertCell(newRow,'<div style="vertical-align:middle;text-align:right;white-space:nowrap;"><a onclick = "doEdit(<?=$id; ?>);" class="yellowBtn">edit</a><a onclick = "doDelete(<?=$id; ?>);" class="yellowBtn">delete</a></div>')
		newCell.className = "editButtons"
		var newCell = parent.insertCell(newRow,"<div id='name_<?=$id; ?>'><?=$name;?></div>")
		
		var newCell = parent.insertCell(newRow,"<input type='checkbox' value='<?=$id;?>' <? if($isActive == 1) print "checked"; ?> onChange='changeActiveState(this)'>")
		newCell.className = "centerContent"
		//parent.delayLyteboxClose();
	</script>
</head>
<body id="popup" style="background-color:#FFFFFF;">

<h1><?=$name?> Added</h1>
<div class="popupContent">

    Thank You! <b><?=$name?></b> has been added to the list of business types.<BR /><BR />
    
    <a href="javascript:parent.window.location = 'businesses.php?id=<?=$id;?>';">Click here</a> to return to Businesses admin page<BR /><BR />
    
    <input type="button" value="Close Window" onclick="parent.delayLyteboxClose();" />
    
</div>
</body>
</html>
