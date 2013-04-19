<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
include_once("includes/imageUploadFunctions.php");

$city_id = 65; // 65 is the city ID for Minneapolis

if(isset($_GET["id"]) && !isset($_SESSION["bounceFromErrors"])){
	$Query = "SELECT * FROM neighborhoods WHERE neighborhood_id = " . $_GET["id"];
	$Results = $db->Execute($Query);
	while (!$Results->EOF) {	
		  $_SESSION["formVars"]["neighborhood_name"] = $Results->fields['neighborhood_name'];
		  $Results->MoveNext();
	}
}

if(isset($_GET["id"])) {
	$pageTitle = "Edit \"" . @$_SESSION["formVars"]["neighborhood_name"] . "\"";
	$buttonLabel = "Edit";
	$successURL = "neighborhood_updated.php";
} else {
	$pageTitle = "Add New Neighborhood";
	$buttonLabel = "Add";
	$successURL = "neighborhood_added.php";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Admin - <?=$pageTitle?></title>  	
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css" />
  	<script src="../scripts/modernizr-1.5.min.js"></script>
    <link href="includes/adminstyles.css" rel="stylesheet" type="text/css" />
    <script src="includes/adminScripts.js"></script>
	<script type="text/javascript" src="../includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript" src="../includes/tinymce/tinymceScripts.js"></script>
 	
</head>
<body id="popup">
<h1><?=$pageTitle?></h1>
<div class="popupContent">


<form action="neighborhood_submit.php" method="post" enctype="multipart/form-data">
<div class="formTable">
<table>
	<tr>
        <th>Name</th>        
        <td colspan="3"><input type="text" name="neighborhood_name" id="neighborhood_name" value="<?=@$_SESSION["formVars"]["neighborhood_name"];?>" /><?=required("neighborhood_name");?></td>
    </tr>

	<tr>
        <th></th>        
        <td colspan="3"><input type="submit" id="submit" name="submit" value="<?=$buttonLabel?>" class="blackBtn"/></td>
    </tr>    
</table>
</div>
<input type="hidden" name="city_id" value="<?=$city_id;?>" />
<input type="hidden" name="id" value="<?=@$_GET["id"];?>"/>
<input type="hidden" name="returnURL" value="<?=curPageURL()?>" />
<input type="hidden" name="successURL" value="<?=$successURL;?>"/>
</form>

</div>
</body>
</html>
