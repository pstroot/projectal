<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
include_once("includes/imageUploadFunctions.php");

$Query = "SELECT * FROM business_types WHERE type_id = " . $_GET["id"];
$Results = $db->Execute($Query);
while (!$Results->EOF) {	
	  $id = $Results->fields['type_id'];
	  $name = $Results->fields['type_name'];
 	  $Results->MoveNext();
} 
	

$Query = "DELETE FROM business_types WHERE type_id = " . $_GET["id"];
$Results = $db->Execute($Query);

        
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Admin - <?=$name?> Deleted</title>  	
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css" />
  	<script src="../scripts/modernizr-1.5.min.js"></script>
    <link href="includes/adminstyles.css" rel="stylesheet" type="text/css" />
    <script src="includes/adminScripts.js"></script>
	<script>		
		parent.deleteTableRow("row_<?=$_GET["id"];?>")
		parent.delayLyteboxClose();
	</script>
</head>
<body id="popup" style="background-color:#FFFFFF;">

<h1><?=$name?> Deleted</h1>

<div class="popupContent">

	Thank You! <?=$name?> has been deleted.
    
</div>
</body>
</html>
