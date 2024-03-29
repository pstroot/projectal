<?php
/**
 * product_listing module
 *
 * @package modules
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: product_listing.php 6787 2007-08-24 14:06:33Z drbyte $
 * UPDATED TO WORK WITH COLUMNAR PRODUCT LISTING For Zen Cart v1.3.6 - 10/25/2006
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
// Column Layout Support originally added for Zen Cart v 1.1.4 by Eric Stamper - 02/14/2004
// Upgraded to be compatible with Zen-cart v 1.2.0d by Rajeev Tandon - Aug 3, 2004
// Column Layout Support (Grid Layout) upgraded for v1.3.0 compatibility DrByte 04/04/2006
//
if (!defined('PRODUCT_LISTING_LAYOUT_STYLE')) define('PRODUCT_LISTING_LAYOUT_STYLE','rows');
if (!defined('PRODUCT_LISTING_COLUMNS_PER_ROW')) define('PRODUCT_LISTING_COLUMNS_PER_ROW',3);
$row = 0;
$col = 0;
$list_box_contents = array();
$title = '';

$max_results = (PRODUCT_LISTING_LAYOUT_STYLE=='columns' && PRODUCT_LISTING_COLUMNS_PER_ROW>0) ? (PRODUCT_LISTING_COLUMNS_PER_ROW * (int)(MAX_DISPLAY_PRODUCTS_LISTING/PRODUCT_LISTING_COLUMNS_PER_ROW)) : MAX_DISPLAY_PRODUCTS_LISTING;


$show_submit = zen_run_normal();
$listing_split = new splitPageResults($listing_sql, $max_results, 'p.products_id', 'page');
$zco_notifier->notify('NOTIFY_MODULE_PRODUCT_LISTING_RESULTCOUNT', $listing_split->number_of_rows);
$how_many = 0;

// Begin Row Layout Header
if (PRODUCT_LISTING_LAYOUT_STYLE == 'rows') {		// For Column Layout (Grid Layout) add on module

$list_box_contents[0] = array('params' => 'class="productListing-rowheading"');

$zc_col_count_description = 0;
$lc_align = '';
for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
  switch ($column_list[$col]) {
    case 'PRODUCT_LIST_MODEL':
    $lc_text = TABLE_HEADING_MODEL;
    $lc_align = '';
    $zc_col_count_description++;
    break;
    case 'PRODUCT_LIST_NAME':
    $lc_text = TABLE_HEADING_PRODUCTS;
    $lc_align = '';
    $zc_col_count_description++;
    break;
    case 'PRODUCT_LIST_MANUFACTURER':
    $lc_text = TABLE_HEADING_MANUFACTURER;
    $lc_align = '';
    $zc_col_count_description++;
    break;
    case 'PRODUCT_LIST_PRICE':
    $lc_text = TABLE_HEADING_PRICE;
    $lc_align = 'right' . (PRODUCTS_LIST_PRICE_WIDTH > 0 ? '" width="' . PRODUCTS_LIST_PRICE_WIDTH : '');
    $zc_col_count_description++;
    break;
    case 'PRODUCT_LIST_QUANTITY':
    $lc_text = TABLE_HEADING_QUANTITY;
    $lc_align = 'right';
    $zc_col_count_description++;
    break;
    case 'PRODUCT_LIST_WEIGHT':
    $lc_text = TABLE_HEADING_WEIGHT;
    $lc_align = 'right';
    $zc_col_count_description++;
    break;
    case 'PRODUCT_LIST_IMAGE':
    $lc_text = TABLE_HEADING_IMAGE;
    $lc_align = 'center';
    $zc_col_count_description++;
    break;
  }

  if ( ($column_list[$col] != 'PRODUCT_LIST_IMAGE') ) {
    $lc_text = zen_create_sort_heading($_GET['sort'], $col+1, $lc_text);
  }



  $list_box_contents[0][$col] = array('align' => $lc_align,
                                      'params' => 'class="productListing-heading"',
                                      'text' => $lc_text );
}

} // End Row Layout Header used in Column Layout (Grid Layout) add on module

/////////////  HEADER ROW ABOVE /////////////////////////////////////////////////

$num_products_count = $listing_split->number_of_rows;

if ($listing_split->number_of_rows > 0) {
  $rows = 0;
  // Used for Column Layout (Grid Layout) add on module
  $column = 0;	
  if (PRODUCT_LISTING_LAYOUT_STYLE == 'columns') {
    if ($num_products_count < PRODUCT_LISTING_COLUMNS_PER_ROW || PRODUCT_LISTING_COLUMNS_PER_ROW == 0 ) {
      $col_width = floor(100/$num_products_count) - 0.5;
    } else {
      $col_width = floor(100/PRODUCT_LISTING_COLUMNS_PER_ROW) - 0.5;
    }
  }
  // Used for Column Layout (Grid Layout) add on module


  $listing = $db->Execute($listing_split->sql_query);
  $extra_row = 0;
  while (!$listing->EOF) {

	$listingID = $listing->fields['products_id'];
	$listingHREF = zen_href_link(zen_get_info_page($listingID), 'cPath=' . (($_GET['manufacturers_id'] > 0 and $_GET['filter_id']) > 0 ?  zen_get_generated_category_path_rev($_GET['filter_id']) : ($_GET['cPath'] > 0 ? zen_get_generated_category_path_rev($_GET['cPath']) : zen_get_generated_category_path_rev($master_categories_id))) . '&products_id=' . $listingID);	
	$listingName = $listing->fields['products_name'];
	$master_categories_id = $listing->fields['master_categories_id'];
	$imageSrc = DIR_WS_IMAGES . $listing->fields['products_image'];
	$imageAlt = $listing->fields['products_name'];
	$imageWidth = IMAGE_PRODUCT_LISTING_WIDTH;
	$imageHeight = IMAGE_PRODUCT_LISTING_HEIGHT;
	$imageParams = 'class="listingProductImage"';
	
	$imageWidth = 187; // override the width setting above because we're using a tshirt with a set width
	$imageHeight = 221; // override the height setting above because we're using a tshirt with a set height

					
					
    if (PRODUCT_LISTING_LAYOUT_STYLE == 'rows') { // Used in Column Layout (Grid Layout) Add on module
    $rows++;

    if ((($rows-$extra_row)/2) == floor(($rows-$extra_row)/2)) {
      $list_box_contents[$rows] = array('params' => 'class="productListing-even"');
    } else {
      $list_box_contents[$rows] = array('params' => 'class="productListing-odd"');
    }

    $cur_row = sizeof($list_box_contents) - 1;
    }   // End of Conditional execution - only for row (regular style layout)

    $product_contents = array(); // Used For Column Layout (Grid Layout) Add on module

	for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
		$lc_align = '';
      	switch ($column_list[$col]) {
			case 'PRODUCT_LIST_MODEL':
				$lc_align = '';
				$lc_text = $listing->fields['products_model'];
				break;
			case 'PRODUCT_LIST_NAME':
				$lc_align = '';
				$lc_text = projectal_get_listing_title($listingName,$listingID,$listingHREF); // UPDATED BY PAUL STROOT
				/*
				if (isset($_GET['manufacturers_id'])) {
				  $lc_text = '<h3 class="itemTitle"><a href="' . $listingHREF . '">' . $listingName . '</a></h3><div class="listingDescription">' . zen_trunc_string(zen_clean_html(stripslashes(zen_get_products_description($listingID, $_SESSION['languages_id']))), PRODUCT_LIST_DESCRIPTION) . '</div>' ;
				} else {
				  $lc_text = '<h3 class="itemTitle"><a href="' . $listingHREF . '">' . $listingName . '</a></h3><div class="listingDescription">' . zen_trunc_string(zen_clean_html(stripslashes(zen_get_products_description($listingID, $_SESSION['languages_id']))), PRODUCT_LIST_DESCRIPTION) . '</div>';
				}
				*/
				break;
			case 'PRODUCT_LIST_MANUFACTURER':
				$lc_align = '';
				$lc_text = '<a href="' . zen_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $listing->fields['manufacturers_id']) . '">' . $listing->fields['manufacturers_name'] . '</a>';
				break;
			case 'PRODUCT_LIST_PRICE':
				$lc_price = zen_get_products_display_price($listingID) . '<br />';
				$lc_align = 'right';
				$lc_text =  $lc_price;
	
				// more info in place of buy now
				$lc_button = '';
				if (zen_has_product_attributes($listingID) or PRODUCT_LIST_PRICE_BUY_NOW == '0') {
					$lc_button = '<a href="' . zen_href_link(zen_get_info_page($listingID), 'cPath=' . (($_GET['manufacturers_id'] > 0 and $_GET['filter_id']) > 0 ?  zen_get_generated_category_path_rev($_GET['filter_id']) : ($_GET['cPath'] > 0 ? $_GET['cPath'] : zen_get_generated_category_path_rev($master_categories_id))) . '&products_id=' . $listingID) . '">' . MORE_INFO_TEXT . '</a>';
				} else {
				  	if (PRODUCT_LISTING_MULTIPLE_ADD_TO_CART != 0) {
						if (
							// not a hide qty box product
							$listing->fields['products_qty_box_status'] != 0 &&
							// product type can be added to cart
							zen_get_products_allow_add_to_cart($listingID) != 'N'
							&&
							// product is not call for price
							$listing->fields['product_is_call'] == 0
							&&
							// product is in stock or customers may add it to cart anyway
							($listing->fields['products_quantity'] > 0 || SHOW_PRODUCTS_SOLD_OUT_IMAGE == 0) ) {
						  	$how_many++;
						}
						// hide quantity box
						if ($listing->fields['products_qty_box_status'] == 0) {
						  $lc_button = '<a href="' . zen_href_link($_GET['main_page'], zen_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listingID) . '">' . zen_image_button(BUTTON_IMAGE_BUY_NOW, BUTTON_BUY_NOW_ALT, 'class="listingBuyNowButton"') . '</a>';
						} else {
						  $lc_button = TEXT_PRODUCT_LISTING_MULTIPLE_ADD_TO_CART . "<input type=\"text\" name=\"products_id[" . $listingID . "]\" value=\"0\" size=\"4\" />";
						}
				  	} else {
		// qty box with add to cart button
						if (PRODUCT_LIST_PRICE_BUY_NOW == '2' && $listing->fields['products_qty_box_status'] != 0) {
						  $lc_button= zen_draw_form('cart_quantity', zen_href_link(zen_get_info_page($listingID), zen_get_all_get_params(array('action')) . 'action=add_product&products_id=' . $listingID), 'post', 'enctype="multipart/form-data"') . '<input type="text" name="cart_quantity" value="' . (zen_get_buy_now_qty($listingID)) . '" maxlength="6" size="4" /><br />' . zen_draw_hidden_field('products_id', $listingID) . zen_image_submit(BUTTON_IMAGE_IN_CART, BUTTON_IN_CART_ALT) . '</form>';
						} else {
						  $lc_button = '<a href="' . zen_href_link($_GET['main_page'], zen_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listingID) . '">' . zen_image_button(BUTTON_IMAGE_BUY_NOW, BUTTON_BUY_NOW_ALT, 'class="listingBuyNowButton"') . '</a>';
						}
			  		}
        		}
				$the_button = $lc_button;
				$products_link = '<a href="' . zen_href_link(zen_get_info_page($listingID), 'cPath=' . ( ($_GET['manufacturers_id'] > 0 and $_GET['filter_id']) > 0 ? zen_get_generated_category_path_rev($_GET['filter_id']) : $_GET['cPath'] > 0 ? zen_get_generated_category_path_rev($_GET['cPath']) : zen_get_generated_category_path_rev($master_categories_id)) . '&products_id=' . $listingID) . '">' . MORE_INFO_TEXT . '</a>';
				$lc_text .= '<br />' . zen_get_buy_now_button($listingID, $the_button, $products_link) . '<br />' . zen_get_products_quantity_min_units_display($listingID);
				$lc_text .= '<br />' . (zen_get_show_product_switch($listingID, 'ALWAYS_FREE_SHIPPING_IMAGE_SWITCH') ? (zen_get_product_is_always_free_shipping($listingID) ? TEXT_PRODUCT_FREE_SHIPPING_ICON . '<br />' : '') : '');
		
				break;
			case 'PRODUCT_LIST_QUANTITY':
				$lc_align = 'right';
				$lc_text = $listing->fields['products_quantity'];
				break;
				case 'PRODUCT_LIST_WEIGHT':
				$lc_align = 'right';
				$lc_text = $listing->fields['products_weight'];
			break;
				case 'PRODUCT_LIST_IMAGE':
				$lc_align = 'center';
				if ($listing->fields['products_image'] == '' and PRODUCTS_IMAGE_NO_IMAGE_STATUS == 0) {
				  $lc_text = '';
				} else {
				  if (isset($_GET['manufacturers_id'])) {
					$lc_text = '<a href="' . $listingHREF . '">' . 
								projectal_zen_image($imageSrc, $imageAlt, $imageWidth ,$imageHeight, $imageParams, $listingID) . 
								'</a>';
				  } else {
					$lc_text = '<a href="' . $listingHREF . '">' . 
								projectal_zen_image($imageSrc,$imageAlt ,$imageWidth ,$imageHeight ,$imageParams, $listingID ) . 
								'</a>';
				  }
				}
				break;
			}

      	$product_contents[] = $lc_text; // Used For Column Layout (Grid Layout) Option

      	if (PRODUCT_LISTING_LAYOUT_STYLE == 'rows') {
      		$list_box_contents[$rows][$col] = array('align' => $lc_align,
                                               		'params' => 'class="productListing-data"',
                                                	'text'  => $lc_text);
      	}
    }

    // add description and match alternating colors
    //if (PRODUCT_LIST_DESCRIPTION > 0) {
    //  $rows++;
    //  if ($extra_row == 1) {
    //    $list_box_description = "productListing-data-description-even";
    //    $extra_row=0;
    //  } else {
    //    $list_box_description = "productListing-data-description-odd";
    //    $extra_row=1;
    //  }
    //  $list_box_contents[$rows][] = array('params' => 'class="' . $list_box_description . '" colspan="' . $zc_col_count_description . '"',
    //  'text' => zen_trunc_string(zen_clean_html(stripslashes(zen_get_products_description($listingID, $_SESSION['languages_id']))), PRODUCT_LIST_DESCRIPTION));
    //}

    // Following code will be executed only if Column Layout (Grid Layout) option is chosen
    if (PRODUCT_LISTING_LAYOUT_STYLE == 'columns') {
      $lc_text = implode('<br />', $product_contents);
      $list_box_contents[$rows][$column] = array('params' => 'class="centerBoxContentsProducts centeredContent back productHoverBox"' . ' ' . 'style="width:' . $col_width . '%;"',
                                                 'text'  => $lc_text);
      $column ++;
      if ($column >= PRODUCT_LISTING_COLUMNS_PER_ROW) {
        $column = 0;
        $rows ++;
      }
    }
    // End of Code fragment for Column Layout (Grid Layout) option in add on module
    $listing->MoveNext();
  }
  $error_categories = false;
} else {
  $list_box_contents = array();

  $list_box_contents[0] = array('params' => 'class="productListing-odd"');
  $list_box_contents[0][] = array('params' => 'class="productListing-data"',
                                              'text' => TEXT_NO_PRODUCTS);

  $error_categories = true;
}

if (($how_many > 0 and $show_submit == true and $listing_split->number_of_rows > 0) and (PRODUCT_LISTING_MULTIPLE_ADD_TO_CART == 1 or  PRODUCT_LISTING_MULTIPLE_ADD_TO_CART == 3) ) {
  $show_top_submit_button = true;
} else {
  $show_top_submit_button = false;
}
if (($how_many > 0 and $show_submit == true and $listing_split->number_of_rows > 0) and (PRODUCT_LISTING_MULTIPLE_ADD_TO_CART >= 2) ) {
  $show_bottom_submit_button = true;
} else {
  $show_bottom_submit_button = false;
}



  if ($how_many > 0 && PRODUCT_LISTING_MULTIPLE_ADD_TO_CART != 0 and $show_submit == true and $listing_split->number_of_rows > 0) {
  // bof: multiple products
    echo zen_draw_form('multiple_products_cart_quantity', zen_href_link(FILENAME_DEFAULT, zen_get_all_get_params(array('action')) . 'action=multiple_products_add_product'), 'post', 'enctype="multipart/form-data"');
  }

?>
