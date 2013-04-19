<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
include_once("includes/imageUploadFunctions.php");

$Query = "SELECT * FROM businesses WHERE business_id = " . $_GET["id"];
$Results = $db->Execute($Query);
while (!$Results->EOF) {	
	  $business_name = $Results->fields['business_name'];
	  $business_website = $Results->fields['business_website'];
	  $business_description = $Results->fields['business_description'];
	  $business_id =  $Results->fields['business_id'];
	  $business_logo =  $Results->fields['business_logo'];
	  $business_image =  $Results->fields['business_image'];
	  $business_address =  $Results->fields['business_address'];
	  $business_city =  $Results->fields['business_city'];
	  $business_state =  $Results->fields['business_state'];
	  $business_zip =  $Results->fields['business_zip'];
	  $isActive =  $Results->fields['isActive']; 

	  
 	  $Results->MoveNext();
} 
        
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Admin - <?=$business_name?> Added</title>  	
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css" />
  	<script src="../scripts/modernizr-1.5.min.js"></script>
    <link href="includes/adminstyles.css" rel="stylesheet" type="text/css" />
    <script src="includes/adminScripts.js"></script>
	<script>
		var ID = <?=$_GET["id"];?>;
		var newRow = parent.createRow("theDataTable")
		newRow.id = "row_"+ID
		var lastCell = parent.insertCell(newRow,'<div style="vertical-align:middle;text-align:right;white-space:nowrap;"> <a onclick = "doAssign(<?=$business_id; ?>);" class="yellowBtn">Assign to Products</a> <a onclick = "doEdit(<?=$business_id; ?>);" class="yellowBtn">edit</a><a onclick = "doDelete(<?=$business_id; ?>);" class="yellowBtn">delete</a></div>')
		parent.insertCell(newRow,"<div id='zip_<?=$business_id; ?>'><?=$business_zip;?></div>")
		parent.insertCell(newRow,"<div id='city_<?=$business_id; ?>'><?=$business_city;?></div>")
		parent.insertCell(newRow,"<div id='address_<?=$business_id; ?>'><?=$business_address;?></div>")
		parent.insertCell(newRow,"<div id='logo_<?=$business_id; ?>'><img src='../<?=$business_logo;?>' height='60'></div>")
		parent.insertCell(newRow,"<div id='name_<?=$business_id; ?>'><?=$business_name;?></div>")
		var checkboxCell = parent.insertCell(newRow,"<input type='checkbox' value='<?=$business_id;?>' <? if($isActive == 1) print "checked"; ?> onChange='changeActiveState(this)'>")
		lastCell.className = "editButtons"
		checkboxCell.className = "checkboxCell"
		//parent.delayLyteboxClose();
	</script>
</head>
<body id="popup" style="background-color:#FFFFFF;">

<h1><?=$business_name?> Added</h1>
<div class="popupContent">

    Thank You! <?=$business_name?> has been added.
    
    <a href="javascript:parent.window.location = 'business_assign.php?id=<?=$_GET["id"]?>'">Click here</a> if you would like to assign products to "<?=$business_name?>"<BR /><BR />
    
    <input type="button" value="Close Window" onclick="parent.lbIframeClose();" />
    
</div>
</body>
</html>
