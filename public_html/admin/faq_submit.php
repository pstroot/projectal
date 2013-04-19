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


if (count($errors)){
	$_SESSION["formErrors"] = $errors;	
	$_SESSION["bounceFromErrors"] = true;
	print ("<meta http-equiv='refresh' content='0; URL=".$_POST["returnURL"]."'>");
	exit;
} 
unset($_SESSION["bounceFromErrors"]);

// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// ::::::::::::::::::::::::::::::: ADD A F.A.Q.  ::::::::::::::::::::::::::::::::::::::::::::::
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

if (strtoupper($_POST["submit"]) == "ADD"){	
	$theOrder = 0;
	$Query = "SELECT theOrder FROM faq ORDER BY theOrder DESC LIMIT 1";
	$Results = $db->Execute($Query);
	while (!$Results->EOF) {	
		$theOrder =  $Results->fields['theOrder'];
		$Results->MoveNext();
	}
	
	$Query = "INSERT INTO faq (cat_id,question,answer,theOrder)
							VALUES
								({$_SESSION["formVars"]["cat_id"]},
								'{$_SESSION["formVars"]["question"]}',
								'{$_SESSION["formVars"]["answer"]}',
								  $theOrder);";

	$Results = $db->Execute($Query);
	$theID = mysql_insert_id();

}
 




// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// :::::::::::::::::::::::::::::: EDIT A F.A.Q. :::::::::::::::::::::::::::::::::::::::::::::
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

if (strtoupper($_POST["submit"]) == "EDIT"){
	$theID = $_SESSION["formVars"]["id"];
	
	$Query = "UPDATE faq SET 
			  cat_id = {$_SESSION["formVars"]["cat_id"]},
			  question = '{$_SESSION["formVars"]["question"]}',
			  answer = '{$_SESSION["formVars"]["answer"]}'
			  WHERE faq_id = $theID;";

	$Results = $db->Execute($Query);
	
}


unset($_SESSION["formVars"]);
unset($_SESSION["formErrors"]);
header( 'Location: ' . $_POST["successURL"] . '?id=' . $theID);
?>