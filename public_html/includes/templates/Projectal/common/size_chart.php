
<? 
$categoryID = 0;
// try to determine which category we're in
if(strpos($_GET['products_id'],":") > 0){
	$theProductID =substr($_GET['products_id'],0,strpos($_GET['products_id'],":"));
} else {
	$theProductID =$_GET['products_id'];
}
$query = "SELECT categories_id FROM zen_products_to_categories WHERE products_id = ".$theProductID;
$Results = $db->Execute($query);
while (!$Results->EOF) {			
	$categoryID = $Results->fields['categories_id'];		
	$Results->MoveNext();
}	

if ($categoryID==67){ 
	$sizeChartDescription="Women's Sizing Chart"; 
	$img =  "Womens-Sizing-Chart.jpg";   
} else if ($categoryID==66 || $categoryID == 0){ 
	$sizeChartDescription="Men's Sizing Chart";
	$img =  "Mens-Sizing-Chart.jpg"; 
} else {
	$sizeChartDescription="Sizing Chart";
	$img =  "Mens-Sizing-Chart.jpg"; 
}

?>

<a class="lytebox" 
    data-title="<?php echo $sizeChartDescription; ?>" 
    href="includes/templates/Projectal/images/<?php echo $img; ?>" 
    style="cursor: help; position: relative;" 
	id="sizingChartLink">
Sizing Chart</a>

<div class="sizeChart" id="<?php echo $sizeChartId; ?>"></div>
