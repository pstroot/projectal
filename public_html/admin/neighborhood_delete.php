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
 	  $Results->MoveNext();
} 
	

$Query = "DELETE FROM neighborhoods WHERE neighborhood_id = " . $_GET["id"];
$Results = $db->Execute($Query);

        
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Admin - <?=$neighborhood_name?> Deleted</title>  	
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

<h1><?=$neighborhood_name?> Deleted</h1>

<div class="popupContent">

	Thank You! <?=$neighborhood_name?> has been deleted.
    
</div>
</body>
</html>
