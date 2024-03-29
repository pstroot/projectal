<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=account_edit.<br />
 * Displays information related to a single specific order
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_account_history_info_default.php 6247 2007-04-21 21:34:47Z wilt $
 */
?>
<div class="centerColumn" id="accountHistInfo">

<!-- bof  breadcrumb -->
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

<div class="forward"><?php echo HEADING_ORDER_DATE . ' ' . zen_date_long($order->info['date_purchased']); ?></div>
<br class="clearBoth" />

<table  width="100%" id="productTable" summary="Itemized listing of previous order, includes number ordered, items and prices">
<caption><h2 id="orderHistoryDetailedOrder"><?php echo HEADING_TITLE . ORDER_HEADING_DIVIDER . sprintf(HEADING_ORDER_NUMBER, $_GET['order_id']); ?></h2></caption>
    <tr class="tableHeading">
        <th scope="col" id="scProductsHeading" class="productImage">&nbsp;</th>
        <th scope="col" id="myAccountProducts" class="productDetail"><?php echo HEADING_PRODUCTS; ?></th>
        <th scope="col" id="myAccountPrice"    class="productPrice">Price</th>
        <th scope="col" id="myAccountQuantity" class="productQuantity"><?php echo HEADING_QUANTITY; ?></th>
		<?php
        if (sizeof($order->info['tax_groups']) > 1) {?>
            <th scope="col" id="myAccountTax"  class="productTax"><?php echo HEADING_TAX; ?></th><?php
        }
        ?>
        <th scope="col" id="myAccountTotal" class="productTotal"><?php echo HEADING_TOTAL; ?></th>
    </tr>
    
	<?php
  	for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
		$rowClass = (($i / 2) == floor($i / 2)) ? "rowEven" : "rowOdd";
  		?>
        <tr>
            <td class="productImage <?php echo $rowClass; ?>"><?php echo getProductImage($order->products[$i]['id'],$order->products[$i]['attributes'],$order->products[$i]['name']); ?></td>
            <td class="productDetail <?php echo $rowClass; ?>"><div id="title"><?php echo  $order->products[$i]['name']; ?></div>
           	 	<?   
				if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
				  echo '<ul class="productAttributes">';
				  for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
					echo '<li>' . $order->products[$i]['attributes'][$j]['option'] . TEXT_OPTION_DIVIDER . nl2br(zen_output_string_protected($order->products[$i]['attributes'][$j]['value'])) . '</li>';
				  }
				  echo '</ul>';
				}
				?>
            </td>
            <td class="productPrice <?php echo $rowClass; ?>"><?php echo $currencies->format($order->products[$i]['price']); ?></td>
            <td class="productQuantity <?php echo $rowClass; ?>"><?php echo  $order->products[$i]['qty'] . QUANTITY_SUFFIX; ?></td>
            <?php
                if (sizeof($order->info['tax_groups']) > 1) { ?>
                    <td class="productTax <?php echo $rowClass; ?>"><?php echo zen_display_tax_value($order->products[$i]['tax']) . '%' ?></td><?php
                }
            ?>
            <td class="productTotal <?php echo $rowClass; ?>"><?php echo $currencies->format(zen_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ($order->products[$i]['onetime_charges'] != 0 ? '<br />' . $currencies->format(zen_add_tax($order->products[$i]['onetime_charges'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) : '') ?></td>
        </tr>
        <?php
	}
?>
</table>
<hr />
<div id="orderTotals">
<?php
  for ($i=0, $n=sizeof($order->totals); $i<$n; $i++) {
?>
     <div class="amount larger forward"><?php echo $order->totals[$i]['text'] ?></div>
     <div class="lineTitle larger forward"><?php echo $order->totals[$i]['title'] ?></div>
<br class="clearBoth" />
<?php
  }
?>

</div>

<?php
/**
 * Used to display any downloads associated with the cutomers account
 */
  if (DOWNLOAD_ENABLED == 'true') require($template->get_template_dir('tpl_modules_downloads.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_downloads.php');
?>


<?php
/**
 * Used to loop thru and display order status information
 */
if (sizeof($statusArray)) {
?>

<table border="0" width="100%" cellspacing="0" cellpadding="0"  id="productTable" summary="Table contains the date, order status and any comments regarding the order">
<caption><h2 id="orderHistoryStatus"><?php echo HEADING_ORDER_HISTORY; ?></h2></caption>
    <tr class="tableHeading">
        <th scope="col" id="myAccountStatusDate"><?php echo TABLE_HEADING_STATUS_DATE; ?></th>
        <th scope="col" id="myAccountStatus"><?php echo TABLE_HEADING_STATUS_ORDER_STATUS; ?></th>
        <th scope="col" id="myAccountStatusComments"><?php echo TABLE_HEADING_STATUS_COMMENTS; ?></th>
       </tr>
	<?php
    $i = 0;
  	foreach ($statusArray as $statuses) {
		$rowClass = (($i / 2) == floor($i / 2)) ? "rowEven" : "rowOdd";
	  	$i++;
		
  
	?>
    <tr>
        <td class="<?= $rowClass; ?>"><?php echo zen_date_short($statuses['date_added']); ?></td>
        <td class="<?= $rowClass; ?>"><?php echo $statuses['orders_status_name']; ?></td>
        <td class="<?= $rowClass; ?>"><?php echo (empty($statuses['comments']) ? '&nbsp;' : nl2br(zen_output_string_protected($statuses['comments']))); ?></td> 
     </tr>
<?php
  }
?>
</table>
<?php } ?>

<hr />
<div id="myAccountShipInfo" class="floatingBox back">
<?php
  if ($order->delivery != false) {
?>
<h3><?php echo HEADING_DELIVERY_ADDRESS; ?></h3>
<address><?php echo zen_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br />'); ?></address>
<?php
  }
?>

<?php
    if (zen_not_null($order->info['shipping_method'])) {
?>
<h4><?php echo HEADING_SHIPPING_METHOD; ?></h4>
<div><?php echo $order->info['shipping_method']; ?></div>
<?php } else { // temporary just remove these 4 lines ?>
<div>WARNING: Missing Shipping Information</div>
<?php
    }
?>
</div>

<div id="myAccountPaymentInfo" class="floatingBox forward">
<h3><?php echo HEADING_BILLING_ADDRESS; ?></h3>
<address><?php echo zen_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br />'); ?></address>

<h4><?php echo HEADING_PAYMENT_METHOD; ?></h4>
<div><?php echo $order->info['payment_method']; ?></div>
</div>



</div> <!-- END centerColumnPadding -->
<div class="shadow" id="bottom" ></div>
</div> <!-- END centerColumnContent -->
</div> <!-- END centerColumn -->





<? 
function getProductImage($productID,$productAttributes,$productName){
	global $db;

	// get the selected color attribute
	foreach($productAttributes as $att){
		if(strtolower($att["option"]) == "color"){
			$option_id = $att["option_id"];
		}
	}
	
	
	// Get the ID of the default color value, and the default image of the attribute (options_id = 1 is the id of the "color" attributes list)
	// Ordering the results by "attributes_default" will make the default item show up first, but if it doesn't exist, then another one will still be selected at random.
	$query = $db->Execute("SELECT options_values_id,attributes_image 
		 FROM zen_products_attributes 
		 WHERE attributes_image != '' 
	     AND products_id = " . $productID . " 
		 AND options_id = $option_id 
		 ORDER BY attributes_default DESC 
		 LIMIT 1");
	while (!$query->EOF) {			
		$src = $query->fields['attributes_image'];
		$query->MoveNext();
	}
	$tinyImage = calculateOptimizedImage($src,"TINY");
	$tinyImage = str_replace("images/","",$tinyImage);
	$productsImage = (IMAGE_SHOPPING_CART_STATUS == 1 ? zen_image(DIR_WS_IMAGES . $tinyImage, $productName, IMAGE_SHOPPING_CART_WIDTH, IMAGE_SHOPPING_CART_HEIGHT) : '');
	
	return $productsImage;
}
?>
