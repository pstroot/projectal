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
if (defined('WOZ_CONFIG_STATUS') && WOZ_CONFIG_STATUS == 'true') {
?>
<link rel="stylesheet" href="<?php woz_ssl_convert(bloginfo('stylesheet_url')); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php
  $out = '';
  //start wp output
  ob_start();
  wp_head();
  $out = ob_get_clean();
  //end wp output
  echo woz_ssl_convert(woz_str_convert($out));
?>
<style type="text/css">
<!--
/* for Zen-Cart v1.3 */
.leftBoxContainer ul li, .rightBoxContainer ul li , .singleBoxContainer ul li{
  list-style-type: none;
  margin:0px;
  padding-left: 0px;
}

.leftBoxContainer ul, .rightBoxContainer ul , .singleBoxContainer ul{
  margin:0px;
  padding-left:0px;
}

.leftBoxContainer ul ul, .rightBoxContainer ul ul, .singleBoxContainer ul ul{
  list-style-type: square;
  margin:0px;
  padding-left:5px;
}

/* for Zen-Cart v1.2 */
.leftboxcontent ul li, .rightboxcontent ul li , .singleboxcontent ul li{
  list-style-type: none;
  margin:0px;
  padding-left: 0px;
}

.leftboxcontent ul, .rightboxcontent ul , .singleboxcontent ul{
  margin:0px;
  padding-left:0px;
}

.leftboxcontent ul ul, .rightboxcontent ul ul, .singleboxcontent ul ul{
  list-style-type: square;
  margin:0px;
  padding-left:5px;
}
-->
</style>
<?php
}
?>
