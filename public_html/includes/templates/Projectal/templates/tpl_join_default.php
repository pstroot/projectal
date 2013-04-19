<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=contact_us.<br />
 * Displays contact us page form.
 *
 * @package templateSystem
 * @copyright Copyright 2003-2009 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_contact_us_default.php 16307 2010-05-21 21:50:06Z wilt $
 */
?>
<div class="centerColumn" id="joinDefault">


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




<?php
if (isset($_GET['action']) && ($_GET['action'] == 'success')) {
	?>
	<h1><?= HEADING_TITLE_SUCCESS; ?></h1>
	<div class="mainContent success"><?php echo TEXT_SUCCESS; ?></div>
    <BR /><BR /><BR /><BR /><BR /><BR />
		
	<?php
} else {
	?>
    
	<div class="bigWhiteArrow"></div>
    
    <div class="topText">
    By joining ProjectAl you'll be able to create a customer profile which allows you to shop faster, track the status of your current orders and review your previous orders.
    </div>
    
	<!--<h1 id="mainTitle"><?= HEADING_TITLE; ?></h1>-->
    
    <?php echo zen_draw_form('joinForm', zen_href_link(FILENAME_JOIN, 'action=send'),"POST","id='joinForm' accept-charset='UTF-8'"); ?>
	<?php if (DEFINE_JOIN_STATUS >= '1' && DEFINE_JOIN_STATUS <= '2') { ?>
        <div id="joinNoticeContent" class="content">
        <?php
        /**
         * require html_define for the join page
         */
          require($define_page);
        ?>
        </div>
    <?php } ?>

	<?php if ($messageStack->size('join') > 0) echo $messageStack->output('join'); ?>
    
    <fieldset id="userInfo_join">
    <div class="alert" id="requiredInfoLabel"><?php echo FORM_REQUIRED_INFORMATION; ?></div>
	<div id="legend"><?= HEADING_TITLE; ?></div>
    
    
    <div class="formInputItem">
        <label class="inputLabel" for="firstname"><?php echo ENTRY_FIRST_NAME; ?></label>
        <span class="alert"><?php echo ENTRY_REQUIRED_SYMBOL; ?></span>
		<?php echo zen_draw_input_field('firstname', $firstname, ' size="40" id="firstname"'); ?>
        <div class="note"><?php echo ENTRY_FIRST_NAME_NOTE; ?></div>
    </div>
    
    <div class="formInputItem">
        <label class="inputLabel" for="email"><?php echo ENTRY_EMAIL; ?></label>
        <span class="alert"><?php echo ENTRY_REQUIRED_SYMBOL; ?></span>
		<?php echo zen_draw_input_field('email', $email, ' size="40" id="email"'); ?>
        <div class="note"><?php echo ENTRY_EMAIL_NOTE; ?></div>
    </div>
    
    <div class="formInputItem">
        <label class="inputLabel" for="email_confirmation"><?php echo ENTRY_EMAIL_CONFIRMATION; ?></label>
        <span class="alert"><?php echo ENTRY_REQUIRED_SYMBOL; ?></span>
		<?php echo zen_draw_input_field('email_confirmation', $email_confirmation, ' size="40" id="email_confirmation"'); ?>
        <div class="note"><?php echo ENTRY_EMAIL_CONFIRMATION_NOTE; ?></div>
    </div>
    
    <div class="formInputItem">
        <label class="inputLabel" for="password"><?php echo ENTRY_PASSWORD; ?></label>
        <span class="alert"><?php echo ENTRY_REQUIRED_SYMBOL; ?></span>
		<?php echo zen_draw_password_field('password', $password, ' size="40" id="password"'); ?>
        <div class="note"><?php echo ENTRY_PASSWORD_NOTE; ?></div>
    </div>
        
    <div class="formInputItem">
        <label class="inputLabel" for="confirmation"><?php echo ENTRY_CONFIRMATION; ?></label>
        <span class="alert"><?php echo ENTRY_REQUIRED_SYMBOL; ?></span>
		<?php echo zen_draw_password_field('confirmation', $confirmation, ' size="40" id="confirmation"'); ?>
        <div class="note"><?php echo ENTRY_CONFIRMATION_NOTE; ?></div>
    </div>
    

    </fieldset>


	<div class="newsletterSignup">
    <!--<h1><?php echo NEWSLETTER_TITLE; ?></h1>-->
    
        
        <div class="formRadioBlock">
       		<?
        	$isChecked = '';							
			if(!isset($newsletter) || $newsletter == 1) $isChecked = "checked";
            ?>
            <input type="checkbox" name="newsletter" value="1" <?php echo $isChecked; ?>> 
            <?php echo MAIN_NEWSLETTER_NAME; ?>
            <!--<span class="note"><?php echo MAIN_NEWSLETTER_NOTE; ?></span>-->
        </div>
        <?
            $mailinglist_query = "SELECT * FROM mailinglists WHERE isActive = 1 ORDER BY list_order";
            $mailinglist = $db->Execute($mailinglist_query);
			$descriptionArray = array();
			array_push($descriptionArray,"Includes new products, special offers, and community announcements"); // description for the default mailing list
			
            while (!$mailinglist->EOF) {
				$isChecked = '';	
				if(isset($_POST["lists"])){								
					if(in_array($mailinglist->fields['list_id'],$_POST["lists"])) $isChecked = "checked";
				}
				?>    
                <div class="formRadioBlock">
                    <input type="checkbox" name="lists[]" value="<?= $mailinglist->fields['list_id']; ?>" <?php echo $isChecked; ?>> 
                    <?= $mailinglist->fields['list_name']; ?> 
                    <span class="subscriptionNote">(<?= $mailinglist->fields['list_description']; ?>)</span>
                </div>
                <?
                $mailinglist->MoveNext();
            }
        
		?>

        <input type="hidden"  name="email_format" value="HTML"/>
        
        <!--
        <?
        $HtmlChecked = 'checked';
        $TextChecked = '';				
		if(isset($email_format) && $email_format == "TEXT"){
			$HtmlChecked = '';
			$TextChecked = 'checked';				
		}
        ?>
        <div class="formRadioBlock">
            <input type="radio" name="email_format" value="HTML" <?php echo $HtmlChecked; ?>>  <?php echo FORMAT_HTML; ?> 
            <input type="radio" name="email_format" value="TEXT" <?php echo $TextChecked; ?>>  <?php echo FORMAT_TEXT; ?> 
            <span class="note"><?php echo FORMAT_NOTE; ?> </span>
        </div>
        -->
        
    </div> <!-- END newsletter signup --> 
        
    <br clear="both" />
    <div class="buttonRow forward"><input type="submit" name="<?php echo BUTTON_JOIN_ALT; ?>" value="<?php echo BUTTON_JOIN_ALT; ?>" class="fancybutton greenButton" /></div>
    <!-- ../images/btn_join_projectal.jpg -->
    
    
    
    </form>
    <BR /><BR /><BR />
<?php
}
?>
</div> <!-- END centerColumnPadding -->
<div class="shadow" id="bottom" ></div>
</div> <!-- END centerColumnContent -->
</div> <!-- END centerColumn -->