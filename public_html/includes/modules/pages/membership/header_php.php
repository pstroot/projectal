<?php
/**
 * Page 2
 *
 * @package page
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: header_php.php 3230 2006-03-20 23:21:29Z drbyte $
 */
 
// if the user is logged in - pre-populate some fields
if ($_SESSION['customer_id']) { // IF THE USER IS LOGGED IN
	
	// check to see if this person is already a participant.
	$check_participant_query = "select count(*) as total
										from participants
										where customer_id = " . (int)$_SESSION['customer_id'] . ";";
	$check_participant = $db->Execute($check_participant_query);
	if ($check_participant->fields['total'] > 0) {
		$isParticipant = true;
	}
	
	
	//$defaultAddressID = $_SESSION['customer_default_address_id'];
	$account_query = "SELECT customers_gender, customers_firstname, customers_lastname,
							 customers_dob, customers_email_address, customers_telephone,
							 customers_fax, customers_email_format, customers_newsletter
					  FROM   " . TABLE_CUSTOMERS . "
					  WHERE  customers_id = :customersID";
	
	$account_query = $db->bindVars($account_query, ':customersID', $_SESSION['customer_id'], 'integer');
	$account = $db->Execute($account_query);
	
	/*
	if (ACCOUNT_GENDER == 'true') {
	  if (isset($gender)) {
		$male = ($gender == 'm') ? true : false;
	  } else {
		$male = ($account->fields['customers_gender'] == 'm') ? true : false;
	  }
	  $female = !$male;
	}
	
	// if DOB field has database default setting, show blank:
	$dob = ($dob == '0001-01-01 00:00:00') ? '' : $dob;
	*/

	$firstname = $account->fields['customers_firstname'];
	$lastname = $account->fields['customers_lastname'];
    $email = $account->fields['customers_email_address'];
    $phone = $account->fields['customers_telephone'];
    $newsletter = $account->fields['customers_newsletter'];
    $email_format = $account->fields['customers_email_format'];
	
	$addresses_query = "SELECT address_book_id, entry_firstname as firstname, entry_lastname as lastname,
                           entry_company as company, entry_street_address as street_address,
                           entry_suburb as suburb, entry_city as city, entry_postcode as postcode,
                           entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id
                    FROM   " . TABLE_ADDRESS_BOOK . "
                    WHERE  customers_id = :customersID
                    ORDER BY firstname, lastname";

	$addresses_query = $db->bindVars($addresses_query, ':customersID', $_SESSION['customer_id'], 'integer');
	$addresses = $db->Execute($addresses_query);
	
    $city = $addresses->fields['city'];
    $state = $addresses->fields['state'];
    $zip = $addresses->fields['postcode'];
    //$profession = zen_db_prepare_input(strip_tags($_POST['profession']));
    //$lists = zen_db_prepare_input(strip_tags($_POST['lists']));
	
	$lists_query = "SELECT list_id, status  
                    FROM   mailinglist_emails
                    WHERE  customer_id = :customersID";
	$lists_query = $db->bindVars($lists_query, ':customersID', $_SESSION['customer_id'], 'integer');

	$lists = $db->Execute($lists_query);
	$listArray = array();
	while (!$lists->EOF) {
  		array_push($listArray,$lists->fields['list_id']);
 		$lists->MoveNext();
	}
}
	


require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));
$breadcrumb->add("Participate","index.php?main_page=participate");
//$breadcrumb->add(NAVBAR_TITLE);



$error = false;
if (isset($_GET['action']) && ($_GET['action'] == 'send')) {
	$error = false;
	if($isParticipant){	
		$messageStack->add('membership', "You are already a participant");
		//$error = true;
	}
		
	if (!$error) {
		$firstname = zen_db_prepare_input($_POST['firstname']);
		$lastname = zen_db_prepare_input($_POST['lastname']);
		$birthday = zen_db_prepare_input($_POST['birthday']);
		$city = zen_db_prepare_input($_POST['city']);
		$state = zen_db_prepare_input($_POST['state']);
		$zip = zen_db_prepare_input($_POST['zip']);
		$email = zen_db_prepare_input($_POST['email']);
		$email_confirmation = zen_db_prepare_input($_POST['email_confirmation']);
		$phone = zen_db_prepare_input($_POST['phone']);  
		$password = zen_db_prepare_input(strip_tags($_POST['password']));
		$confirmation = zen_db_prepare_input(strip_tags($_POST['confirmation']));
		$profession = zen_db_prepare_input(strip_tags($_POST['profession']));
		$newsletter = zen_db_prepare_input(strip_tags($_POST['newsletter']));
		$listArray = $_POST['lists'];
		$email_format = zen_db_prepare_input(strip_tags($_POST['email_format']));
	
		if($newsletter == '') $newsletter = 0;
	
		$zc_validate_email = zen_validate_email($email);
	
	  
		if (empty($firstname)) {
		  $messageStack->add('membership', FIRST_NAME_BLANK_ERROR);
		  $error = true;
		}else if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
		  $messageStack->add('membership', FIRST_NAME_TOO_SHORT_ERROR);
		  $error = true;
		}
		
		
		if (empty($lastname)) {
		  $messageStack->add('membership', LAST_NAME_BLANK_ERROR);
		  $error = true;
		}else if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
		  $messageStack->add('membership', LAST_NAME_TOO_SHORT_ERROR);
		  $error = true;
		}
		
		/*
		if (empty($birthday)) {
		  $messageStack->add('membership', AGE_BLANK_ERROR);
		  $error = true;
		} 
		*/
		
		if (!isset($_POST["loggedInUserID"])) { // if the user is not logged in, then we are  creating a new account, so validate the password and email
			if (empty($password)) {
			  $messageStack->add('membership', PASSWORD_BLANK_ERROR);
			  $error = true;
			} else if (strlen(trim($password)) < 7) {
			  $messageStack->add('membership', PASSWORD_TOO_SHORT_ERROR);
			  $error = true;
			  } else if (empty($confirmation)) {
			  $messageStack->add('membership', CONFIRMATION_BLANK_ERROR);
			  $error = true;
			} else if ($password != $confirmation) {
			  $messageStack->add('membership', PASSWORDS_DONT_MATCH_ERROR);
			  $error = true;
			}	
		
	
			
		
			if (strlen($email) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
			  $messageStack->add('membership', EMAIL_TOO_SHORT_ERROR);
			  $error = true;
			} else if (strlen(trim($email_confirmation)) <= 0) {
			  $messageStack->add('membership', CONFIRMATION_EMAIL_BLANK_ERROR);
			  $error = true;
			} else if (strtolower(trim($email)) != strtolower(trim($email_confirmation))) {
			  $messageStack->add('membership', CONFIRMATION_EMAIL_ERROR);
			  $error = true;
			} else if ($zc_validate_email == false) {
			  $messageStack->add('membership', INVALID_EMAIL_ERROR);
			  $error = true;
			}
			// CHECK TO SEE IF THE EMAIL ADDRESS ALREADY EXISTS.
			else if (!empty($email)) {
				$check_email_query = "select count(*) as total
												from " . TABLE_CUSTOMERS . "
												where customers_email_address = '" . $email . "'
												and COWOA_account != 1";
				$check_email = $db->Execute($check_email_query);
				if ($check_email->fields['total'] > 0) {
				  $messageStack->add('membership', EMAIL_EXISTS_ERROR);
				  $error = true;
				}
			}
		}
		
		/*
		if (empty($city)) {
		  $messageStack->add('membership', CITY_BLANK_ERROR);
		  $error = true;
		}
		
		if (empty($state)) {
		  $messageStack->add('membership', STATE_BLANK_ERROR);
		  $error = true;
		}
		
		if (empty($zip)) {
		  $messageStack->add('membership', ZIP_BLANK_ERROR);
		  $error = true;
		}
	
		if (empty($phone)) {
		  $messageStack->add('membership', PHONE_BLANK_ERROR);
		  $error = true;
		}
		*/
		

	} // END if(!$error)
	

if (!$error) {
	
	if (!isset($_POST["loggedInUserID"])) { 
		//*************************************************************************************************************
		//************************************ PARSE THE FORM DATA ... NEW ACCOUNT ****************************************************
		//*************************************************************************************************************
	
		// INSERT THE DATA INTO zen_customers TABLE
		$sql = "INSERT INTO " . TABLE_CUSTOMERS . " 
										(customers_firstname,customers_lastname,customers_dob,customers_telephone, customers_password, customers_email_address,customers_newsletter,customers_email_format)
										VALUES (
										'" . $firstname . "', 
										'" . $lastname . "', 
										'" . $birthday . "', 
										'" . $phone . "', 
										'" . zen_encrypt_password($password) . "',
										'" . $email . "',
										" . $newsletter . ",
										'" . $email_format . "'
										) "; 
		if ($verbose) print $sql . "<BR><BR>";
										
		if($create_customer = $db->Execute($sql)){
			$_SESSION['customer_id'] = $db->Insert_ID();
		} else {
			$messageStack->add('membership', 'There was an error saving to the customer table<BR><BR>$create_customer_query');
		}
									
		if(!$error){
			// INSERT THE DATA INTO zen_customers_info TABLE
			$sql = "INSERT INTO " . TABLE_CUSTOMERS_INFO . "
									(customers_info_id, customers_info_number_of_logons,customers_info_date_account_created)
									   VALUES 
									(" . (int)$_SESSION['customer_id'] . ", 0, now())";
			if ($verbose) print $sql . "<BR><BR>";
			if(!$db->Execute($sql)){	
				  $messageStack->add('membership', 'There was an error saving to the customer info table<BR><BR>$sql');
				  $error = true;
			}
		}
		
		if(!$error){
			// INSERT A DEFAULT ADDRESS FOR THIS USER
			$country_id = 223;
			$zone_id = 32;		
			$sql = "INSERT INTO " . TABLE_ADDRESS_BOOK . "
									(customers_id,entry_firstname,entry_lastname,entry_city, entry_state, entry_postcode, entry_country_id,entry_zone_id)
									   VALUES 
									('" . (int)$_SESSION['customer_id'] . "', '" . $firstname . "', '" . $lastname . "', '" . $city . "', '" . $state . "', '" . $zip . "', $country_id, $zone_id)";
			
			if ($verbose) print $sql . "<BR><BR>";
			if(!$db->Execute($sql)){
				  $messageStack->add('membership', 'There was an error saving to the address book<BR><BR>$sql');
				  $error = true;
			}
			$customers_default_address_id = $db->Insert_ID();
		}
		
		if(!$error){
			// INSERT THE DEFAULT ADDRESS ID INTO THE CUSTOMERS TABLE
			$sql = "UPDATE " . TABLE_CUSTOMERS . "
						  SET customers_default_address_id = '" . $customers_default_address_id . "'
						  WHERE customers_id = '" . (int)$_SESSION['customer_id'] . "'";
			if ($verbose) print $sql . "<BR><BR>";
			if(!$db->Execute($sql)){	
				  $messageStack->add('membership', 'There was an error adding the default address to the customer info<BR><BR>$sql');
				  $error = true;
			}
		}
		
	
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
		
	
	
	  
	} else { // if the user IS currently logged in
		//*************************************************************************************************************
		//************************************ PARSE THE FORM DATA ... EXISTING ACCOUNT ****************************************************
		//*************************************************************************************************************
	
		if(!$error){
			// UPDATE THE CUSTOMER TABLE 
			$sql = "UPDATE " . TABLE_CUSTOMERS . "
						  SET 
						  customers_firstname = '$firstname',
						  customers_lastname = '$lastname',
						  customers_email_address = '$email',
						  customers_newsletter = $newsletter,
						  customers_email_format = '$email_format',
						  customers_dob = '$birthday'					
						  WHERE customers_id = '" . (int)$_POST["loggedInUserID"] . "'";

			if(!$db->Execute($sql)){	
				  $messageStack->add('membership', 'There was an error updating the user information<BR><BR>$sql');
				  $error = true;
			}
		} 
		
		
		if(!$error){
			
			// UPDATE THE CUSTOMER TABLE 
			/*
			$sql = "UPDATE " . TABLE_ADDRESS_BOOK . "
						  SET 
						  entry_firstname = '$firstname',
						  entry_lastname = '$lastname',
						  entry_city = '$city',
						  entry_state = '$state',
						  entry_postcode = '$zip'					  
						  WHERE address_book_id = '" . (int)$_POST["loggedInAddressID"] . "'";
			if ($verbose) print $sql . "<BR><BR>";
			if(!$db->Execute($sql)){	
				  $messageStack->add('membership', 'There was an error updating the address<BR><BR>$sql');
				  $error = true;
			}
			*/
		}
		if(!$error){
			// REMOVE ANY EXISTING MAILING LIST SELECTIONS...WE'LL ADD THEM BACK IN THE NEXT STEP
			$sql = "DELETE FROM  mailinglist_emails WHERE customer_id = '" . (int)$_POST["loggedInUserID"] . "'";
			if ($verbose) print $sql . "<BR><BR>";
			if ($verbose) print $sql . "<BR><BR>";
			if(!$db->Execute($sql)){	
				  $messageStack->add('membership', 'There was an error removing existing mailinglist selections<BR><BR>$sql');
				  $error = true;
			}
		}
	}
} // END if (!$error)

// update or insert selected mailing lists
if(!$error){
	if(isset($_POST["lists"])){
		foreach($_POST["lists"] as $listID){
			$sql = "INSERT INTO mailinglist_emails
							   (customer_id,list_id,firstname,lastname,email,date_added,status)
								   VALUES 
								(" . (int)$_SESSION['customer_id'] . ",
								$listID,
								'" . $firstname . "', 
								'" . $lastname . "', 
								'" . $email . "', 
								now(),
								'active')";
			if ($verbose) print $sql . "<BR><BR>";
			if(!$db->Execute($sql)){	
				  $messageStack->add('membership', 'There was an error adding the customer to the mailing list with ID of '.$listID.'<BR><BR>$sql');
				  $error = true;
			}
		}
	}
}

if(!$error && !$isParticipant){
	$sql = "INSERT INTO participants
			(customer_id,profession,date_created)
			VALUES 
			(" . (int)$_SESSION['customer_id'] . ",
									'$profession', 
									now())";
	if ($verbose) print $sql . "<BR><BR>";
	if(!$db->Execute($sql)){	
		$messageStack->add('membership', 'There was an error adding the customer to the participants database<BR><BR>$sql');
		$error = true;
	} else {
		$new_participant_added = true;	
	}
	
}

	//*************************************************************************************************************
	//************************************ END FORM PARSING *******************************************************
	//*************************************************************************************************************
	
	
	if(!$error){
	    zen_redirect(zen_href_link(FILENAME_MEMBERSHIP, 'action=success'));
	}
	

} // end action==send


// include template specific file name defines
$define_page = zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_PARTICIPATE, 'false');
$breadcrumb->add(NAVBAR_TITLE);


?>