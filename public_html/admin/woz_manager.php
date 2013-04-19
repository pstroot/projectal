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

  require('includes/application_top.php');
                          
  function install_woz($path = '') {
    global $db;
    $db->Execute("INSERT INTO " . TABLE_CONFIGURATION_GROUP . " VALUES ('', '" . BOX_CONFIG_WOZ . "', 'WOZ Settings', '1', '1')");
    $group_id = mysql_insert_id();
    $db->Execute("UPDATE " . TABLE_CONFIGURATION_GROUP . " SET sort_order = " . $group_id . " WHERE configuration_group_id = " . $group_id);
    $db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " VALUES ('', '" . TEXT_WOZ_INSTALL_STATUS_TITLE . "', 'WOZ_CONFIG_STATUS', 'true', '" . TEXT_WOZ_INSTALL_STATUS_INFO . "', " . $group_id . ", '0', NULL, now(), NULL, 'zen_cfg_select_option(array(\"true\", \"false\"),'),('', '" . WOZ_CONFIG_INFO . "', 'WOZ_CONFIG_INFO', '%3C%21--Do+not+delete+this+copyright+link%2Ftext%21+When+you+want+to+delete+it%2C+please+purchase+a+professional+edition.+Please+understand+that+I+spend+time+on+development.--%3E%3Cdiv+id%3D%22wozz_footer%22+style%3D%22clear%3A+both%3B+text-align%3A+center%3B+font-size%3A+small%3B+display%3A+none%3B+visibility%3A+visible%3B%22%3E%3Ca+href%3D%22http%3A%2F%2Fwww.s-page.net%2Fproducts%2F62.html%22%3EWordPress+On+ZenCart%3C%2Fa%3E+%28C%29+%3Ca+href%3D%22http%3A%2F%2Fwww.s-page.net%2F%22%3ES-page%3C%2Fa%3E%3C%2Fdiv%3E', '', '6', '5', NULL, now(), NULL, ''),('', '" . TEXT_WOZ_CONFIG_ORDER_ID_TITLE . "', 'WOZ_CONFIG_ORDER_ID', '0', '" . TEXT_WOZ_CONFIG_ORDER_ID_INFO . "', " . $group_id . ", '10', NULL, now(), NULL, '')");
     $db->Execute("CREATE TABLE IF NOT EXISTS " . TABLE_WOZ . "(
                    woz_id int(11) NOT NULL auto_increment,
                    woz_dir varchar(128) NOT NULL DEFAULT '',
                    woz_language varchar(64) NOT NULL DEFAULT '0',
                    PRIMARY KEY  (woz_id),
                    KEY `idx_woz_lang_zen` (`woz_language`)
                  ) TYPE=MyISAM AUTO_INCREMENT=1;");
     $db->Execute("INSERT INTO " . TABLE_WOZ . " VALUES ('','" . $path . "', '0')");;

  }

  function remove_woz() {
    global $db;
    $al_keys = array('WOZ_CONFIG_STATUS','WOZ_CONFIG_ORDER_ID','WOZ_CONFIG_INFO');
    $sql = "delete from " . TABLE_CONFIGURATION_GROUP . "
        WHERE configuration_group_title = '" . BOX_CONFIG_WOZ . "'";
        
    $db->Execute($sql);

    foreach ($al_keys as $al_key) {
      @$db->Execute("delete from " . TABLE_CONFIGURATION . "
               where configuration_key = '" . $al_key . "'");
    }
    
    $db->Execute("DROP TABLE " . TABLE_WOZ);
  }
    
  $languages = zen_get_languages();
  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  $new_path = (isset($_POST['path']) ? zen_db_input(zen_db_prepare_input($_POST['path'])) : DIR_FS_CATALOG);
  $default_path = (isset($_GET['default_path']) ? zen_db_input(zen_db_prepare_input($_GET['default_path'])) : DIR_FS_CATALOG);
  
  function check_path($path = ''){
    global $messageStack;
    $check = false;
    if(!is_dir($path)){
      $messageStack->add_session(TEXT_WOZ_CHECK_PATH_ERROR1, 'caution');
    }else if (!file_exists($path.'wp-config.php')) {
      $messageStack->add_session(TEXT_WOZ_CHECK_PATH_ERROR2, 'caution');
    }else{
      $messageStack->add_session(TEXT_WOZ_CHECK_PATH_SUCCESS, 'success');
      $check = true;
    }
    return $check;
  }
  
  
  function ceon_uri_mapping_build_woz_uri_fields($prev_uri_mappings)
  {
      global $languages;
      
      $num_prev_uri_mappings = sizeof($prev_uri_mappings);
      
      $num_languages = sizeof($languages);
      
      $uri_mapping_input_fields = zen_draw_separator('pixel_black.gif', '100%', '2');
      
      $uri_mapping_input_fields .= '<table border="0" cellspacing="0" cellpadding="0">' . "\n\t";
      $uri_mapping_input_fields .= '<tr>' . "\n\t\t" .
          '<td rowspan="2" class="main" valign="top" style="width: 10em; padding-top: 0.5em;">';
      $uri_mapping_input_fields .= TEXT_URI_MAPPING_MANUFACTURER_URI . '</td>' . "\n\t\t" .
          '<td class="main" style="padding-top: 0.5em; padding-bottom: 0;">' . "\n";
      
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $uri_mapping_input_fields .= "<p>";
          
          if (!isset($prev_uri_mappings[$languages[$i]['id']])) {
              $prev_uri_mappings[$languages[$i]['id']] = '';
          }
          
          $uri_mapping_input_fields .= zen_draw_hidden_field('prev_uri_mappings[' .
              $languages[$i]['id'] . ']', $prev_uri_mappings[$languages[$i]['id']]);
          
          $uri_mapping_input_fields .= zen_image(DIR_WS_CATALOG_LANGUAGES .
              $languages[$i]['directory'] . '/images/' .
              $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' .
              zen_draw_input_field('uri_mappings[' . $languages[$i]['id'] . ']',
              $prev_uri_mappings[$languages[$i]['id']], 'size="100"');
          
          $uri_mapping_input_fields .= "</p>\n";
      }
      
      $uri_mapping_input_fields .= '</td>' . "\n\t</tr>\n\t<tr>\n\t\t" .
          '<td class="main" style="padding-top: 1em; padding-bottom: 0.5em;">' . "\n";
      
      $uri_mapping_input_fields .= "<p>";
      
      if (ceon_uri_mapping_autogen_enabled()) {
          if ($num_languages == 1) {
              $autogen_message = TEXT_URI_MAPPING_WOZ_URI_AUTOGEN;
          } else {
              $autogen_message = TEXT_URI_MAPPING_WOZ_URIS_AUTOGEN;
          }
          if ($num_prev_uri_mappings == 0) {
              $autogen_selected = true;
          } else {
              $autogen_selected = false;
              
              if ($num_prev_uri_mappings == 1) {
                  $autogen_message .= '<br />' . TEXT_URI_MAPPING_URI_AUTOGEN_ONE_EXISTING_MAPPING;
              } else if ($num_prev_uri_mappings == $num_languages) {
                  $autogen_message .= '<br />' . TEXT_URI_MAPPING_URI_AUTOGEN_ALL_EXISTING_MAPPINGS;
              } else {
                  $autogen_message .= '<br />' . TEXT_URI_MAPPING_URI_AUTOGEN_SOME_EXISTING_MAPPINGS;
              }
          }
          
          $uri_mapping_input_fields .=
              zen_draw_checkbox_field('uri_mapping_autogen', '1', $autogen_selected) . ' ' .
              $autogen_message;
      } else {
          $uri_mapping_input_fields .= TEXT_URI_MAPPING_URI_AUTOGEN_DISABLED;
      }
      
      $uri_mapping_input_fields .= "</p>";
      
      $uri_mapping_input_fields .= "\n\t\t</td>\n\t</tr>\n</table>\n";
      
      $uri_mapping_input_fields .= zen_draw_separator('pixel_black.gif', '100%', '2');
      
      return $uri_mapping_input_fields;
  }
  
  if (zen_not_null($action)) {
    switch ($action) {
      case 'check_path':
        check_path($new_path);
        zen_redirect(zen_href_link(FILENAME_WOZ_MANAGER, 'default_path=' . urlencode($new_path) . zen_get_all_get_params(array('action')), 'NONSSL'));
        break;
      case 'install':
        if (zen_admin_demo()) {
          $_GET['action']= '';
          $messageStack->add_session(ERROR_ADMIN_DEMO, 'caution');
          zen_redirect(zen_href_link(FILENAME_WOZ_MANAGER, zen_get_all_get_params(array('action')), 'NONSSL'));
        }
        install_woz($new_path);
        $messageStack->add_session(TEXT_WOZ_MSGSTACK_INSTALL_SUCCESS, 'success');
        if($_POST['check_blog_address'] == 'true'){
          include_once($new_path.'wp-config.php');
          global $table_prefix;
          $link = mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);
          mysql_select_db(DB_NAME,$link);
          $url = HTTP_SERVER . DIR_WS_CATALOG;
          $url = substr($url, 0, (strlen($url)-1) );
          $sql = "UPDATE " . $table_prefix . "options SET option_value = '" . $url . "' WHERE option_name = 'home' LIMIT 1";
          $result = mysql_query($sql, $link);
          if($result){
            $messageStack->add_session(TEXT_WOZ_MSGSTACK_UPDATE_BLOG_ADDRESS, 'success');
          }
        }

        zen_redirect(zen_href_link(FILENAME_WOZ_MANAGER, '', 'NONSSL'));
        break;
      case 'remove_confirm':
        break;
      case 'remove':
        if (zen_admin_demo()) {
          $_GET['action']= '';
          $messageStack->add_session(ERROR_ADMIN_DEMO, 'caution');
          zen_redirect(zen_href_link(FILENAME_WOZ_MANAGER, zen_get_all_get_params(array('action')), 'NONSSL'));
        }
        remove_woz();
        $messageStack->add_session(TEXT_WOZ_MSGSTACK_REMOVE_SUCCESS, 'success');
        zen_redirect(zen_href_link(FILENAME_WOZ_MANAGER, '', 'NONSSL'));
        break;
      
      case 'insert':
        $check_query = $db->Execute("select * from " . TABLE_WOZ . " where woz_language = '" . $_POST['lang'] . "'");
        if ($check_query->RecordCount() < 1 ) {
          $check = check_path($new_path);
          if($check == true){
            $db->Execute("insert into " . TABLE_WOZ . " (woz_dir, woz_language) values ('" . $new_path . "', '" . $_POST['lang'] . "')");
            $_GET['tID'] = $db->Insert_ID();
            $messageStack->add_session(TEXT_MSGSTACK_INFO_SUCCESS, 'success');
            zen_redirect(zen_href_link(FILENAME_WOZ_MANAGER, zen_get_all_get_params(array('action')), 'NONSSL'));
          }
        }
        $action="";
        zen_redirect(zen_href_link(FILENAME_WOZ_MANAGER, zen_get_all_get_params(array('action')) . '&action=new&default_path=' . urlencode($new_path), 'NONSSL'));
        
        break;
      case 'save':
        $check = check_path($new_path);
        if($check == true){
          $db->Execute("update " . TABLE_WOZ . " set woz_dir = '" . $new_path . "' where woz_id = '" . $_GET['tID'] . "'");
          $messageStack->add_session(TEXT_MSGSTACK_INFO_SUCCESS, 'success');
          zen_redirect(zen_href_link(FILENAME_WOZ_MANAGER, zen_get_all_get_params(array('action')), 'NONSSL'));
        }
        zen_redirect(zen_href_link(FILENAME_WOZ_MANAGER, zen_get_all_get_params(array('action')) . '&action=edit&default_path=' . urlencode($new_path), 'NONSSL'));
        break;
      case 'deleteconfirm':
        $check_query = $db->Execute("select woz_language from " . TABLE_WOZ . " where woz_id = '" . $_GET['tID'] . "'");
        if ( $check_query->fields['woz_language'] != 0 ) {
          $db->Execute("delete from " . TABLE_WOZ . " where woz_id = '" . $_GET['tID'] . "'");
          zen_redirect(zen_href_link(FILENAME_WOZ_MANAGER, 'page=' . $_GET['page']));
        }
        $action="";
        break;
      case 'ceon_url_save':
        // BEGIN CEON URI MAPPING 1 of 4
        $uri_mapping_autogen = (isset($_POST['uri_mapping_autogen']) ? true : false);
        
        $languages = zen_get_languages();
        
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $prev_uri_mapping = trim($_POST['prev_uri_mappings'][$languages[$i]['id']]);
          
          // Auto-generate the URI if requested
          if ($uri_mapping_autogen) {
            $uri = FILENAME_WORDPRESS;
            if (strlen(DIR_WS_CATALOG) > 0) {
                $uri = DIR_WS_CATALOG . $uri;
            } else {
                $uri = '/' . $uri;
            }
            $uri_mapping = $uri ;
          } else {
            $uri_mapping = $_POST['uri_mappings'][$languages[$i]['id']];
          }
          
          if (strlen($uri_mapping) > 1) {
            // Make sure URI mapping is relative to root of site and does not have more than one
            // trailing slash or any illegal characters
            $uri_mapping = ceon_uri_mapping_cleanup_uri_mapping($uri_mapping);
          }
          
          $insert_uri_mapping = false;
          $update_uri_mapping = false;
          
          if ($uri_mapping != '') {
            // Check if the URI mapping is being updated or does not yet exist
            if ($prev_uri_mapping == '') {
              $insert_uri_mapping = true;
            } else if ($prev_uri_mapping != $uri_mapping) {
              $update_uri_mapping = true;
            }
          }
          
          if ($insert_uri_mapping || $update_uri_mapping) {
            if ($update_uri_mapping) {
              // Consign previous mapping to the history, so old URI mapping isn't broken
              $db->Execute("
                UPDATE
                  " . TABLE_CEON_URI_MAPPINGS . "
                SET
                  current_uri = '0'
                WHERE
                  main_page = '" . zen_db_input(FILENAME_WORDPRESS) . "'
                AND
                  language_id = '" . (int) $languages[$i]['id'] . "';");
            }
            
            $sql_data_array = array(
              'uri' => zen_db_prepare_input($uri_mapping),
              'language_id' => (int) $languages[$i]['id'],
              'current_uri' => 1,
              'main_page' => FILENAME_WORDPRESS,
              'date_added' => date('Y-m-d H:i:s')
              );
            
            zen_db_perform(TABLE_CEON_URI_MAPPINGS, $sql_data_array);
          } else if ($prev_uri_mapping != '' && $uri_mapping == '') {
            // No URI mapping, consign existing mapping to the history, so old URI mapping isn't
            // broken
            $db->Execute("
              UPDATE
                " . TABLE_CEON_URI_MAPPINGS . "
              SET
                current_uri = '0'
              WHERE
                main_page = '" . zen_db_input(FILENAME_WORDPRESS) . "'
              AND
                language_id = '" . (int) $languages[$i]['id'] . "';");
          }
        }
        // END CEON URI MAPPING 1 of 4
                  
        // BEGIN CEON URI MAPPING 2 of 4
        $delete_check_text = '';
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $delete_check_text .= $_POST['uri_mappings'][$languages[$i]['id']];
        }
        if(!isset($_POST['uri_mapping_autogen']) && $delete_check_text === ''){
          $db->Execute("DELETE FROM " . TABLE_CEON_URI_MAPPINGS . "
            WHERE main_page = '" . FILENAME_WORDPRESS . "'");
        }
        // END CEON URI MAPPING 2 of 4

        $messageStack->add_session(TEXT_MSGSTACK_INFO_SUCCESS, 'success');
        zen_redirect(zen_href_link(FILENAME_WOZ_MANAGER, zen_get_all_get_params(array('action')), 'NONSSL'));
        break;
      default:

    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="init()">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if($action == 'remove_confirm'){
    echo '<tr><td>' . TEXT_WARNING_REMOVE . '<br />' .
    '<a href="' . zen_href_link(FILENAME_WOZ_MANAGER, 'action=remove', 'NONSSL') . '">' . zen_image_button('button_confirm_red.gif', IMAGE_CONFIRM) . '</a>&nbsp;' . 
    '<a href="' . zen_href_link(FILENAME_WOZ_MANAGER, '', 'NONSSL') . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' .
    '</td></tr>';
  }else if(defined('WOZ_CONFIG_STATUS')){ // installed
?>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                <td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr class="dataTableHeadingRow">
                      <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LANGUAGE; ?></td>
                      <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DIRECTORY; ?></td>
                      <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                    </tr>
                    <?php
  $woz_query_raw = "select * from " . TABLE_WOZ;
  $woz_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $woz_query_raw, $woz_query_numrows);
  $templates = $db->Execute($woz_query_raw);
  while (!$templates->EOF) {
    if ((!isset($_GET['tID']) || (isset($_GET['tID']) && ($_GET['tID'] == $templates->fields['woz_id']))) && !isset($tInfo) && (substr($action, 0, 3) != 'new')) {
      $tInfo = new objectInfo($templates->fields);
    }

    if (isset($tInfo) && is_object($tInfo) && ($templates->fields['woz_id'] == $tInfo->woz_id)) {
      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . zen_href_link(FILENAME_WOZ_MANAGER, 'page=' . $_GET['page'] . '&tID=' . $tInfo->woz_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . zen_href_link(FILENAME_WOZ_MANAGER, 'page=' . $_GET['page'] . '&tID=' . $templates->fields['woz_id']) . '\'">' . "\n";
    }
    if ($templates->fields['woz_language'] == 0) {
      $woz_language = "Default(All)";
    } else {
      $ln = $db->Execute("select name
                          from " . TABLE_LANGUAGES . "
                          where languages_id = '" . $templates->fields['woz_language'] . "'");
      $woz_language = $ln->fields['name'];
    }
?>
                    <td class="dataTableContent"><?php echo $woz_language; ?></td>
                    <td class="dataTableContent" align="center"><?php echo $templates->fields['woz_dir']; ?></td>
                    <td class="dataTableContent" align="right">
                      <?php if (isset($tInfo) && is_object($tInfo) && ($templates->fields['woz_id'] == $tInfo->woz_id) ) { echo zen_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . zen_href_link(FILENAME_WOZ_MANAGER, 'page=' . $_GET['page'] . '&tID=' . $templates->fields['woz_id']) . '">' . zen_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>
                      &nbsp;</td>
                    </tr>
<?php
    $templates->MoveNext();
  }
?>
                    <tr>
                      <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                          <tr>
                            <td class="smallText" valign="top"><?php echo $woz_split->display_count($woz_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_WP); ?></td>
                            <td class="smallText" align="right"><?php echo $woz_split->display_links($woz_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                          </tr>
                          <?php
  if (empty($action)) {
?>
                          <tr>
                            <td colspan="2" align="right"><?php echo '<a href="' . zen_href_link(FILENAME_WOZ_MANAGER, 'page=' . $_GET['page'] . '&action=new') . '">' . zen_image_button('button_new_language.gif', IMAGE_NEW_WOZ) . '</a>'; ?></td>
                          </tr>
                          <?php
  }
?>
                        </table></td>
                    </tr>
                  </table></td>
                <?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW . '</b>');

      $contents = array('form' => zen_draw_form('wozmanger', FILENAME_WOZ_MANAGER, 'page=' . $_GET['page'] . '&action=insert'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
      $lns = $db->Execute("select name, languages_id from " . TABLE_LANGUAGES);
      while (!$lns->EOF) {
        $language_array[] = array('text' => $lns->fields['name'], 'id' => $lns->fields['languages_id']);
        $lns->MoveNext();
      } 
      $contents[] = array('text' => '<br>' . TEXT_INFO_WP_PATH . '<br>' . zen_draw_input_field('path', $default_path, ' size="100"'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_NAME . '<br>' . zen_draw_pull_down_menu('lang', $language_array, $_POST['lang']));
      $contents[] = array('align' => 'center', 'text' => '<br>' . zen_image_submit('button_insert.gif', IMAGE_INSERT) . '&nbsp;<a href="' . zen_href_link(FILENAME_WOZ_MANAGER, 'page=' . $_GET['page']) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT . '</b>');

      $contents = array('form' => zen_draw_form('wozmanger', FILENAME_WOZ_MANAGER, 'page=' . $_GET['page'] . '&tID=' . $tInfo->woz_id . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);

      $contents[] = array('text' => '<br>' . TEXT_INFO_WP_PATH . '<br>' . zen_draw_input_field('path', $tInfo->woz_dir, ' size="100"'));
      $contents[] = array('align' => 'center', 'text' => '<br>' . zen_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . zen_href_link(FILENAME_WOZ_MANAGER, 'page=' . $_GET['page'] . '&tID=' . $tInfo->woz_id) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE . '</b>');

      $contents = array('form' => zen_draw_form('wozmanger', FILENAME_WOZ_MANAGER, 'page=' . $_GET['page'] . '&tID=' . $tInfo->woz_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $woz_info[$tInfo->woz_dir]['name'] . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . zen_image_submit('button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . zen_href_link(FILENAME_WOZ_MANAGER, 'page=' . $_GET['page'] . '&tID=' . $tInfo->woz_id) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'ceon_url_setting':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_CEON_URL_SETTING . '</b>');

      $contents = array('form' => zen_draw_form('wozmanger', FILENAME_WOZ_MANAGER, 'page=' . $_GET['page'] . '&tID=' . $tInfo->woz_id . '&action=ceon_url_save'));
      $contents[] = array('text' => TEXT_INFO_CEON_URL_SETTING_INTRO);

      // BEGIN CEON URI MAPPING 4 of 4
      // Get any current manufacturer mappings from the database, up to one for each language
      $prev_uri_mappings = array();
            
      $prev_uri_mappings_sql = "
        SELECT
          language_id,
          uri
        FROM
          " . TABLE_CEON_URI_MAPPINGS . "
        WHERE
          main_page = '" . FILENAME_WORDPRESS . "'
        AND
          current_uri = '1';";
      
      $prev_uri_mappings_result = $db->Execute($prev_uri_mappings_sql);
      
      while (!$prev_uri_mappings_result->EOF) {
        $prev_uri_mappings[$prev_uri_mappings_result->fields['language_id']] =
          $prev_uri_mappings_result->fields['uri'];
        
        $prev_uri_mappings_result->MoveNext();
      }
      
      $uri_mapping_input_fields = ceon_uri_mapping_build_woz_uri_fields($prev_uri_mappings);
      
      $contents[] = array('text' => $uri_mapping_input_fields);
      // END CEON URI MAPPING 4 of 4
      
      $contents[] = array('align' => 'center', 'text' => '<br>' . zen_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . zen_href_link(FILENAME_WOZ_MANAGER, 'page=' . $_GET['page'] . '&tID=' . $tInfo->woz_id) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      $contents[] = array('text' => TEXT_INFO_CEON_URL_SETTING_NOTE);
      break;
    default:
      if (isset($tInfo) && is_object($tInfo)) {
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT . '</b>');
        if ($tInfo->woz_language == 0) {
          $contents[] = array('align' => 'center', 'text' => '<a href="' . zen_href_link(FILENAME_WOZ_MANAGER, 'page=' . $_GET['page'] . '&tID=' . $tInfo->woz_id . '&action=edit') . '">' . zen_image_button('button_edit.gif', IMAGE_EDIT) . '</a>');
        } else {
          $contents[] = array('align' => 'center', 'text' => '<a href="' . zen_href_link(FILENAME_WOZ_MANAGER, 'page=' . $_GET['page'] . '&tID=' . $tInfo->woz_id . '&action=edit') . '">' . zen_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . zen_href_link(FILENAME_WOZ_MANAGER, 'page=' . $_GET['page'] . '&tID=' . $tInfo->woz_id . '&action=delete') . '">' . zen_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        }

      }
      break;
  }

  if ( (zen_not_null($heading)) && (zen_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td>Option:<br />
<?php
  if (defined('CEON_URI_MAPPING_ENABLED') && CEON_URI_MAPPING_ENABLED == 1){ 
    echo '<a href="' . zen_href_link(FILENAME_WOZ_MANAGER, 'page=' . $_GET['page'] . '&action=ceon_url_setting') . '">' . TEXT_OPTION_CEON_URI_MAPPING_SETTING . '</a>';
  }
?>
          </td>
        </tr>
<?php
    echo '<tr><td>' . '<a href="' . zen_href_link(FILENAME_WOZ_MANAGER, 'action=remove_confirm', 'NONSSL') . '">' . TEXT_OPTION_WOZ_REMOVE_CONFIRM . '</a>' . '</td></tr>';
  }else{ // yet install
    echo '<tr><td>' .
    TEXT_WOZ_CHECK_PATH_INFO . '<br />' .
    zen_draw_form('check_path', FILENAME_WOZ_MANAGER, 'action=check_path', 'post', '', true) . zen_hide_session_id() .
    zen_draw_input_field('path', $default_path, ' size="100"') . '&nbsp;';
    
    if (!file_exists($default_path.'wp-config.php')) {
      echo zen_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10) . '<br />';
      echo '<br />' . zen_image_submit('button_confirm.gif', IMAGE_CONFIRM);
      echo '</form>';
    }else{
      echo '</form>';
      echo zen_draw_form('install', FILENAME_WOZ_MANAGER, 'action=install', 'post', '', true) . zen_hide_session_id() .
      zen_draw_hidden_field('path', $default_path) .
      zen_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '<br />';
      echo zen_draw_checkbox_field('check_blog_address', 'true', 'true') . TEXT_INFO_CHECK_BLOG_ADDRESS . '<br />'; // Setting > General > [Blog address (URL)]
      echo '<br />' . zen_image_submit('button_module_install.gif', IMAGE_MODULE_INSTALL);
      echo '</form>';
    }

    echo '</td></tr>';
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br />
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
