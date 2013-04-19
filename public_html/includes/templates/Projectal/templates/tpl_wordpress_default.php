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
?>

<div class="centerColumn" id="wordpressDefault">
<div class="centerColumnContent whiteContent">
<div class="centerColumnPadding">



<?php
if (defined('WOZ_CONFIG_STATUS') && WOZ_CONFIG_STATUS == 'true') {
	print $define_page;
}else{
	echo TEXT_ERROR_ABSPATH;
}
?>



</div> <!-- END centerColumnPadding -->
<div class="shadow" id="bottom" ></div>
</div> <!-- END centerColumnContent -->
</div> <!-- END centerColumn -->
