<?php
/**
 * @package languageDefines
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: J_Schilz for Integrated COWOA - 14 April 2007
 */
if($_SESSION['COWOA']) $COWOA=TRUE;

define('NAVBAR_TITLE_1', 'Checkout');
define('NAVBAR_TITLE_2', 'Shipping Method');

if($COWOA)
define('HEADING_TITLE', 'Step 2 of 4 - Shipping Method');
else
define('HEADING_TITLE', 'Step 1 of 3 - Shipping');


define('TABLE_HEADING_SHIPPING_ADDRESS', 'Shipping Address');
define('TEXT_CHOOSE_SHIPPING_DESTINATION', 'Your order will be shipped to the address at the left or you may change the shipping address by clicking the <em>Change Address</em> button.');
define('TITLE_SHIPPING_ADDRESS', 'Shipping Information:');

define('TABLE_HEADING_SHIPPING_METHOD', 'Shipping Method:');
define('TEXT_CHOOSE_SHIPPING_METHOD', 'Please select the preferred shipping method to use on this order.');
define('TITLE_PLEASE_SELECT', 'Please Select');
define('TEXT_ENTER_SHIPPING_INFORMATION', 'Please select a shipping method listed below.');
define('TITLE_NO_SHIPPING_AVAILABLE', 'Not Available At This Time');
define('TEXT_NO_SHIPPING_AVAILABLE','<span class="alert">Sorry, we are not shipping to your region at this time.</span><br />Please contact us for alternate arrangements.');

define('TABLE_HEADING_COMMENTS', 'Special Instructions or Comments About Your Order');

if($COWOA)
define('TITLE_CONTINUE_CHECKOUT_PROCEDURE', 'Continue to Step 3');
else
define('TITLE_CONTINUE_CHECKOUT_PROCEDURE', 'Continue to Step 2');
define('TEXT_CONTINUE_CHECKOUT_PROCEDURE', '- choose your payment method.');

// when free shipping for orders over $XX.00 is active
  define('FREE_SHIPPING_TITLE', 'Free Shipping');
  define('FREE_SHIPPING_DESCRIPTION', 'Free shipping for orders over %s');
?>
