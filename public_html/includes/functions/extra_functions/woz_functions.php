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
function woz_ssl_convert ($str){
  global $request_type;
  if($request_type == 'SSL'){
    $str = str_replace(HTTP_SERVER . DIR_WS_CATALOG, HTTPS_SERVER . DIR_WS_HTTPS_CATALOG, $str);
  }
  return $str;
}

function woz_str_convert ($str) {
  global $db;
  $str = str_replace(HTTP_SERVER . DIR_WS_CATALOG . '?"', zen_href_link(FILENAME_WORDPRESS) . '"', $str);
  if (defined('CEON_URI_MAPPING_ENABLED') && CEON_URI_MAPPING_ENABLED == 1) {
    // for Ceon URI Mapping BOF
    $check_query = "
    SELECT
        um.language_id,
        um.main_page,
        um.current_uri
    FROM
        " . TABLE_CEON_URI_MAPPINGS . " um
    WHERE
        um.main_page = '" . FILENAME_WORDPRESS . "' 
        and um.language_id = '" . (int)$_SESSION['languages_id'] . "' 
        and um.current_uri = 1
    ";
    $check_result = $db->Execute($check_query);
    if ($check_result->RecordCount() > 0) {
        $str = str_replace(HTTP_SERVER . DIR_WS_CATALOG . '?', zen_href_link(FILENAME_WORDPRESS) . '?', $str);
    }else{
        $str = str_replace(HTTP_SERVER . DIR_WS_CATALOG . '?', zen_href_link(FILENAME_WORDPRESS) . '&amp;', $str);
    }
    // for Ceon URI Mapping EOF
  }else{
    $str = str_replace(HTTP_SERVER . DIR_WS_CATALOG . '?', zen_href_link(FILENAME_WORDPRESS) . '&amp;', $str);
  }
  
  return $str;
}

?>
