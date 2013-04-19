<?php

/**
 * Common Template - tpl_header.php
 *
 * this file can be copied to /templates/your_template_dir/pagename<br />
 * example: to override the privacy page<br />
 * make a directory /templates/my_template/privacy<br />
 * copy /templates/templates_defaults/common/tpl_footer.php to /templates/my_template/privacy/tpl_header.php<br />
 * to override the global settings and turn off the footer un-comment the following line:<br />
 * <br />
 * $flag_disable_header = true;<br />
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_header.php 4813 2006-10-23 02:13:53Z drbyte $
 */
?>


<div class="container">

<!--bof-navigation display-->
<div id="navMainWrapper">
<div id="navMain">
    <ul class="back">
    <li><?php echo '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . '">'; ?><?php echo HEADER_TITLE_CATALOG; ?></a></li>
<?php if ($_SESSION['customer_id']) { ?>
    <li><a href="<?php echo zen_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>"><?php echo HEADER_TITLE_LOGOFF; ?></a></li>
    <?php if (!($_SESSION['COWOA'])) { ?>
		<li><a href="<?php echo zen_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><?php echo HEADER_TITLE_MY_ACCOUNT; ?></a></li>
	<?php } ?>
<?php
      } else {
        if (STORE_STATUS == '0') {
?>
    <li><a href="<?php echo zen_href_link(FILENAME_LOGIN, '', 'SSL'); ?>"><?php echo HEADER_TITLE_LOGIN; ?></a></li>
<?php } } ?>

<?php if ($_SESSION['cart']->count_contents() != 0) { ?>
    <li><a href="<?php echo zen_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'); ?>"><?php echo HEADER_TITLE_CART_CONTENTS; ?></a></li>
    <li><a href="<?php echo zen_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'); ?>"><?php echo HEADER_TITLE_CHECKOUT; ?></a></li>
<?php }?>
</ul>
</div>
<div id="navMainSearch"><?php require(DIR_WS_MODULES . 'sideboxes/search_header.php'); ?></div>
<div class="clearBoth"></div>
</div>
<!--eof-navigation display-->


<header>  	
	<?php 
	if($current_page == "product_info"){
		include(DIR_WS_TEMPLATE . "common/logo_bar_product_detail.php");
	} else {
		include(DIR_WS_TEMPLATE . "common/logo_bar.php");
	} 
	?> 
    <?php include(DIR_WS_TEMPLATE . "common/main_navigation.php"); ?>      
    <?php include(DIR_WS_TEMPLATE . "common/top_row_navigation.php"); ?>  
    <?php //include(DIR_WS_TEMPLATE . "common/shopping_cart_header.php"); ?> 
</header>
<div class="header-after" ></div>

<section id="bigButtons">
	<div class="defaultBanner">
	<img class='topBannerHeader' src="images/topbanner/home_header.png" />
	<div class="topBannerBlock" id="business">
    	<p> The Business <BR />on Your Shirt</p>
        <a href="index.php?main_page=how_it_works">Learn More</a> &rsaquo;
    </div>
	<div class="topBannerBlock" id="artist">
    	<p>The artist who<BR />created your shirt</p>
        <a href="index.php?main_page=how_it_works">Learn More</a> &rsaquo;
    </div>
	<div class="topBannerBlock" id="charity">
    	<p>a charity<BR />of your choice</p>
        <a href="index.php?main_page=how_it_works">Learn More</a> &rsaquo;
    </div>
    
</div>



<!--    
    <div class="arrows"><div class="text">Proceeds from every shirt you buy are shared with 3 local parts of your community:</div></div>
	<ul id="menu" class="buttons">
		<li id="business"><a href="index.php?main_page=how_it_works">the Business</a></li>
		<li id="designer"><a href="index.php?main_page=how_it_works">the Designer</a></li>
 		<li id="charity"><a href="index.php?main_page=how_it_works">the Charity</a></li>
	</ul>
    -->
</section>

<script>
$(document).ready(function() {    
	$("ul#menu li a").wrapInner("<span></span>");      
	$("ul#menu li a span").css({"opacity" : 0});
    $("ul#menu li a").hover(function(){
    	$(this).children("span").animate({"opacity" : 1}, 400);
    }, function(){
		$(this).children("span").animate({"opacity" : 0}, 400);
    });	
});
</script>





<section id="main">

<?php
  // Display all header alerts via messageStack:
  if ($messageStack->size('header') > 0) {
    echo $messageStack->output('header');
  }
  if (isset($_GET['error_message']) && zen_not_null($_GET['error_message'])) {
  echo htmlspecialchars(urldecode($_GET['error_message']));
  }
  if (isset($_GET['info_message']) && zen_not_null($_GET['info_message'])) {
   echo htmlspecialchars($_GET['info_message']);
} else {

}
?>


<!--bof-header logo and navigation display-->
<?php
if (!isset($flag_disable_header) || !$flag_disable_header) {
?>                            
    <div id="headerWrapper">
    
    <!--bof-optional categories tabs navigation display-->
    <!-- SHOWS ALL CATEGORIES. In Our case, this would be the city name. -->
    <?php 
    // require($template->get_template_dir('tpl_modules_categories_tabs.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_categories_tabs.php'); ?>
    <!--eof-optional categories tabs navigation display-->
    
    <!--bof-header ezpage links-->
    <!-- SHOWS ALL EZ-PAGE LINKS. -->
    <?php //if (EZPAGES_STATUS_HEADER == '1' or (EZPAGES_STATUS_HEADER == '2' and (strstr(EXCLUDE_ADMIN_IP_FOR_MAINTENANCE, $_SERVER['REMOTE_ADDR'])))) { ?>
    <?php //require($template->get_template_dir('tpl_ezpages_bar_header.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_ezpages_bar_header.php'); ?>
    <?php //} ?>
    <!--eof-header ezpage links-->												
</div>
<?php } ?>