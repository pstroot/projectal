<?php
/**
 * Header code file for the Account Newsletters page - To change customers Newsletter options
 *
 * @package page
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: header_php.php 3162 2006-03-11 01:39:16Z drbyte $
 */
if (!$_SESSION['customer_id']) {
  $_SESSION['navigation']->set_snapshot();
  zen_redirect(zen_href_link(FILENAME_LOGIN, '', 'SSL'));
}

require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));



$getEmail_query = "SELECT customers_email_address, customers_firstname, customers_lastname FROM " . TABLE_CUSTOMERS . " WHERE  customers_id = :customersID";
$getEmail_query = $db->bindVars($getEmail_query, ':customersID',$_SESSION['customer_id'], 'integer');
$emailData = $db->Execute($getEmail_query);
$customers_email = $emailData->fields['customers_email_address'];
$customers_firstname = $emailData->fields['customers_firstname'];
$customers_lastname = $emailData->fields['customers_lastname'];




$newsletterCustom_query = "SELECT m.list_id, m.list_name, m.list_description,
							(SELECT COUNT(e.id) FROM mailinglist_emails e WHERE e.email = :customersEmail AND e.list_id = m.list_id > 0) as isSignedUp
							FROM mailinglists m 
							WHERE m.isActive = 1 
							ORDER BY list_order";
$newsletterCustom_query = $db->bindVars($newsletterCustom_query, ':customersEmail',$customers_email, 'string');
$newsletterCustom = $db->Execute($newsletterCustom_query);



$newsletter_query = "SELECT customers_newsletter
                     FROM   " . TABLE_CUSTOMERS . "
                     WHERE  customers_id = :customersID";

$newsletter_query = $db->bindVars($newsletter_query, ':customersID',$_SESSION['customer_id'], 'integer');
$newsletter = $db->Execute($newsletter_query);




if (isset($_POST['action']) && ($_POST['action'] == 'process')) {

  if (isset($_POST['newsletter_general']) && is_numeric($_POST['newsletter_general'])) {
    $newsletter_general = zen_db_prepare_input($_POST['newsletter_general']);
  } else {
    $newsletter_general = '0';
  }

  if ($newsletter_general != $newsletter->fields['customers_newsletter']) {
    $newsletter_general = (($newsletter->fields['customers_newsletter'] == '1') ? '0' : '1');

    $sql = "UPDATE " . TABLE_CUSTOMERS . "
            SET    customers_newsletter = :customersNewsletter
            WHERE  customers_id = :customersID";

    $sql = $db->bindVars($sql, ':customersID',$_SESSION['customer_id'], 'integer');
    $sql = $db->bindVars($sql, ':customersNewsletter',$newsletter_general, 'integer');
    $db->Execute($sql);
  }

// insert into custom mailing lists...(added by pstroot on 3/9/2012)
 if (isset($_POST['custom_mailinglist_id']) && is_array($_POST['custom_mailinglist_id'])) {

	 foreach($_POST['custom_mailinglist_id'] as $mailinglist_id){
		
		 $mailinglist_id = zen_db_prepare_input($mailinglist_id);
		 
		 // this mailing list option was unchecked, so remove it
		 if(!isset($_POST['custom_mailinglist_' . $mailinglist_id])){
			 $sql = "DELETE FROM mailinglist_emails WHERE list_id = :mailinglistID AND customer_id = :customersID";
			 $sql = $db->bindVars($sql, ':customersID',$_SESSION['customer_id'], 'integer');
			 $sql = $db->bindVars($sql, ':mailinglistID',$mailinglist_id, 'integer');;
			 $db->Execute($sql);
		 } else {
			 // check to see if this email address is already part of this mailinglist
			 $sql = "SELECT id FROM mailinglist_emails WHERE list_id = :mailinglistID AND customer_id = :customersID";
			 $sql = $db->bindVars($sql, ':customersID',$_SESSION['customer_id'], 'integer');
			 $sql = $db->bindVars($sql, ':mailinglistID',$mailinglist_id, 'integer');
			 $mailinglistResults = $db->Execute($sql);

			
			 // if it already exists, make sure the status is set to "active"
			 if($mailinglistResults->RecordCount() > 0){
				 $sql = "UPDATE mailinglist_emails
					SET    status = 'active'
					WHERE list_id = :mailinglistID AND customer_id = :customersID";
				 $sql = $db->bindVars($sql, ':customersID',$_SESSION['customer_id'], 'integer');
				 $sql = $db->bindVars($sql, ':mailinglistID',$mailinglist_id, 'integer');;
				 $db->Execute($sql);
	
			 } 
			 
			 // if it does not exist, then get the email from the users account and add it to the "mailinglist_emails" table
			 else {
				 // first, check to see if this email address is already part of this mailinglist
				 $sql = "INSERT INTO mailinglist_emails (
														list_id, 
														email, 
														customer_id, 
														firstname, 
														lastname, 
														date_added,  
														status
													) VALUES (
														" . $mailinglist_id . ", 
														'" . $customers_email. "', 
														" . $_SESSION['customer_id'] . ", 
														'" . $customers_firstname. "', 
														'" . $customers_lastname. "', 
														NOW(), 
														'active'
													)";
													
				 $db->Execute($sql);
			 }
		  
		 }
	 }
 }



  $messageStack->add_session('account', SUCCESS_NEWSLETTER_UPDATED, 'success');

  zen_redirect(zen_href_link(FILENAME_ACCOUNT, '', 'SSL'));
}

$breadcrumb->add(NAVBAR_TITLE_1, zen_href_link(FILENAME_ACCOUNT, '', 'SSL'));
$breadcrumb->add(NAVBAR_TITLE_2);
?>
