<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=account_notifications.<br />
 * Allows customer to manage product notifications
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_account_notifications_default.php 3206 2006-03-19 04:04:09Z birdbrain $
 */
?>
<div class="centerColumn" id="accountNotifications">

<!-- bof  breadcrumb -->
<?php if (DEFINE_BREADCRUMB_STATUS == '1' || (DEFINE_BREADCRUMB_STATUS == '2' && !$this_is_home_page) ) { ?>
<div class="breadcrumbs">
	<?php 
	//unset($breadcrumb->_trail[1]); // remove the first level category, which is the city
	$breadcrumb->_trail= array_values($breadcrumb->_trail); // reassigns the array indicies so there isn't a hole.
	echo $breadcrumb->trail(BREAD_CRUMBS_SEPARATOR); ?><span class="activeCrumb"></span>
</div>
<?php } ?>
<!-- eof breadcrumb -->


<div class="centerColumnContent whiteContent">
<div class="centerColumnPadding">
    
    
    <?php echo zen_draw_form('account_notifications', zen_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'SSL')) . zen_draw_hidden_field('action', 'process'); ?>
    
    <h1 id="accountNotificationsHeading"><?php echo HEADING_TITLE; ?></h1>
    
    <div class="notice"><?php echo MY_NOTIFICATIONS_DESCRIPTION; ?></div>
    
    <fieldset>
    <legend><?php echo GLOBAL_NOTIFICATIONS_TITLE; ?></legend>
    
    <?php echo zen_draw_checkbox_field('product_global', '1', (($global->fields['global_product_notifications'] == '1') ? true : false), 'id="globalnotify"'); ?>
    <label class="checkboxLabel" for="globalnotify"><?php echo GLOBAL_NOTIFICATIONS_DESCRIPTION; ?></label>
    <br class="clearBoth" />
    </fieldset>
    
    <?php
    if ($flag_global_notifications != '1') {
    ?>
    <fieldset>
    <legend><?php echo NOTIFICATIONS_TITLE; ?></legend>
    
    <?php
    	if ($flag_products_check) {
			?>
			<div class="notice"><?php echo NOTIFICATIONS_DESCRIPTION; ?></div>
			<?php
			/**
			 * Used to loop thru and display product notifications
			 */
			  foreach ($notificationsArray as $notifications) { 
				?>
                <div>
                <?php echo zen_draw_checkbox_field('notify[]', $notifications['products_id'], true, 'id="notify-' . $notifications['counter'] . '"'); ?>
				<label class="checkboxLabel" for="<?php echo 'notify-' . $notifications['counter']; ?>"><?php echo $notifications['products_name']; ?></label>
				</div>
				<?php
			  }
			?>
			</fieldset>
			
			<div class="buttonRow forward"><input type="submit" value="<?php echo BUTTON_UPDATE_ALT; ?>" title="<?php echo BUTTON_UPDATE_ALT; ?>" class="fancybutton greenButton small" /></div> 
			<div class="buttonRow back"><?php echo '<a href="' . zen_href_link(FILENAME_ACCOUNT, '', 'SSL') . '" class="fancybutton greenButton small">' . BUTTON_BACK_ALT . '</a>'; ?></div>
			
			<?php
        } else {
			?>
            
			<div class="notice"><?php echo NOTIFICATIONS_NON_EXISTING; ?></div>
			</fieldset>
			<div class="buttonRow forward"><input type="submit" value="<?php echo BUTTON_UPDATE_ALT; ?>" title="<?php echo BUTTON_UPDATE_ALT; ?>" class="fancybutton greenButton small" /></div> 
			<div class="buttonRow back"><?php echo '<a href="' . zen_href_link(FILENAME_ACCOUNT, '', 'SSL') . '" class="fancybutton greenButton small">' . BUTTON_BACK_ALT . '</a>'; ?></div>
			<?php
        }
    ?>
    
    <?php
    }
    ?>
    
    </form>    

</div> <!-- END centerColumnPadding -->
<div class="shadow" id="bottom" ></div>
</div> <!-- END centerColumnContent -->
</div> <!-- END centerColumn -->

