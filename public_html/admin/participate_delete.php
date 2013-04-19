<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
include_once("includes/imageUploadFunctions.php");



$Query = "SELECT p.participant_id, c.customers_firstname, c.customers_lastname FROM participants p
				  LEFT JOIN zen_customers c ON c.customers_id = p.customer_id
				  WHERE participant_id = " . $_GET["id"];
$Results = $db->Execute($Query);
while (!$Results->EOF) {	
	  $id = $Results->fields['participant_id'];
	  $name = $Results->fields['customers_firstname'] . " " . $Results->fields['customers_lastname'];
 	  $Results->MoveNext();
} 
  
if(isset($_GET["remove"])){
	$Query = "DELETE FROM participants WHERE participant_id = " . $_GET["id"];
	if(!$Results = $db->Execute($Query)){
		print "There was an error removing participant with ID of " . $_GET["id"] . "<BR><BR>" . $Query;
	}
}      
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
</head>

<body id="popup" style="background-color:#FFFFFF;">

<? if(!isset($_GET["remove"])){ ?>
    <h1>Remove "<?=$name;?>" from participants</h1>
    
    <div class="popupContent">
    
    NOTE: This will <b>not</b> remove this user from our main database, it will simply remove him or her from our lists of participants. This person will still be able to log in and out of their account on the website.
    <BR /><BR />
    If you would like to permanantly remove this user, you must do so through the <a href="../projectal_admin/customers.php">store administration panel</a>.
    <BR /><BR />
    <form action="" method="GET">
    <input type="submit" id="submit" name="submit" value="Confirm Removal" class="blackBtn"/>
    <input type="hidden" name="id" value="<?=@$_GET["id"];?>"/>
    <input type="hidden" name="remove" value="1"/>
    </form>
    </div>

<? } else { ?>

	<script>		
		parent.deleteTableRow("row_<?=$_GET["id"];?>")
		parent.delayLyteboxClose();
	</script>
    
	<h1>"<?=$name;?>" has been removed</h1>
    <div class="popupContent">
    This panel will close momentarily
    </div>
<? } ?>

</body>
</html>
