<script>
activateNav('nav-info');
activateNav('nav-customerservice');
</script>

<div class="centerColumn" id="customerService">

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

<?php 
echo zen_draw_form('customer_service', zen_href_link(FILENAME_CUSTOMER_SERVICE, 'action=send'),"POST","accept-charset='UTF-8'"); 

if (isset($_GET['action']) && ($_GET['action'] == 'success')) {
	?>	
	<div class="mainContent success"><?php echo TEXT_SUCCESS; ?></div>	
	<!-- <div class="buttonRow"><?php echo zen_back_link() . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a>'; ?></div> -->
	<?php
} else {

	if ($messageStack->size('customer_service') > 0) echo $messageStack->output('customer_service'); ?>

    <fieldset id="customerServiceForm">
        
    <h1><?php echo HEADING_TITLE; ?></h1>
        
    <?php 
    if (DEFINE_CUSTOMER_SERVICE_STATUS >= '1' and DEFINE_CUSTOMER_SERVICE_STATUS <= '2') { ?>
        <div id="customerServiceContent" class="content">
        <?php require($define_page); ?>
        </div>
        <?php 
    } 
    ?>
    <div class="alert forward"><?php echo FORM_REQUIRED_INFORMATION; ?></div>
    
    <br class="clearBoth" />
    
    <label class="inputLabel" for="contactname"><?php echo ENTRY_NAME; ?></label>
    <?php echo zen_draw_input_field('contactname', $name, ' size="40" id="contactname"'); ?>
    <?php echo '<span class="alert">' . ENTRY_REQUIRED_SYMBOL . '</span>'; ?>
    <div class="clearBoth"  style="margin-top:10px;" ></div>
    
    
    <label class="inputLabel" for="email-address"><?php echo ENTRY_EMAIL; ?></label>
    <?php echo zen_draw_input_field('email', ($email_address), ' size="40" id="email-address"'); ?>
    <?php echo '<span class="alert">' . ENTRY_REQUIRED_SYMBOL . '</span>'; ?>
    <div class="clearBoth"  style="margin-top:10px;" ></div>
    
    
    <label class="inputLabel" for="enquiry"><?php echo ENTRY_ENQUIRY; ?></label>
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