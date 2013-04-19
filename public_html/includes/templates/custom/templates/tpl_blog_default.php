<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=blog.<br />
 * Displays conditions page.
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 */
?>
<div class="centerColumn" id="blog">
<h1 id="blogHeading"><?php echo HEADING_TITLE; ?></h1>

<div id="blogMainContent" class="content">
<?php
/**
 * require the html_define for the blog page
 */
  require($define_page);
?>
<?php
require_once DIR_FS_CATALOG . 'carp/carp/carp.php';
// Add any desired configuration settings before CarpCacheShow
// using "CarpConf" and other functions
CarpConf('ai', '<br />'); 
CarpCacheShow('http://www.ecommercetimes.com/perl/syndication/rssfull.pl');
?>
</div>

<div class="buttonRow back"><?php echo zen_back_link() . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a>'; ?></div>
</div>
