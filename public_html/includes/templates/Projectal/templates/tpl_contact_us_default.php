<script>
activateNav('nav-info');
activateNav('nav-contact');
</script>

<div class="centerColumn" id="contactUsDefault">

<!-- bof  breadcrumb -->
<?php if (DEFINE_BREADCRUMB_STATUS == '1' || (DEFINE_BREADCRUMB_STATUS == '2' && !$this_is_home_page) ) { ?>
<div class="breadcrumbs">
	<?php 
	$breadcrumb->_trail= array_values($breadcrumb->_trail); 
	echo $breadcrumb->trail(BREAD_CRUMBS_SEPARATOR); ?><span class="activeCrumb"></span>
</div>
<?php } ?>
<!-- eof breadcrumb -->


<div class="centerColumnContent whiteContent">
<div class="centerColumnPadding">


<?php echo zen_draw_form('contact_us', zen_href_link(FILENAME_CONTACT_US, 'action=send'),"POST","accept-charset='UTF-8'"); ?>

<?php if (CONTACT_US_STORE_NAME_ADDRESS== '1') { ?>
<address><?php echo nl2br(STORE_NAME_ADDRESS); ?></address>
<?php } ?>

<?php
  if (isset($_GET['action']) && ($_GET['action'] == 'success')) {
?>

<div class="mainContent success"><?php echo TEXT_SUCCESS; ?></div>

<div class="buttonRow"><?php echo zen_back_link() . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a>'; ?></div>

<?php
  } else {
?>

<?php if (DEFINE_CONTACT_US_STATUS >= '1' and DEFINE_CONTACT_US_STATUS <= '2') { ?>
<div id="contactUsNoticeContent" class="content">
<?php
/**
 * require html_define for the contact_us page
 */
  require($define_page);
?>
</div>
<?php } ?>

<?php if ($messageStack->size('contact') > 0) echo $messageStack->output('contact'); ?>

<fieldset id="contactUsForm">

<?
// added by pstroot
$page_title = HEADING_TITLE;
$nameLabel = ENTRY_NAME;
$emailLabel = ENTRY_EMAIL;
$messageLabel = ENTRY_ENQUIRY;



if(isset($_REQUEST["regarding"])){
	if($_REQUEST["regarding"] == "suggest_a_tee"){
		$page_title = "Suggest a Tee";
		$messageLabel = "Suggest a Tee:";	
	} else if($_REQUEST["regarding"] == "bug_report"){
		$page_title = "Report a bug with the website";
		$messageLabel = "Please describe the bug, including where you found it and steps to recreate it:";
	} else if($_REQUEST["regarding"] == "charity") 	{	
		$page_title = "Suggest a Charity";
		$messageLabel = "Please tell us about the charity:";	
	}
	print '<input type="hidden" name="regarding" value="' .  @$_REQUEST["regarding"] . '">';
}
// end added by pstroot
?>
    
<h1><?php echo $page_title; ?></h1>

<div class="alert forward"><?php echo FORM_REQUIRED_INFORMATION; ?></div>

<br class="clearBoth" />

<?php
// show dropdown if set
if (CONTACT_US_LIST !=''){
	?>
	<label class="inputLabel" for="send-to"><?php echo SEND_TO_TEXT; ?></label>
	<?php echo zen_draw_pull_down_menu('send_to',  $send_to_array, 0, 'id="send-to"') . '<span class="alert">' . ENTRY_REQUIRED_SYMBOL . '</span>'; ?>
	<br class="clearBoth" />
	<?php
}


?>

<label class="inputLabel" for="contactname"><?php echo $nameLabel; ?></label>
<?php echo zen_draw_input_field('contactname', $name, ' size="40" id="contactname"'); ?>
<?php echo '<span class="alert">' . ENTRY_REQUIRED_SYMBOL . '</span>'; ?>
<div class="clearBoth"  style="margin-top:10px;" ></div>

<label class="inputLabel" for="email-address"><?php echo $emailLabel; ?></label>
<?php echo zen_draw_input_field('email', ($email_address), ' size="40" id="email-address"'); ?>
<?php echo '<span class="alert">' . ENTRY_REQUIRED_SYMBOL . '</span>'; ?>
<div class="clearBoth"  style="margin-top:10px;" ></div>


<label class="inputLabel" for="enquiry"><?php echo $messageLabel; ?></label>
<?php echo zen_draw_textarea_field('enquiry', '30', '7', $enquiry, 'id="enquiry"'); ?>
<?php echo '<span class="alert">' . ENTRY_REQUIRED_SYMBOL . '</span>'; ?>
<div class="clearBoth"  style="margin-top:10px;" ></div>



</fieldset>

<div class="buttonRow forward"><?php echo zen_image_submit(BUTTON_IMAGE_SEND, BUTTON_SEND_ALT); ?></div>
<!--<div class="buttonRow back"><?php echo zen_back_link() . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a>'; ?></div>-->
<?php
  }
?>
</form>
</div> <!-- END centerColumnPadding -->
<div class="shadow" id="bottom" ></div>
</div> <!-- END centerColumnContent -->
</div> <!-- END centerColumn -->