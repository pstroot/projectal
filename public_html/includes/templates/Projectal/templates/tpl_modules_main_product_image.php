<?php
/**
 * Module Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_modules_main_product_image.php 3208 2006-03-19 16:48:57Z birdbrain $
 */
?>
<?php require(DIR_WS_MODULES . zen_get_module_directory(FILENAME_MAIN_PRODUCT_IMAGE)); ?> 


<?php
//$shirtImage = DIR_WS_TEMPLATE . "images/tshirt_templates/men_white.png";
//list($width, $height, $type, $attr) = getimagesize($shirtImage);
//$bkg_width = MEDIUM_IMAGE_WIDTH;
//$bkg_height = MEDIUM_IMAGE_HEIGHT;
$bkg_width = 428;//$width;
$bkg_height = 507;//$height;

// Calculate URL for zoom image
$query = $db->Execute("SELECT options_values_id,attributes_image 
		 FROM zen_products_attributes 
		 WHERE attributes_image != '' 
	     AND products_id = " . $theProductID . " 
		 AND options_id = 1 
		 ORDER BY attributes_default DESC 
		 LIMIT 1"); 
if($query->fields['attributes_image'] != ""){
	$attributes_image = calculateOptimizedImage($query->fields['attributes_image'],"LARGE"); 
	$zoomURL = zen_href_link(FILENAME_POPUP_IMAGE, 'main_page=popup_image_additional&pID=' . $_GET['products_id']) . "&products_image_large_additional=" . $attributes_image;
} else { 
	$zoomURL =  zen_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $_GET['products_id']); 
}


?>



<!-- /////////////////////////////////////////////////////////////////// -->
<!-- ////////////////////////////// IMAGE ////////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////// -->
<div id="theImage" >


	<? $yAdjust = (428/2) - (31/3); ?>
    <div id="loadingAnimation" style="display:none;position:absolute;margin-left:<?=$yAdjust?>px;margin-top:200px;padding:2px;background-color:#FFFFFF;">
        <img src="<?php echo DIR_WS_TEMPLATE; ?>images/loading29.gif" width="31" height="31" />
    </div>
	<? 
    
    if(strpos($_GET['products_id'],":") > 0){
        $theProductID =substr($_GET['products_id'],0,strpos($_GET['products_id'],":"));
    } else {
        $theProductID =$_GET['products_id'];
    }	
    
    $parameters = "id='tshirtImage'"; // give this tshirt image an ID name so that we can control it with javascript when the color attribute is updated
//    print "<a href=\"javascript:popupWindow('" . $zoomURL . "')\">";
//    print projectal_zen_image($products_image_medium ,$products_name, $bkg_width, $bkg_height, $parameters , $theProductID,"MEDIUM"); 
    // echo zen_image($products_image_medium, $products_name, MEDIUM_IMAGE_WIDTH, MEDIUM_IMAGE_HEIGHT);
//    print '</a>';
	
	
	$query = $db->Execute("SELECT options_values_id,attributes_image 
		 FROM zen_products_attributes 
		 WHERE attributes_image != '' 
	     AND products_id = " . $theProductID . " 
		 AND options_id = 1 
		 ORDER BY attributes_default DESC 
		 LIMIT 1");
		while (!$query->EOF) {			
			$src = "images/" . $query->fields['attributes_image'];
			$query->MoveNext();
		}
		if(!is_file($src)){
			$src = "images/" . PRODUCTS_IMAGE_NO_IMAGE;	
		}
		$src = str_replace(" ","%20",$src);
		$mediumImage = calculateOptimizedImage($src,"MEDIUM");
		$largeImage = calculateOptimizedImage($src,"LARGE");
		
		list($width_m, $height_m, $type_m, $attr_m) = getimagesize($mediumImage);
		list($width_l, $height_l, $type_l, $attr_l) = getimagesize($largeImage);
		
		if($width_l > $width_m)
			print '<a href="'.$largeImage.'" class="zoomableProductImage" title="'.$products_name.'">';
		
       	print '<img src="'.$mediumImage.'" title="'.$products_name.'">';
    	
		if($width_l > $width_m) 
			print '</a>';
		
		print '<div style="clear:both;"></div>';  
    
    ?>
</div>


<!-- /////////////////////////////////////////////////////////////////// -->
<!-- /////////////////////////// ZOOM BUTTON /////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////// -->

<!-- NOTE: This link used to have the following listener... onclick="doZoom('zoomed Image','<?=$products_image?>')" -->
<!--
<div style="float:right;margin-right:100px;">
	<a href="javascript:popupWindow('<?php echo $zoomURL; ?>')" >
    	<img src="<?php echo DIR_WS_TEMPLATE?>images/icon_zoom.gif" alt="Zoom">
    </a>
</div>
-->