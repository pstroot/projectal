<?php
/**
 * Do not delete this copyright link/text when you use free edition. 
 * When you want to delete it, please purchase a professional edition. 
 * Please understand that I spend time on development.
 * 
 * @package    WordPress On ZenCart
 * @author     HIRAOKA Tadahito (hira)
 * @copyright  Copyright 2008-2010 S-page
 * @copyright  Copyright 2003-2007 Zen Cart Development Team
 * @copyright  Portions Copyright 2003 osCommerce
 * @link       http://www.s-page.net/products/62.html
 * @license    http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 */
  require(DIR_WS_MODULES . 'require_languages.php');
  
  if (!defined('WOZ_CONFIG_STATUS') or WOZ_CONFIG_STATUS != 'true') {
    echo TEXT_ERROR_ABSPATH;
    exit;
  }

  if(isset($_GET['s']) && DB_CHARSET == 'utf8' && CHARSET == 'EUC-JP' && !isset($_GET['redirected'])){
    zen_redirect(zen_href_link(FILENAME_WORDPRESS) . '&redirected=1&s=' . mb_convert_encoding(urldecode($_GET['s']), "UTF-8","EUC-JP"));
    exit;
  }
  
  $blog_title = woz_str_convert(get_bloginfo('name'));
  
  // get page_title BOF
  if (is_home()) {
    $page_title = $blog_title;
  } else if (is_category()) {
    $page_title = woz_str_convert(single_cat_title('',false));
  } else if (is_tag()) {
    $page_title = woz_str_convert(single_tag_title('', false));
  } else if (is_day()) {
    $page_title = woz_str_convert(get_the_time('F jS, Y'));
  } else if (is_month()) {
    $page_title = woz_str_convert(get_the_time('F, Y'));
  } else if (is_year()) {
    $page_title = woz_str_convert(get_the_time('Y'));
  } else if (is_author()) {
    $page_title = TEXT_TITLE_IS_AUTHOR;
  } else if (is_search()) {
    $page_title = TEXT_TITLE_IS_SEARCH;
  } else if (is_single()) {
    $page_title = woz_str_convert(get_the_title());
  } else if (is_page()) {
    $page_title = woz_str_convert(get_the_title());
  } else if (is_404()) {
    $page_title = 'not found';
   }
  // get page_title EOF
  
  // set breadcrumb & navbar BOF
  if($page_title != $blog_title){ 
    $breadcrumb->add($blog_title, zen_href_link(FILENAME_WORDPRESS));
    $breadcrumb->add($page_title);
    define('NAVBAR_TITLE', $page_title . SECONDARY_SECTION . $blog_title);
  }else{
    $breadcrumb->add($blog_title);
    define('NAVBAR_TITLE', $page_title);
  }
  // set breadcrumb & navbar EOF  
  $zc_template = $template;
  ob_start();
  require_once(ABSPATH . WPINC . '/template-loader.php');
  $out = ob_get_clean() . urldecode(WOZ_CONFIG_INFO);
  $define_page = woz_str_convert($out);
  $template = $zc_template;
?>
