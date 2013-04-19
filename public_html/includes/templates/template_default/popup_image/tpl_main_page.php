<?php
/**
 * Override Template for common/tpl_main_page.php
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_main_page.php 2993 2006-02-08 07:14:52Z birdbrain $
 */

?>
<style type="text/css">
<!--
body {
	margin: 0px;
	padding: 0px;
	height: 100%;
}

.content{
	text-align:center;
	width:100%;
	height:100%;
}


-->
</style>
<body id="popupImage" class="centeredContent" style="background-color:#FFFFFF;" onLoad="resize();">

<table class="content" border="1"><TR><TD style="vertical-align:middle;">
<?php
  // $products_values->fields['products_image']
  echo '<a href="javascript:window.close()">' . zen_image($products_image_large, $products_values->fields['products_name'] . ' ' . TEXT_CLOSE_WINDOW) . '</a>';
?>
</TD></TR></table>
</body>