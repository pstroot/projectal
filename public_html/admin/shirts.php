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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Admin - Tshirt Color Images</title>  	
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

	function doEdit(catID,optionID){	
		var rev = "width: 600px; height: 500px; scrolling: auto;border:3px solid #CC0000;"
		var url = "tshirts_edit.php?catID=" + catID + "&oID=" + optionID
		var title = "EDIT TSIHRT COLOR"
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
		createColumnHeader('Category','category_id');
		createColumnHeader('Option','option_id');
		createColumnHeader('Image','product_image');
		createColumnHeader('Comments','comments');
		?>   
        <th></th>
    </tr> 
    <?PHP
		$Query = "SELECT DISTINCT c.categories_id,zpov.products_options_values_id, cd.categories_name, zpov.products_options_values_name, tci.product_image, tci.comments, tci.match_id
					FROM zen_products p
					JOIN zen_products_description pd ON pd.products_id = p.products_id
					LEFT JOIN zen_products_attributes zpa ON zpa.products_id = p.products_id
					LEFT JOIN zen_products_options_values zpov ON zpa.options_values_id= zpov.products_options_values_id
					LEFT JOIN zen_categories c ON c.categories_id= p.master_categories_id
					JOIN zen_categories_description cd ON c.categories_id= cd.categories_id
					LEFT JOIN tshirt_color_images tci ON tci.category_id = c.categories_id AND tci.option_id = zpov.products_options_values_id
					WHERE zpa.options_id= 1
				  ORDER BY $sortby $sortDir";
		$Results = $db->Execute($Query);
		 while (!$Results->EOF) {	
			  $id = $Results->fields['match_id'];
			  $category_id = $Results->fields['categories_id'];
			  $categories_name = $Results->fields['categories_name'];
			  $option_id = $Results->fields['products_options_values_id'];
			  $option_name = $Results->fields['products_options_values_name'];
			  $product_image = $Results->fields['product_image'];
			  $comments =  $Results->fields['comments'];
			  ?>
			  <tr onclick="" id="row_<?=$id; ?>">
                <td><div id="category_<?=$id; ?>"><?= stripslashes($categories_name); ?></div></td>
				 <td><div id="option_<?=$id; ?>"><?= $option_name; ?></div></td>
                <td><div id="image_<?=$id; ?>"><img src="../<?= stripslashes($product_image); ?>" height="60"/></div></td>
                <td><div id="comment_<?=$id; ?>"><?= stripslashes($comments); ?></div></td>
				<td style="text-align:right;white-space:nowrap;">
                    <div id="buttons_<?=$id; ?>">
                    <a onclick = "doEdit(<?= $category_id?>,<?= $option_id?>);" class="yellowBtn">edit</a>
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
