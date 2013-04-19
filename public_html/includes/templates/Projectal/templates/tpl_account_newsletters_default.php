<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=account_newsletters.<br />
 * Subscribe/Unsubscribe from General Newsletter
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_account_newsletters_default.php 2896 2006-01-26 19:10:56Z birdbrain $
 */
?>
<div class="centerColumn" id="acctNewslettersDefault">

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

	<?php echo zen_draw_form('account_newsletter', zen_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL')) . zen_draw_hidden_field('action', 'process'); ?>
    
    <h1 id="acctNewslettersDefaultHeading"><?php echo HEADING_TITLE; ?></h1>
    
    <fieldset>
    <legend><?php echo MY_NEWSLETTERS_GENERAL_NEWSLETTER; ?></legend>
    <?php echo zen_draw_checkbox_field('newsletter_general', '1', (($newsletter->fields['customers_newsletter'] == '1') ? true : false), 'id="newsletter"'); ?>
    <label class="checkboxLabel" for="newsletter"><?php echo MY_NEWSLETTERS_GENERAL_NEWSLETTER_DESCRIPTION; ?></label>
    <br class="clearBoth" />
    </fieldset>
    
    
    
        
    <? 
    while (!$newsletterCustom->EOF) { ?>
        <fieldset>
            <?php echo zen_draw_hidden_field('custom_mailinglist_id[]', $newsletterCustom->fields['list_id'], 'id="newsletter"'); ?>
            <legend><? echo $newsletterCustom->fields['list_name']; ?></legend>
            <?php echo zen_draw_checkbox_field('custom_mailinglist_' . $newsletterCustom->fields['list_id'], $newsletterCustom->fields['list_id'], (($newsletterCustom->fields['isSignedUp'] == '1') ? true : false), 'id="newsletter"'); ?>
            <label class="checkboxLabel" for="newsletter"><? echo $newsletterCustom->fields['list_description']; ?></label>
            <br class="clearBoth" />
        </fieldset>
        <? 
        $newsletterCustom->MoveNext();
    } ?>
    
    
    
    <div class="buttonRow forward"><input type="submit" value="<?php echo BUTTON_UPDATE_ALT; ?>" title="<?php echo BUTTON_UPDATE_ALT; ?>" class="fancybutton greenButton small" /></div> 
    <div class="buttonRow back"><?php echo '<a href="' . zen_href_link(FILENAME_ACCOUNT, '', 'SSL') . '" class="fancybutton greenButton small">' . BUTTON_BACK_ALT . '</a>'; ?></div>
    
    </form>



</div> <!-- END centerColumnPadding -->
<div class="shadow" id="bottom" ></div>
</div> <!-- END centerColumnContent -->
</div> <!-- END centerColumn -->