<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
include_once("includes/imageUploadFunctions.php");

$Query = "SELECT * FROM businesses WHERE business_id = " . $_GET["id"];
$Results = $db->Execute($Query);
while (!$Results->EOF) {	
	$business_name = $Results->fields['business_name'];
 	$Results->MoveNext();
} 

$productsList = "";
$Query = "SELECT * FROM product_to_businesses b
		  JOIN zen_products p ON p.products_id = b.product_id
		  JOIN zen_products_description d ON d.products_id = p.products_id		  
		  WHERE b.business_id = " . $_GET["id"];
$Results = $db->Execute($Query);
while (!$Results->EOF) {	
	$productsList .= "<li>" . $Results->fields['products_name'] . "<BR>";
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
		parent.delayLyteboxClose();
	</script>
</head>
<body id="popup" style="background-color:#FFFFFF;">

<h1><?=$business_name?> Assigned to products.</h1>
<div class="popupContent">

    Thank You! <?=$business_name?> has been assigned to the following products.<BR />
    
    <ul style="padding:20px;"><?=$productsList?></ul>
    
    This window will close momentarily.

</div>
</body>
</html>
