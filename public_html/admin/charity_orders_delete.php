<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
include_once("includes/imageUploadFunctions.php");

	$Query = "SELECT m.*, c.name, u.customers_firstname, u.customers_lastname
					FROM charity_orders_match m
					LEFT JOIN charities c ON c.charity_id = m.charity
					LEFT JOIN zen_customers u ON u.customers_id = m.customers_id
					WHERE m.id = " . $_GET["id"];
$Results = $db->Execute($Query);

	$customers_id = $Results->fields['customers_id'];
 	$customers_firstname = $Results->fields['customers_firstname'];
	$customers_lastname = $Results->fields['customers_lastname'];
	$subtotal = $Results->fields['subtotal'];
	$shipping_cost = $Results->fields['shipping_cost'];
	$total = $Results->fields['total'];
	$charity_id = $Results->fields['charity'];
	$charity_name = $Results->fields['name'];
 	$Results->MoveNext();
 
	

$Query = "DELETE FROM charity_orders_match WHERE id = " . $_GET["id"];
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

<h1>Charity Contribution Deleted</h1>

<div class="popupContent">

	Thank You! Charity Contribution to <b><?= $charity_name; ?></b> from  <b><?= $customers_firstname . " " . $customers_lastname?></b> Deleted
    
</div>
</body>
</html>
