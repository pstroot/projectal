<?php
/**
 * Side Box Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_shopping_cart.php 7192 2007-10-06 13:30:46Z drbyte $
 */
  $currencies = new currencies();
  $content ="";

  $content .= '<div id="' . str_replace('_', '-', $box_id . 'Content') . '" class="sideBoxContent">';
  if ($_SESSION['cart']->count_contents() > 0) {
  $content .= '<div id="cartBoxListWrapper">' . "\n" . '<ul>' . "\n";
    $products = $_SESSION['cart']->get_products();
	$total_items = 0;
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      $content .= '<li>';



      if (isset($_SESSION['new_products_id_in_cart']) && ($_SESSION['new_products_id_in_cart'] == $products[$i]['id'])) {
        $content .= '<span class="cartNewItem">';
      } else {
        $content .= '<span class="cartOldItem">';
      }
	  
	  
      // IMAGE
	  $productsImage = (IMAGE_SHOPPING_CART_STATUS == 1 ? zen_image(DIR_WS_IMAGES . $products[$i]['image'], $products[$i]['name'], 32) : '');
	  $content .= '<div id="image">' . $productsImage . '</div>';


      // QUANTITY
	  $content .= '<div id="quantity">(' . $products[$i]['quantity'] . 'x)</div>';
	  $total_items += $products[$i]['quantity'];
	  
 
	  //  PRODUCT NAME
	  // get the business name if it exists.
	  $listingHREF = zen_href_link(zen_get_info_page($products[$i]['id']), 'products_id=' . $products[$i]['id']);
	  $productName = projectal_get_listing_title($products[$i]['name'],$products[$i]['id'],$listingHREF);
	
	  
	  
      $content .= '<div id="name">'.$productName.'</div></a>';
	  $content .= '<div id="price">' . $currencies->display_price($products[$i]['price'] * $products[$i]['quantity'],0). '</div>';
	  
	  $content .= '</span><div style="clear:both;"></div>';	  
	  $content .= '</li>' . "\n";

      if (isset($_SESSION['new_products_id_in_cart']) && ($_SESSION['new_products_id_in_cart'] == $products[$i]['id'])) {
        $_SESSION['new_products_id_in_cart'] = '';
      }
    }
    $content .= '</ul>' . "\n" . '</div>';
  } else {
    $content .= '<div id="cartBoxEmpty">' . BOX_SHOPPING_CART_EMPTY . '</div>';
  }

  if ($_SESSION['cart']->count_contents() > 0) {

    $content .= '<div id="summary">';
	$content .= '<div id="summaryHeader">Order Summary</div>';
	$content .= "<table style='width:100%;' >\n";
	
	// $order_total_modules->output();

	if(isset($order_total_modules)){
		$content .= $order_total_modules->output(true);		
	}else{
		$content .= '<tr><td class="ot-subtotal-Text">Total Items:</td><td class="ot-subtotal-Amount">' . $total_items . '</td></tr>';	
		$content .= '<tr><td class="ot-total-Text">Grand Total:</td><td class="ot-total-Amount">' . $currencies->format($_SESSION['cart']->show_total()) . '</td></tr>';
	}
 	$content .= "</table>\n";
	$content .= '</div>';
    $content .= '<div id="viewCartLink"><a href="index.php?main_page=shopping_cart">View cart</a></div>';
    $content .= '<div id="checkoutBtn"><a href="index.php?main_page=checkout_shipping" class="fancybutton greenButton small">Checkout</a></div>';

  }

  if (isset($_SESSION['customer_id'])) {
    $gv_query = "select amount
                 from " . TABLE_COUPON_GV_CUSTOMER . "
                 where customer_id = '" . $_SESSION['customer_id'] . "'";
   $gv_result = $db->Execute($gv_query);

    if ($gv_result->RecordCount() && $gv_result->fields['amount'] > 0 ) {
      $content .= '<div id="cartBoxGVButton"><a href="' . zen_href_link(FILENAME_GV_SEND, '', 'SSL') . '" >' . zen_image_button(BUTTON_IMAGE_SEND_A_GIFT_CERT , BUTTON_SEND_A_GIFT_CERT_ALT) . '</a></div>';
      $content .= '<div id="cartBoxVoucherBalance">' . VOUCHER_BALANCE . $currencies->format($gv_result->fields['amount']) . '</div>';
    }
  }
  $content .= '<br style="clear:both;" />';
  $content .= '</div>';
?>
