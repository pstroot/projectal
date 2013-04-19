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

////////////////// Check for required fields ////////////////////
if (isset($_REQUEST["required"])){
	foreach($_REQUEST["required"] as $val){
		if (!isBlank($_SESSION["formVars"]["$val"])){
			$errors["$val"] = "This field is required";
		}
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
// ::::::::::::::::::::::::::::::: ADD A PARTICIPANT  ::::::::::::::::::::::::::::::::::::::::::::::
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

if (strtoupper($_POST["submit"]) == "ADD"){	
	
	$Query = "INSERT INTO participants (customer_id, profession, date_created) VALUES ({$_SESSION["formVars"]["customer_id"]},'{$_SESSION["formVars"]["profession"]}', now());";

	$Results = $db->Execute($Query);

	unset($_SESSION["formVars"]);
	unset($_SESSION["formErrors"]);
	header( 'Location: ' . $_POST["successURL"] . '?id=' . mysql_insert_id() );
}
 




// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// :::::::::::::::::::::::::::::: EDIT A PARTICIPANT  ::::::::::::::::::::::::::::::::::::::::::::::
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

if (strtoupper($_POST["submit"]) == "EDIT"){
	$Query = "UPDATE participants SET profession = '{$_SESSION["formVars"]["profession"]}' WHERE participant_id = {$_SESSION["formVars"]["id"]};";
	$Results = $db->Execute($Query);
	
	$Query = "UPDATE zen_customers SET 
			  customers_firstname = '{$_SESSION["formVars"]["firstname"]}', 
			  customers_lastname = '{$_SESSION["formVars"]["lastname"]}' ,
			  customers_email_address = '{$_SESSION["formVars"]["email"]}' 
			  WHERE customers_id = {$_SESSION["formVars"]["customer_id"]};";
	$Results = $db->Execute($Query);
	
	unset($_SESSION["formVars"]);
	unset($_SESSION["formErrors"]);
	header( 'Location: ' . $_POST["successURL"] . '?id=' . $_POST["id"] );
}


?>