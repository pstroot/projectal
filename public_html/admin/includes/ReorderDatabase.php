<?php 
$pathToRoot = "../";
require_once("admin_init.php");
$output = array();
$errors = array();


if(!isset($_POST["table"])){
	$errors[] = "'table' was not passed in.";	
}
if(!isset($_POST["idLabel"])){
	$errors[] = "'idLabel' was not passed in.";	
}
if(!isset($_POST["data"])){
	$errors[] = "'data' was not passed in.";	
}
foreach($_POST as $key => $val){
	if(!is_array($_POST[$key])){
		$_POST[$key] = mysql_real_escape_string($val);	
	}
}
if(count($errors) == 0){	
	foreach($_POST["data"] as $val){
		$Query = "UPDATE " . $_POST["table"] . " SET " . $_POST["orderLabel"] . " = " . $val["order"] . " WHERE  " . $_POST["idLabel"] . " = " . $val["id"];			
		if(!$Results = @$db->Execute($Query)){
			$errors[] = "Could not update table with ID of " . $val["id"];
			break;
		}
	}	
}

if(count($errors) == 0){
	$output["result"] = "success";
} else {
	$output["result"] = "error";
	$output["errors"] = $errors;
}
echo json_encode($output);



?>