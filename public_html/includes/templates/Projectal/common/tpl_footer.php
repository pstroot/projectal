<?php
/**
 * Common Template - tpl_footer.php
 *
 * this file can be copied to /templates/your_template_dir/pagename<br />
 * example: to override the privacy page<br />
 * make a directory /templates/my_template/privacy<br />
 * copy /templates/templates_defaults/common/tpl_footer.php to /templates/my_template/privacy/tpl_footer.php<br />
 * to override the global settings and turn off the footer un-comment the following line:<br />
 * <br />
 * $flag_disable_footer = true;<br />
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_footer.php 4821 2006-10-23 10:54:15Z drbyte $
 */
require(DIR_WS_MODULES . zen_get_module_directory('footer.php'));
?>


</section> <!-- END <section id="main"> -->

<footer>
  <div class="copyright">&copy; <?=date("Y",strtotime("now")); ?> ProjectAl, LLC All rights reserved,<BR />
  designed by ProjectAl and developed by <a href="http://paulstroot.com">dancing paul</a>.</div>
   
    <nav id="footer">   
    	<? if( isset($_SERVER['HTTPS'] ) ) { ?>
			<img src="<?php echo DIR_WS_TEMPLATE?>images/SSL_Seal.png" style="position:absolute;margin-top:-45px;margin-left:-60px;"/>
        <? } ?>
        <ul>
        <li><a href='<?php echo DIR_WS_CATALOG; ?>index.php?main_page=wordpress' style="color:#272525;border:none;">BLOG</a></li>
        <li><a href='<?php echo DIR_WS_CATALOG; ?>index.php?main_page=contact_us' style='border:none;' onmouseover='rollOverContactLink()' onmouseout='rollOffContactLink()'>Contact</a></li>
        
		<?php 
        $Query = "SELECT * FROM zen_ezpages WHERE status_footer = 1 ORDER BY footer_sort_order";
        $Results = $db->Execute($Query);
       
	    while (!$Results->EOF) {	
            $title = $Results->fields['pages_title'];
            $id = $Results->fields['pages_id'];
            $useExternal = $Results->fields['alt_url_external'];
            $useInternal = $Results->fields['alt_url'];
            
			if($Results->fields['page_open_new_window'] == 1){
                $target = "_blank";
            }else{
                $target = "_self";
            }
            
            if($useExternal != ""){
                $link = $useExternal;
            } else if($useInternal != ""){
                $link = $useInternal;
            } else {
                $link = DIR_WS_CATALOG . "index.php?main_page=page&amp;id=" . $id;
            }
			
            print "<li><a href='$link' target='$target'>$title</a></li>\n";
            $Results->MoveNext();
        } 
		
        ?>
        </ul>
    </nav>
</footer>

<canvas id="easteregg_horsehead" width="114" height="134" ></canvas>
<script type="text/javascript" src="<?php echo DIR_WS_TEMPLATE?>scripts/horseHead.js"></script>
    
    
<!--bof-footer ezpage links-->
<?php 

if (EZPAGES_STATUS_FOOTER == '1' or (EZPAGES_STATUS_FOOTER == '2' and (strstr(EXCLUDE_ADMIN_IP_FOR_MAINTENANCE, $_SERVER['REMOTE_ADDR'])))) {
	//require($template->get_template_dir('tpl_ezpages_bar_footer.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_ezpages_bar_footer.php');
} 

?>
<!--eof-footer ezpage links-->	
    
    
    
</div> <!-- END CONTAINER DIV -->


<?php
// the 1==2 should disable this without having to delete the code
if (1==2 && (!isset($flag_disable_footer) || !$flag_disable_footer)) {
?>

<!--bof-navigation display -->
<div id="navSuppWrapper">
<div id="navSupp">
<ul>
<li><?php echo '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . '">'; ?><?php echo HEADER_TITLE_CATALOG; ?></a></li>
<?php if (EZPAGES_STATUS_FOOTER == '1' or (EZPAGES_STATUS_FOOTER == '2' and (strstr(EXCLUDE_ADMIN_IP_FOR_MAINTENANCE, $_SERVER['REMOTE_ADDR'])))) { ?>
<li><?php require($template->get_template_dir('tpl_ezpages_bar_footer.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_ezpages_bar_footer.php'); ?></li>
<?php } ?>
</ul>
</div>
</div>
<!--eof-navigation display -->


<?php
} // flag_disable_footer
?>


<!--bof-ip address display -->
<?php
if (SHOW_FOOTER_IP == '1') {
?>
<div id="siteinfoIP"><?php echo TEXT_YOUR_IP_ADDRESS . '  ' . $_SERVER['REMOTE_ADDR']; ?></div>
<?php
}
?>
<!--eof-ip address display -->

<!--bof-banner #5 display -->
<?php
  if (SHOW_BANNERS_GROUP_SET5 != '' && $banner = zen_banner_exists('dynamic', SHOW_BANNERS_GROUP_SET5)) {
    if ($banner->RecordCount() > 0) {
?>
<div id="bannerFive" class="banners"><?php echo zen_display_banner('static', $banner); ?></div>
<?php
    }
  }
?>
<!--eof-banner #5 display -->


<!-- GOOGLE ANALYTICS -->
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-9807123-3']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<!-- END GOOGLE ANALYTICS -->


