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
    <title>Admin - <?=$business_name?> Updated</title>  	
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css" />
  	<script src="../scripts/modernizr-1.5.min.js"></script>
    <link href="includes/adminstyles.css" rel="stylesheet" type="text/css" />
    <script src="includes/adminScripts.js"></script>
	<script>
		var ID = <?=$_GET["id"];?>;
		parent.updateData("name_"+ID,"<?=$business_name;?>")
		parent.updateData("address_"+ID,"<?=$business_address;?>")
		parent.updateData("city_"+ID,"<?=$business_city;?>")
		parent.updateData("zip_"+ID,"<?=$business_zip;?>")
		<? if ($business_logo != ""){ ?>		
		parent.updateData("logo_"+ID,"<img src='../<?=$business_logo;?>' height='60' />")
		<? } ?>
		parent.delayLyteboxClose();
	</script>
</head>
<body id="popup" style="background-color:#FFFFFF;">

<h1><?=$business_name?> Updated</h1>
<div class="popupContent">
	
	Thank You! This window will close momentarily.

</div>
</body>
</html>
