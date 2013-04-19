<?php
/**
 * product_info header_php.php 
 *
 * @package page
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: header_php.php 6963 2007-09-08 02:36:34Z drbyte $
 */

  // This should be first line of the script:

if (isset($_POST['action']) && ($_POST['action'] == 'addToNewsletter')) {
    $email_address = zen_db_prepare_input($_POST['email']);
	$redirect = $_POST["redirect"];
	
	// check to see if this email address already exists
	$check_account_query = "SELECT COUNT(*) as total, customer_id FROM mailinglist_emails WHERE list_id = 1 AND email = '" . $email_address . "';";
	$check_account = $db->Execute($check_account_query);
	if ($check_account->fields['total'] > 0) {
		$userID = $check_account->fields['customers_id'];
	 	$messageStack->add('contact', "This email address is already set to receive notices.");
	} else {
		$addQuery = "INSERT INTO mailinglist_emails SET list_id = 1, email = '$email_address'";
		// if the user is logged in, add his user ID
		if ($_SESSION['customer_id']) $addQuery .= ", customer_id = ".$_SESSION['customer_id'];
		
		$query = $db->Execute($addQuery);
		if($userID = $db->Insert_ID()){
			 $messageStack->add('contact', "Thank You. You will now receive notices when ProjectAl has new tees");
		} else {			
			 $messageStack->add('contact', "Sorry, there was a problem adding your email to the mailing list");
		}
	}
	
	//zen_redirect(zen_href_link('', 'action=success'));
	 
}



  $zco_notifier->notify('NOTIFY_HEADER_START_PRODUCT_INFO');

  require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));

  // if specified product_id is disabled or doesn't exist, ensure that metatags and breadcrumbs don't share inappropriate information
  $sql = "select count(*) as total
          from " . TABLE_PRODUCTS . " p, " .
                   TABLE_PRODUCTS_DESCRIPTION . " pd
          where    p.products_status = '1'
          and      p.products_id = '" . (int)$_GET['products_id'] . "'
          and      pd.products_id = p.products_id
          and      pd.language_id = '" . (int)$_SESSION['languages_id'] . "'";
  $res = $db->Execute($sql);
  if ( $res->fields['total'] < 1 ) {
    unset($_GET['products_id']);
    unset($breadcrumb->_trail[sizeof($breadcrumb->_trail)-1]['title']);
    header('HTTP/1.1 404 Not Found');
  }

  // ensure navigation snapshot in case must-be-logged-in-for-price is enabled
  if (!$_SESSION['customer_id']) {
    $_SESSION['navigation']->set_snapshot();
  }




 // get THE BUSINESS INFORMATION ASSOCIATED WITH THIS PRODUCT.
if(strpos($_GET['products_id'],":") > 0){
	$theProductID =substr($_GET['products_id'],0,strpos($_GET['products_id'],":"));
} else {
	$theProductID =$_GET['products_id'];
}
			
$business_query = $db->Execute("SELECT DISTINCT b.*
                                FROM businesses b
                                LEFT JOIN product_to_businesses p ON p.business_id = b.business_id
                                WHERE p.product_id = ".$theProductID);
while (!$business_query->EOF) {	
    $business_name = stripslashes($business_query->fields['business_name']);
    $business_website = $business_query->fields['business_website'];
    $business_description = $business_query->fields['business_description'];
    $business_id =  $business_query->fields['business_id'];
    $business_logo =  $business_query->fields['business_logo'];
    $business_image =  $business_query->fields['business_image'];
    $business_address =  $business_query->fields['business_address'];
    $business_city =  $business_query->fields['business_city'];
    $business_state =  $business_query->fields['business_state'];
	$business_zip =  $business_query->fields['business_zip'];
             
    $business_query->MoveNext();
}
      



// **************** GET INFO FOR THE ROTATING BANNER ************************* //
$sql = "select p.products_id, pd.products_name,
                  pd.products_description, p.products_model,
                  p.products_quantity, p.products_image,
                  pd.products_url, p.products_price,
                  p.products_tax_class_id, p.products_date_added,
                  p.products_date_available, p.manufacturers_id, p.products_quantity,
                  p.products_weight, p.products_priced_by_attribute, p.product_is_free,
                  p.products_qty_box_status,
                  p.products_quantity_order_max,
                  p.products_discount_type, p.products_discount_type_from, p.products_sort_order, p.products_price_sorter
           from   " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd
           where  p.products_status = '1'
           and    p.products_id = '" . (int)$_GET['products_id'] . "'
           and    pd.products_id = p.products_id
           and    pd.language_id = '" . (int)$_SESSION['languages_id'] . "'";

$product_info = $db->Execute($sql);

$products_name = stripslashes($product_info->fields['products_name']);
$products_model = $product_info->fields['products_model'];
$products_description = $product_info->fields['products_description'];
$products_price = $currencies->display_price($product_info->fields['products_price'], zen_get_tax_rate($product_info->fields['products_tax_class_id']));
$manufacturers_name= zen_get_products_manufacturers_name((int)$_GET['products_id']);
$products_url = $product_info->fields['products_url'];

 	// if specified product_id is disabled or doesn't exist, ensure that metatags and breadcrumbs don't share inappropriate information
	$topBannerData = array();
	$theQuery = $db->Execute("SELECT * from rotating_banner b
	LEFT JOIN rotating_banner_placement p ON b.id = p.banner_id
	WHERE b.isActive = '1' 
	AND (p.page_id IS NULL OR p.page_id = ".(int)$_GET['products_id'].")
	ORDER BY b.theOrder");
	while (!$theQuery->EOF) {		
		$tmpArray = array();	
		$isHTML = $theQuery->fields['isHTML'];
		
		if($isHTML){
			$tmpArray["content"] = stripslashes($theQuery->fields['content']);
			$tmpArray["content"] = str_replace("::PRODUCT_ID::",(int)$_GET['products_id'],$tmpArray["content"]);
			$tmpArray["content"] = str_replace("::PRODUCT_CPATH::",(int)$_GET['cPath'],$tmpArray["content"]);
			$tmpArray["content"] = str_replace("::PRODUCT_NAME::",$products_name,$tmpArray["content"]);
			$tmpArray["content"] = str_replace("::PRODUCT_PRICE::",$products_price,$tmpArray["content"]);
			$tmpArray["content"] = str_replace("::PRODUCT_MODEL::",$products_model,$tmpArray["content"]);
			$tmpArray["content"] = str_replace("::PRODUCT_DESCRIPTION::",$products_description,$tmpArray["content"]);
			$tmpArray["content"] = str_replace("::PRODUCT_MANUFACTURER::",$manufacturers_name,$tmpArray["content"]);
			
			$tmpArray["content"] = str_replace("::BUSINESS_NAME::",$business_name,$tmpArray["content"]);
			$tmpArray["content"] = str_replace("::BUSINESS_WEBSITE::",$business_website,$tmpArray["content"]);
			$tmpArray["content"] = str_replace("::BUSINESS_ID::",$business_id,$tmpArray["content"]);
			$tmpArray["content"] = str_replace("::BUSINESS_ADDRESS::",$business_address,$tmpArray["content"]);
			$tmpArray["content"] = str_replace("::BUSINESS_CITY::",$business_city,$tmpArray["content"]);
			$tmpArray["content"] = str_replace("::BUSINESS_STATE::",$business_state,$tmpArray["content"]);
			$tmpArray["content"] = str_replace("::BUSINESS_ZIP::",$business_zip,$tmpArray["content"]);
			$tmpArray["content"] = str_replace("::BUSINESS_LOGO::","includes/templates/Projectal/" . $business_logo,$tmpArray["content"]);
			
			
		} else {
			$tmpArray["content"] = "<img src='" . $theQuery->fields['content'] . "' alt='".$theQuery->fields['title']."'>";
		}
		
		if(trim($theQuery->fields['link']) != ""){
			$tmpArray["content"] = "<a href='".$theQuery->fields['link']."'>" . $tmpArray["content"] . "</a>";
		}
		
		$tmpArray["delay"] = $theQuery->fields['delay'];
		
		array_push($topBannerData,$tmpArray);
		$theQuery->MoveNext();
	}	
	


  // This should be last line of the script:
  $zco_notifier->notify('NOTIFY_HEADER_END_PRODUCT_INFO');
  
  
  
if(strpos($_GET['products_id'],":") > 0){
	$theProductID =substr($_GET['products_id'],0,strpos($_GET['products_id'],":"));
} else {
	$theProductID =$_GET['products_id'];
}
?>



<?php

// get the product image from the database to be used in the facebook LIKE button
$sql = "SELECT p.products_image FROM   " . TABLE_PRODUCTS . " p WHERE p.products_id = '" . (int)$_GET['products_id'] . "'";
$product_info = $db->Execute($sql);
	  if ($product_info->fields['products_image'] == '' and PRODUCTS_IMAGE_NO_IMAGE_STATUS == '1') {
    $products_image = PRODUCTS_IMAGE_NO_IMAGE;
} else {
    $products_image = $product_info->fields['products_image'];
}


// Calculate URL for zoom image
$query = $db->Execute("SELECT options_values_id,attributes_image 
		 FROM zen_products_attributes 
		 WHERE attributes_image != '' 
	     AND products_id = " . $theProductID . " 
		 AND options_id = 1 
		 ORDER BY attributes_default DESC 
		 LIMIT 1"); 
if($query->fields['attributes_image'] != ""){
	$facebook_image = calculateOptimizedImage($query->fields['attributes_image'],"THUMB"); 
}

?>
  