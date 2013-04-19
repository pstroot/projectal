<?
error_reporting(E_ALL);
ini_set('display_errors', '1');


//include_once("includes/initAdminPage.php");
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formValidation.php");
include_once("includes/imageUploadFunctions.php");


// create a session array out of our POST variables
$errors = array();
$_SESSION["formVars"] = array();
foreach($_POST as $varname => $value){
	$_SESSION["formVars"]["{$varname}"] = cleanUp($_POST, $varname, 10000);
}

////////////////// Check for required fields ////////////////////
if (isset($_REQUEST["required"])){
	foreach($_REQUEST["required"] as $val){
		if (!isBlank($_SESSION["formVars"]["$val"])){
			$errors["$val"] = "This field is required";
		}
	}
}


$business_logo = "";
$business_image = "";

if (!count($errors)){
	$business_logo = imageUpload("business_logo","images/businesses/logos/","../");
	if($business_logo === false){
		$errors["business_logo"] = "There was an error uploading your image";
	}
}

if (!count($errors)){
	$business_image = imageUpload("business_image","images/businesses/storeProductPage/","../");
	if($business_image === false){
		$errors["business_image"] = "There was an error uploading your image";
	}
}

if (count($errors)){
	$_SESSION["formErrors"] = $errors;	
	$_SESSION["bounceFromErrors"] = true;
	print ("<meta http-equiv='refresh' content='0; URL=".$_POST["returnURL"]."'>");
	exit;
} 
unset($_SESSION["bounceFromErrors"]);

// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// ::::::::::::::::::::::::::::::: ADD A BUSINESS  ::::::::::::::::::::::::::::::::::::::::::::::
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

if (strtoupper($_POST["submit"]) == "ADD"){	
	$theOrder = 0;
	$Query = "SELECT business_order FROM businesses ORDER BY business_order DESC LIMIT 1";
	$Results = $db->Execute($Query);
	while (!$Results->EOF) {	
		$theOrder =  $Results->fields['business_order'];
		$Results->MoveNext();
	}
	
	$Query = "INSERT INTO businesses (business_name,business_description,business_image,business_logo,business_address,business_city,business_state,business_zip,business_phone,business_website,business_order)
							VALUES
								('{$_SESSION["formVars"]["business_name"]}',
								'{$_SESSION["formVars"]["description"]}',
								'$business_image',
								'$business_logo',
								'{$_SESSION["formVars"]["business_address"]}',
								'{$_SESSION["formVars"]["business_city"]}',
								'{$_SESSION["formVars"]["business_state"]}',
								'{$_SESSION["formVars"]["business_zip"]}',
								'{$_SESSION["formVars"]["business_phone"]}',
								'{$_SESSION["formVars"]["business_website"]}',
								  $theOrder);";

	$Results = $db->Execute($Query);
	$theID = mysql_insert_id();

}
 




// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// :::::::::::::::::::::::::::::: EDIT A BUSINESS  ::::::::::::::::::::::::::::::::::::::::::::::
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

if (strtoupper($_POST["submit"]) == "EDIT"){
	$theID = $_SESSION["formVars"]["id"];
	
	$Query = "UPDATE businesses SET 
								business_name = '{$_SESSION["formVars"]["business_name"]}',
								business_description = '{$_SESSION["formVars"]["description"]}',
								business_image = '$business_image',
								business_logo = '$business_logo',
								business_address = '{$_SESSION["formVars"]["business_address"]}',
								business_city = '{$_SESSION["formVars"]["business_city"]}',
								business_state = '{$_SESSION["formVars"]["business_state"]}',
								business_zip = '{$_SESSION["formVars"]["business_zip"]}',
								business_phone = '{$_SESSION["formVars"]["business_phone"]}',
								business_website = '{$_SESSION["formVars"]["business_website"]}'
							WHERE business_id = $theID;";

	$Results = $db->Execute($Query);
	
	
	// DELETE ANY EXISTING BUSINESS TYPE MATCHES
	$Query = "DELETE FROM business_to_type WHERE business_id = $theID";
	$Results = $db->Execute($Query);
	
	// DELETE ANY EXISTING NEIGHBORHOOD MATCHES
	$Query = "DELETE FROM business_to_neighborhood WHERE business_id = $theID";
	$Results = $db->Execute($Query);
	
	// DELETE ANY EXISTING THEME MATCHES
	$Query = "DELETE FROM business_to_theme WHERE business_id = $theID";
	$Results = $db->Execute($Query);
}

// ADD THE NEW BUSINESS TYPE MATCHES
if(isset($_POST["business_type"])){
	foreach($_POST["business_type"] as $typeID){
		if($typeID != ""){
			$Query = "INSERT INTO business_to_type (business_id,type_id) VALUES ($theID,$typeID)";
			$Results = $db->Execute($Query);
		}
	}
}


// ADD THE NEW NEIGHBORHOOD MATCHES
if(isset($_POST["business_neighborhood"])){
	foreach($_POST["business_neighborhood"] as $neighborhoodID){
		if($neighborhoodID != ""){
			$Query = "INSERT INTO business_to_neighborhood (business_id,neighborhood_id) VALUES ($theID,$neighborhoodID)";
			$Results = $db->Execute($Query);
		}
	}
}


// ADD THE NEW THEME MATCHES
if(isset($_POST["business_theme"])){
	foreach($_POST["business_theme"] as $themeID){
		if($themeID != ""){
			$Query = "INSERT INTO business_to_theme (business_id,theme_id) VALUES ($theID,$themeID)";
			$Results = $db->Execute($Query);
		}
	}
}

unset($_SESSION["formVars"]);
unset($_SESSION["formErrors"]);
header( 'Location: ' . $_POST["successURL"] . '?id=' . $theID);
?>