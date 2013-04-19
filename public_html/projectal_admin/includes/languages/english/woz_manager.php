<?php
/**
 * Do not delete this copyright link/text when you use free edition. 
 * When you want to delete it, please purchase a professional edition. 
 * Please understand that I spend time on development.
 * 
 * @package    WordPress On ZenCart
 * @author     HIRAOKA Tadahito (hira)
 * @copyright  Copyright 2008-2010 S-page
 * @copyright  Copyright 2003-2007 Zen Cart Development Team
 * @copyright  Portions Copyright 2003 osCommerce
 * @link       http://www.s-page.net/products/62.html
 * @license    http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 */

define('HEADING_TITLE', 'WOZ Manager');

define('TABLE_HEADING_LANGUAGE', 'Language');
define('TABLE_HEADING_DIRECTORY', 'WordPress Directory');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_INFO_HEADING_EDIT', 'Edit WordPress Path');
define('TEXT_INFO_HEADING_DELETE', 'Delete WordPress association');
define('TEXT_INFO_EDIT_INTRO', 'the following path is a COMPLETE path to your WordPress files.<br /> eg: ' . DIR_FS_CATALOG . 'blog/');
define('TEXT_INFO_DELETE_INTRO', 'Delete this association');
define('TEXT_INFO_WP_PATH', 'WordPress Path');
define('TEXT_INFO_LANGUAGE_NAME', 'Language Name');
define('TEXT_INFO_HEADING_NEW', 'Associate WordPress with language');
define('TEXT_INFO_INSERT_INTRO', 'Choose below to associate a wordpress with a language');
define('TEXT_DISPLAY_NUMBER_OF_WP', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> wordpress associations)');

define('TEXT_INFO_CHECK_BLOG_ADDRESS', 'Do you change Blog address (URL) of WordPress?&nbsp;<small>When you will change it later, you can change it from wp-admin > Setting > General > [Blog address (URL)]</small>');
define('TEXT_WOZ_MSGSTACK_UPDATE_BLOG_ADDRESS','Update Blog address (URL) Successfull');

define('TEXT_WARNING_REMOVE','Are you sure you want to remove this module?');
define('TEXT_WOZ_MSGSTACK_INSTALL_SUCCESS','Installation Successfull!');
define('TEXT_WOZ_MSGSTACK_REMOVE_SUCCESS','Uninstallation Successfull!');
define('TEXT_MSGSTACK_INFO_SUCCESS','successfully updated.');

define('TEXT_WOZ_CHECK_PATH_INFO','<p><b>WordPress root physical path</b><br />
        the following path is a COMPLETE path to your WordPress files.<br /> eg: ' . DIR_FS_CATALOG . 'blog/');
define('TEXT_WOZ_CHECK_PATH_SUCCESS','WordPress Path was found.');
define('TEXT_WOZ_CHECK_PATH_ERROR1','WordPress path was not found.');
define('TEXT_WOZ_CHECK_PATH_ERROR2','WordPress config(wp-config.php) was not found.');

define('TEXT_OPTION_WOZ_REMOVE_CONFIRM','Uninstallation of WOZ');

// for Ceon URI Mapping BOF
define('TEXT_OPTION_CEON_URI_MAPPING_SETTING', 'Ceon URI Mapping Setting');
define('TEXT_INFO_HEADING_CEON_URL_SETTING', 'Ceon URI Mapping Setting');
define('TEXT_INFO_CEON_URL_SETTING_INTRO', 'It is necessary to change the Blog address (URL).<br />(In WordPress Admin::General Settings)');
define('TEXT_INFO_CEON_URL_SETTING_NOTE', 'NOTE:<br />
please edit the following file.<br />
/includes/init_includes/init_ceon_uri_mapping.php<br />
Add the new lines shown, around approx line 115:
<hr />
$uri_to_match = preg_replace(\'/[^a-zA-Z0-9_\-\.\/%]/\', \'\', $request_uri);<br />
<br />
<font color="red">// for WordPress On ZenCart BOF<br />
$woz_uri_query = "<br />
	SELECT<br />
		um.language_id,<br />
		um.uri<br />
	FROM<br />
		" . TABLE_CEON_URI_MAPPINGS . " um<br />
	WHERE<br />
		um.main_page = \'" . FILENAME_WORDPRESS . "\'<br />
	ORDER BY<br />
		BIT_LENGTH(um.uri) DESC;";<br />
$woz_uri_result = $db->Execute($woz_uri_query);<br />
$woz_uri = \'\';<br />
while (!$woz_uri_result->EOF) {<br />
	$woz_uri = $woz_uri_result->fields[\'uri\'];<br />
	if(ereg("^$woz_uri", $uri_to_match)){<br />
		if(ereg("/comments/feed/", $uri_to_match)){<br />
			$_GET[\'feed\'] = \'comments-rss2\';<br />
		}else if(ereg("/feed/", $uri_to_match)){<br />
			$_GET[\'feed\'] = \'rss2\';<br />
		}<br />
		$uri_to_match = $woz_uri;<br />
		break;<br />
	}<br />
	$woz_uri_result->MoveNext();<br />
}<br />
// for WordPress On ZenCart EOF</font><br />
<br />
if (substr($uri_to_match, -1) == \'/\') {<br />
<hr />
');

define('TEXT_URI_MAPPING_WOZ_URI_AUTOGEN', 'Tick this box to have the URI auto-generated for WOZ.');
define('TEXT_URI_MAPPING_WOZ_URIS_AUTOGEN', 'Tick this box to have the URIs auto-generated for WOZ.');
// for Ceon URI Mapping EOF

?>
