<?php
/**
 * Module Template
 *
 * Template used to render attribute display/input fields
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_modules_attributes.php 3208 2006-03-19 16:48:57Z birdbrain $
 */
?>
<div id="productAttributes">
<?php if ($zv_display_select_option > 0) { ?>
<h3 id="attribsOptionsText"><?php echo TEXT_PRODUCT_OPTIONS; ?></h3>
<?php } // show please select unless all are readonly ?>

<?php
for($i=0;$i<sizeof($options_name);$i++) {
	if ($options_comment[$i] != '' and $options_comment_position[$i] == '0') {
		?>
		<h3 class="attributesComments"><?php echo $options_comment[$i]; ?></h3>
		<?php
	}
	?>
	

	<div class="wrapperAttribsOptions">
    	<? if ($options_name[$i] != "Size" && $options_name[$i] != "Color"){ ?>
		<h4 class="optionName back"><?php echo $options_name[$i]; ?></h4>
        <? } ?>
        
        
    	<? if ($options_name[$i] != "Size"){ ?>
			<div class="back"><?php echo "\n" . $options_menu[$i]; ?></div>
        <? } else { ?>
        	<ul class="back" style="padding:0px;margin:0px;"><?php echo "\n" . $options_menu[$i]; ?></ul>
        <? } ?>
		
        <?php echo "\n" . $options_javascript[$i]; // ADDED BY PAUL STROOT ?> 
        <?php echo "\n" . $options_javascript_2[$i]; // ADDED BY PAUL STROOT ?> 
        
        
		<div class="clearBoth"></div>
	</div>
	
	
	<?php if ($options_comment[$i] != '' and $options_comment_position[$i] == '1') { ?>
		<div class="ProductInfoComments"><?php echo $options_comment[$i]; ?></div>
	<?php } ?>
	
	
	<?php
	/*
	if ($options_attributes_image[$i] != '') {
		echo $options_attributes_image[$i];
	}
	*/
	?>
	<div class="clearBoth"></div>
	<?php
}
?>


<?php
  if ($show_onetime_charges_description == 'true') {
?>
    <div class="wrapperAttribsOneTime"><?php echo TEXT_ONETIME_CHARGE_SYMBOL . TEXT_ONETIME_CHARGE_DESCRIPTION; ?></div>
<?php } ?>


<?php
  if ($show_attributes_qty_prices_description == 'true') {
?>
    <div class="wrapperAttribsQtyPrices"><?php echo zen_image(DIR_WS_TEMPLATE_ICONS . 'icon_status_green.gif', TEXT_ATTRIBUTES_QTY_PRICE_HELP_LINK, 10, 10) . '&nbsp;' . '<a href="javascript:popupWindowPrice(\'' . zen_href_link(FILENAME_POPUP_ATTRIBUTES_QTY_PRICES, 'products_id=' . $_GET['products_id'] . '&products_tax_class_id=' . $products_tax_class_id) . '\')">' . TEXT_ATTRIBUTES_QTY_PRICE_HELP_LINK . '</a>'; ?></div>
<?php } ?>
</div>