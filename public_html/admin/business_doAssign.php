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
	$_SESSION["formVars"]["{$varname}"] = cleanUp($_POST, $varname, 500);
}
	
////////////////// check form values for errors ////////////////////


if (count($errors)){
	$_SESSION["formErrors"] = $errors;	
	$_SESSION["bounceFromErrors"] = true;
	print ("<meta http-equiv='refresh' content='0; URL=".$_POST["returnURL"]."'>");
	exit;
} 
unset($_SESSION["bounceFromErrors"]);


if (strtoupper($_POST["submit"]) == "ASSIGN TO BUSINESS"){
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// ::::::::::::::::::::::::::::::: DELETE ALL CURRENTLY ASSIGNED PRODUCTS  ::::::::::::::::::::::::::::::::::::::::::::::
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
	$Query = "DELETE FROM product_to_businesses WHERE business_id =  {$_SESSION["formVars"]["id"]};";
	if(!$Results = $db->Execute($Query)){
		print "ERROR";
		$db->show_error();
	}
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// :::::::::::::::::::::::::::::: EDIT A BUSINESS  ::::::::::::::::::::::::::::::::::::::::::::::
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
	foreach($_POST["productID"] as $id){	
		$Query = "INSERT INTO product_to_businesses (business_id,product_id) VALUES ({$_SESSION["formVars"]["id"]},$id);";

		if(!$Results = $db->Execute($Query)){
			print "ERROR";
			$db->show_error();
		}
	}
	
	unset($_SESSION["formVars"]);
	unset($_SESSION["formErrors"]);
	header( 'Location: ' . $_POST["successURL"] . '?id=' . $_POST["id"] );
}

?>