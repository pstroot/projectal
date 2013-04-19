
<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=checkout_shipping.<br />
 * Displays allowed shipping modules for selection by customer.
 *
 * @package templateSystem
 * @copyright Copyright 2003-2009 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_checkout_shipping_default.php 14807 2009-11-13 17:22:47Z drbyte $
 */

?>  
<script type="text/javascript" language="javascript">
	activateNav("nav-shop");
</script>

<script src="<?php echo DIR_WS_TEMPLATE; ?>common/shipping.js"></script>

<div class="centerColumn" id="checkoutShipping">
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
        <li class="step2" id="active">Shipping Rates</li>
        <li class="step3"><a href="index.php?main_page=checkout_payment">Payment Info</a></li>
        <li class="step4"><a href="index.php?main_page=checkout_confirmation">Order</a></li>
        <!--<li class="step5"><a href="index.php?main_page=checkout_confirmation">Order</a></li>--> 
    </ul>

<?php } else { ?>

    <ul class="checkoutSteps">
        <li class="step1" id="active">Shipping Rates</li>
        <li class="step2"><a href="index.php?main_page=checkout_payment">Payment Info</a></li>
        <li class="step3"><a href="index.php?main_page=checkout_confirmation">Order</a></li> 
    </ul>
<?php } ?>


<div class="centerColumnContent whiteContent" id="checkoutStepsAbove">
<div class="centerColumnPadding">



<?php echo zen_draw_form('checkout_address', zen_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL')) . zen_draw_hidden_field('action', 'process'); ?>

<h1 id="checkoutShippingHeading"><?php echo HEADING_TITLE; ?></h1>


<?php if ($messageStack->size('checkout_shipping') > 0) echo $messageStack->output('checkout_shipping'); ?>


<!--
<h2 id="checkoutShippingHeadingAddress"><?php echo TITLE_SHIPPING_ADDRESS; ?></h2>
-->

<div id="checkoutShipto" class="floatingBox back" style="float:left;">
    
    <address class="">
	<?php echo zen_address_label($_SESSION['customer_id'], $_SESSION['sendto'], true, ' ', '<br />'); ?>
    </address>
    
    <?php if ($displayAddressEdit) { ?>
        <div class=""><?php echo '<a href="' . $editShippingButtonLink . '" class="fancybutton blueButton">' . BUTTON_CHANGE_ADDRESS_ALT . '</a>'; ?></div>
    <?php } ?>
</div>

<div style="font-size:11.5px;padding-top:20px;margin-right:250px;color:#999;"><?php echo TEXT_CHOOSE_SHIPPING_DESTINATION; ?></div>
<br class="clearBoth" />

<?php
// if there is at least one shipping module
if (zen_count_shipping_modules() > 0) {
	?>
	<!--<h2 id="checkoutShippingHeadingMethod"><?php echo TABLE_HEADING_SHIPPING_METHOD; ?></h2>-->
	<?php
    if (sizeof($quotes) > 1 && sizeof($quotes[0]) > 1) {
		?>
        <BR /><BR />
		<div id="checkoutShippingContentChoose" class="important"><?php echo TEXT_CHOOSE_SHIPPING_METHOD; ?></div>
		<?php
    } elseif ($free_shipping == false) {
		?>
		<!--<div id="checkoutShippingContentChoose" class="important"><?php echo TEXT_ENTER_SHIPPING_INFORMATION; ?></div>-->
		<?php
    }
	?>
	<?php
    if ($free_shipping == true) {
		// this could be set in admin > modules > order totals > shipping to allow free shipping if the order total is over a certain amount
		?>
		<div id="freeShip" class="important" ><?php echo FREE_SHIPPING_TITLE; ?>&nbsp;<?php echo $quotes[$i]['icon']; ?></div>
		<div id="defaultSelected"><?php echo sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) . zen_draw_hidden_field('shipping', 'free_free'); ?></div>
		<?php
    } else {
		$radio_buttons = 0;
	  
		$shippingToUSA = false; // Check to see if free shipping is available. This would be set up in admin > modules > shipping > Free Shipping Options
		$freeoptionsActive = false; // Check to see if free shipping is available. This would be set up in admin > modules > shipping > Free Shipping Options
		
		// loop through all fo the shipping options to see if "Shipping to US" is an option. If so, set a flag.
		foreach ($quotes as $quote){			 
			foreach ($quote["methods"] as $method){
				if(strpos("-".$method["title"],"Shipping to US") > 0) $shippingToUSA = true;
			}			
		}
		
		// if shipping to the USA, check the shopping optinos to see of "freeoptions" exists, if so, set a flag
		if($shippingToUSA){
			for ($i=0, $n=sizeof($quotes); $i<$n; $i++) {
				if($quotes[$i]["id"] == "freeoptions") $freeoptionsActive = true;
			}
		}
		
		for ($i=0, $n=sizeof($quotes); $i<$n; $i++) {
			
			// allows FedEx to work comment comment out Standard and Uncomment FedEx
			//  if ($quotes[$i]['id'] != '' || $quotes[$i]['module'] != '') { // FedEx
      		if ($quotes[$i]['module'] != '') { // Standard
				?>
				<fieldset>				
				<? 

				// if there is a free shipping option, ONLY display the free shipping option
				if($freeoptionsActive == true && $shippingToUSA == true && $quotes[$i]['module'] != "Free Shipping Options"){
					echo "<h2 id='checkoutShippingHeadingMethod'>You ordered  " . MODULE_SHIPPING_FREEOPTIONS_ITEMS_MIN . " or more shirts so your shipping is free!</h2>";
					continue;
				} else if($freeoptionsActive == false){
					echo "<h2 id='checkoutShippingHeadingMethod'>Our flat shipping rate is " . $currencies->format(zen_add_tax($quotes[$i]['methods'][0]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0)))." for single shirt orders</h2>";		
				} // END if ($quotes[$i]['module'] == "Zone Rates")

				echo "<div class='zone-rate-shipping-description'>";
				if($shippingToUSA == true){
					echo "We ship all domestic orders via USPS First Class or Priority mail. We will do our best to make sure you receive your order within 3-4 business days. ";
				} else {
					echo "We ship all international orders via Global Priority mail.  We will do our best to make sure you receive your order within 7-14 days after placing it, but delays can occur with international shipments. ";
				}
				echo "After your order is placed you will receive an e-mail confirmation with a tracking number.";
				echo "</div>";		

				?>
                
                <!-- SHOW THE TYPE OF SHIPPING (i.e. UPS, Fed Ex, Per Item, USPS...)-->
                <!--<legend><?php echo $quotes[$i]['module']; ?>&nbsp;<?php if (isset($quotes[$i]['icon']) && zen_not_null($quotes[$i]['icon'])) { echo $quotes[$i]['icon']; } ?></legend>-->
                
                <?php
                
                if (isset($quotes[$i]['error'])) {
                    // DISPLAY AN ERROR IF THERE IS ONE
                    ?>
                    <div><?php echo $quotes[$i]['error']; ?></div>
                    <?php
                } else {
                    ?>
                    <div class="moduleRow">
                        <div class="description" id="colHeader">&nbsp;</div>
                        <div class="important cost" id="colHeader">Shipping Cost</div>
                        <div class="important total" id="colHeader">Total - <span style="color:#00556c;font-size:11px;">(With Shipping)</span></div>
                        <br class="clearBoth" />
                    </div>
                    <?
                    
                    // LOOP THROUGH ALL OF THE SHIPPING METHODS AND DISPLAY THEM
                
                    for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++) {
                        
                        // set the radio button to be checked if it is the method chosen
                        $checked = (($quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'] == $_SESSION['shipping']['id']) ? true : false);
                        $rowID = 'ROW_'.$quotes[$i]['id'] . '_' . str_replace(' ', '-', $quotes[$i]['methods'][$j]['id']);
                        if ( ($checked == true) || ($n == 1 && $n2 == 1) ) {
                            $className = "activeModuleRow";
                            echo '<script> selectedShippingRow = "'.$rowID . '";</script>';
                        } else {          
                            $className = "moduleRow";
                        }
                        ?>
                        <div id="<?=$rowID; ?>" class="<?=$className;?>"> 
                        <div class="description">
                        <?
                        echo zen_draw_radio_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'], $checked, 'id="ship-'.$quotes[$i]['id'] . '-' . str_replace(' ', '-', $quotes[$i]['methods'][$j]['id']) .'" onChange="selectShipping(this)"'); 
                        ?>
                        
                        
                        <label for="ship-<?php echo $quotes[$i]['id'] . '-' . str_replace(' ', '-', $quotes[$i]['methods'][$j]['id']); ?>" class="checkboxLabel" ><?php echo $quotes[$i]['methods'][$j]['title']; ?></label>
                        </div>
                        
                        <?
                        // DISPLAY THE PRICE! 
                        if ( ($n > 1) || ($n2 > 1) ) {
                            ?>	
                            <div class="important cost"><?php  echo $currencies->format(zen_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0))); ?></div>
                            <?php
                        } else {
                            ?>
                            <div class="important cost"><?php  echo $currencies->format(zen_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0))) . zen_draw_hidden_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id']); ?></div>
                            <?php
                        }
                        
                        // DISPLAY THE TOTAL! 
                        //if ( ($n > 1) || ($n2 > 1) ) {
                            ?>	
                            <div class="important total"><?= $currencies->format($quotes[$i]['methods'][$j]['cost'] + $_SESSION["cart"]->total) ;?></div>
                            <?php
                        //}
                        ?>
                        <br class="clearBoth" />
                        </div> <!-- END div class="moduleRow" -->
                        <?
                        $radio_buttons++;
                    } // END  for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++)
                
                } // END if (isset($quotes[$i]['error'])) 
                ?>
                </fieldset>
				<?php
				
			} // END if ($quotes[$i]['module'] != '') {
			
		} // END for ($i=0, $n=sizeof($quotes); $i<$n; $i++)
	  				
					
		if($freeoptionsActive == false && $shippingToUSA == true){
			echo "<div class='free-shipping-checkout-message'>";
			print "<div style='float:right;'><input type='button' name='Continue Shopping' value='Continue Shopping' class='fancybutton greyButton' onclick='window.location=\"/index.php?main_page=store\"'/></div>";
			print "If you order  " . MODULE_SHIPPING_FREEOPTIONS_ITEMS_MIN . " or more shirts your shipping is FREE!";	
			echo "</div>";					
		}
	  
		/*
		// DISPLAY FREE SHIPPING INFORMATION TO THE USER IF THE FREE SHIPPING MODULE IS NOT VISIBLE
		if($freeoptionsActive == false){ ?>
			<legend>Free Shipping Options&nbsp;</legend>
		
			<div class="moduleRow">
				<div class="description" id="colHeader">&nbsp;</div>			
				<div class="important cost" id="colHeader">Shipping Cost</div>
				<div class="important total" id="colHeader">Total - <span style="color:#00556c;font-size:11px;">(With Shipping)</span></div>
				<br class="clearBoth" />
			</div>
			<div id="ROW_freeoptions_freeoptions" class="moduleRow"> 
				<div class="description">
				<label for="ship-freeoptions-freeoptions" class="checkboxLabel" >Receive FREE shipping when your order  total exceeds $<?=MODULE_SHIPPING_FREEOPTIONS_TOTAL_MIN?> within the United States.</label>
				</div>
				<div class="important cost">$0.00</div>
				<div class="important total"><?= $currencies->format($_SESSION['cart']->show_total()) ?></div>
			</div>
			<?
		}
		*/
		
    } // END if ($free_shipping == true)
	?>
	
	<?php
} else {
	?>
	<h2 id="checkoutShippingHeadingMethod"><?php echo TITLE_NO_SHIPPING_AVAILABLE; ?></h2>
	<div id="checkoutShippingContentChoose" class="important"><?php echo TEXT_NO_SHIPPING_AVAILABLE; ?></div>
	<?php
}
?>

<!--
<fieldset class="shipping" id="comments">
<legend><?php echo TABLE_HEADING_COMMENTS; ?></legend>
<?php echo zen_draw_textarea_field('comments', '45', '3'); ?>
</fieldset>
-->


<div class="buttonRow forward" style="margin-top:15px;"><input type="submit" name="Next Step, Billing" value="Next Step, Billing" class="fancybutton greenButton" /></div>

<!--
<div class="buttonRow back"><?php echo '<strong>' . TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</strong><br />' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></div>
-->
</form>





</div> <!-- END centerColumnPadding -->
<div class="shadow" id="bottom" ></div>
</div> <!-- END centerColumnContent -->
</div> <!-- END centerColumn -->



<script>
 $(document).ready(function() {
	 $('input[type=radio][name=shipping]:first').attr("checked",true);
 });
</script>