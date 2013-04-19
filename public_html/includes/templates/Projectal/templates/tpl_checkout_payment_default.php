<script>
activateNav('nav-shop');
</script>
<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=checkout_payment.<br />
 * Displays the allowed payment modules, for selection by customer.
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_checkout_payment_default.php 5414 2006-12-27 07:51:03Z drbyte $
 */
?>
<?php echo $payment_modules->javascript_validation(); ?>
<div class="centerColumn" id="checkoutPayment">


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
        <li class="step2"><a href="index.php?main_page=checkout_shipping">Edit</a> Shipping Rates</li>
        <li class="step3" id="active"><a href="index.php?main_page=checkout_payment">Payment Info</a></li>
        <li class="step4"><a href="index.php?main_page=checkout_confirmation">Order</a></li> 
        <!--<li class="step5"><a href="index.php?main_page=checkout_confirmation">Order</a></li>--> 
    </ul>

<?php } else {  ?>

<ul class="checkoutSteps">
	<li class="step1" id="completed"><a href="<?php echo $editShippingButtonLink; ?>">Edit</a> Shipping Rates</li>
	<li class="step2" id="active">Payment Info</li>
	<li class="step3"><a href="index.php?main_page=checkout_confirmation">Order</a></li> 
</ul>
<?php } ?>


<div class="centerColumnContent whiteContent" id="checkoutStepsAbove">
<div class="centerColumnPadding">





<?php echo zen_draw_form('checkout_payment', zen_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'), 'post', ($flagOnSubmit ? 'onsubmit="return check_form();"' : '')); ?>

<h1 id="checkoutPaymentHeading"><?php echo HEADING_TITLE; ?></h1>



<?php if ($messageStack->size('redemptions') > 0) echo $messageStack->output('redemptions'); ?>
<?php if ($messageStack->size('checkout') > 0) echo $messageStack->output('checkout'); ?>
<?php if ($messageStack->size('checkout_payment') > 0) echo $messageStack->output('checkout_payment'); ?>

<?php
  if (DISPLAY_CONDITIONS_ON_CHECKOUT == 'true') {
?>
<fieldset>
<h2><?php echo TABLE_HEADING_CONDITIONS; ?></h2>
<div><?php echo TEXT_CONDITIONS_DESCRIPTION;?></div>
<?php echo  zen_draw_checkbox_field('conditions', '1', false, 'id="conditions"');?>
<label class="checkboxLabel" for="conditions"><?php echo TEXT_CONDITIONS_CONFIRM; ?></label>
</fieldset>
<?php
  }
?>


<!-- HIDING THE TOTAL BECAUSE WE'LL HAVE THE FLOATING SHOPPINGCART BOX ON THE RIGHT -->  
<!--
<fieldset id="checkoutOrderTotals">
<h2 id="checkoutPaymentHeadingTotal"><?php echo TEXT_YOUR_TOTAL; ?></h2>
<?php
  if (MODULE_ORDER_TOTAL_INSTALLED) {
    $order_totals = $order_total_modules->process();
?>
<?php $order_total_modules->output(); ?>
<?php
  }
?>
</fieldset>
-->



<div class="checkoutIndent">
<!-- //////////////////////////////////////////////////////////////// -->
<!-- //////////////// BEGIN DISCOUNT CODE SECTION /////////////////// -->
<!-- //////////////////////////////////////////////////////////////// -->
<?php
  $selection =  $order_total_modules->credit_selection();
  if (sizeof($selection)>0) {
    for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
      if ($_GET['credit_class_error_code'] == $selection[$i]['id']) {
		?>
		<div class="messageStackError"><?php echo zen_output_string_protected($_GET['credit_class_error']); ?></div>
		
		<!-- NEW BLOCK OF CODE REPLACED WITH IntegratedCOWOA -->
		<?php
      }
      for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) {
		?>
		<?php if(!($COWOA && $selection[$i]['module']==MODULE_ORDER_TOTAL_GV_TITLE)) {?>
          <fieldset>
          <h2 id="checkoutPaymentHeadingDiscountCode"><?php echo $selection[$i]['module']; ?></h2>
          <?php echo $selection[$i]['redeem_instructions']; ?>
          <div class="gvBal larger"><?php echo $selection[$i]['checkbox']; ?></div>
          <label style="display:none;" class="inputLabel"<?php echo ($selection[$i]['fields'][$j]['tag']) ? ' for="'.$selection[$i]['fields'][$j]['tag'].'"': ''; ?>><?php echo $selection[$i]['fields'][$j]['title']; ?></label>
          <?php echo $selection[$i]['fields'][$j]['field']; ?>
          </fieldset>
          <?php 
	   } ?>
	   <?php
     }
  }
?>
<!-- END NEW BLOCK OF CODE -->

<!-- OLD BLOCK OF CODE -->
<?php
/*
      }
      for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) {
		?>
		
		<!-- Discount Codes -->
		<fieldset>
		<h2><?php echo $selection[$i]['module']; ?></h2>
        <!-- add legend_tagline div around the instructions - Paul Stroot -->
		<div id="legend_tagline"><?php echo $selection[$i]['redeem_instructions']; ?></div>
		<div class="gvBal larger"><?php echo $selection[$i]['checkbox']; ?></div>
		<!--<label class="inputLabel"<?php echo ($selection[$i]['fields'][$j]['tag']) ? ' for="'.$selection[$i]['fields'][$j]['tag'].'"': ''; ?>><?php echo $selection[$i]['fields'][$j]['title']; ?></label>-->
		<?php echo $selection[$i]['fields'][$j]['field']; ?>
		</fieldset>
		<?php
      }
    }
	*/
?>
<!-- END OLD BLOCK OF CODE -->




<?php
    }
?>
<!-- //////////////////////////////////////////////////////////////// -->
<!-- /////////////////// END DISCOUNT CODE SECTION ////////////////// -->
<!-- //////////////////////////////////////////////////////////////// -->
</div>


<div class="checkoutIndent">
<!-- //////////////////////////////////////////////////////////////// -->
<!-- /////////////// BEGIN PAYMENT TYPE RADIO SELECTION ///////////// -->
<!-- //////////////////////////////////////////////////////////////// -->
<?php // ** BEGIN PAYPAL EXPRESS CHECKOUT **
if (!$payment_modules->in_special_checkout()) {
// ** END PAYPAL EXPRESS CHECKOUT ** ?>
      
<fieldset>
<h2 id="checkoutPaymentHeadingPaymentMethods"><?php echo TABLE_HEADING_PAYMENT_METHOD; ?></h2>

<?php // DISPLAY THE "We Accept: Visa, AmEx, Discover, Matercard"

if (SHOW_ACCEPTED_CREDIT_CARDS != '0') {
?>

	<?php
	
    if (SHOW_ACCEPTED_CREDIT_CARDS == '1') {
      echo TEXT_ACCEPTED_CREDIT_CARDS . zen_get_cc_enabled();
    }
    if (SHOW_ACCEPTED_CREDIT_CARDS == '2') {
      echo TEXT_ACCEPTED_CREDIT_CARDS . zen_get_cc_enabled('IMAGE_');
    }
	?>
	<br class="clearBoth" />
<?php } ?>

<?php // Show the Tagline under "Payment Method"
$selection = $payment_modules->selection();

if (sizeof($selection) > 1) {
	?>
	<div id="legend_tagline"><?php echo TEXT_SELECT_PAYMENT_METHOD; ?></div>
	<?php
} elseif (sizeof($selection) == 0) {
	?>
	<div id="legend_tagline"><?php echo TEXT_NO_PAYMENT_OPTIONS_AVAILABLE; ?></div>
	<?php
} ?>

<?php // Display Each Option for payment
for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
	if (sizeof($selection) > 1) {
		if (empty($selection[$i]['noradio'])) {
 			echo zen_draw_radio_field('payment', $selection[$i]['id'], ($selection[$i]['id'] == $_SESSION['payment'] ? true : false), 'id="pmt-'.$selection[$i]['id'].'" onclick="changePaymentType(this)"'); 
		} 
	} else {
		echo zen_draw_hidden_field('payment', $selection[$i]['id']);
		
    }
	?>
	<label for="pmt-<?php echo $selection[$i]['id']; ?>" class="radioButtonLabel">
	<?php 
	if($selection[$i]['id'] == "authorizenet_aim" || $selection[$i]['id'] == "authorizenet_sim"){
		echo zen_get_cc_enabled('IMAGE_');
	} else {
		echo $selection[$i]['module'];
	} 
	?>
    </label>
    <BR />    
<? }  ?>
</fieldset>


<?php
$radio_buttons = 0;
for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
    if (defined('MODULE_ORDER_TOTAL_COD_STATUS') && MODULE_ORDER_TOTAL_COD_STATUS == 'true' and $selection[$i]['id'] == 'cod') {
        ?>
        <div class="alert"><?php echo TEXT_INFO_COD_FEES; ?></div>
        <?php
    } else {
        // echo 'WRONG ' . $selection[$i]['id'];
    }
    ?>


    
    <?php
	if (isset($selection[$i]['error'])) {
		?>
		<div><?php echo $selection[$i]['error']; ?></div>
		<?php
	} elseif (isset($selection[$i]['fields']) && is_array($selection[$i]['fields'])) {
		
		?>
    	<div class="ccinfo" id='details_<?php echo $selection[$i]['id']; ?>'  style='display:none;'>
		<fieldset>
		<?php
        for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) {
            ?>
            <label <?php echo (isset($selection[$i]['fields'][$j]['tag']) ? 'for="'.$selection[$i]['fields'][$j]['tag'] . '" ' : ''); ?>class="inputLabelPayment"><?php echo $selection[$i]['fields'][$j]['title']; ?></label><?php echo $selection[$i]['fields'][$j]['field']; ?>
            <br class="clearBoth" />
            <?php
        }
        ?>
        </fieldset>        
		</div>
        <?php

    }
    $radio_buttons++;

}
?>
<script>getSelectedRadio("checkout_payment","payment");</script>

<?php // ** BEGIN PAYPAL EXPRESS CHECKOUT **
} else {
	?><input type="hidden" name="payment" value="<?php echo $_SESSION['payment']; ?>" /><?php
}
 // ** END PAYPAL EXPRESS CHECKOUT ** ?>

<!-- //////////////////////////////////////////////////////////////// -->
<!-- //////////////// END PAYMENT TYPE RADIO SELECTION ////////////// -->
<!-- //////////////////////////////////////////////////////////////// -->
</div>


<div class="checkoutIndent">
<!-- //////////////////////////////////////////////////////////////// -->
<!-- ///////////////////// BEGIN BILLING ADDRESS //////////////////// -->
<!-- //////////////////////////////////////////////////////////////// -->
<?php // ** BEGIN BILLING ADDRESS **
if (!$payment_modules->in_special_checkout()) {
    ?>
	<fieldset>
    <h2 id="checkoutPaymentHeadingAddress"><?php echo TITLE_BILLING_ADDRESS; ?></h2>

    <div id="checkoutBillto" class="floatingBox back"  style="float:left;">
        
       
        <address><?php echo zen_address_label($_SESSION['customer_id'], $_SESSION['billto'], true, ' ', '<br />'); ?></address>
   
    	<?php if (MAX_ADDRESS_BOOK_ENTRIES >= 2) { ?>
        <div class=""><?php echo '<a href="' . zen_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '" class="fancybutton blueButton">' . BUTTON_CHANGE_ADDRESS_ALT . '</a>'; ?></div>
        <?php } ?>
        
    </div>
    
    <div style="font-size:16px;font-weight:bold;line-height:22px;">
	The billing address should match the address on your credit card statement. 
    </div>
    <div style="font-size:14px;margin-top:10px;">
    This may be different from your shipping address. You can change the billing address by clicking the <em><b><a href="<?php echo zen_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL'); ?>">Change Address</a></b></em> button.
    </div>
    <br class="clearBoth" />
    </fieldset>
	<?php 
	
}
// ** END BILLING ADDRESS ** ?>     
<!-- //////////////////////////////////////////////////////////////// -->
<!-- ///////////////////// END BILLING ADDRESS //////////////////// -->
<!-- //////////////////////////////////////////////////////////////// -->
</div>



<!-- //////////////////////////////////////////////////////////////// -->
<!-- ///////////////////// BEGIN CHARITY //////////////////// -->
<!-- //////////////////////////////////////////////////////////////// -->

<div class="checkoutIndent">
<?php 
if (!$payment_modules->in_special_checkout()) {
    ?>
	<fieldset>
    <h2 id="checkoutPaymentHeadingAddress">Charity</h2>

    <div id="checkoutBillto" class="floatingBox back"  style="float:left;">
        <address>Select Charity</address>
        <div class="">
           <select name="charity">
           <?php
            $query = "select * FROM charities WHERE isActive = 1 ORDER BY theOrder";
    		$Results = $db->Execute($query);
			while (!$Results->EOF) {
           		echo '<option value="'.$Results->fields['charity_id'].'">'.$Results->fields['name'].'</option>\n';
				$Results->MoveNext();
			}	
		   ?>
		   </select>
        </div>
    </div>
    
    <div class=" " style="font-size:11.5px;">
        A percentage of the proceeds from your order are donated to a charity of your choice. <BR />
        Don't worry, this is already factored into the cost of your product. <BR />
        <a href="index.php?main_page=contact_us&regarding=charity">Suggest a charity.</a>
    </div>
    <br class="clearBoth" />
    </fieldset>
	<?php 
}
// ** END BILLING ADDRESS ** ?>     
</div>

<!-- //////////////////////////////////////////////////////////////// -->
<!-- ///////////////////// END CHARITY ////////////////////////////// -->
<!-- //////////////////////////////////////////////////////////////// -->
     
     
     
     
<!-- //////////////////////////////////////////////////////////////// -->
<!-- ///////////////////// BEGIN SPECIAL INSTRUCTIONS//////////////// -->
<!-- //////////////////////////////////////////////////////////////// -->
<!-- commented out by Paul Stroot -->      
<!--
<div class="checkoutIndent">
    <fieldset>
    <h2><?php echo TABLE_HEADING_COMMENTS; ?></h2>
    <?php echo zen_draw_textarea_field('comments', '45', '3'); ?>
    </fieldset>
</div>
-->
<!-- //////////////////////////////////////////////////////////////// -->
<!-- ///////////////////// END SPECIAL INSTRUCTIONS//////////////// -->
<!-- //////////////////////////////////////////////////////////////// -->


<div class="checkoutIndent" style="padding-top:10px;padding-bottom: 10px;border-bottom:none;">
	<input type="submit" name="Next Step, Billing" value="Last Step, Order" class="fancybutton greenButton" style="float:left;" onclick="submitFunction(<?php echo zen_user_has_gv_account($_SESSION['customer_id']); ?>,<?php echo $order->info['total']; ?>)" />
	
    <div class=" " style="font-size:11.5px;margin-left:300px;">
    	(You will be able to confirm your order before submitting payment)
    </div>
	<?php // echo zen_image_submit(BUTTON_IMAGE_CONTINUE_CHECKOUT_2, BUTTON_CONTINUE_ALT, 'onclick="submitFunction('.zen_user_has_gv_account($_SESSION['customer_id']).','.$order->info['total'].')"'); ?>
    <!--
    <div class="buttonRow back"><?php echo TITLE_CONTINUE_CHECKOUT_PROCEDURE . '<br />' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></div>
    -->
</div>
</form>





</div> <!-- END centerColumnPadding -->
<div class="shadow" id="bottom" ></div>
</div> <!-- END centerColumnContent -->
</div> <!-- END centerColumn -->


