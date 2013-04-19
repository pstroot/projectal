<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
include_once("includes/imageUploadFunctions.php");





$product_id = $_GET["pID"];
$option_id = $_GET["oID"];
$IMAGES_DIRECTORY = "images/";
$PATH_TO_IMAGES_DIR = "../";

$smallFoldername = "";
$tinyFoldername = "tiny/";
$mediumFoldername = "medium/";
$largeFoldername = "large/";

$smallSuffix = "";
$tinySuffix = "_TINY";
$mediumSuffix = "_MED";
$largeSuffix = "_LRG";

// Get teh product name, product id, and the attributes image assigned to this product attribute.
$Query = "SELECT DISTINCT p.products_id,p.products_image, pd.products_name, zpa.attributes_image
					FROM zen_products p
					JOIN zen_products_description pd ON pd.products_id = p.products_id
					LEFT JOIN zen_products_attributes zpa ON zpa.products_id = p.products_id
					WHERE p.products_id = $product_id AND zpa.options_values_id = $option_id";
$Results = $db->Execute($Query);
$products_name = $Results->fields['products_name'];
$products_id = $Results->fields['products_id'];
// make sure an attribute image exists before proceeding any further
if($Results->fields['attributes_image'] != "" || $Results->fields['products_image'] != ""){
	
	$src = $Results->fields['products_image'];
	if ($Results->fields['attributes_image'] != ""){
		$src = $Results->fields['attributes_image'];	
	}
	//$src = str_replace("images/","",$src);			
	$extension = substr($src, strrpos($src, '.'));			
	$base = str_replace($extension, '', $src);
	
	
	$tinyImage =   calculateOptimizedImage($src,"TINY");
	$smallImage =  calculateOptimizedImage($src,"SMALL");
	$mediumImage = calculateOptimizedImage($src,"MEDIUM");
	$largeImage =  calculateOptimizedImage($src,"LARGE");
	
	if($tinyImage){
		$displayImage = $tinyImage;
		$largestImage = $tinyImage;
	}
	
	if($smallImage){
		$displayImage = $smallImage;
		if(!isset($largestImage) || getImageWidth($PATH_TO_IMAGES_DIR . $IMAGES_DIRECTORY . $smallImage) > getImageWidth($PATH_TO_IMAGES_DIR . $IMAGES_DIRECTORY . $largestImage)){
			$largestImage = $smallImage;
		}
	}
	if($mediumImage){
		if(!isset($displayImage)) $displayImage = $mediumImage;
		if(!isset($largestImage) ||getImageWidth($PATH_TO_IMAGES_DIR . $IMAGES_DIRECTORY . $mediumImage) > getImageWidth($PATH_TO_IMAGES_DIR . $IMAGES_DIRECTORY . $largestImage)){
			$largestImage = $mediumImage;
		}
	}
	if($largeImage){
		if(!isset($displayImage)) $displayImage = $largeImage;
		if(!isset($largestImage) ||getImageWidth($PATH_TO_IMAGES_DIR . $IMAGES_DIRECTORY . $largeImage) > getImageWidth($PATH_TO_IMAGES_DIR . $IMAGES_DIRECTORY . $largestImage)){
			$largestImage = $largeImage;	
		}
	}
	
	$tinyName = $base . $tinySuffix . $extension;
	$smallName = $IMAGES_DIRECTORY . $smallFoldername . $base . $smallSuffix . $extension;
	$mediumName = $IMAGES_DIRECTORY . $mediumFoldername . $base . $mediumSuffix . $extension;
	$largeName = $IMAGES_DIRECTORY . $largeFoldername . $base . $largeSuffix . $extension;
	
	$baseDirectory = substr($base,0,strrpos($base,"/"));
	
}



// GET WIDTHS AND HEIGHTS

$Query = "SELECT configuration_key,configuration_value
					FROM zen_configuration
					WHERE configuration_key = 'SMALL_IMAGE_WIDTH' or
					 configuration_key = 'SMALL_IMAGE_HEIGHT' or
					 configuration_key = 'MEDIUM_IMAGE_WIDTH' or
					 configuration_key = 'MEDIUM_IMAGE_HEIGHT' or
					 configuration_key = 'IMAGE_SHOPPING_CART_WIDTH' or
					 configuration_key = 'IMAGE_SHOPPING_CART_HEIGHT'";
$Results = $db->Execute($Query);
$CONFIG = array();
while (!$Results->EOF) {	
	$CONFIG[$Results->fields['configuration_key']] = $Results->fields['configuration_value'];
	$Results->MoveNext();
} 
// FOR THE SHOPPING CART
$tinyWidth = $CONFIG["IMAGE_SHOPPING_CART_WIDTH"];
$tinyHeight = $CONFIG["IMAGE_SHOPPING_CART_HEIGHT"];
// FOR THE FEATURED PRODUCTS AND HOME PAGE GRID
$smallWidth = $CONFIG["SMALL_IMAGE_WIDTH"];
$smallHeight = $CONFIG["SMALL_IMAGE_HEIGHT"];
// FOR THE PRODUCT INFO PAGE
$mediumWidth = $CONFIG["MEDIUM_IMAGE_WIDTH"];
$mediumHeight = $CONFIG["MEDIUM_IMAGE_HEIGHT"];
// FOR THE ZOOM LARGE PAGE
$largeWidth = 800;
$largeHeight = 800;

if($tinyWidth <= 0) $tinyHeight = 200;
if($tinyHeight <= 0) $tinyHeight = 200;
if($smallWidth <= 0) $smallWidth = 400;
if($smallHeight <= 0) $smallHeight = 400;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Admin - Updating Images For <?=$products_name;?></title>  	
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css" />
  	<script src="../scripts/modernizr-1.5.min.js"></script>
    <link href="includes/adminstyles.css" rel="stylesheet" type="text/css" />
    <script src="includes/adminScripts.js"></script> 	
</head>
<body id="popup">
<h1>Updating Images For <?=$products_name;?></h1>
<div class="popupContent">

<?

include_once("includes/ImageMagick_noUpload_class.php");	
// make the tiny size image
$largestImageFullPath = $PATH_TO_IMAGES_DIR . $IMAGES_DIRECTORY . $largestImage;
$imObj = new ImageMagick_noUpload($largestImageFullPath);

$imObj -> setTargetdir($PATH_TO_IMAGES_DIR . $IMAGES_DIRECTORY . $tinyFoldername . $baseDirectory);
$imObj -> Convert('png');
//$imObj -> setVerbose(true);

$imObj -> Resize($largeWidth,$largeHeight, 'keep_aspect');
$largeURL = $imObj -> Save($base . $largeSuffix . $extension,$PATH_TO_IMAGES_DIR . $IMAGES_DIRECTORY . $largeFoldername);
$largeFullPath = $PATH_TO_IMAGES_DIR . $IMAGES_DIRECTORY . $largeFoldername . $largeURL;
list($width, $height, $type, $attr) = getimagesize($largeFullPath);
print "Created Large Image at ".$width." x ".$height.": <a href='" . $largeFullPath . "' target='_blank'>" . $largeURL . "</a><BR>";
//print "<img src='" . $PATH_TO_IMAGES_DIR . $IMAGES_DIRECTORY . $largeFoldername . $largeURL . "'><BR>";
print "<BR>";

$imObj -> Resize($mediumWidth,$mediumHeight, 'keep_aspect');
$mediumURL = $imObj -> Save($base . $mediumSuffix . $extension,$PATH_TO_IMAGES_DIR . $IMAGES_DIRECTORY . $mediumFoldername);
$mediumFullPath = $PATH_TO_IMAGES_DIR . $IMAGES_DIRECTORY . $mediumFoldername . $mediumURL;
list($width, $height, $type, $attr) = getimagesize($mediumFullPath);
print "Created Medium Image at ".$width." x ".$height.": <a href='" . $mediumFullPath . "' target='_blank'>" . $mediumURL . "</a><BR>";
//print "<img src='" . $PATH_TO_IMAGES_DIR . $IMAGES_DIRECTORY . $mediumFoldername . $mediumURL . "'><BR>";
print "<BR>";

$imObj -> Resize($smallWidth,$smallHeight, 'keep_aspect');
$smallURL = $imObj -> Save($base . $smallSuffix . $extension,$PATH_TO_IMAGES_DIR . $IMAGES_DIRECTORY . $smallFoldername);
$smallFullPath = $PATH_TO_IMAGES_DIR . $IMAGES_DIRECTORY . $smallFoldername . $smallURL;
list($width, $height, $type, $attr) = getimagesize($smallFullPath);
print "Created Small Image at ".$width." x ".$height.": <a href='" . $smallFullPath . "' target='_blank'>" . $smallURL . "</a><BR>";
//print "<img src='" . $PATH_TO_IMAGES_DIR . $IMAGES_DIRECTORY . $smallFoldername . $smallURL . "'><BR>";
print "<BR>";

$imObj -> Resize($tinyWidth,$tinyHeight, 'keep_aspect');
$tinyURL = $imObj -> Save($base . $tinySuffix . $extension,$PATH_TO_IMAGES_DIR . $IMAGES_DIRECTORY . $tinyFoldername);
$tinyFullPath = $PATH_TO_IMAGES_DIR . $IMAGES_DIRECTORY . $tinyFoldername . $tinyURL;
list($width, $height, $type, $attr) = getimagesize($tinyFullPath);
print "Created Tiny Image at ".$width." x ".$height.": <a href='" . $tinyFullPath . "' target='_blank'>" . $tinyURL . "</a><BR>";
//print "<img src='" . $PATH_TO_IMAGES_DIR . $IMAGES_DIRECTORY . $tinyFoldername . $tinyURL . "'><BR>";
print "<BR>";


$imObj -> CleanUp();
?>

<!--
<b>Image Directory:</b>
<?=$IMAGES_DIRECTORY;?><BR />

<b>Base Directory:</b>
<?=$baseDirectory;?><BR />

<b>Largest Image:</b>
<?=$largestImage;?><BR />

<b>Tiny:</b>
<?=$tinyName;?><BR />

<b>Small:</b>
<?=$smallName;?><BR />

<b>Medium:</b>
<?=$mediumName;?><BR />

<b>Large:</b>
<?=$largeName;?><BR />

    <img src="../<?= $PATH_TO_IMAGES_DIR . $IMAGES_DIRECTORY . $displayImage; ?>" /></td>
-->
</div>

</div>
</body>
</html>

<?

function calculateOptimizedImage($src,$size){
	global $tinyFoldername,$smallFoldername,$mediumFoldername,$largeFoldername;
	global $tinySuffix,$smallSuffix,$mediumSuffix,$largeSuffix;
	global $PATH_TO_IMAGES_DIR, $IMAGES_DIRECTORY;
	$src = str_replace($IMAGES_DIRECTORY,"",$src);			
	$extension = substr($src, strrpos($src, '.'));			
	$base = str_replace($extension, '', $src);
	$dir = $PATH_TO_IMAGES_DIR . $IMAGES_DIRECTORY;
			
	if(strtoupper($size) == "MEDIUM"){
		$src_resized = $base . $mediumSuffix . $extension;
		$imageDir = $mediumFoldername;
	}else if(strtoupper($size) == "LARGE"){
		$src_resized = $base . $largeSuffix . $extension;
		$imageDir = $largeFoldername;
	}else if(strtoupper($size) == "TINY"){
		$src_resized = $base . $tinySuffix . $extension;
		$imageDir = $tinyFoldername;
	}else if(strtoupper($size) == "SMALL"){
		$src_resized = $base . $smallSuffix . $extension;
		$imageDir = $smallFoldername;
	}else{
		$src_resized = $src;
		$imageDir = "";
	}
	// check for a medium image else use small
	if (file_exists($dir . $imageDir . $src_resized)) {
		return  $imageDir . $src_resized;
	} else if (file_exists($dir . $imageDir . $src)) {
		 return  $imageDir . $src;
	} else {
		return false;
	}
}


function getImageWidth($src){
	list($width, $height, $type, $attr) = getimagesize($src);
	return $width;
}

?>