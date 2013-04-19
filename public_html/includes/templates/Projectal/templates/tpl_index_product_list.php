<script>
activateNav('nav-shop');
<?
$activeCategories = explode("_",@$_REQUEST["cPath"]);
foreach($activeCategories as $catID){
	print "activateNav('shop_".$catID."');\n"; 
}
?>
</script>
<?php 
/**
 * Page Template
 *
 * Loaded by main_page=index<br />
 * Displays product-listing when a particular category/subcategory is selected for browsing
 *
 * @package templateSystem
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_index_product_list.php 15589 2010-02-27 15:03:49Z ajeh $
 */
?>


<div id="top_right_align"> 
	<!-- bof: Previous / Next Buttons for multiple pages -->  
    <div id="previous_next"> 		
		<?  
        $listing_sql = "select " . $select_column_list . " p.products_id, p.products_type, p.master_categories_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, pd.products_description, IF(s.status = 1, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status =1, s.specials_new_products_price, p.products_price) as final_price, p.products_sort_order, p.product_is_call, p.product_is_always_free_shipping, p.products_qty_box_status
           from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " .
           TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, " .
           TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS . " s on p2c.products_id = s.products_id
           where p.products_status = 1
             and p.products_id = p2c.products_id
             and pd.products_id = p2c.products_id
             and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
             and p2c.categories_id = '" . (int)$current_category_id . "'";
        $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_PRODUCTS_LISTING, 'p.products_id', 'page');
    
        if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
        ?>
        <div id="productsListingListingTopLinks" class="navSplitPagesLinks forward"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, zen_get_all_get_params(array('page', 'info', 'x', 'y', 'main_page'))); ?></div>
        <?php
        }
        ?>
	</div>
    <!-- eof: Previous / Next Buttons for multiple pages -->  
    
    <!-- bof: Category List -->    
    <div class="crossSellList">
    	<?
		// $cityID is set in "includes -> functions -> extra_functions -> projectal_functions.php";
		$query = "SELECT DISTINCT c.categories_id, d.categories_name, COUNT(p.products_id) AS nbrOfProducts
		FROM zen_categories c, zen_categories_description d
		LEFT JOIN zen_products p ON p.master_categories_id = d.categories_id
		WHERE c.categories_id = d.categories_id  
		AND parent_id = $cityID 
		AND p.products_status = 1
		GROUP BY c.categories_id
		ORDER BY c.sort_order";
		$Results = $db->Execute($query);
		while (!$Results->EOF) {
			$className = "";
			if ($Results->cursor == 0) $className = "first";
			if ($Results->cursor == $Results->RecordCount()-1) $className = "last";	
			$catID = $Results->fields['categories_id'];
			$catTitle = stripslashes($Results->fields['categories_name']);
			print "<a href='index.php?main_page=index&amp;cPath=".$cityID."_".$catID."' id='category_".$cityID."_".$catID."' class='".$className."'>".$catTitle."</a>";			
			$Results->MoveNext();
		}
		
		?>
    </div>
	<script>
        highlightCategory("category_<?=$_GET["cPath"];?>")
    </script>
    <!-- eof: Category List -->
    
    
</div>




<div class="centerColumn" id="indexProductList" >
<!-- <h1 id="productListHeading"><?php echo $breadcrumb->last(); ?></h1> -->

<!-- bof  breadcrumb -->
<?php if (DEFINE_BREADCRUMB_STATUS == '1' || (DEFINE_BREADCRUMB_STATUS == '2' && !$this_is_home_page) ) { ?>
<div class="breadcrumbs">
	<?php 
	//unset($breadcrumb->_trail[1]); // remove the first level category, which is the city
	$breadcrumb->_trail= array_values($breadcrumb->_trail); // reassigns the array indicies so there isn't a hole.
	echo $breadcrumb->trail(BREAD_CRUMBS_SEPARATOR); ?>
</div>
<?php } ?>
<!-- eof breadcrumb -->


<div class="centerColumnContent">
<div class="centerColumnPadding">

    
    <?php
    if (PRODUCT_LIST_CATEGORIES_IMAGE_STATUS == 'true') {
    // categories_image
      if ($categories_image = zen_get_categories_image($current_category_id)) {
    ?>
    <div id="categoryImgListing" class="categoryImg"><?php echo zen_image(DIR_WS_IMAGES . $categories_image, '', CATEGORY_ICON_IMAGE_WIDTH, CATEGORY_ICON_IMAGE_HEIGHT); ?></div>
    <?php
      }
    } // categories_image
    ?>
    <?php
    // categories_description
        if ($current_categories_description != '') {
    ?>
    <div id="indexProductListCatDescription" class="content"><?php echo $current_categories_description;  ?></div>
    <?php } // categories_description ?>
    
    <?php
      $check_for_alpha = $listing_sql;
      $check_for_alpha = $db->Execute($check_for_alpha);
    
      if ($do_filter_list || ($check_for_alpha->RecordCount() > 0 && PRODUCT_LIST_ALPHA_SORTER == 'true')) {
      $form = zen_draw_form('filter', zen_href_link(FILENAME_DEFAULT), 'get') . '<label class="inputLabel">' .TEXT_SHOW . '</label>';
    ?>
    
    <?php
      echo $form;
      echo zen_draw_hidden_field('main_page', FILENAME_DEFAULT);
      echo zen_hide_session_id();
    ?>
    <?php
      // draw cPath if known
      if (!$getoption_set) {
        echo zen_draw_hidden_field('cPath', $cPath);
      } else {
        // draw manufacturers_id
        echo zen_draw_hidden_field($get_option_variable, $_GET[$get_option_variable]);
      }
    
      // draw music_genre_id
      if (isset($_GET['music_genre_id']) && $_GET['music_genre_id'] != '') echo zen_draw_hidden_field('music_genre_id', $_GET['music_genre_id']);
    
      // draw record_company_id
      if (isset($_GET['record_company_id']) && $_GET['record_company_id'] != '') echo zen_draw_hidden_field('record_company_id', $_GET['record_company_id']);
    
      // draw typefilter
      if (isset($_GET['typefilter']) && $_GET['typefilter'] != '') echo zen_draw_hidden_field('typefilter', $_GET['typefilter']);
    
      // draw manufacturers_id if not already done earlier
      if ($get_option_variable != 'manufacturers_id' && isset($_GET['manufacturers_id']) && $_GET['manufacturers_id'] > 0) {
        echo zen_draw_hidden_field('manufacturers_id', $_GET['manufacturers_id']);
      }
    
      // draw sort
      echo zen_draw_hidden_field('sort', $_GET['sort']);
    
      // draw filter_id (ie: category/mfg depending on $options)
      if ($do_filter_list) {
        echo zen_draw_pull_down_menu('filter_id', $options, (isset($_GET['filter_id']) ? $_GET['filter_id'] : ''), 'onchange="this.form.submit()"');
      }
    
      // draw alpha sorter
      require(DIR_WS_MODULES . zen_get_module_directory(FILENAME_PRODUCT_LISTING_ALPHA_SORTER));
    ?>
    </form>
    <?php
      }
    ?>
    <br class="clearBoth" />
    
    <?php
    /* require the code for listing products */
     require($template->get_template_dir('tpl_modules_product_listing.php', DIR_WS_TEMPLATE, $current_page_base,'templates'). '/' . 'tpl_modules_product_listing.php');
    ?>
    
    
    <?php
    //// bof: categories error
    if ($error_categories==true) {
      // verify lost category and reset category
      $check_category = $db->Execute("select categories_id from " . TABLE_CATEGORIES . " where categories_id='" . $cPath . "'");
      if ($check_category->RecordCount() == 0) {
        $new_products_category_id = '0';
        $cPath= '';
      }
    ?>
    
    <?php
    $show_display_category = $db->Execute(SQL_SHOW_PRODUCT_INFO_MISSING);
    
    while (!$show_display_category->EOF) {
    ?>
    
    <?php
      if ($show_display_category->fields['configuration_key'] == 'SHOW_PRODUCT_INFO_MISSING_FEATURED_PRODUCTS') { ?>
    <?php
    /**
     * display the Featured Products Center Box
     */
    ?>
    <?php require($template->get_template_dir('tpl_modules_featured_products.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_featured_products.php'); ?>
    <?php } ?>
    
    <?php
      if ($show_display_category->fields['configuration_key'] == 'SHOW_PRODUCT_INFO_MISSING_SPECIALS_PRODUCTS') { ?>
    <?php
    /**
     * display the Special Products Center Box
     */
    ?>
    <?php require($template->get_template_dir('tpl_modules_specials_default.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_specials_default.php'); ?>
    <?php } ?>
    
    <?php
      if ($show_display_category->fields['configuration_key'] == 'SHOW_PRODUCT_INFO_MISSING_NEW_PRODUCTS') { ?>
    <?php
    /**
     * display the New Products Center Box
     */
    ?>
    <?php require($template->get_template_dir('tpl_modules_whats_new.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_whats_new.php'); ?>
    <?php } ?>
    
    <?php
      if ($show_display_category->fields['configuration_key'] == 'SHOW_PRODUCT_INFO_MISSING_UPCOMING') {
        include(DIR_WS_MODULES . zen_get_module_directory(FILENAME_UPCOMING_PRODUCTS));
      }
    ?>
    <?php
      $show_display_category->MoveNext();
    } // !EOF
    ?>
    <?php } //// eof: categories error ?>
    
    <?php
    //// bof: categories
    $show_display_category = $db->Execute(SQL_SHOW_PRODUCT_INFO_LISTING_BELOW);
    if ($error_categories == false and $show_display_category->RecordCount() > 0) {
    ?>
    
    <?php
      $show_display_category = $db->Execute(SQL_SHOW_PRODUCT_INFO_LISTING_BELOW);
      while (!$show_display_category->EOF) {
    ?>
    
    <?php
        if ($show_display_category->fields['configuration_key'] == 'SHOW_PRODUCT_INFO_LISTING_BELOW_FEATURED_PRODUCTS') { ?>
    <?php
    /**
     * display the Featured Products Center Box
     */
    ?>
    <?php require($template->get_template_dir('tpl_modules_featured_products.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_featured_products.php'); ?>
    <?php } ?>
    
    <?php
        if ($show_display_category->fields['configuration_key'] == 'SHOW_PRODUCT_INFO_LISTING_BELOW_SPECIALS_PRODUCTS') { ?>
    <?php
    /**
     * display the Special Products Center Box
     */
    ?>
    <?php require($template->get_template_dir('tpl_modules_specials_default.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_specials_default.php'); ?>
    <?php } ?>
    
    <?php
        if ($show_display_category->fields['configuration_key'] == 'SHOW_PRODUCT_INFO_LISTING_BELOW_NEW_PRODUCTS') { ?>
    <?php
    /**
     * display the New Products Center Box
     */
    ?>
    <?php require($template->get_template_dir('tpl_modules_whats_new.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_whats_new.php'); ?>
    <?php } ?>
    
    <?php
        if ($show_display_category->fields['configuration_key'] == 'SHOW_PRODUCT_INFO_LISTING_BELOW_UPCOMING') {
          include(DIR_WS_MODULES . zen_get_module_directory(FILENAME_UPCOMING_PRODUCTS));
        }
    ?>
    <?php
      $show_display_category->MoveNext();
      } // !EOF
    ?>
    
    <?php
    } //// eof: categories
    ?>

</div> <!-- END centerColumnPadding -->
</div> <!-- END centerColumnContent -->
</div> <!-- END centerColumn -->
