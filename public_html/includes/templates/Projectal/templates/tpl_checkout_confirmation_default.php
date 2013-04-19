<script>
activateNav('nav-shop');
</script>
<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=checkout_confirmation.<br />
 * Displays final checkout details, cart, payment and shipping info details.
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_checkout_confirmation_default.php 6247 2007-04-21 21:34:47Z wilt $
 */
 
 
 

 // replace the product image with the colored tshirt image if it exists.
for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {	 	// loop through all of the products in our cart
	$order->products[$i]['productsImage'] = ""; // set the image to nothing for starters
	
	// get the ID of the image
	if(strpos($order->products[$i]["id"],":") > 0){
		$theProductID =substr($order->products[$i]["id"],0,strpos($order->products[$i]["id"],":"));
	} else {
		$theProductID = $order->products[$i]["id"];
	}
	
	// get the default image first.
	$query = "SELECT products_image  FROM " . TABLE_PRODUCTS . " WHERE products_id = ".$theProductID."";
	$results = $db->Execute($query);
	if($results->fields['products_image'] != ""){ 
		$thisSrc = calculateOptimizedImage($results->fields['products_image'],"TINY"); // check to see if a TINY image exists.			
		$order->products[$i]['productsImage'] = '<img src="'.$thisSrc .'" alt="'. $order->products['name'].'" title="'. $order->products['name'].'" width="50"  />';
	}				   
							   
	if (isset($order->products[$i]['attributes']) && is_array($order->products[$i]['attributes'])) { // IF this has attributes
		 foreach ($order->products[$i]['attributes'] as $option => $value) { // loop through each attribute, and query the database to get the attribute image for it
			if($value["option_id"] == 1){
				$attributes = "SELECT poval.products_options_values_name, poval.products_options_values_id, pa.options_id, pa.attributes_image
							   FROM " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
							   WHERE pa.options_values_id = ".$value["value_id"]."	
							   AND pa.products_id = ".$theProductID."							 
							   AND pa.options_id = 1					 
							   AND pa.options_values_id = poval.products_options_values_id
							   AND poval.language_id = ".$_SESSION['languages_id'];
				$attributes_values = $db->Execute($attributes);
				if($attributes_values->fields['options_id'] == "1" && $attributes_values->fields['attributes_image'] != ""){ // IF the attribute is a COLOR attribute (id = 1), and an image exists, then replace the "productImage" value in the products array
					$thisSrc = calculateOptimizedImage($attributes_values->fields['attributes_image'],"TINY"); // check to see if a TINY image exists.			
					$order->products[$i]['productsImage'] = '<img src="'.$thisSrc .'" alt="'. $order->products['name'].'" title="'. $order->products['name'].'" width="50"  />';
				}
		
			}
		}	
	}
}




?>
<div class="centerColumn" id="checkoutConfirmDefault">

<?php if (DEFINE_BREADCRUMB_STATUS == '1' || (DEFINE_BREADCRUMB_STATUS == '2' && !$this_is_home_page) ) { ?>
<div class="breadcrumbs">
	<?php 
	//unset($breadcrumb->_trail[1]); // remove the first level category, which is the city
	$breadcrumb->_trail= array_values($breadcrumb->_trail); // reassigns the array indicies so there isn't a hole.
	echo $breadcrumb->trail(BREAD_CRUMBS_SEPARATOR); ?><span class="activeCrumb"></span>
</div>
<?php } ?>
<!-- eof breadcrumb -->


<?php if($_SESSION['COWOA']){ ?>
    <ul class="checkoutSteps" id="fourSteps">
        <li class="step1"><a href="index.php?main_page=no_account">Edit</a> Shipping Address</li>
        <li class="step2"><a href="<?=$editShippingButtonLink;?>">Edit</a> Shipping Rates</li>
        <li class="step3"><a href="index.php?main_page=checkout_payment">Edit</a> Payment Info</li>
        <li class="step4" id="active">Order</li>
        <!--<li class="step5"><a href="index.php?main_page=checkout_confirmation">Order</a></li>--> 
    </ul>

<?php } else { ?>
<ul class="checkoutSteps">
	<li class="step1" id="completed"><a href="<?=$editShippingButtonLink;?>">Edit</a> Shipping Rates</li>
	<li class="step2" id="completed"><a href="<?php echo zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'); ?>">Edit</a> Payment Info</li>
	<li class="step3" id="active">Order</li> 
</ul>
<? } ?>



<div class="centerColumnContent whiteContent" id="checkoutStepsAbove">
<div class="centerColumnPadding">





<h1 id="checkoutConfirmDefaultHeading" style="margin-bottom:30px;"><?php echo HEADING_TITLE; ?></h1>




<?php if ($messageStack->size('redemptions') > 0) echo $messageStack->output('redemptions'); ?>
<?php if ($messageStack->size('checkout_confirmation') > 0) echo $messageStack->output('checkout_confirmation'); ?>
<?php if ($messageStack->size('checkout') > 0) echo $messageStack->output('checkout'); ?>



<!-- //////////////////////////////////////////////////////////////// -->
<!-- /////////////////// BEGIN SHIPPING INFORMATION ///////////////// -->
<!-- //////////////////////////////////////////////////////////////// -->
<?php
if ($_SESSION['sendto'] != false) {
?>
<div class="leftAlignedEditLink"><a href="<?php echo $editShippingButtonLink; ?>">EDIT</a></div>
<div class="indentOrderDetails">
	<h2 id="checkoutConfirmDefaultShippingAddress"><?php echo HEADING_DELIVERY_ADDRESS; ?></h2>
	<address><?php echo zen_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br />'); ?></address>

	<?php
	if ($order->info['shipping_method']) {
		?>
		<h2 id="checkoutConfirmDefaultShipment"><?php echo HEADING_SHIPPING_METHOD; ?></h2>
		<h4 id="checkoutConfirmDefaultShipmentTitle"><?php echo $order->info['shipping_method']; ?></h4>
		<?php
	}
	?>
	<?php
}
?>
</div>
<!-- //////////////////////////////////////////////////////////////// -->
<!-- ////////////////////// END SHIPPING METHOD ///////////////////// -->
<!-- //////////////////////////////////////////////////////////////// -->
    




<!-- //////////////////////////////////////////////////////////////// -->
<!-- //////////////////// BEGIN BILLING INFORMATION ///////////////// -->
<!-- //////////////////////////////////////////////////////////////// -->
<div class="leftAlignedEditLink">
	<?php if (!$flagDisablePaymentAddressChange) { ?>
    <a href="<?php echo zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'); ?>">EDIT</a>
    <? } ?>
</div>
<div class="indentOrderDetails">
    <h2 id="checkoutConfirmDefaultBillingAddress"><?php echo HEADING_BILLING_ADDRESS; ?></h2>
    <address><?php echo zen_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br />'); ?></address>
	<?php
      $class =& $_SESSION['payment'];
    ?>
</div>
<!-- //////////////////////////////////////////////////////////////// -->
<!-- ////////////////////// END BILLING INFORMATION ///////////////// -->
<!-- //////////////////////////////////////////////////////////////// -->


<!-- //////////////////////////////////////////////////////////////// -->
<!-- ////////////////////// BEGIN PAYMENT METHOD ///////////////// -->
<!-- //////////////////////////////////////////////////////////////// -->
<div class="leftAlignedEditLink">
	<?php if (!$flagDisablePaymentAddressChange) { ?>
    <a href="<?php echo zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'); ?>">EDIT</a>
    <? } ?>
</div>
<div class="indentOrderDetails">
    <h2 id="checkoutConfirmDefaultPayment"><?php echo HEADING_PAYMENT_METHOD; ?></h2> 
    <? if (strtoupper($GLOBALS[$class]->title) == "PAYPAL"){ ?>
    	 <img style='vertical-align:middle;' src='<?php echo DIR_WS_TEMPLATE; ?>images/icons/paypal.png' alt='PayPal' />
    <? } ?>
    <!-- <h4 id="checkoutConfirmDefaultPaymentTitle"><?php echo $GLOBALS[$class]->title; ?></h4> -->
    

    <?php
    if (is_array($payment_modules->modules)) {
        if ($confirmation = $payment_modules->confirmation()) {
            ?>
            <div class="important"><?php echo $confirmation['title']; ?></div>
            <?php
        }
        for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
			if(strtoupper($confirmation['fields'][$i]['title']) == "CREDIT CARD TYPE:"){
				switch (strtoupper($confirmation['fields'][$i]['field'])){
					case "VISA":
						$img="cc1.gif"; break;
					case "MASTERCARD":
						$img="cc2.gif"; break;
					case "AMERICAN EXPRESS":
						$img="cc3.gif"; break;
					case "DISCOVER":
						$img="cc5.gif"; break;
							
				}
				echo "<img style='vertical-align:middle;' src='" . DIR_WS_TEMPLATE . "images/icons/$img' alt='".$confirmation['fields'][$i]['field']."' />";
			} else if(strlen(strstr($confirmation['fields'][$i]['title'],"Credit Card Number"))>0){
            	echo $confirmation['fields'][$i]['field'];
			} else { 
				?>
                <!-- 
                <div class="back">-<?php echo $confirmation['fields'][$i]['title']; ?>-</div>
                <div><?php echo $confirmation['fields'][$i]['field']; ?></div>
                -->
                <?php
			}
			
        }
    }
    ?>
    
    <br class="clearBoth" />

<!-- //////////////////////////////////////////////////////////////// -->
<!-- ////////////////////// END PAYMENT METHOD ///////////////// -->
<!-- //////////////////////////////////////////////////////////////// -->
</div>



<!-- //////////////////////////////////////////////////////////////// -->
<!-- //////////////////// BEGIN CHARITY INFORMATION ///////////////// -->
<!-- //////////////////////////////////////////////////////////////// -->
 <?php 
 if (isset($_SESSION["charity"])) { 
 	$charitySQL = "SELECT name, description FROM charities WHERE charity_id = ".$_SESSION["charity"];
	$charity_values = $db->Execute($charitySQL);
	$charity_name = $charity_values->fields['name'];
	$charity_description = $charity_values->fields['description'];
 	?>
	<div class="leftAlignedEditLink">
        <a href="<?php echo zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'); ?>">EDIT</a>
    </div>
    
    <div class="indentOrderDetails">
        <h2 id="checkoutConfirmDefaultBillingCharity">Charity:</h2>
		<h4 id="checkoutConfirmDefaultShipmentTitle"><?php echo $charity_name; ?></h4>
        <div style="margin-top:5px;"><?php echo $charity_description; ?></div>         
    </div>
    <?php
}
?>
<!-- //////////////////////////////////////////////////////////////// -->
<!-- ////////////////////// END CHARITY INFORMATION ///////////////// -->
<!-- //////////////////////////////////////////////////////////////// -->



<?php
// always show comments
if ($order->info['comments']) {
	?>	
	<h2 id="checkoutConfirmDefaultHeadingComments"><?php echo HEADING_ORDER_COMMENTS; ?></h2>
	<div class="buttonRow forward"><?php echo  '<a href="' . zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '">' . zen_image_button(BUTTON_IMAGE_EDIT_SMALL, BUTTON_EDIT_SMALL_ALT) . '</a>'; ?></div>
	<div><?php echo (empty($order->info['comments']) ? NO_COMMENTS_TEXT : nl2br(zen_output_string_protected($order->info['comments'])) . zen_draw_hidden_field('comments', $order->info['comments'])); ?></div>
	<br class="clearBoth" />
	<hr />
	<?php
}
?>


<h2 id="checkoutConfirmDefaultHeadingCart"><?php echo HEADING_PRODUCTS; ?></h2>

<div class="buttonRow forward"><?php echo '<a href="' . zen_href_link(FILENAME_SHOPPING_CART, '', 'SSL') . '" class="fancybutton blueButton small">' . BUTTON_EDIT_SMALL_ALT . '</a>'; ?></div>
<br class="clearBoth" />

<?php  if ($flagAnyOutOfStock) { ?>
<?php    if (STOCK_ALLOW_CHECKOUT == 'true') {  ?>
<div class="messageStackError"><?php echo OUT_OF_STOCK_CAN_CHECKOUT; ?></div>
<?php    } else { ?>
<div class="messageStackError"><?php echo OUT_OF_STOCK_CANT_CHECKOUT; ?></div>
<?php    } //endif STOCK_ALLOW_CHECKOUT ?>
<?php  } //endif flagAnyOutOfStock ?>


      <table border="1" width="100%" cellspacing="5" cellpadding="0" id="productTable">
        <tr class="cartTableHeading">
        <th scope="col" id="ccImageHeading" width="30"></th>
        <th scope="col" id="ccProductsHeading"><?php echo TABLE_HEADING_PRODUCTS; ?></th>
        <th scope="col" id="ccDescriptionHeading">Description</th>
        <th scope="col" id="ccPriceHeading">Price</th>
        <th scope="col" id="ccQuantityHeading" width="30"><?php echo TABLE_HEADING_QUANTITY; ?></th>
			<?php
              // If there are tax groups, display the tax columns for price breakdown
              if (sizeof($order->info['tax_groups']) > 1) {
            ?>
          <th scope="col" id="ccTaxHeading"><?php echo HEADING_TAX; ?></th>
			<?php
              }
            ?>
          <th scope="col" id="ccTotalHeading"><?php echo TABLE_HEADING_TOTAL; ?></th>
        </tr>
        
        
<?php // now loop thru all products to display quantity and price ?>
<?php for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {  ?>
        <tr class="">
          <td  class="cartImage <?php echo $order->products[$i]['rowClass']; ?>">
          	<?php 
			echo $order->products[$i]['productsImage']; ?>
          </td>
          <td class="cartProductDisplay <?php echo $order->products[$i]['rowClass']; ?>">
		  <div id="productTitle"><?php echo $order->products[$i]['name']; ?></div>
         <!-- by mathiole, Mr Rocks, <BR />
          francobolli, and arzie13 -->
          <?php  echo $stock_check[$i]; ?>

        </td> 
        
        <td  class="cartDescription <?php echo $order->products[$i]['rowClass']; ?>">
			<?php
            	$query = "SELECT products_model  FROM " . TABLE_PRODUCTS . " WHERE products_id = ".$theProductID."";
				$results = $db->Execute($query);
				echo $results->fields['products_model'] . "<BR>";
					
					 // if there are attributes, loop thru them and display one per line
                if (isset($order->products[$i]['attributes']) && sizeof($order->products[$i]['attributes']) > 0 ) {

                  for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {  
						print $order->products[$i]['attributes'][$j]["option"] . ": ";            			
						if($order->products[$i]['attributes'][$j]['option_id'] == 2){
							if(strtoupper($order->products[$i]['attributes'][$j]['value']) == "S") print "Small";
							if(strtoupper($order->products[$i]['attributes'][$j]['value']) == "M") print "Medium";
							if(strtoupper($order->products[$i]['attributes'][$j]['value']) == "L") print "Large";
							if(strtoupper($order->products[$i]['attributes'][$j]['value']) == "XL") print "X-Large";
							if(strtoupper($order->products[$i]['attributes'][$j]['value']) == "XXL") print "XX-Large";	
						} else {
							print 	$order->products[$i]['attributes'][$j]['value'];
						}
					print "<BR>" ;
                  } // end loop
                } // endif attribute-info
				           
				?>
            </td>
        <td  class="cartPrice <?php echo $order->products[$i]['rowClass']; ?>"><?= $currencies->display_price($order->products[$i]['final_price']); ?></td>
 		<td  class="cartQuantity <?php echo $order->products[$i]['rowClass']; ?>"><?php echo $order->products[$i]['qty']; ?></td>
         
<?php // display tax info if exists ?>
<?php if (sizeof($order->info['tax_groups']) > 1)  { ?>
        <td class="cartTotalDisplay <?php echo $order->products[$i]['rowClass']; ?>">
          <?php echo zen_display_tax_value($order->products[$i]['tax']); ?>%</td>
<?php    }  // endif tax info display  ?>
        <td class="cartTotalDisplay <?php echo $order->products[$i]['rowClass']; ?>">
          <?php echo $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']);
          if ($order->products[$i]['onetime_charges'] != 0 ) echo '<br /> ' . $currencies->display_price($order->products[$i]['onetime_charges'], $order->products[$i]['tax'], 1);
?>
        </td>
      </tr>
<?php  }  // end for loopthru all products ?>
      </table>
      <hr />

<?php
  if (MODULE_ORDER_TOTAL_INSTALLED) {
    $order_totals = $order_total_modules->process();
?>
<div id="orderTotals"><?php $order_total_modules->output(); ?></div>
<?php
  }
?>

<?php
  echo zen_draw_form('checkout_confirmation', $form_action_url, 'post', 'id="checkout_confirmation" onsubmit="submitonce();"');

  if (is_array($payment_modules->modules)) {
    echo $payment_modules->process_button();
  }
?>
<div class="buttonRow forward"><input type="submit" name="btn_submit" id="btn_submit" value="<?php echo BUTTON_CONFIRM_ORDER_ALT; ?>" title="<?php echo BUTTON_CONFIRM_ORDER_ALT; ?>" class="fancybutton greenButton" /></div>
</form>

<!--
<div class="buttonRow back"><?php echo TITLE_CONTINUE_CHECKOUT_PROCEDURE . '<br />' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></div>
-->





</div> <!-- END centerColumnPadding -->
<div class="shadow" id="bottom" ></div>
</div> <!-- END centerColumnContent -->
</div> <!-- END centerColumn -->