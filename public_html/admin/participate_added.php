<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
include_once("includes/imageUploadFunctions.php");

$Query = "SELECT p.profession, p.participant_id, p.customer_id, c.customers_firstname, c.customers_lastname, p.date_created, c.customers_email_address, p.isActive FROM participants p
				  LEFT JOIN zen_customers c ON c.customers_id = p.customer_id
				  WHERE participant_id = " . $_GET["id"];
$Results = $db->Execute($Query);
while (!$Results->EOF) {
 	  $id = 	$Results->fields['participant_id'];
	  $firstname = $Results->fields['customers_firstname'];
	  $lastname = $Results->fields['customers_lastname'];
	  $email = $Results->fields['customers_email_address'];
	  $profession = $Results->fields['profession'];
	  $since = date("F j, Y g:i a",strtotime($Results->fields['date_created']));
	  $isActive = $Results->fields['isActive'];
 	  $Results->MoveNext();
} 
        
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Admin - Participant Added</title>  	
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css" />
  	<script src="../scripts/modernizr-1.5.min.js"></script>
    <link href="includes/adminstyles.css" rel="stylesheet" type="text/css" />
    <script src="includes/adminScripts.js"></script>
	<script>
		var ID = <?=$id;?>;
		var newRow = parent.createRow("theDataTable")
		var newCell = parent.insertCell(newRow,'<div style="vertical-align:middle;text-align:right;white-space:nowrap;"><a onclick = "doEdit(<?=$id; ?>);" class="yellowBtn">edit</a><a onclick = "doDelete(<?=$id; ?>);" class="yellowBtn">delete</a></div>')
		newCell.className = "editButtons"
		var newCell = parent.insertCell(newRow,"<?=$profession;?>")
		var newCell = parent.insertCell(newRow,"<?=$email;?>")
		var newCell = parent.insertCell(newRow,"<?=$since;?>")
		var newCell = parent.insertCell(newRow,"<?=$lastname;?>")
		var newCell = parent.insertCell(newRow,"<?=$firstname;?>")		
		var newCell = parent.insertCell(newRow,"<input type='checkbox' value='<?=$id;?>' <? if($isActive == 1) print "checked"; ?> onChange='changeActiveState(this)'>")
		newCell.className = "centerContent"
		//parent.delayLyteboxClose();
	</script>
</head>
<body id="popup" style="background-color:#FFFFFF;">

<h1>Participant Added</h1>
<div class="popupContent">

    Thank You! An Participant has been added.
    
    <BR /><BR />
    
    <input type="button" value="Close Window" onclick="parent.lbIframeClose();" />
    
</div>
</body>
</html>
