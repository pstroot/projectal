<?php
/**
 * new_products.php module
 *
 * @package modules
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: new_products.php 4629 2006-09-28 15:29:18Z ajeh $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
// display limits
//$display_limit = zen_get_products_new_timelimit();
      $display_limit = zen_get_new_date_range();

if ( (!isset($new_products_category_id)) || ($new_products_category_id == '0') ) {
  $new_products_query = "select p.products_id, p.products_image, p.products_tax_class_id, p.products_price, p.products_date_added, pd.products_description
                           from " . TABLE_PRODUCTS . " p 
						   left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id 
                           where p.products_status = 1 " . $display_limit;
} else {
  $new_products_query = "select distinct p.products_id, p.products_image, p.products_tax_class_id, p.products_date_added, pd.products_description,
                                           p.products_price
                           from " . TABLE_PRODUCTS . " p 
						   left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id 
                           left join " . TABLE_SPECIALS . " s
                           on p.products_id = s.products_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " .
  TABLE_CATEGORIES . " c
                           where p.products_id = p2c.products_id
                           and p2c.categories_id = c.categories_id
                           and c.parent_id = '" . (int)$new_products_category_id . "'
                           and p.products_status = 1 " . $display_limit;
}
$new_products = $db->ExecuteRandomMulti($new_products_query, MAX_DISPLAY_NEW_PRODUCTS);

$row = 0;
$col = 0;
$list_box_contents = array();
$title = '';

$num_products_count = $new_products->RecordCount();

// show only when 1 or more
if ($num_products_count > 0) {
  if ($num_products_count < SHOW_PRODUCT_INFO_COLUMNS_NEW_PRODUCTS || SHOW_PRODUCT_INFO_COLUMNS_NEW_PRODUCTS == 0 ) {
    $col_width = floor(100/$num_products_count);
  } else {
    $col_width = floor(100/SHOW_PRODUCT_INFO_COLUMNS_NEW_PRODUCTS);
  }

  while (!$new_products->EOF) {
  
    $products_img = (($new_products->fields['products_image'] == '' and PRODUCTS_IMAGE_NO_IMAGE_STATUS == 0) ? '' : '<a href="' . zen_href_link(zen_get_info_page($new_products->fields['products_id']), 'products_id=' . $new_products->fields['products_id']) . '">' . zen_image(DIR_WS_IMAGES . $new_products->fields['products_image'], $new_products->fields['products_name'], IMAGE_PRODUCT_NEW_WIDTH, IMAGE_PRODUCT_NEW_HEIGHT) . '</a>');
	
	$products_name = '<a class="name" href="' . zen_href_link(zen_get_info_page($new_products->fields['products_id']), 'products_id=' . $new_products->fields['products_id']) . '">' . zen_get_products_name($new_products->fields['products_id']) . '</a>';
    
	$products_desc = substr(strip_tags($new_products->fields['products_description']), 0, 25) . '...';
	
    $products_price = '<strong>' . zen_get_products_display_price($new_products->fields['products_id']) . '</strong>';
	
	$products_butt = '<a href="' . zen_href_link(zen_get_info_page($new_products->fields['products_id']), 'products_id=' . $new_products->fields['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_GOTO_PROD_DETAILS, BUTTON_GOTO_PROD_DETAILS_ALT) . '</a>';
  
  
  	$img_col_w = IMAGE_PRODUCT_NEW_WIDTH + 10;

  

	if (SHOW_PRODUCT_INFO_COLUMNS_NEW_PRODUCTS > 1 && $num_products_count > 1) {
	
		if ($col > 0 && $col < SHOW_PRODUCT_INFO_COLUMNS_NEW_PRODUCTS) {
			$tm_param = 'style="margin-left:2px;"';
		} else {
			$tm_param = '';
		}
	
    $list_box_contents[$row][$col] = array('params' => 'class="centerBoxContentsNew centeredContent back"' . ' ' . 'style="width:' . $col_width . '%;"',
    'text' => 
	
			'<div class="product product_border" align="center" ' . $tm_param . '>
				<div class="margin_col">
					' . $products_img . '<br />
					<br style="line-height:10px;" />
					' . $products_name . '<br />
					<div class="text">
						' . $products_desc . '<br />
					</div>
					' . $products_price . '<br>
					<br style="line-height:10px;" />
					' . $products_butt . '
					<div class="spacer"><br class="clear" /></div>
				</div>
			</div>'
				
				
				);
				
	} else {

    $list_box_contents[$row][$col] = array('params' =>'class="centerBoxContentsNew centeredContent back"' . ' ' . 'style="width:' . $col_width . '%;"',
    'text' => 
	
			'<div class="product">
				<div class="w_100">
					<div class="right" style="margin-left:-' . $img_col_w . 'px;">
						<div class="margin" style="margin-left:' . $img_col_w . 'px;">
							' . $products_name . '<br />
							<div class="text">
								' . $products_desc . '
							</div>
							<div class="price">
								' . $products_price . '
							</div>
							<div class="button">' . $products_butt . '</div>
							<div class="clear"></div>
						</div>
					</div>
					<div class="left" style="width:' . $img_col_w . 'px;">
						' . $products_img . '
					</div>
					<div class="clear"></div>
				</div>
			</div>'
					
			);
	
	}
	
	

    $col ++;
    if ($col > (SHOW_PRODUCT_INFO_COLUMNS_NEW_PRODUCTS - 1)) {
      $col = 0;
      $row ++;
    }
    $new_products->MoveNextRandom();
  }

  if ($new_products->RecordCount() > 0) {
    if (isset($new_products_category_id) && $new_products_category_id != 0) {
      $category_title = zen_get_categories_name((int)$new_products_category_id);
      $title = '<h2 class="centerBoxHeading">' . sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')) . ($category_title != '' ? ' - ' . $category_title : '' ) . '</h2>';
    } else {
      $title = '<h2 class="centerBoxHeading">' . sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')) . '</h2>';
    }
    $zc_show_new_products = true;
  }
}
?>