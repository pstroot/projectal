<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
unset($_SESSION["bounceFromErrors"]);

$sortby = "cd.categories_name, pd.products_name"; // default sort field 
$sortDir = "ASC"; // default sort order

if (isset($_GET["dir"])){
	$sortDir = "DESC";
}
if (isset($_GET["sortby"])){
	$sortby = $_GET["sortby"];
}

unset($_SESSION["formNeedsCorrection"]);
unset($_SESSION["formVars"]);


function calculateOptimizedImage($src,$size){
	$src = str_replace("images/","",$src);			
	$extension = substr($src, strrpos($src, '.'));			
	$base = str_replace($extension, '', $src);
	$dir = "../images/";
			
	if(strtoupper($size) == "MEDIUM"){
		$src_resized = $base . "_MED" . $extension;
		$imageDir = "medium/";
	}else if(strtoupper($size) == "LARGE"){
		$src_resized = $base . "_LRG" . $extension;
		$imageDir = "large/";
	}else if(strtoupper($size) == "TINY"){
		$src_resized = $base . "_TINY" . $extension;
		$imageDir = "tiny/";
	}else if(strtoupper($size) == "SMALL"){
		$src_resized = $base . "_SMALL" . $extension;
		$imageDir = "";
	}else{
		$src_resized = $src;
		$imageDir = "";
	}
	// check for a medium image else use small
	if (file_exists($dir . $imageDir . $src_resized)) {
		return  $dir . $imageDir . $src_resized;
	} else if (file_exists($dir . $imageDir . $src)) {
		 return  $dir . $imageDir . $src;
	} else {
		return false;
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Admin - Tshirt Images</title>  	
    <link rel="stylesheet" href="../includes/templates/Projectal/css/html5reset-1.6.1.css" />
  	<script src="../includes/templates/Projectal/scripts/modernizr-1.5.min.js"></script>
    <link href="includes/adminstyles.css" rel="stylesheet" type="text/css" />
    <script src="includes/adminScripts.js"></script>
    <!--[if IE]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script> 
    <script src="../includes/templates/Projectal/scripts/excanvas.js"></script>
    <![endif]-->
    <!--[if lte IE 6]> 
    <script defer type="text/javascript" src="../includes/templates/Projectal/scripts/pngfix.js"></script>
    <![endif]-->
	<script type="text/javascript" src="../includes/templates/Projectal/includes/lytebox/lytebox.js"></script>
    <link rel="stylesheet" href="../includes/templates/Projectal/includes/lytebox/lytebox.css" type="text/css" media="screen" />

    <script>

	function doDuplicate(productID,optionID){	
		var rev = "width: 600px; height: 500px; scrolling: auto;border:3px solid #CC0000;"
		var url = "tshirtImages_edit.php?pID=" + productID + "&oID=" + optionID
		var title = "EDIT TSHIRT COLOR"
		startLytebox(url,title,rev) 
	}
	

	</script>
</head>
<body onLoad="activateNav('nav3')">
<a id="lytebox_misc"></a>
<? include("includes/inc_header.php"); ?>

<!--<input type="button" value="Add New Charity" style="float:right;margin-left:5px;" class="blackBtn" onclick="doAdd()" /> -->
<h1>Tshirt Color Images</h1>

<div class="dataTable">
<table id="theDataTable">
	<tr>
        <?
		// "createColumnHeader()" included in formScripts.php
		createColumnHeader('Product','option_id');
		createColumnHeader('Category','category_id');
		createColumnHeader('Option','option_id');
		?>   
        <th>Image</th>
        <th>Tiny</th>
        <th>Small</th>
        <th>Med.</th>
        <th>Lrg.</th>
        <th></th>
    </tr> 
    <?PHP
		$Query = "SELECT DISTINCT p.products_id,p.products_image, pd.products_name, zpa.attributes_image, c.categories_id,zpov.products_options_values_id, cd.categories_name, zpov.products_options_values_name
					FROM zen_products p
					JOIN zen_products_description pd ON pd.products_id = p.products_id
					LEFT JOIN zen_products_attributes zpa ON zpa.products_id = p.products_id
					LEFT JOIN zen_products_options_values zpov ON zpa.options_values_id= zpov.products_options_values_id
					LEFT JOIN zen_categories c ON c.categories_id= p.master_categories_id
					JOIN zen_categories_description cd ON c.categories_id= cd.categories_id
					WHERE zpa.options_id= 1
				  ORDER BY $sortby $sortDir";
		$Results = $db->Execute($Query);
		 while (!$Results->EOF) {	
			  $category_id = $Results->fields['categories_id'];
			  $categories_name = $Results->fields['categories_name'];
			  $option_id = $Results->fields['products_options_values_id'];
			  $option_name = $Results->fields['products_options_values_name'];
			  $products_name = $Results->fields['products_name'];
			  $products_id = $Results->fields['products_id'];
			  $src = false;
			 //$src = "images/" . $Results->fields['products_image'];
			  if($Results->fields['attributes_image'] != ""){
				  $src = "images/" . $Results->fields['attributes_image'];
			  }
			  $tinyExists =   calculateOptimizedImage($src,"TINY");
			  $smallExists =  calculateOptimizedImage($src,"SMALL");
			  $mediumExists = calculateOptimizedImage($src,"MEDIUM");
			  $largeExists =  calculateOptimizedImage($src,"LARGE");
			  $displayImage = $largeExists;
			  if($mediumExists)   $displayImage = $mediumExists;
			  if($smallExists)   $displayImage = $smallExists;
			  if($tinyExists)   $displayImage = $tinyExists;

			  ?>
			  <tr onclick="" id="row_<?=$id; ?>">
                <td><div id="category_<?=$id; ?>"><?= stripslashes($products_name); ?></div></td>
                <td><div id="category_<?=$id; ?>"><?= stripslashes($categories_name); ?></div></td>
				<td><div id="option_<?=$id; ?>"><?= $option_name; ?></div></td>
                
                <td style="text-align:center;"><div id="image_<?=$id; ?>"><img src="../<?= $displayImage; ?>" width="50"/></div></td>
                <td style="text-align:center;">
				<? if($src && $tinyExists){
					print "<a href='$tinyExists'>X</a>";
				}
				?>
                </td>
                <td style="text-align:center;">
				<? if($src && $smallExists){
					print "<a href='$smallExists'>X</a>";
				}
				?>
                </td style="text-align:center;">
                <td style="text-align:center;">
				<? if($src && $mediumExists){
					print "<a href='$mediumExists'>X</a>";
				}
				?>
                </td>
                <td style="text-align:center;">
				<? if($src && $largeExists){
					print "<a href='$largeExists'>X</a>";
				}
				?>
                </td>
               
                
				<td style="text-align:right;white-space:nowrap;">
                    <div id="buttons_<?=$id; ?>">
                    <!-- <a onclick = "doEdit(<?= $category_id?>,<?= $option_id?>);" class="yellowBtn">upload new</a> -->
                    <a onclick = "doDuplicate(<?= $products_id; ?>,<?= $option_id?>);" class="yellowBtn">create all sizes</a>
              		</div>
                </td>
			</tr>          
			<? 
			$Results->MoveNext();
		} ?>
        <div id="newRows"></div>
</table>
</div>


	    

<? include_once("includes/inc_footer.php"); ?>

</body>
</html>
