<?php
/**
 * Customer Service Page
 */
require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));
$name = "";
$error = false;
if (isset($_GET['action']) && ($_GET['action'] == 'send')) {
  $name = zen_db_prepare_input($_POST['contactname']);
  
  $email_address = zen_db_prepare_input($_POST['email']);
  $enquiry = zen_db_prepare_input(strip_tags($_POST['enquiry']));

  $zc_validate_email = zen_validate_email($email_address);

  if ($zc_validate_email and !empty($enquiry) and !empty($name)) {
    // auto complete when logged in
    if($_SESSION['customer_id']) {
      $sql = "SELECT customers_id, customers_firstname, customers_lastname, customers_password, customers_email_address, customers_default_address_id
              FROM " . TABLE_CUSTOMERS . "
              WHERE customers_id = :customersID";

      $sql = $db->bindVars($sql, ':customersID', $_SESSION['customer_id'], 'integer');
      $check_customer = $db->Execute($sql);
      $customer_email= $check_customer->fields['customers_email_address'];
      $customer_name= $check_customer->fields['customers_firstname'] . ' ' . $check_customer->fields['customers_lastname'];
    } else {
      $customer_email = NOT_LOGGED_IN_TEXT;
      $customer_name = NOT_LOGGED_IN_TEXT;
    }

	$send_to_email = trim(SEND_TO_EMAIL);
	$send_to_name =  trim(STORE_NAME);
	$email_subject = trim(EMAIL_SUBJECT);
    

    // Prepare extra-info details
    $extra_info = email_collect_extra_info($name, $email_address, $customer_name, $customer_email);
    // Prepare Text-only portion of message
    $text_message = OFFICE_FROM . "\t" . $name . "\n" .
    OFFICE_EMAIL . "\t" . $email_address . "\n\n" .
    '------------------------------------------------------' . "\n\n" .
    strip_tags($_POST['enquiry']) .  "\n\n" .
    '------------------------------------------------------' . "\n\n" .
    $extra_info['TEXT'];
    // Prepare HTML-portion of message
    $html_msg['EMAIL_MESSAGE_HTML'] = strip_tags($_POST['enquiry']);
    $html_msg['CUSTOMER_SERVICE_OFFICE_FROM'] = OFFICE_FROM . ' ' . $name . '<br />' . OFFICE_EMAIL . '(' . $email_address . ')';
    $html_msg['EXTRA_INFO'] = $extra_info['HTML'];
    // Send message
	
	
		
	
	
    zen_mail($send_to_name, $send_to_email, $email_subject, $text_message, $name, $email_address, $html_msg,'customer_service');

    zen_redirect(zen_href_link(FILENAME_CUSTOMER_SERVICE, 'action=success'));
  } else {
    $error = true;
    if (empty($name)) {
      $messageStack->add('customer_service', ENTRY_EMAIL_NAME_CHECK_ERROR);
    }
    if ($zc_validate_email == false) {
      $messageStack->add('customer_service', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    }
    if (empty($enquiry)) {
      $messageStack->add('customer_service', ENTRY_EMAIL_CONTENT_CHECK_ERROR);
    }
  }
} // end action==send


// default email and name if customer is logged in
if($_SESSION['customer_id']) {
  $sql = "SELECT customers_id, customers_firstname, customers_lastname, customers_password, customers_email_address, customers_default_address_id
          FROM " . TABLE_CUSTOMERS . "
          WHERE customers_id = :customersID";

  $sql = $db->bindVars($sql, ':customersID', $_SESSION['customer_id'], 'integer');
  $check_customer = $db->Execute($sql);
  $email_address = $check_customer->fields['customers_email_address'];
  $name= $check_customer->fields['customers_firstname'] . ' ' . $check_customer->fields['customers_lastname'];
}


// include template specific file name defines
$define_page = zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_CUSTOMER_SERVICE, 'false');

$breadcrumb->add("Info","index.php?main_page=info");
$breadcrumb->add(NAVBAR_TITLE);