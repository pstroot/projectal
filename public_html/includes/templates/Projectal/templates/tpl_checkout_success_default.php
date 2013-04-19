<script>
activateNav('nav-shop');
</script>
<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=checkout_success.<br />
 * Displays confirmation details after order has been successfully processed.
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: J_Schilz for Integrated COWOA - 14 April 2007
 */
?>
<?php if($_SESSION['COWOA']) $COWOA=TRUE; ?>

<div class="centerColumn" id="checkoutSuccess">


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



<!--bof -gift certificate- send or spend box-->
<?php
// only show when there is a GV balance
  if ($customer_has_gv_balance ) {
?>
<div id="sendSpendWrapper">
<?php require($template->get_template_dir('tpl_modules_send_or_spend.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_send_or_spend.php'); ?>
</div>
<?php
  }
?>
<!--eof -gift certificate- send or spend box-->

<h1 id="checkoutSuccessHeading"></h1>


<?php if (DEFINE_CHECKOUT_SUCCESS_STATUS >= 1 and DEFINE_CHECKOUT_SUCCESS_STATUS <= 2) { ?>
<div id="checkoutSuccessMainContent" class="content">
<?php
/**
 * require the html_defined text for checkout success
 */
 // require($define_page);
?>
<div class="greyBubble">
    <h1><?php echo HEADING_TITLE; ?></h1>
    Your support is appreciated by:
    </div>
    <ul>
        <li>Projectal</li> 
        <li>The local business on your shirt</li>
        <li>The designer who created it</li>
        <li>The charity you just donated to</li>
    </ul>
    
    <div id="checkoutSuccessOrderNumber"><b>Your Order Number is:</b> <span style="color:#CC0000;"><?php echo  $zv_orders_id; ?></span></div>
    
    <div id="checkoutSuccessEmailNotice">You will be receiving an order confirmation e-mail shortly.</div>
    
    
    <!--bof logoff-->
    <div id="checkoutSuccessLogoff">
		<?php
          if (isset($_SESSION['customer_guest_id'])) {
            echo "NOTE: To complete your order, a temporary account was created. You may close this account by clicking Log Off. Clicking Log Off also ensures that your receipt and purchase information is not visible to the next person using this computer. If you wish to continue shopping, feel free! You may log off at anytime using the link at the top of the page.";
          } elseif (isset($_SESSION['customer_id'])) {
            echo "Please click the Log Off link to ensure that your receipt and purchase information is not visible to the next person using this computer.";
          }
        ?>
        
        <div class="buttonRow">
            <a href="<?php echo zen_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>" class="fancybutton greenButton small"><?php echo BUTTON_LOG_OFF_ALT; ?></a>
        </div>
    </div>
    <!--eof logoff-->



    
    <!--bof -product downloads module-->
    <?php
      if (DOWNLOAD_ENABLED == 'true') require($template->get_template_dir('tpl_modules_downloads.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_downloads.php');
    ?>
    <!--eof -product downloads module-->
    
    
    
    <!-- You can view your order history by going to the My Account page and by clicking on "View All Orders". -->
    <?php if(!($_SESSION['COWOA'])) { ?> <div id="checkoutSuccessOrderLink"><?php echo TEXT_SEE_ORDERS;?></div> <?php } ?>
    
    <!-- This text can be edited in projectal_admin -> Tools -> Define Pages Editor -> define_checkout_success -->
    <div id="checkoutSuccessDetails"><?php  require($define_page);?></div>
    




</div>
<?php } ?>







<br class="clearBoth" />


<!--bof -product notifications box-->
<?php
/**
 * The following creates a list of checkboxes for the customer to select if they wish to be included in product-notification
 * announcements related to products they've just purchased.
 **/
    if ($flag_show_products_notification == true && !($_SESSION['COWOA'])) {
?>
<fieldset id="csNotifications">
<legend><?php echo TEXT_NOTIFY_PRODUCTS; ?></legend>
<?php echo zen_draw_form('order', zen_href_link(FILENAME_CHECKOUT_SUCCESS, 'action=update', 'SSL')); ?>

<?php foreach ($notificationsArray as $notifications) { ?>
<?php echo zen_draw_checkbox_field('notify[]', $notifications['products_id'], true, 'id="notify-' . $notifications['counter'] . '"') ;?>
<label class="checkboxLabel" for="<?php echo 'notify-' . $notifications['counter']; ?>"><?php echo $notifications['products_name']; ?></label>
<br />
<?php } ?>




<div class="buttonRow forward"><input type="submit" name="<?php echo BUTTON_UPDATE_ALT; ?>" class="fancybutton greenButton small" value="<?php echo BUTTON_UPDATE_ALT; ?>" /></div>
</form>
</fieldset>
<?php
    }
?>
<!--eof -product notifications box-->





</div> <!-- END centerColumnPadding -->
<div class="shadow" id="bottom" ></div>
</div> <!-- END centerColumnContent -->
</div> <!-- END centerColumn -->



<script>
	$('#checkoutSuccessMainContent .greyBubble').after("<div class='bubbleArrow'></div>");
</script>


