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


if (!count($errors) && $_SESSION["formVars"]["isHTML"] == 0){
	$_SESSION["formVars"]["content"] = imageUpload("banner_image","images/topbanner/","../");
	if($_SESSION["formVars"]["content"] === false){
		$errors["banner_image"] = "There was an error uploading your image";
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
// ::::::::::::::::::::::::::::::: ADD A BANNER  ::::::::::::::::::::::::::::::::::::::::::::::
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

if (strtoupper($_POST["submit"]) == "ADD"){	
	$theOrder = 0;
	$Query = "SELECT theOrder FROM rotating_banner ORDER BY theOrder DESC LIMIT 1";
	$Results = $db->Execute($Query);
	while (!$Results->EOF) {	
		$theOrder =  $Results->fields['theOrder'];
		$Results->MoveNext();
	}
	
	$Query = "INSERT INTO rotating_banner (title,content,link,theOrder,isActive,delay,isHTML) VALUES ('{$_SESSION["formVars"]["title"]}','{$_SESSION["formVars"]["content"]}','{$_SESSION["formVars"]["link"]}', $theOrder,{$_SESSION["formVars"]["isActive"]},{$_SESSION["formVars"]["delay"]},{$_SESSION["formVars"]["isHTML"]});";

	$Results = $db->Execute($Query);
	$bannerID =  mysql_insert_id();

}
 




// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// :::::::::::::::::::::::::::::: EDIT A BANNER  ::::::::::::::::::::::::::::::::::::::::::::::
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

if (strtoupper($_POST["submit"]) == "EDIT"){
	$Query = "UPDATE rotating_banner SET 
	title = '{$_SESSION["formVars"]["title"]}',
	content = '{$_SESSION["formVars"]["content"]}',
	link = '{$_SESSION["formVars"]["link"]}',
	delay = {$_SESSION["formVars"]["delay"]},
	isActive = {$_SESSION["formVars"]["isActive"]},
	isHTML = {$_SESSION["formVars"]["isHTML"]}
	 WHERE id = {$_SESSION["formVars"]["id"]};";

	$Results = $db->Execute($Query);
	$bannerID = $_SESSION["formVars"]["id"];
	
}

$Query = "DELETE FROM rotating_banner_placement WHERE banner_id = $bannerID ";
$Results = $db->Execute($Query);
if(isset($_SESSION["formVars"]["which-pages"]) && trim($_SESSION["formVars"]["which-pages"]) != ""){
	$pageIdArray = explode(",",$_SESSION["formVars"]["which-pages"]);
	foreach($pageIdArray as $pageID){
		$Query = "INSERT INTO rotating_banner_placement (banner_id,page_id,page) VALUES ($bannerID,$pageID,'product');";
		$Results = $db->Execute($Query);	
	}
		
}


unset($_SESSION["formVars"]);
unset($_SESSION["formErrors"]);
header( 'Location: ' . $_POST["successURL"] . '?id=' . $_POST["id"] );
header( 'Location: ' . $_POST["successURL"] . '?id=' . $bannerID );
?>