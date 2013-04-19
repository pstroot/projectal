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
  if (!defined('IS_ADMIN_FLAG')) {
   die('Illegal Access');
  }

if (defined('WOZ_CONFIG_STATUS') && WOZ_CONFIG_STATUS == 'true') {
  $woz_dir = "";
  $sql = "select woz_dir
            from " . TABLE_WOZ . "
            where woz_language = 0";
  $woz_query = $db->Execute($sql);
  $woz_dir = $woz_query->fields['woz_dir'];

  $sql = "select woz_dir
            from " . TABLE_WOZ . "
            where woz_language = '" . $_SESSION['languages_id'] . "'";
  $woz_query = $db->Execute($sql);
  if ($woz_query->RecordCount() > 0) {
    $woz_dir = $woz_query->fields['woz_dir'];
  }

  define('ABSPATH', $woz_dir);
  define('WP_USE_THEMES', true);
  $wp_did_header = true;
  
  require_once(ABSPATH . 'wp-config.php');
  wp();

  /*$i = strlen(DIR_WS_CATALOG);
  $req = substr($_SERVER['REQUEST_URI'],$i,1);
  if($req == '?'){
    $_GET['main_page'] = FILENAME_WORDPRESS;
  }*/

  if ($_GET['main_page'] == FILENAME_WORDPRESS && isset($_GET['feed']) && ($_GET['feed'] == 'rss2' || $_GET['feed'] == 'comments-rss2')) {
    gzip_compression();
    require_once(ABSPATH . WPINC . '/template-loader.php');
    exit();
  }
}
?>
