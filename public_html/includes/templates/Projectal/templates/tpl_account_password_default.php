<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=account_password.<br />
 * Allows customer to change their password
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_account_password_default.php 2896 2006-01-26 19:10:56Z birdbrain $
 */
?>
<div class="centerColumn" id="accountPassword">

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
    
    <?php echo zen_draw_form('account_password', zen_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL'), 'post', 'onsubmit="return check_form(account_password);"') . zen_draw_hidden_field('action', 'process'); ?>
    
    <fieldset>
    <legend><?php echo HEADING_TITLE; ?></legend>
    <div class="alert forward"><?php echo FORM_REQUIRED_INFORMATION; ?></div> 
    <br class="clearBoth" />
    
    <?php if ($messageStack->size('account_password') > 0) echo $messageStack->output('account_password'); ?>
    
    <label class="inputLabel" for="password-current"><?php echo ENTRY_PASSWORD_CURRENT; ?></label>
    <?php echo zen_draw_password_field('password_current','','id="password-current"') . (zen_not_null(ENTRY_PASSWORD_CURRENT_TEXT) ? '<span class="alert">' . ENTRY_PASSWORD_CURRENT_TEXT . '</span>': ''); ?>
    <br class="clearBoth" />
    
    <label class="inputLabel" for="password-new"><?php echo ENTRY_PASSWORD_NEW; ?></label>
    <?php echo zen_draw_password_field('password_new','','id="password-new"') . (zen_not_null(ENTRY_PASSWORD_NEW_TEXT) ? '<span class="alert">' . ENTRY_PASSWORD_NEW_TEXT . '</span>': ''); ?>
    <br class="clearBoth" />
    
    <label class="inputLabel" for="password-confirm"><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></label>
    <?php echo zen_draw_password_field('password_confirmation','','id="password-confirm"') . (zen_not_null(ENTRY_PASSWORD_CONFIRMATION_TEXT) ? '<span class="alert">' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '</span>': ''); ?>
    <br class="clearBoth" />
    </fieldset>
    
     <div class="buttonRow forward"><input type="submit" value="<?php echo BUTTON_SUBMIT_ALT; ?>" title="<?php echo BUTTON_SUBMIT_ALT; ?>" class="fancybutton greenButton small" /></div>
     <div class="buttonRow back"><?php echo '<a href="' . zen_href_link(FILENAME_ACCOUNT, '', 'SSL') . '" class="fancybutton greenButton small"">' . BUTTON_BACK_ALT . '</a>'; ?></div>
    
    </form>

</div> <!-- END centerColumnPadding -->
<div class="shadow" id="bottom" ></div>
</div> <!-- END centerColumnContent -->
</div> <!-- END centerColumn -->
