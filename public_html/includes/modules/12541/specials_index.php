<?php
/**
 * specials_index module
 *
 * @package modules
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: specials_index.php 4629 2006-09-28 15:29:18Z ajeh $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
if ( (!isset($new_products_category_id)) || ($new_products_category_id == '0') ) {
  $specials_index_query = "select p.products_id, p.products_image, pd.products_name, pd.products_description
                           from (" . TABLE_PRODUCTS . " p
                           left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id
                           left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id )
                           where p.products_id = s.products_id and p.products_id = pd.products_id and p.products_status = '1' and s.status = 1 and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'";
} else {
  $specials_index_query = "select distinct p.products_id, p.products_image, pd.products_name, pd.products_description
                           from (" . TABLE_PRODUCTS . " p
                           left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id
                           left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id ), " .
  TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " .
  TABLE_CATEGORIES . " c
                           where p.products_id = p2c.products_id
                           and p2c.categories_id = c.categories_id
                           and c.parent_id = '" . (int)$new_products_category_id . "'
                           and p.products_id = s.products_id and p.products_id = pd.products_id and p.products_status = '1' and s.status = '1' and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'";

}
$specials_index = $db->ExecuteRandomMulti($specials_index_query, MAX_DISPLAY_SPECIAL_PRODUCTS_INDEX);

$row = 0;
$col = 0;
$list_box_contents = array();
$title = '';

$num_products_count = $specials_index->RecordCount();
// show only when 1 or more
if ($num_products_count > 0) {
  if ($num_products_count < SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS || SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS == 0 ) {
    $col_width = floor(100/$num_products_count);
  } else {
    $col_width = floor(100/SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS);
  }

  $list_box_contents = array();
  while (!$specials_index->EOF) {

    $products_img = (($specials_index->fields['products_image'] == '' and PRODUCTS_IMAGE_NO_IMAGE_STATUS == 0) ? '' : '<a href="' . zen_href_link(zen_get_info_page($specials_index->fields['products_id']), 'products_id=' . (int)$specials_index->fields['products_id']) . '">' . zen_image(DIR_WS_IMAGES . $specials_index->fields['products_image'], $specials_index->fields['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>');
    
	$products_name = '<a class="name" href="' . zen_href_link(zen_get_info_page($specials_index->fields['products_id']), 'products_id=' . $specials_index->fields['products_id']) . '">' . zen_get_products_name($specials_index->fields['products_id']) . '</a>';
    
	$products_desc = substr(strip_tags($specials_index->fields['products_description']), 0, 25) . '...';
	
    $products_price = '<strong>' . zen_get_products_display_price($specials_index->fields['products_id']) . '</strong>';
	
	$products_butt = '<a href="' . zen_href_link(zen_get_info_page($specials_index->fields['products_id']), 'products_id=' . $specials_index->fields['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_GOTO_PROD_DETAILS, BUTTON_GOTO_PROD_DETAILS_ALT) . '</a>';


	$img_col_w = SMALL_IMAGE_WIDTH + 10;



	if (SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS > 1 && $num_products_count > 1) {
	
		if ($col > 0 && $col < SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS) {
			$tm_param = 'style="margin-left:2px;"';
		} else {
			$tm_param = '';
		}
	
    $list_box_contents[$row][$col] = array('params' =>'class="centerBoxContentsSpecials centeredContent back"' . ' ' . 'style="width:' . $col_width . '%;"',
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

    $list_box_contents[$row][$col] = array('params' =>'class="centerBoxContentsSpecials centeredContent back"' . ' ' . 'style="width:' . $col_width . '%;"',
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
    if ($col > (SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS - 1)) {
      $col = 0;
      $row ++;
    }
    $specials_index->MoveNextRandom();
  }

  if ($specials_index->RecordCount() > 0) {
    $title = '<h2 class="centerBoxHeading">' . sprintf(TABLE_HEADING_SPECIALS_INDEX, strftime('%B')) . '</h2>';
    $zc_show_specials = true;
  }
}
?>