<?php
/**
 * Contact Us Page
 *
 * @package page
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: header_php.php 17608 2010-09-24 14:51:46Z drbyte $
 */
require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));

$error = false;
if (isset($_GET['action']) && ($_GET['action'] == 'send')) {

  $firstname = zen_db_prepare_input($_POST['firstname']);
  $email = zen_db_prepare_input($_POST['email']);
  $password = zen_db_prepare_input(strip_tags($_POST['password']));
  $confirmation = zen_db_prepare_input(strip_tags($_POST['confirmation']));
  $newsletter = zen_db_prepare_input(strip_tags($_POST['newsletter']));
  $email_format = zen_db_prepare_input(strip_tags($_POST['email_format']));

  if($newsletter == '') $newsletter = 0;

  $zc_validate_email = zen_validate_email($email);

  $error = false;
    if (empty($firstname)) {
      $messageStack->add('join', NAME_BLANK_ERROR);
  	  $error = true;
    }
	if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
	  $messageStack->add('join', NAME_TOO_SHORT_ERROR);
  	  $error = true;
	}
	if (strlen($email) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
	  $messageStack->add('join', EMAIL_TOO_SHORT_ERROR);
  	  $error = true;
	}
    if ($zc_validate_email == false) {
      $messageStack->add('join', INVALID_EMAIL_ERROR);
  	  $error = true;
    }
    if (empty($password)) {
      $messageStack->add('join', PASSWORD_BLANK_ERROR);
  	  $error = true;
    } else if (empty($confirmation)) {
      $messageStack->add('join', CONFIRMATION_BLANK_ERROR);
  	  $error = true;
    } else if ($password != $confirmation) {
      $messageStack->add('join', PASSWORDS_DONT_MATCH_ERROR);
  	  $error = true;
    }	

	// CHECK TO SEE IF THE EMAIL ADDRESS ALREADY EXISTS.
	if (!empty($email)) {
		$check_email_query = "select count(*) as total
									from " . TABLE_CUSTOMERS . "
									where customers_email_address = '" . $email . "'
									and COWOA_account != 1";
		$check_email = $db->Execute($check_email_query);
		if ($check_email->fields['total'] > 0) {
		  $messageStack->add('join', EMAIL_EXISTS_ERROR);
		  $error = true;
		}
	}

if (!$error) {
	//*************************************************************************************************************
	//************************************ PARSE THE FORM DATA ****************************************************
	//*************************************************************************************************************
	
	// INSERT THE DATA INTO zen_customers TABLE
	$sql = "INSERT INTO " . TABLE_CUSTOMERS . " 
									(customers_firstname, customers_password, customers_email_address,customers_newsletter,customers_email_format)
									VALUES (
									'" . $firstname . "', 
									'" . zen_encrypt_password($password) . "',
                                    '" . $email . "',
									" . $newsletter . ",
									'" . $email_format . "'
									) "; 
									



	if($create_customer = $db->Execute($sql)){
		$_SESSION['customer_id'] = $db->Insert_ID();
	} else {
		  $messageStack->add('join', 'There was an error saving to the customer table<BR><BR>$create_customer_query');
	}
								
	if(!$error){
		// INSERT THE DATA INTO zen_customers_info TABLE
		$sql = "INSERT INTO " . TABLE_CUSTOMERS_INFO . "
								(customers_info_id, customers_info_number_of_logons,customers_info_date_account_created)
								   VALUES 
								(" . (int)$_SESSION['customer_id'] . ", 0, now())";
		if(!$db->Execute($sql)){	
			  $messageStack->add('join', 'There was an error saving to the customer info table<BR><BR>$sql');
			  $error = true;
		}
	}
	
	if(!$error){
		$country_id = 223;
		$zone_id = 32;
		// INSERT A DEFAULT ADDRESS FOR THIS USER
		$sql = "INSERT INTO " . TABLE_ADDRESS_BOOK . "
								(customers_id,entry_firstname,entry_country_id,entry_zone_id)
								   VALUES 
								('" . (int)$_SESSION['customer_id'] . "', '" . $firstname . "', $country_id, $zone_id)";
		if(!$db->Execute($sql)){
			  $customers_default_address_id = 	$db->Insert_ID();
			  $messageStack->add('join', 'There was an error saving to the address book<BR><BR>$sql');
			  $error = true;
		}
	}
	
	if(!$error){
		// INSERT THE DEFAULT ADDRESS ID INTO THE CUSTOMERS TABLE
		$sql = "UPDATE " . TABLE_CUSTOMERS . "
					  SET customers_default_address_id = '" . $customers_default_address_id . "'
					  WHERE customers_id = '" . (int)$_SESSION['customer_id'] . "'";
		if(!$db->Execute($sql)){	
			  $messageStack->add('join', 'There was an error adding the default address to the customer info<BR><BR>$sql');
			  $error = true;
		}
	}
	
	if(!$error){
		if(isset($_POST["lists"])){
			foreach($_POST["lists"] as $listID){
				$sql = "INSERT INTO mailinglist_emails
								   (customer_id,list_id,firstname,date_added,email,status)
									   VALUES 
									(" . (int)$_SESSION['customer_id'] . ",
									$listID,
									'" . $firstname . "', 
									now(),
									'" . $email . "',
									'active')";
				if(!$db->Execute($sql)){	
					  $messageStack->add('join', 'There was an error adding the customer to the mailing list with ID of '.$listID.'<BR><BR>$sql');
					  $error = true;
				}
			}
		}
	}
	
	// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
	
	//*************************************************************************************************************
	//************************************ END FORM PARSING *******************************************************
	//*************************************************************************************************************
	
	
	
	
	//*************************************************************************************************************
	//************************************ LOG THE NEW CUSTOMER IN ************************************************
	//*************************************************************************************************************
	
	if ($session_started == false) {
	  zen_redirect(zen_href_link(FILENAME_COOKIE_USAGE));
	}
	
	if (SESSION_RECREATE == 'True') {
          zen_session_recreate();
       }
	    $_SESSION['customer_id'] = (int)$_SESSION['customer_id'];
        $_SESSION['customer_default_address_id'] = $customers_default_address_id;
        $_SESSION['customers_authorization'] = 1;
        $_SESSION['customer_first_name'] = $firstname;
        $_SESSION['customer_last_name'] = '';
        $_SESSION['customer_country_id'] = $country_id;
        $_SESSION['customer_zone_id'] = $zone_id;

        $sql = "UPDATE " . TABLE_CUSTOMERS_INFO . "
              SET customers_info_date_of_last_logon = now(),
                  customers_info_number_of_logons = customers_info_number_of_logons+1
              WHERE customers_info_id = :customersID";

        $sql = $db->bindVars($sql, ':customersID',  $_SESSION['customer_id'], 'integer');
        $db->Execute($sql);		
		
	//*************************************************************************************************************
	//****************************************** END LOG IN *******************************************************
	//*************************************************************************************************************
	if(!$error){
	    zen_redirect(zen_href_link(FILENAME_JOIN, 'action=success'));
	}
  }
} // end action==send


// include template specific file name defines
$define_page = zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_JOIN, 'false');

$breadcrumb->add(NAVBAR_TITLE);
