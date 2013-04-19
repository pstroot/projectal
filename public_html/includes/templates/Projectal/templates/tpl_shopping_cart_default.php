<script>
activateNav('nav-shop');
</script>

<?php

/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=shopping_cart.<br />
 * Displays shopping-cart contents
 *
 * @package templateSystem
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_shopping_cart_default.php 15881 2010-04-11 16:32:39Z wilt $
 */
?>
<div class="centerColumn" id="shoppingCartDefault"><!-- bof  breadcrumb -->
<?php if (DEFINE_BREADCRUMB_STATUS == '1' || (DEFINE_BREADCRUMB_STATUS == '2' && !$this_is_home_page) ) { ?>
<div class="breadcrumbs">
	<?php 
	//unset($breadcrumb->_trail[1]); // remove the first level category, which is the city
	$breadcrumb->_trail= array_values($breadcrumb->_trail); // reassigns the array indicies so there isn't a hole.
	echo $breadcrumb->trail(BREAD_CRUMBS_SEPARATOR); ?><span class="activeCrumb"></span>
</div>
<?php } ?>
<!-- eof breadcrumb -->


<div class="centerColumnContent whiteContent">
<div class="centerColumnPadding">


<?php
  if ($flagHasCartContents) {
?>

<?php
  if ($_SESSION['cart']->count_contents() > 0) {
?>
<div class="forward" id="helpButton"><?php echo TEXT_VISITORS_CART; ?></div>
<?php
  }
?>

<h1 id="cartDefaultHeading"><?php echo HEADING_TITLE; ?></h1>

<?php if ($messageStack->size('shopping_cart') > 0) echo $messageStack->output('shopping_cart'); ?>

<?php echo zen_draw_form('cart_quantity', zen_href_link(FILENAME_SHOPPING_CART, 'action=update_product', $request_type)); ?>
<div id="cartInstructionsDisplay" class="content"><?php echo TEXT_INFORMATION; ?></div>

<?php if (!empty($totalsDisplay)) { ?>
  <div class="cartTotalsDisplay important"><?php echo $totalsDisplay; ?></div>
  <br class="clearBoth" />
<?php } ?>

<?php  if ($flagAnyOutOfStock) { ?>

<?php    if (STOCK_ALLOW_CHECKOUT == 'true') {  ?>

<div class="messageStackError"><?php echo OUT_OF_STOCK_CAN_CHECKOUT; ?></div>

<?php    } else { ?>
<div class="messageStackError"><?php echo OUT_OF_STOCK_CANT_CHECKOUT; ?></div>

<?php    } //endif STOCK_ALLOW_CHECKOUT ?>
<?php  } //endif flagAnyOutOfStock ?>


<?
// replace the product image with the colored tshirt image if it exists.
for ($i=0, $n=sizeof($productArray); $i<$n; $i++) {	 	// loop through all of the products in our cart
	if(strpos($products[$i]["id"],":") > 0){
		$theProductID =substr($products[$i]["id"],0,strpos($products[$i]["id"],":"));
	} else {
		$theProductID =$products[$i]["id"];
	}
	
	if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) { // IF this has attributes
		 foreach ($productArray[$i]['attributes'] as $option => $value) { // loop through each attribute, and query the database to get the attribute image for it

			$attributes = "SELECT poval.products_options_values_name, poval.products_options_values_id, pa.options_id, pa.attributes_image
                      	   FROM " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                     	   WHERE pa.options_values_id = ".$value["options_values_id"]."	
						   AND pa.products_id = ".$theProductID."							 
                     	   AND pa.options_id = 1					 
                     	   AND pa.options_values_id = poval.products_options_values_id
                     	   AND poval.language_id = ".$_SESSION['languages_id'];
			$attributes_values = $db->Execute($attributes);
			if($attributes_values->fields['options_id'] == "1" && $attributes_values->fields['attributes_image'] != ""){ // IF the attribute is a COLOR attribute (id = 1), and an image exists, then replace the "productImage" value in the products array
				$thisSrc = calculateOptimizedImage($attributes_values->fields['attributes_image'],"TINY"); // check to see if a TINY image exists.			
				$productArray[$i]['productsImage'] = '<img src="'.$thisSrc .'" alt="'. $productArray[$i]['productsName'].'" title="'. $productArray[$i]['productsName'].'" width="50"  />';
			}
		}		
	}
}
?>

<table  border="0" width="100%" cellspacing="5" cellpadding="0" id="productTable">
     <tr class="tableHeading">
        <th scope="col" id="scProductsHeading" class="productImage">&nbsp;</th>
        <th scope="col" id="scProductsHeading" class="productDetail"><?php echo TABLE_HEADING_PRODUCTS; ?></th>
        <th scope="col" id="scUnitHeading" class="productPrice"><?php echo TABLE_HEADING_PRICE; ?></th>
        <th scope="col" id="scQuantityHeading" class="productQuantity"><?php echo TABLE_HEADING_QUANTITY; ?></th>
        <th scope="col" id="scTotalHeading" class="productTotal"><?php echo TABLE_HEADING_TOTAL; ?></th>
        <th scope="col" id="scRemoveHeading" class="productButtons">&nbsp;</th>
     </tr>
         <!-- Loop through all products /-->
<?php
  foreach ($productArray as $product) {
?>
     <tr >
		<td class="productImage <?php echo $product['rowClass']; ?>"><?php echo $product['productsImage']; ?></td>
        <td class="productDetail <?php echo $product['rowClass']; ?>">
        

<!-- this is the addition from the "Stock by Attributes" plug-in -->
	<div id="title">
    <a href="<?php echo $product['linkProductsName']; ?>"></a>
    <?php 
		echo $product['productsName'] ;
    	
		if($product['flagStockCheck'] != ""){
			echo '<BR><div class="messageStackWarning">';
			echo zen_image($template->get_template_dir(ICON_IMAGE_ERROR, DIR_WS_TEMPLATE, $current_page_base,'images/icons'). '/' . ICON_IMAGE_ERROR, ICON_ERROR_ALT) ;
        	if($product['stockAvailable'] > 0){
				echo 'Only '.$product['stockAvailable'] . ' ' . ' item left in stock. You will need to update your quantity before checking out.';
			} else {
				echo $product['flagStockCheck'];
			}
			echo '</div>';
			echo "
				<script>$(document).ready(function() { 
					$('.checkoutButton img').hide(); 
					$('.checkoutButton').addClass('messageStackWarning').css('width','260px');
					$('.checkoutButton').html('Please update your cart quantities to eliminate out of stock items before continuing');	
				});
				</script>";
		}
    ?>
    
    
    <?php 
	if ((STOCK_SHOW_LOW_IN_CART == 'true') && $product['flagStockCheck']) {
         echo '<span class="alert bold">';
         echo PWA_STOCK_AVAILABLE;
		echo ((isset($product['stockAvailable'])) ? $product['stockAvailable']: 0);
         echo '</span>';
	}
?>



    </div>
<!-- END "Stock by Attributes" plug-in -->


			<?php
            echo $product['attributeHiddenField'];
            if (isset($product['attributes']) && is_array($product['attributes'])) {
              	echo '<ul class="productAttributes">';
                reset($product['attributes']);
                foreach ($product['attributes'] as $option => $value) {
            		?>
            		<li><?php echo $value['products_options_name'] . TEXT_OPTION_DIVIDER . nl2br($value['products_options_values_name']); ?></li>
					<?php
                }
				echo '</ul>';
            }
            ?>
       </td>
       <td class="productPrice <?php echo $product['rowClass']; ?>"><?php echo $product['productsPriceEach']; ?></td>
       <td class="productQuantity <?php echo $product['rowClass']; ?>">
			<?php
			if ($product['flagShowFixedQuantity']) {
            	echo $product['showFixedQuantityAmount'];
            } else {
				echo $product['quantityField'];
            }
			if($product['flagStockCheck'] != ""){
				 echo '<br /><span class="alert bold">' . $product['flagStockCheck'] . '</span>';
			}
			if($product['showMinUnits'] != ""){
				echo '<br />' . $product['showMinUnits'];
			}
            ?>
            <?php
              if ($product['buttonUpdate'] == '') {
                echo '' ;
              } else {
                echo $product['buttonUpdate'];
              }
            ?>
       </td>
       
       <td class="productTotal <?php echo $product['rowClass']; ?>"><?php echo $product['productsPrice']; ?></td>
       <td class="productButtons <?php echo $product['rowClass']; ?>">
<?php
  if ($product['buttonDelete']) {
?>
           <a href="<?php echo zen_href_link(FILENAME_SHOPPING_CART, 'action=remove_product&product_id=' . $product['id']); ?>" class="fancybutton blackButton small">
		   Remove
           </a>
<?php
  }
  if ($product['checkBoxDelete'] ) {
    //echo zen_draw_checkbox_field('cart_delete[]', $product['id']);
  }
?>
</td>
     </tr>
<?php
  } // end foreach ($productArray as $product)
  
  
// show update cart button
if (SHOW_SHOPPING_CART_UPDATE == 2 or SHOW_SHOPPING_CART_UPDATE == 3) {
?>

       <!-- Finished loop through all products /-->
       <tr class="tableHeading">
        <th scope="col" id="scProductsHeading">&nbsp;</th>
        <th scope="col" id="scProductsHeading">&nbsp;</th>
        <th scope="col" id="scUnitHeading">&nbsp;</th>
        <th scope="col" id="scQuantityHeading"><input type="submit" name="submit" value="Refresh Qty." class="fancybutton blueButton small" style="margin:0px;"/></th>
        <th scope="col" id="scTotalHeading">&nbsp;</th>
        <th scope="col" id="scRemoveHeading">&nbsp;</th>
     </tr>
<? } // don't show update button below cart ?>
</table>

<div id="cartSubTotal"><?php echo SUB_TITLE_SUB_TOTAL; ?> <?php echo $cartShowTotal; ?></div>
<br class="clearBoth" />

<!--bof shopping cart buttons-->
<div class="buttonRow forward checkoutButton"><?php echo '<a href="' . zen_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="fancybutton greenButton">' . BUTTON_CHECKOUT_ALT . '</a>'; ?></div>
<div class="buttonRow"><?php echo '<a href="' . zen_back_link(true) . '" class="fancybutton blueButton">' . BUTTON_CONTINUE_SHOPPING_ALT . '</a>'; ?></div>

<?php
if (SHOW_SHIPPING_ESTIMATOR_BUTTON == '1') {
	?>
    <div class="buttonRow"><?php echo '<a href="javascript:popupWindow(\'' . zen_href_link(FILENAME_POPUP_SHIPPING_ESTIMATOR) . '\')" class="fancybutton blueButton">' . BUTTON_SHIPPING_ESTIMATOR_ALT . '</a>'; ?></div>
	<?php
}
?>

<!--eof shopping cart buttons-->
</form>


<!-- ** BEGIN PAYPAL EXPRESS CHECKOUT ** -->
<?php  // the tpl_ec_button template only displays EC option if cart contents >0 and value >0
if (defined('MODULE_PAYMENT_PAYPALWPP_STATUS') && MODULE_PAYMENT_PAYPALWPP_STATUS == 'True') {
  include(DIR_FS_CATALOG . DIR_WS_MODULES . 'payment/paypal/tpl_ec_button.php');
}
?>
<!-- ** END PAYPAL EXPRESS CHECKOUT ** -->

<?php
      if (SHOW_SHIPPING_ESTIMATOR_BUTTON == '2') {
/**
 * load the shipping estimator code if needed
 */
?>
      <?php require(DIR_WS_MODULES . zen_get_module_directory('shipping_estimator.php')); ?>

<?php
      }
?>
<?php
  } else {
?>

<h2 id="cartEmptyText"><?php echo TEXT_CART_EMPTY; ?></h2>

<?php
$show_display_shopping_cart_empty = $db->Execute(SQL_SHOW_SHOPPING_CART_EMPTY);

while (!$show_display_shopping_cart_empty->EOF) {
?>

<?php
  if ($show_display_shopping_cart_empty->fields['configuration_key'] == 'SHOW_SHOPPING_CART_EMPTY_FEATURED_PRODUCTS') { ?>
<?php
/**
 * display the Featured Products Center Box
 */
?>
<?php require($template->get_template_dir('tpl_modules_featured_products.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_featured_products.php'); ?>
<?php } ?>

<?php
  if ($show_display_shopping_cart_empty->fields['configuration_key'] == 'SHOW_SHOPPING_CART_EMPTY_SPECIALS_PRODUCTS') { ?>
<?php
/**
 * display the Special Products Center Box
 */
?>
<?php require($template->get_template_dir('tpl_modules_specials_default.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_specials_default.php'); ?>
<?php } ?>

<?php
  if ($show_display_shopping_cart_empty->fields['configuration_key'] == 'SHOW_SHOPPING_CART_EMPTY_NEW_PRODUCTS') { ?>
<?php
/**
 * display the New Products Center Box
 */
?>
<?php require($template->get_template_dir('tpl_modules_whats_new.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_whats_new.php'); ?>
<?php } ?>

<?php
  if ($show_display_shopping_cart_empty->fields['configuration_key'] == 'SHOW_SHOPPING_CART_EMPTY_UPCOMING') {
    include(DIR_WS_MODULES . zen_get_module_directory(FILENAME_UPCOMING_PRODUCTS));
  }
?>
<?php
  $show_display_shopping_cart_empty->MoveNext();
} // !EOF
?>
<?php
  }
?>


</div> <!-- END centerColumnPadding -->
<div class="shadow" id="bottom" ></div>
</div> <!-- END centerColumnContent -->
</div> <!-- END centerColumn -->


