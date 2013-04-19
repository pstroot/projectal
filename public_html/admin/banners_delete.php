<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");

$Query = "SELECT * FROM rotating_banner WHERE id = " . $_GET["id"];
$Results = $db->Execute($Query);
while (!$Results->EOF) {
	$id = $Results->fields['id'];
	$title = $Results->fields['title'];
	$Results->MoveNext();
} 

  
if(isset($_GET["remove"])){
	$Query = "DELETE FROM rotating_banner_placement WHERE banner_id = " . $_GET["id"];
	$Results = $db->Execute($Query);
	
	$Query = "DELETE FROM rotating_banner WHERE id = " . $_GET["id"];
	if(!$Results = $db->Execute($Query)){
		print "There was an error removing banner with ID of " . $_GET["id"] . "<BR><BR>" . $Query;
		exit();
	}
}      
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Admin - Banner Delete</title>  	
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css" />
  	<script src="../scripts/modernizr-1.5.min.js"></script>
    <link href="includes/adminstyles.css" rel="stylesheet" type="text/css" />
    <script src="includes/adminScripts.js"></script>
</head>

<body id="popup" style="background-color:#FFFFFF;">

<? if(!isset($_GET["remove"])){ ?>
    <h1>Remove <i><?=$title;?></i> from Banners?</h1>
    
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
    
	<h1>"<?=$title;?>" has been removed from Banners</h1>
    <div class="popupContent">
    This panel will close momentarily
    </div>
<? } ?>

</body>
</html>
