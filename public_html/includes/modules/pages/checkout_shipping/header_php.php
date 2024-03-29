<?php
/**
 * Checkout Shipping Page
 *
 * @package page
 * @copyright Copyright 2003-2009 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: header_php.php 18007 2010-10-21 06:41:51Z drbyte $
 * Stock by Attributes version
 */
// This should be first line of the script:
  $zco_notifier->notify('NOTIFY_HEADER_START_CHECKOUT_SHIPPING');

  require_once(DIR_WS_CLASSES . 'http_client.php');

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($_SESSION['cart']->count_contents() <= 0) {
    zen_redirect(zen_href_link(FILENAME_TIME_OUT));
  }

// if the customer is not logged on, redirect them to the login page
  if (!isset($_SESSION['customer_id']) || !$_SESSION['customer_id']) {
    $_SESSION['navigation']->set_snapshot();
    zen_redirect(zen_href_link(FILENAME_LOGIN, '', 'SSL'));
  } else {
    // validate customer
    if (zen_get_customer_validate_session($_SESSION['customer_id']) == false) {
      $_SESSION['navigation']->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_SHIPPING));
      zen_redirect(zen_href_link(FILENAME_LOGIN, '', 'SSL'));
    }
  }

// Validate Cart for checkout
  $_SESSION['valid_to_checkout'] = true;
  $_SESSION['cart']->get_products(true);
  if ($_SESSION['valid_to_checkout'] == false) {
    $messageStack->add('header', ERROR_CART_UPDATE, 'error');
    zen_redirect(zen_href_link(FILENAME_SHOPPING_CART));
  }

// Stock Check
  if ( (STOCK_CHECK == 'true') && (STOCK_ALLOW_CHECKOUT != 'true') ) {
    $products = $_SESSION['cart']->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {

      // Added to allow individual stock of different attributes
      unset($attributes);
      if(is_array($products[$i]['attributes'])) {
	      $attributes = $products[$i]['attributes'];
      } else  {
	      $attributes = '';
      }

      if (zen_check_stock($products[$i]['id'], $products[$i]['quantity'], $attributes)) {
// End change
        zen_redirect(zen_href_link(FILENAME_SHOPPING_CART));
        break;
      }
    }
  }
// if no shipping destination address was selected, use the customers own address as default
  if (!$_SESSION['sendto']) {
    $_SESSION['sendto'] = $_SESSION['customer_default_address_id'];
  } else {
// verify the selected shipping address
    $check_address_query = "SELECT count(*) AS total
                            FROM   " . TABLE_ADDRESS_BOOK . "
                            WHERE  customers_id = :customersID
                            AND    address_book_id = :addressBookID";

    $check_address_query = $db->bindVars($check_address_query, ':customersID', $_SESSION['customer_id'], 'integer');
    $check_address_query = $db->bindVars($check_address_query, ':addressBookID', $_SESSION['sendto'], 'integer');
    $check_address = $db->Execute($check_address_query);

    if ($check_address->fields['total'] != '1') {
      $_SESSION['sendto'] = $_SESSION['customer_default_address_id'];
      $_SESSION['shipping'] = '';
    }
  }

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

// register a random ID in the session to check throughout the checkout procedure
// against alterations in the shopping cart contents
if (isset($_SESSION['cart']->cartID)) {
  if (!isset($_SESSION['cartID']) || $_SESSION['cart']->cartID != $_SESSION['cartID']) {
    $_SESSION['cartID'] = $_SESSION['cart']->cartID;
  }
} else {
  zen_redirect(zen_href_link(FILENAME_TIME_OUT));
}

// if the order contains only virtual products, forward the customer to the billing page as
// a shipping address is not needed
  if ($order->content_type == 'virtual') {
    $_SESSION['shipping'] = 'free_free';
    $_SESSION['shipping']['title'] = 'free_free';
    $_SESSION['sendto'] = false;
    zen_redirect(zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
  }

  $total_weight = $_SESSION['cart']->show_weight();
  $total_count = $_SESSION['cart']->count_contents();

// load all enabled shipping modules
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping;

  $pass = true;
  if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
    $pass = false;

    switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
      case 'national':
        if ($order->delivery['country_id'] == STORE_COUNTRY) {
          $pass = true;
        }
        break;
      case 'international':
        if ($order->delivery['country_id'] != STORE_COUNTRY) {
          $pass = true;
        }
        break;
      case 'both':
        $pass = true;
        break;
    }

    $free_shipping = false;
    if ( ($pass == true) && ($_SESSION['cart']->show_total() >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
      $free_shipping = true;
    }
  } else {
    $free_shipping = false;
  }

  require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));

  if (isset($_SESSION['comments'])) {
    $comments = $_SESSION['comments'];
  }


// process the selected shipping method
  if ( isset($_POST['action']) && ($_POST['action'] == 'process') ) {
    if (zen_not_null($_POST['comments'])) {
      $_SESSION['comments'] = zen_db_prepare_input($_POST['comments']);
    }
    $comments = $_SESSION['comments'];
    $quote = array();

    if ( (zen_count_shipping_modules() > 0) || ($free_shipping == true) ) {
      if ( (isset($_POST['shipping'])) && (strpos($_POST['shipping'], '_')) ) {
        /**
         * check to be sure submitted data hasn't been tampered with
         */
        if ($_POST['shipping'] == 'free_free' && ($order->content_type != 'virtual' && !$pass)) {
          $quote['error'] = 'Invalid input. Please make another selection.';
        } else {
          $_SESSION['shipping'] = $_POST['shipping'];
        }

        list($module, $method) = explode('_', $_SESSION['shipping']);
        if ( is_object($$module) || ($_SESSION['shipping'] == 'free_free') ) {
          if ($_SESSION['shipping'] == 'free_free') {
            $quote[0]['methods'][0]['title'] = FREE_SHIPPING_TITLE;
            $quote[0]['methods'][0]['cost'] = '0';
          } else {
            $quote = $shipping_modules->quote($method, $module);
          }
          if (isset($quote['error'])) {
            $_SESSION['shipping'] = '';
          } else {
            if ( (isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost'])) ) {
              $_SESSION['shipping'] = array('id' => $_SESSION['shipping'],
                                'title' => (($free_shipping == true) ?  $quote[0]['methods'][0]['title'] : $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')'),
                                'cost' => $quote[0]['methods'][0]['cost']);

              zen_redirect(zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
            }
          }
        } else {
          $_SESSION['shipping'] = false;
        }
      }
    } else {
      $_SESSION['shipping'] = false;

      zen_redirect(zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
    }
  }
  
  
// ********************* BEGIN ADDITIONS BY PSTROOT *********************

// IF THE PRIMARY ADDRESS IS DOES NOT EXIST, TRY TO GRAB ANOTHER ONE
$addressCheck_query = "SELECT address_book_id FROM   " . TABLE_ADDRESS_BOOK . "
                  WHERE  customers_id = :customersID
                  AND    address_book_id = :addressBookID";

$addressCheck_query = $db->bindVars($addressCheck_query, ':customersID', $_SESSION['customer_id'], 'integer');
$addressCheck_query = $db->bindVars($addressCheck_query, ':addressBookID',$_SESSION['sendto'], 'integer');
$addressCheck = $db->Execute($addressCheck_query);
if ($addressCheck->RecordCount() <= 0) {
	// There is no default address defined, so get any other address for this user.	
	$addressGet_query = "SELECT address_book_id FROM   " . TABLE_ADDRESS_BOOK . " WHERE  customers_id = :customersID ORDER BY address_book_id";
	$addressGet_query = $db->bindVars($addressGet_query, ':customersID', $_SESSION['customer_id'], 'integer');
	$addressGet = $db->Execute($addressGet_query);
	if ($addressGet->RecordCount() == 0) {
		// there are NO addresses for this user, redirect to the "add a new address" page
		zen_redirect('index.php?main_page=address_book_process&redirect='.curPageURL(), '', 'SSL');
		exit();
	} else {
		// send to the first non-default address we can find
		$_SESSION['sendto'] = $addressGet->fields['address_book_id'];
		//print "SEND TO " . $_SESSION['sendto'];exit();
	}
}

// IF THE ADDRESS IS INCOMPLETE, FORWARD TO THE PAGE TO ALLOW THE USER TO COMPLETE IT.
if(!projectal_is_address_complete($_SESSION['customer_id'], $_SESSION['sendto'])){
	zen_redirect('index.php?main_page=address_book_process&edit='.$_SESSION['sendto'].'&redirect='.curPageURL(), '', 'SSL');
	exit();
} 

// ********************* *END ADDITIONS BY PSTROOT *********************


// get all available shipping quotes
  $quotes = $shipping_modules->quote();

// if no shipping method has been selected, automatically select the cheapest method.
// if the modules status was changed when none were available, to save on implementing
// a javascript force-selection method, also automatically select the cheapest shipping
// method if more than one module is now enabled
  if ( !$_SESSION['shipping'] || ( $_SESSION['shipping'] && ($_SESSION['shipping'] == false) && (zen_count_shipping_modules() > 1) ) ) $_SESSION['shipping'] = $shipping_modules->cheapest();


  // Should address-edit button be offered?
  $displayAddressEdit = (MAX_ADDRESS_BOOK_ENTRIES >= 2);

  // if shipping-edit button should be overridden, do so
  $editShippingButtonLink = zen_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL');
  if (isset($_SESSION['payment']) && method_exists($$_SESSION['payment'], 'alterShippingEditButton')) {
    $theLink = $$_SESSION['payment']->alterShippingEditButton();
    if ($theLink) {
      $editShippingButtonLink = $theLink;
      $displayAddressEdit = true;
    }
  }

  
  // ************* added by pstroot ********************  
  // If we're checking out without an account, we want to edit our existing address, not add a new one.
  if($_SESSION['COWOA']){
    $editShippingButtonLink = "index.php?main_page=address_book_process&edit=" . $_SESSION["sendto"] . "&redirect=" . curPageURL();
  }

  
  
  
  
  $breadcrumb->add(NAVBAR_TITLE_1, zen_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2);

// This should be last line of the script:
  $zco_notifier->notify('NOTIFY_HEADER_END_CHECKOUT_SHIPPING');
