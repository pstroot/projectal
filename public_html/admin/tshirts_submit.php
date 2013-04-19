<?
error_reporting(E_ALL);
ini_set('display_errors', '1');

// exit if the button that sent this form is not labeled "SUBMIT"
if (strtoupper($_POST["submit"]) != "SUBMIT") exit();


//include_once("includes/initAdminPage.php");
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formValidation.php");
include_once("includes/imageUploadFunctions.php");




// create a session array out of our POST variables
$errors = array();
$_SESSION["formVars"] = array();
foreach($_POST as $varname => $value){
	$_SESSION["formVars"]["{$varname}"] = cleanUp($_POST, $varname, 500);
}

////////////////// Check for required fields ////////////////////
if (isset($_REQUEST["required"])){
	foreach($_REQUEST["required"] as $val){
		if (!isBlank($_SESSION["formVars"]["$val"])){
			$errors["$val"] = "This field is required";
		}
	}
}


$product_image = "";

if (!count($errors)){
	$product_image = imageUpload("product_image","images/tshirt_templates/","../");
	if($product_image === false){
		$errors["product_image"] = "There was an error uploading your image";
	}
}


if (count($errors)){
	$_SESSION["formErrors"] = $errors;	
	$_SESSION["bounceFromErrors"] = true;
	print ("<meta http-equiv='refresh' content='0; URL=".$_POST["returnURL"]."'>");
	exit;
} 
unset($_SESSION["bounceFromErrors"]);



// first, check to see if this category/option combination already exists in our table
$nbrResults = 0;
$Query = "SELECT COUNT(match_id) AS nbrResults FROM tshirt_color_images WHERE option_id = " .$_SESSION["formVars"]["optionID"] . " AND category_id = " . $_SESSION["formVars"]["catID"];
$Results = $db->Execute($Query);
while (!$Results->EOF) {	
	$nbrResults = $Results->fields['nbrResults'];
	$Results->MoveNext();
}


if($nbrResults == 0){
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// ::::::::::::::::::::::::::::::: ADD A BUSINESS  ::::::::::::::::::::::::::::::::::::::::::::::
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

	$Query = "INSERT INTO tshirt_color_images (option_id,category_id,product_image,comments)
							VALUES
								({$_SESSION["formVars"]["optionID"]},
								 {$_SESSION["formVars"]["catID"]},
								'$product_image',
								'" . addslashes($_SESSION["formVars"]["comments"]) . "');";

	$Results = $db->Execute($Query);

	unset($_SESSION["formVars"]);
	unset($_SESSION["formErrors"]);
	header( 'Location: ' . $_POST["successURL"] . '?id=' . mysql_insert_id() );


} else {

// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// :::::::::::::::::::::::::::::: EDIT A BUSINESS  ::::::::::::::::::::::::::::::::::::::::::::::
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

	$Query = "UPDATE tshirt_color_images SET 
								option_id = {$_SESSION["formVars"]["optionID"]},
								category_id = {$_SESSION["formVars"]["catID"]},
								product_image = '$product_image',
								comments = '" . addslashes($_SESSION["formVars"]["comments"]) . "'
							WHERE match_id = {$_SESSION["formVars"]["id"]};";

	$Results = $db->Execute($Query);
	

	
	unset($_SESSION["formVars"]);
	unset($_SESSION["formErrors"]);
	header( 'Location: ' . $_POST["successURL"] . '?id=' . $_POST["id"] );
}
?>