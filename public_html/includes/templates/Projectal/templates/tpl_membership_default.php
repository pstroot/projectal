<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=membership.<br />
 *
 * @package templateSystem
 * @copyright Copyright 2003-2009 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_membership_default.php 16307 2010-05-21 21:50:06Z wilt $
 */
?>
<script>
activateNav('nav-participate');
activateNav('nav-membership');
</script>

<div class="centerColumn" id="membershipDefault">


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
	<div id="thankYouContent">	
	   <h1><?php echo HEADING_TITLE_SUCCESS; ?></h1>
	   <div class='bubbleArrow'></div>
	   <div class="content" id="thankYouText" ><?php echo TEXT_SUCCESS; ?></div>
	</div>
	
	<?php
} else {
	?>
    
    
	
    
    
    <?php if($new_participant_added || $_REQUEST["action"] == "success"){	?>
    	<h3><?= THANK_YOU_MESSAGE; ?></h3>
    
    <? } else if($isParticipant){ ?>
        <div id="thankYouContent">	
           <h1>HEY THERE!</h1>
           <div class='bubbleArrow'></div>
           <div class="content" id="thankYouText" ><?php echo ALREADY_A_PARTICIPANT_MESSAGE; ?></div>
        </div>
    <? } else { ?>
    	<!-- "MEMBERSHIP" headline -->
        <h1 id="mainTitle"><?= HEADING_TITLE; ?></h1>
        
        <!-- First grey bubble of text -->
        <?php if (DEFINE_MEMBERSHIP_STATUS >= '1' and DEFINE_MEMBERSHIP_STATUS <= '2') { ?>
            <div class="greyBubbleArrow" id="first"></div>
            <div class="greyBubble" id="first" >
            <?php require($define_page); ?>
            </div>
        <?php } ?>
    
    	<!-- Second grey bubble of text -->
        <div class="greyBubble" id="second">
            <h3><?= SECONDARY_HEADLINE; ?></h3>
            <?= SECONDARY_HEADLINE_2; ?>
        </div>
        <div class="greyBubbleArrow" id="second"></div>

        <!-- BIRTHDAY DATE PICKER JAVASCRIPT-->
        <script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery/jquery-1.4.2.js"></script>
        <script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.ui/1.8.5/jquery-ui.js"></script>
        <link type="text/css" rel="Stylesheet" href="http://ajax.microsoft.com/ajax/jquery.ui/1.8.5/themes/redmond/jquery-ui.css" />
            <script type="text/javascript">
            $(function() {
                $( "#birthday" ).datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeYear: true,
                    yearRange: '-100:+0',
                    defaultDate: '-25y'
                });
            });
        </script>
        
        <?php 	
        echo zen_draw_form('membershipForm', zen_href_link(FILENAME_MEMBERSHIP, 'action=send'),"POST","id='membershipForm' accept-charset='UTF-8'"); 
        
        if ($_SESSION['customer_id']) { // IF THE USER IS LOGGED IN
            print '<input type="hidden" name="loggedInUserID" value="'.$_SESSION['customer_id'].'">';
            print '<input type="hidden" name="loggedInAddressID" value="'.$_SESSION['customer_default_address_id'].'">';
        } 
        if ($messageStack->size('membership') > 0) echo $messageStack->output('membership'); ?>
        
        <fieldset id="userInfo_participate">
        <div class="alert" id="requiredInfoLabel"><?php echo FORM_REQUIRED_INFORMATION; ?></div>
        <div id="legend">Personal Information</div>
        
            
        <div class="formInputItem">
            <label class="inputLabel" for="firstname"><?php echo ENTRY_FIRST_NAME; ?></label>
            <span class="alert"><?php echo ENTRY_REQUIRED_SYMBOL; ?></span>
            <?php echo zen_draw_input_field('firstname', $firstname, ' size="40" id="firstname"'); ?>
            <div class="note"><?php echo ENTRY_FIRST_NAME_NOTE; ?></div>
        </div>
        
        <div class="formInputItem">
            <label class="inputLabel" for="lastname"><?php echo ENTRY_LAST_NAME; ?></label>
            <span class="alert"><?php echo ENTRY_REQUIRED_SYMBOL; ?></span>
            <?php echo zen_draw_input_field('lastname', $lastname, ' size="40" id="lastname"'); ?>
            <div class="note"><?php echo ENTRY_LAST_NAME_NOTE; ?></div>
        </div>
        
        <div class="formInputItem">
            <label class="inputLabel" for="profession"><?php echo ENTRY_PROFESSION; ?></label>
            <?php echo zen_draw_input_field('profession', ($profession), ' size="40" id="profession"'); ?>
            <div class="note"><?php echo ENTRY_PROFESSION_NOTE; ?></div>
        </div>
        
        <div class="formInputItem">
            <label class="inputLabel" for="birthday"><?php echo ENTRY_BIRTHDAY; ?></label>
            <span class="alert"><?php echo ENTRY_REQUIRED_SYMBOL; ?></span>
            <?php echo zen_draw_input_field('birthday', $birthday, ' id="birthday"'); ?>
            <div class="note"><?php echo ENTRY_BIRTHDAY_NOTE; ?></div>
        </div>
        <!--
        <div class="formInputItem">
            <label class="inputLabel" for="city"><?php echo ENTRY_CITY; ?></label>
            <span class="alert"><?php echo ENTRY_REQUIRED_SYMBOL; ?></span>
            <?php echo zen_draw_input_field('city', $city, ' id="city"'); ?>
            <div class="note"><?php echo ENTRY_CITY_NOTE; ?></div>
        </div>
        
        <div class="formInputItem">
            <label class="inputLabel" for="state"><?php echo ENTRY_STATE; ?></label>
            <span class="alert"><?php echo ENTRY_REQUIRED_SYMBOL; ?></span>
            <?php echo zen_draw_input_field('state', $state, ' id="state"'); ?>
            <div class="note"><?php echo ENTRY_STATE_NOTE; ?></div>
        </div>
        
        <div class="formInputItem">
            <label class="inputLabel" for="zip"><?php echo ENTRY_ZIP; ?></label>
            <span class="alert"><?php echo ENTRY_REQUIRED_SYMBOL; ?></span>
            <?php echo zen_draw_input_field('zip', $zip, '  id="zip"'); ?>
            <div class="note"><?php echo ENTRY_ZIP_NOTE; ?></div>
        </div>
       
        <div class="formInputItem">
            <label class="inputLabel" for="phone"><?php echo ENTRY_PHONE; ?></label>
            <?php echo zen_draw_input_field('phone', ($phone), ' id="phone"'); ?>
            <div class="note"><?php echo ENTRY_PHONE_NOTE; ?></div>
        </div>
        -->
        
        
        
        
        </fieldset>
        
        
        
        
        <fieldset id="loginInfo_participate">
        
        <div class="alert" id="requiredInfoLabel"><?php echo FORM_REQUIRED_INFORMATION; ?></div>
        <div id="legend">Account Information</div>
        
        <?php if (isset($_SESSION['customer_id'])){ ?>
        
        <div class="formInputItem">
            <label class="inputLabel" for="email"><?php echo ENTRY_EMAIL; ?></label>
            <span class="alert"><?php echo ENTRY_REQUIRED_SYMBOL; ?></span>
            <?php echo zen_draw_input_field('email', trim($email), ' id="email"'); ?>
            <div class="note"><?php echo ENTRY_EMAIL_NOTE; ?></div>
        </div>
        
        <? } else { ?>
        
        <div class="formInputItem">
            <label class="inputLabel" for="password"><?php echo ENTRY_PASSWORD; ?></label>
            <span class="alert"><?php echo ENTRY_REQUIRED_SYMBOL; ?></span>
            <?php echo zen_draw_password_field('password', ($password), ' id="password"') . ''; ?>
            <div class="note"><?php echo ENTRY_PASSWORD_NOTE; ?></div>
        </div>
        
        <div class="formInputItem">
            <label class="inputLabel" for="confirmation"><?php echo ENTRY_CONFIRMATION; ?></label>
            <span class="alert"><?php echo ENTRY_REQUIRED_SYMBOL; ?></span>
            <?php echo zen_draw_password_field('confirmation', ($confirmation), ' id="confirmation"'); ?>
            <div class="note"><?php echo ENTRY_CONFIRMATION_NOTE; ?></div>
        </div>
        
        <div class="formInputItem">
            <label class="inputLabel" for="email"><?php echo ENTRY_EMAIL; ?></label>
            <span class="alert"><?php echo ENTRY_REQUIRED_SYMBOL; ?></span>
            <?php echo zen_draw_input_field('email', trim($email), ' id="email"'); ?>
            <div class="note"><?php echo ENTRY_EMAIL_NOTE; ?></div>
        </div>
        
        <div class="formInputItem">
            <label class="inputLabel" for="email_confirmation"><?php echo ENTRY_EMAIL_CONFIRMATION; ?></label>
            <span class="alert"><?php echo ENTRY_REQUIRED_SYMBOL; ?></span>
            <?php echo zen_draw_input_field('email_confirmation', trim($email_confirmation), ' id="email_confirmation"'); ?>
            <div class="note"><?php echo ENTRY_EMAIL_CONFIRMATION_NOTE; ?></div>
        </div>
        <? } ?>
    




<!-- **************************** BEGIN NEWSLETTERS *********************************** -->
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
            while (!$mailinglist->EOF) {	
				$isChecked = '';	
				if(isset($listArray)){								
					if(in_array($mailinglist->fields['list_id'],$listArray)) $isChecked = "checked";
				}
				?>    
                <div class="formRadioBlock">
                    <input type="checkbox" name="lists[]" value="<?= $mailinglist->fields['list_id']; ?>" <?php echo $isChecked; ?>> 
                    <?= $mailinglist->fields['list_name']; ?> 
                    <!--<span class="newsletterDescription">(<?= $mailinglist->fields['list_description']; ?>)</span>-->
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
        
<!-- **************************** END NEWSLETTERS *********************************** -->

    </fieldset>
    
    <div id="formBottom"></div>

   <!-- ../images/btn_join_projectal.jpg -->


    
    
       <input type="hidden"  name="action" value="send"/>
       <input type="submit"  name="submit" title="<?php echo BUTTON_SUBMIT_ALT; ?>" value="<?php echo BUTTON_SUBMIT_ALT; ?>" class="fancybutton greenButton"  style="float:right;"/>
    
    </form>
    <?php } // END if($isParticipant)	?>
<?php
}// END if the form has not been submitted
?>
</div> <!-- END centerColumnPadding -->
<div class="shadow" id="bottom" ></div>
</div> <!-- END centerColumnContent -->
</div> <!-- END centerColumn -->


