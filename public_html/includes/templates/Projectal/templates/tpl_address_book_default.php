<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=adress_book.<br />
 * Allows customer to manage entries in their address book
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_address_book_default.php 5369 2006-12-23 10:55:52Z drbyte $
 */
?>
<div class="centerColumn" id="addressBookDefault">

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
    
<h1 id="addressBookDefaultHeading"><?php echo HEADING_TITLE; ?></h1>
<?php if(!$hasPrimaryAddress && !$hasAddresses){ 
	if(count($addressArray) > 0){
		$addLink = zen_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $addressArray[0]["address_book_id"], 'SSL');	
	} else {
		$addLink = zen_href_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'SSL');;	
	}


	?> 
	We do not have any addresses on file for you. Click the button below to add one.
    <div class="buttonRow" style="margin-top:10px;margin-bottom:40px;">
	<a href="<?php echo $addLink; ?>" class="fancybutton greenButton"><?php echo BUTTON_ADD_ADDRESS_ALT; ?></a>
    </div>
    
<? } else { ?>
     
    <?php if ($messageStack->size('addressbook') > 0) echo $messageStack->output('addressbook'); ?> 
    <div class="address-block primary-address"> 
        <h2 id="addressBookDefaultPrimary"><?php echo PRIMARY_ADDRESS_TITLE; ?></h2>
        
        <? if( $hasPrimaryAddress ){ ?>
            <address class="back"><?php echo zen_address_label($_SESSION['customer_id'], $_SESSION['customer_default_address_id'], true, ' ', '<br />'); ?></address>
            <div class="instructions"><?php echo PRIMARY_ADDRESS_DESCRIPTION; ?></div>
        <? } else { ?>
        	To set a default address for billing or shipping when making an order, click "EDIT" next to an address below, then check the checkbox box next to "Set as Primary Address" and submit your update.
        <? } ?>
        
        <br class="clearBoth" />
    </div>
    
    
    
    
    <fieldset>
    <legend><?php echo ADDRESS_BOOK_TITLE; ?></legend>
    <?php
    /**
     * Used to loop thru and display address book entries
     */

	if(!$hasAddresses){
    	print "<BR><BR>We do not have a complete address on file for you. Please click the  button below to create one.";
		if(count($addressArray) > 0){
			$addLink = zen_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $addressArray[0]["address_book_id"], 'SSL');	
		} else {
			$addLink = zen_href_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'SSL');;	
		}
		?>
		<div class="buttonRow" style="margin-top:10px;margin-bottom:40px;">
		<a href="<?php echo $addLink; ?>" class="fancybutton greenButton"><?php echo BUTTON_ADD_ADDRESS_ALT; ?></a>
		</div>
        <?
	} else {
		foreach ($addressArray as $addresses) {
		  
		?>
        <div class="alert forward"><?php echo sprintf(TEXT_MAXIMUM_ENTRIES, MAX_ADDRESS_BOOK_ENTRIES); ?></div>
        <br class="clearBoth" />
    
        <div class="address-block">
            <h3 class="addressBookDefaultName"><?php echo zen_output_string_protected($addresses['firstname'] . ' ' . $addresses['lastname']); ?><?php if ($addresses['address_book_id'] == $_SESSION['customer_default_address_id']) echo '&nbsp;' . PRIMARY_ADDRESS ; ?></h3>
             <div class="buttonRow forward"><?php echo '<a href="' . zen_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $addresses['address_book_id'], 'SSL') . '" class="fancybutton blueButton small">' . BUTTON_EDIT_SMALL_ALT . '</a> <a href="' . zen_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $addresses['address_book_id'], 'SSL') . '" class="fancybutton greenButton small">' . BUTTON_DELETE_SMALL_ALT . '</a>'; ?></div>
			
            <address><?php echo zen_address_format($addresses['format_id'], $addresses['address'], true, ' ', '<br />'); ?></address>
            
           <br class="clearBoth"/>
        </div>
        
		<?php
		}
	}
    ?>
    </fieldset>
    
    
    <?php
      if (zen_count_customer_address_book_entries() < MAX_ADDRESS_BOOK_ENTRIES) {
    ?>
       <div class="buttonRow forward"><?php echo '<a href="' . zen_href_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'SSL') . '" class="fancybutton greenButton small">' . BUTTON_ADD_ADDRESS_ALT . '</a>'; ?></div>
    <?php
      }
    ?>
    
	<div class="buttonRow back"><?php echo '<a href="' . zen_href_link(FILENAME_ACCOUNT, '', 'SSL') . '" class="fancybutton greenButton small">' . BUTTON_BACK_ALT . '</a>'; ?></div>
   
    
<? } // END  if(!$hasPrimaryAddress && !$hasAddresses){?>
 
</div> <!-- END centerColumnPadding -->
<div class="shadow" id="bottom" ></div>
</div> <!-- END centerColumnContent -->
</div> <!-- END centerColumn -->
