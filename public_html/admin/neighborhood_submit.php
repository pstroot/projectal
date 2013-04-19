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
// ::::::::::::::::::::::::::::::: ADD A BUSINESS  ::::::::::::::::::::::::::::::::::::::::::::::
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

if (strtoupper($_POST["submit"]) == "ADD"){	
	$theOrder = 0;
	$Query = "SELECT neighborhood_order FROM neighborhoods ORDER BY neighborhood_order DESC LIMIT 1";
	$Results = $db->Execute($Query);
	while (!$Results->EOF) {	
		$theOrder =  $Results->fields['neighborhood_order'];
		$Results->MoveNext();
	}
	
	$Query = "INSERT INTO neighborhoods (neighborhood_name,city_id,neighborhood_order)
							VALUES
								('{$_SESSION["formVars"]["neighborhood_name"]}',
								{$_SESSION["formVars"]["city_id"]},
								  $theOrder);";

	$Results = $db->Execute($Query);

	unset($_SESSION["formVars"]);
	unset($_SESSION["formErrors"]);
	header( 'Location: ' . $_POST["successURL"] . '?id=' . mysql_insert_id() );
}
 




// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// :::::::::::::::::::::::::::::: EDIT A BUSINESS  ::::::::::::::::::::::::::::::::::::::::::::::
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

if (strtoupper($_POST["submit"]) == "EDIT"){
	$Query = "UPDATE neighborhoods SET 
								neighborhood_name = '{$_SESSION["formVars"]["neighborhood_name"]}',
								city_id = {$_SESSION["formVars"]["city_id"]}
							WHERE neighborhood_id = {$_SESSION["formVars"]["id"]};";

	$Results = $db->Execute($Query);
	
	unset($_SESSION["formVars"]);
	unset($_SESSION["formErrors"]);
	header( 'Location: ' . $_POST["successURL"] . '?id=' . $_POST["id"] );
}

?>