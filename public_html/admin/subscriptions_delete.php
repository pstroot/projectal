<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");

$Query = "SELECT * FROM mailinglist_emails e
		  LEFT JOIN mailinglists l ON l.list_id = e.list_id
		  LEFT JOIN zen_customers z ON z.customers_id = e.customer_id
		  WHERE e.id = " . $_GET["id"];
$Results = $db->Execute($Query);
while (!$Results->EOF) {
	$id = $Results->fields['id'];
	$list = $Results->fields['list_name'];
	$firstname = $Results->fields['firstname'];
	$lastname = $Results->fields['lastname'];
	$email = $Results->fields['email'];
	if($firstname == "") $firstname = $Results->fields['customers_firstname'];
	if($lastname == "") $lastname = $Results->fields['customers_lastname'];
	if($email == "") $email = $Results->fields['customers_email_address'];
			$Results->MoveNext();
} 
  
if(isset($_GET["remove"])){
	$Query = "DELETE FROM mailinglist_emails WHERE id = " . $_GET["id"];
	if(!$Results = $db->Execute($Query)){
		print "There was an error removing subscription with ID of " . $_GET["id"] . "<BR><BR>" . $Query;
		exit();
	}
}      
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Admin - Subscription Delete</title>  	
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css" />
  	<script src="../scripts/modernizr-1.5.min.js"></script>
    <link href="includes/adminstyles.css" rel="stylesheet" type="text/css" />
    <script src="includes/adminScripts.js"></script>
</head>

<body id="popup" style="background-color:#FFFFFF;">

<? if(!isset($_GET["remove"])){ ?>
    <h1>Remove <i><?=$firstname . " " . $lastname;?></i> from "<?=$list?>"?</h1>
    
    <div class="popupContent">
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
    
	<h1>"<?=$firstname . " " . $lastname;?>" has been removed from "<?=$list?>"</h1>
    <div class="popupContent">
    This panel will close momentarily
    </div>
<? } ?>

</body>
</html>
