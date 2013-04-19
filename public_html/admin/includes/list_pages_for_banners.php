<?
$pathToRoot = "../";
include_once("../includes/admin_init.php");
$pageArr = array();
$Query = "SELECT products_name, products_id FROM zen_products_description 
			WHERE products_name LIKE '%".  mysql_real_escape_string($_GET["q"])."%' 
			ORDER BY products_name";
$Results = $db->Execute($Query);
while (!$Results->EOF) {
	$productArray = array();	
	$productArray["name"] = $Results->fields['products_name'];
	$productArray["id"] = $Results->fields['products_id'];
	array_push($pageArr,$productArray);
	
	$Results->MoveNext();
}
print json_encode($pageArr)

?>
