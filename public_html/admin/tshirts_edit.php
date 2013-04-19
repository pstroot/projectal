<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
include_once("includes/imageUploadFunctions.php");


$category_id = $_GET["catID"];
$option_id = $_GET["oID"];

$Query = "SELECT * FROM zen_products_options_values WHERE products_options_values_id = " .$option_id ;
$Results = $db->Execute($Query);
while (!$Results->EOF) {	
	$option_name = $Results->fields['products_options_values_name'];
	$Results->MoveNext();
}

$Query = "SELECT * FROM zen_categories_description WHERE categories_id = " .$category_id ;
$Results = $db->Execute($Query);
while (!$Results->EOF) {	
	$category_name = $Results->fields['categories_name'];
	$Results->MoveNext();
}
	
	
$Query = "SELECT * FROM tshirt_color_images WHERE option_id = " .$option_id . " AND category_id = " . $category_id;
$Results = $db->Execute($Query);
while (!$Results->EOF) {	
	$id = $Results->fields['match_id'];
	$_SESSION["formVars"]["product_image"] = $Results->fields['product_image'];
	$_SESSION["formVars"]["comments"] =  $Results->fields['comments'];
	$Results->MoveNext();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Admin - Update Tshirt Image</title>  	
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css" />
  	<script src="../scripts/modernizr-1.5.min.js"></script>
    <link href="includes/adminstyles.css" rel="stylesheet" type="text/css" />
    <script src="includes/adminScripts.js"></script>
	<script type="text/javascript" src="../includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript" src="../includes/tinymce/tinymceScripts.js"></script>
 	
</head>
<body id="popup">
<h1>Update Tshirt Image</h1>
<div class="popupContent">


<form action="tshirts_submit.php" method="post" enctype="multipart/form-data">
<div class="formTable">
<table>

	<tr>
        <th>Category</th>        
        <td colspan="3"><?=$category_name;?><input type="hidden" name="category_id" id="category_id" value="<?=@$category_id;?>" /></td>
    </tr>

	<tr>
        <th>Option</th>        
        <td colspan="3"><?=$option_name;?><input type="hidden" name="option_id" value="<?=@$option_id;?>" /></td>
    </tr>


	<tr>
        <th>Image</th>        
        <td colspan="3"><? imageUploadForm(@$_SESSION["formVars"]["product_image"],"product_image","../","Please scale image to 428 x 507 pixels before uploading.",400,120); ?></td>
    </tr>

	<tr>
        <th>Comments</th>        
        <td colspan="3"><input type="text" name="comments" value="<?=@$_SESSION["formVars"]["comments"];?>" /></td>
    </tr>

	<tr>
        <th></th>        
        <td colspan="3"><input type="submit" id="submit" name="submit" value="Submit" class="blackBtn"/></td>
    </tr>
    
</table>
</div>
<input type="hidden" name="catID" value="<?=@$_GET["catID"];?>"/>
<input type="hidden" name="optionID" value="<?=@$_GET["oID"];?>"/>
<input type="hidden" name="id" value="<?=@$id;?>"/>
<input type="hidden" name="returnURL" value="<?=curPageURL()?>" />
<input type="hidden" name="successURL" value="tshirts_updated.php"/>
</form>

</div>
</body>
</html>
