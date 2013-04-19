<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
include_once("includes/imageUploadFunctions.php");

if(isset($_GET["id"])){
	$existingMatches = array();
	$Query = "SELECT * FROM product_to_businesses p
			  LEFT JOIN businesses b ON b.business_id = p.business_id
			  WHERE p.business_id = " . $_GET["id"];
	$Results = $db->Execute($Query);
	while (!$Results->EOF) {	
		$business_name = $Results->fields['business_name'];
		array_push($existingMatches,$Results->fields['product_id']);
		$Results->MoveNext();
	} 
}

$pageTitle = "Assign \"" . @$business_name . "\" to Products";
$buttonLabel = "ASSIGN TO BUSINESS";
$successURL = "business_assigned.php";

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
<body id="popup" style="background-color:#FFFFFF;">
<h1><?=$pageTitle?></h1>
<div class="popupContent">

<form action="business_doAssign.php" method="post" enctype="multipart/form-data">

<?
print getCategoryProducts(65,0); // 65 is the category ID for Minneapolis

function getCategoryProducts($parentID,$level){
	global $db;
	$Query = "SELECT * FROM zen_categories c, zen_categories_description d WHERE d.categories_id = c.categories_id AND c.parent_id = $parentID";
	$Results = $db->Execute($Query);
	while (!$Results->EOF) {
		$categories_id = $Results->fields['categories_id'];
		$categories_name = $Results->fields['categories_name'];
		$parent_id = $Results->fields['parent_id'];
		print "<div style='float:left;padding:20px;'>";
		print "<div style='font-weight:bold;margin-bottom:4px;'>".$categories_name."</div>";
		listProducts($categories_id);
		print "</div>";
		getCategoryProducts($categories_id,$level+1);
		$Results->MoveNext();
	} 
}
function listProducts($categoryID){
	global $db,$existingMatches;
	$Query = "SELECT * FROM zen_products p
			  JOIN zen_products_description d ON d.products_id = p.products_id
			  LEFT JOIN zen_products_to_categories c ON c.products_id = d.products_id
			  WHERE c.categories_id = $categoryID
			  ORDER BY d.products_name";
	$Results = $db->Execute($Query);
	while (!$Results->EOF) {	
		  $products_id = $Results->fields['products_id'];
		  $products_name = $Results->fields['products_name'];
		  $selected="";
		  if(in_array($products_id,$existingMatches)) $selected="checked";
		  print "<input type='checkbox' name='productID[]' value=".$products_id."  $selected>".$products_name."<BR>";
		  $Results->MoveNext();
	} 
}
?>
<div style="clear:both;"></div>
<input type="submit" id="submit" name="submit" value="<?=$buttonLabel?>" class="submitBtn"/>

<input type="hidden" name="id" value="<?=@$_GET["id"];?>"/>
<input type="hidden" name="returnURL" value="<?=curPageURL()?>" />
<input type="hidden" name="successURL" value="<?=$successURL;?>"/>
</form>

</div>
</body>
</html>
