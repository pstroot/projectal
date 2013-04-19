<?php
/**
 * Page Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_products_next_previous.php 6912 2007-09-02 02:23:45Z drbyte $
 */

/*
 WebMakers.com Added: Previous/Next through categories products
 Thanks to Nirvana, Yoja and Joachim de Boer
 Modifications: Linda McGrath osCommerce@WebMakers.com
*/
	   
?>
<?php
// only display when more than 1
if ($products_found_count > 1) {
	?>
	
	<a href="<?php echo zen_href_link(zen_get_info_page($previous), "cPath=$cPath&products_id=$previous"); ?>"><img src="<?php echo DIR_WS_TEMPLATE?>images/previous_arrow.png" width="9" height="17" alt="previous" id="previous"/></a>
	| 
	<a href="<?php echo zen_href_link(zen_get_info_page($next_item), "cPath=$cPath&products_id=$next_item"); ?>"><img src="<?php echo DIR_WS_TEMPLATE?>images/next_arrow.png" width="9" height="17" alt="next" id="next"/></a>
	<?php
}
?>