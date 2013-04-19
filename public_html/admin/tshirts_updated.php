<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");

$Query = "SELECT tci.*, cd.categories_name,ov.products_options_values_name 
			FROM tshirt_color_images tci
			LEFT JOIN  zen_products_options_values ov ON tci.option_id = ov.products_options_values_id
			LEFT JOIN  zen_categories_description cd ON tci.category_id = cd.categories_id
			WHERE tci.match_id = " . $_GET["id"] ;
$Results = $db->Execute($Query);
while (!$Results->EOF) {	
	$option_name = $Results->fields['products_options_values_name'];
	$category_name = $Results->fields['categories_name'];
	$product_image = $Results->fields['product_image']; 
	$comments = $Results->fields['comments']; 
	$option_id = $Results->fields['option_id']; 
	$category_id = $Results->fields['category_id']; 
	$Results->MoveNext();
}

     
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Admin - <?=$category_name;?> -> <?=$option_name;?> Updated</title>  	
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css" />
  	<script src="../scripts/modernizr-1.5.min.js"></script>
    <link href="includes/adminstyles.css" rel="stylesheet" type="text/css" />
    <script src="includes/adminScripts.js"></script>
	<script>
		var ID = <?=$_GET["id"];?>;
		
		parent.updateData("category_"+ID,"<?=$category_name;?>")
		parent.updateData("option_"+ID,"<?=$option_name;?>")
		parent.updateData("comment_"+ID,"<?=$comments;?>")
		parent.updateData("image_"+ID,"<img src='../<?=$product_image;?>' height='60' />")
		
		parent.delayLyteboxClose();
	</script>
</head>
<body id="popup" style="background-color:#FFFFFF;">

<h1><?=$category_name;?> -> <?=$option_name;?> Updated</h1>
<div class="popupContent">
	
	Thank You! This window will close momentarily.

</div>
</body>
</html>
