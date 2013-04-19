<?
$pathToRoot = "../";
$cityID = "65"; // 65 is the city ID for Minneapolis


function curPageURL() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}
function curPagePath() {
	//$pageURL = $_SERVER["SCRIPT_URI"];
	$pageURL = $_SERVER["SCRIPT_NAME"];
	$lastDash = strrpos($pageURL,"/");
	$path = substr($pageURL,0,$lastDash);
	return $path;
}
function projectal_zen_redirect($url, $httpResponseCode = ''){
	if(isset($_REQUEST["redirect"])){
		$url = $_REQUEST["redirect"];
	}
	zen_redirect($url,$httpResponseCode);	

}



function projectal_is_address_complete($customers_id, $address_id = 0) {
    global $db;
    $address_query = "select entry_firstname as firstname, entry_lastname as lastname,entry_zone_id as 'Zone_ID',
                             entry_company as company, entry_street_address as street_address,
                             entry_suburb as suburb, entry_city as city, entry_postcode as postcode,
                             entry_state as state, entry_zone_id as zone_id,
                             entry_country_id as country_id
                      FROM " . TABLE_ADDRESS_BOOK . "
                      WHERE customers_id = '" . (int)$customers_id . "'";
					  
	if($address_id > 0){
		$address_query .= " AND address_book_id = " . (int)$address_id ;
	}
	$address_query .= " ORDER BY address_book_id LIMIT 1";

	//print $address_query;
    $address = $db->Execute($address_query);
    if ($address->fields['street_address'] == ""){
		//print "street_address not complete (".$address->fields['street_address'].")<BR>";
		return false;
	}
    if ($address->fields['postcode'] == ""){
		//print "postcode not complete (".$address->fields['postcode'].")<BR>";
		return false;
	}
    if ($address->fields['city'] == ""){
		//print "city not complete (".$address->fields['city'].")<BR>";
		return false;
	}
    if ($address->fields['Zone_ID'] == ""){
		print "state not complete (".$address->fields['Zone_ID'].")<BR><BR>" . $address_query;
		return false;
	}
	return true;
  	
}


// Special formatting for the submit button
// included in "includes/templates/Projectal/templates/tpl_product_info_display.php"
function projectal_image_submit($image, $alt = '', $parameters = '', $sec_class = '') {
    global $template, $current_page_base, $zco_notifier;
    if (strtolower(IMAGE_USE_CSS_BUTTONS) == 'yes' && strlen($alt)<30) return zenCssButton($image, $alt, 'submit', $sec_class /*, $parameters = ''*/ );
    $zco_notifier->notify('PAGE_OUTPUT_IMAGE_SUBMIT');
	$image_submit = '<div id="addToCart"><input type="button" class="greenButton" id="submitBtn" onclick="this.form.submit();" value="ADD TO SHOPPING BAG"></div>';

    return $image_submit;
}


// Display the business name instead of the product name (if it exists) in the grid list of products
// included in "includes/modules/Projectal/product_listings.php"
function projectal_get_listing_title($listingName,$listingID,$listingHREF){
	global $db;
	
	// get the ID of the image
	if(strpos($listingID,":") > 0){
		$listingID = substr($listingID,0,strpos($listingID,":"));
	}
	
	$business_query = $db->Execute("SELECT business_name
                                    FROM businesses b
                                    LEFT JOIN product_to_businesses p ON p.business_id = b.business_id
                                    WHERE p.product_id = ".$listingID);
    while (!$business_query->EOF) {	
          $business_name = $business_query->fields['business_name'];
		  $listingName =  $business_name;
          $business_query->MoveNext();
    }
	  
	$theTitle = '<h3 class="itemTitle"><a href="' . $listingHREF . '">' . stripslashes($listingName) . '</a></h3><div class="listingDescription">' . zen_trunc_string(zen_clean_html(stripslashes(zen_get_products_description($listingID, $_SESSION['languages_id']))), PRODUCT_LIST_DESCRIPTION) . '</div>' ;
	return $theTitle;
}
				  
				  
				  
// Display the tshirt image instead of just the log image in the grid list of products
// included in "includes/modules/Projectal/product_listings.php"			  
function projectal_zen_image($src = '',$alt = '', $width = '', $height = '', $parameters = '', $productID = '',$size = 'SMALL') {

	// if this is NOT a guys or girls category.
	//return zen_image($src, $alt = '', $width = 100, $height = '', $parameters = '');

	

	
		global $db;

	
		// Get the ID of the default color value, and the default image of the attribute (options_id = 1 is the id of the "color" attributes list)
		// Ordering the results by "attributes_default" will make the default item show up first, but if it doesn't exist, then another one will still be selected at random.
		$query = $db->Execute("SELECT options_values_id,attributes_image 
		 FROM zen_products_attributes 
		 WHERE attributes_image != '' 
	     AND products_id = " . $productID . " 
		 AND options_id = 1 
		 ORDER BY attributes_default DESC 
		 LIMIT 1");
		while (!$query->EOF) {			
			$src = "images/" . $query->fields['attributes_image'];
			$query->MoveNext();
		}
	
		if(!is_file($src)){
			$src = "images/" . PRODUCTS_IMAGE_NO_IMAGE;	
		}
		
		// adjust the widht and hieght so that it fits within the given boundries
	    if ($image_size = @getimagesize($src)) {
			  $ratioX = $width / $image_size[0];
			  $ratioY = $height / $image_size[1];
			  if($ratioX < $ratioY){
				$ratio = $ratioX; 
			  } else {
				$ratio = $ratioY;				  
			  }
			  $width = ceil($image_size[0] * $ratio);  
			  $height = ceil($image_size[1] * $ratio);     
        }
		
		$src = str_replace(" ","%20",$src);
		
		$src = calculateOptimizedImage($src,$size);
	    $image = zen_image($src, $alt, $width, $height, $parameters);
		
		return $image;
	



   // $thisImage = new TshirtImage($productID, $width, $height, $alt);
	//$image = $thisImage->get();
   // return $image;
}




function calculateOptimizedImage($src,$size){
	$src = str_replace("images/","",$src);			
	$extension = substr($src, strrpos($src, '.'));			
	$base = str_replace($extension, '', $src);
			
	if(strtoupper($size) == "MEDIUM"){
		$src_resized = $base . IMAGE_SUFFIX_MEDIUM . $extension;
		$imageDir = "medium/";
	}else if(strtoupper($size) == "LARGE"){
		$src_resized = $base . IMAGE_SUFFIX_LARGE . $extension;
		$imageDir = "large/";
	}else if(strtoupper($size) == "TINY"){
		$src_resized = $base . "_TINY" . $extension;
		$imageDir = "tiny/";
	}else{
		$src_resized = $src;
		$imageDir = "";
	}
	
	// check for a medium image else use small
	if (file_exists(DIR_WS_IMAGES . $imageDir . $src_resized)) {
		return  DIR_WS_IMAGES . $imageDir . $src_resized;
	} else if (file_exists(DIR_WS_IMAGES . $imageDir . $src)) {
		 return  DIR_WS_IMAGES . $imageDir . $src;
	} else {
		return  DIR_WS_IMAGES . $src;
	}
}
  
?>