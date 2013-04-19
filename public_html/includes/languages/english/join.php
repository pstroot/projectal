<?php
/**
 * @package languageDefines
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: contact_us.php 6202 2007-04-12 22:56:10Z drbyte $
 */

define('HEADING_TITLE', 'Join ProjectAl');
define('NEWSLETTER_TITLE', 'Newsletter & Email Alerts');

define('NAVBAR_TITLE', 'Join');
define('HEADING_TITLE_SUCCESS', 'Thank You!');
define('TEXT_SUCCESS', 'You have successfully joined ProjectAl.');

define('ENTRY_FIRST_NAME', 'First Name:');
define('ENTRY_EMAIL', 'Email Address:');
define('ENTRY_EMAIL_CONFIRMATION', 'Re-type Email:');
define('ENTRY_PASSWORD', 'Password:');
define('ENTRY_CONFIRMATION', 'Re-type Password:');

define('ENTRY_FIRST_NAME_NOTE', '');
define('ENTRY_EMAIL_NOTE', '(We will not spam or sell your email address)');
define('ENTRY_EMAIL_CONFIRMATION_NOTE', '');
define('ENTRY_PASSWORD_NOTE', '(At least 7 characters long)');
define('ENTRY_CONFIRMATION_NOTE', '');

define('MAIN_NEWSLETTER_NAME','Subscribe to our newsletter');
define('MAIN_NEWSLETTER_NOTE','(Includes new products, special offers, and community announcements)');
define('FORMAT_HTML','HTML');
define('FORMAT_TEXT','TEXT');
define('FORMAT_NOTE','Please select your preferred email format');


define('NAME_BLANK_ERROR','Please enter your first name.');
define('INVALID_EMAIL_ERROR','Your email does not appear to be valid. Please check your spelling and try again.');
define('PASSWORD_BLANK_ERROR','You must include a password.');
define('CONFIRMATION_BLANK_ERROR','Please retype your password.');
define('PASSWORDS_DONT_MATCH_ERROR','Your passwords do not match.');
define('EMAILS_DONT_MATCH_ERROR','Your emails do not match.');
define('EMAIL_TOO_SHORT_ERROR','Your email must be at least '.ENTRY_EMAIL_ADDRESS_MIN_LENGTH.' characters.');
define('NAME_TOO_SHORT_ERROR','Your name must be at least '.ENTRY_FIRST_NAME_MIN_LENGTH.' characters.');
define('EMAIL_EXISTS_ERROR','This email address is already assigned to a user.');
?>