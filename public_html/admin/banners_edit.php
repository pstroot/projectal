<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
include_once("includes/imageUploadFunctions.php");

if(isset($_GET["id"]) && !isset($_SESSION["bounceFromErrors"])){
	$Query = "SELECT * FROM rotating_banner WHERE id = " . $_GET["id"];
	$Results = $db->Execute($Query);
	while (!$Results->EOF) {	
		  $_SESSION["formVars"]["title"] = $Results->fields['title'];
		  $_SESSION["formVars"]["content"] = $Results->fields['content'];
		  $_SESSION["formVars"]["isActive"] = $Results->fields['isActive'];
		  $_SESSION["formVars"]["isHTML"] = $Results->fields['isHTML'];
		  $_SESSION["formVars"]["delay"] = $Results->fields['delay'];
		  $_SESSION["formVars"]["link"] = $Results->fields['link'];
		  $Results->MoveNext();
	}



	$pageArr = array();
	$Query = "SELECT  products_name, products_id FROM zen_products_description d
				LEFT JOIN rotating_banner_placement p ON p.page_id = d.products_id
				WHERE p.banner_id = " . $_GET["id"] . ";";
	$Results = $db->Execute($Query);
	while (!$Results->EOF) {
		$productArray = array();	
		$productArray["name"] = $Results->fields['products_name'];
		$productArray["id"] = $Results->fields['products_id'];
		array_push($pageArr,$productArray);
		
		$Results->MoveNext();
	}
}

if(isset($_GET["id"])) {
	$pageTitle = "Edit \"" . @$_SESSION["formVars"]["title"] . "\"";
	$buttonLabel = "Edit";
	$successURL = "banners_updated.php";
} else {
	$pageTitle = "Add New Banner";
	$buttonLabel = "Add";
	$successURL = "banners_added.php";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Admin - <?=$pageTitle?></title>  	
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css" />
  	<script src="../scripts/modernizr-1.5.min.js"></script>
    <link href="includes/adminstyles.css" rel="stylesheet" type="text/css" />
    <script src="includes/adminScripts.js"></script>
	<script type="text/javascript" src="../includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript" src="../includes/tinymce/tinymceScripts.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    
	<script type="text/javascript" src="includes/tokeninput/src/jquery.tokeninput.js"></script>
    <link rel="stylesheet" type="text/css" href="includes/tokeninput/styles/token-input.css" />
    <link rel="stylesheet" href="includes/tokeninput/styles/token-input-facebook.css" type="text/css" />



 	<script>
	$(document).ready(function(){
		
		
		$("#which-pages").tokenInput("includes/list_pages_for_banners.php", {
			hintText: "Enter a Product Name",
			theme: "facebook",
			preventDuplicates: true,
			prePopulate: <?php echo @json_encode($pageArr); ?>
		});
	
		$('#isHTML, #isNotHTML').change(function() {
			if($(this).is(':checked'))  {
				checkHTMLStatus();
			}		
		});
		checkHTMLStatus()
		function checkHTMLStatus(){
			if($('#isHTML').is(':checked'))  {
				showContent()
			} else {
				showImageUpload()
			}
		}
		
		function showContent(){
			$('#contentEdit').show();
			$('#ImageEdit').hide();
		}
		function showImageUpload(){
			$('#contentEdit').hide();
			$('#ImageEdit').show();
		}
	});
	</script>
    
</head>
<body id="popup">
<h1><?=$pageTitle?></h1>
<div class="popupContent">


<form action="banners_submit.php" method="post" enctype="multipart/form-data" accept-charset="character_set">
<div class="formTable">
<table>
	<tr>
        <th style="width:50px;">Title</th>        
        <td style="white-space:nowrap;"><input type="text" name="title" id="title" value="<?=@$_SESSION["formVars"]["title"];?>" /><?=required("title");?></td>
        <td>For easily identifying in the CMS. If uploading an image, the title will be come the ALT tag.</td>
    </tr>
	<tr>
        <th>is Active</th>        
        <td>
            <input type="radio" name="isActive" id="isActive" value="1" <? if(@$_SESSION["formVars"]["isActive"] != 0) print "checked";?> />YES
            <input type="radio" name="isActive" id="isActive" value="0" <? if(@$_SESSION["formVars"]["isActive"] == 0) print "checked";?> />NO
        </td>
        <td></td>
    </tr>
	<tr>
        <th>is HTML</th>        
        <td>
            <input type="radio" name="isHTML" id="isHTML" value="1" <? if(@$_SESSION["formVars"]["isHTML"] == 1) print "checked";?> />YES
            <input type="radio" name="isHTML" id="isNotHTML" value="0" <? if(@$_SESSION["formVars"]["isHTML"] != 1) print "checked";?>/>NO
        </td>
        <td></td>
    </tr>
	<tr id="contentEdit">
        <th>Content</th>        
        <td style="white-space:nowrap;"><textarea name="content" id="content" style="height:200px;font-size:10px;"/><?=@$_SESSION["formVars"]["content"];?></textarea></td>
        <td>Enter HTML input here. Dispaly area will be restricted to 939 x 188 pixels. The following placeholders are allowed:<BR />
			<div style="width:200px;display:inline-block;">::PRODUCT_ID::</div>
			<div style="width:200px;display:inline-block;">::PRODUCT_CPATH::</div>
			<div style="width:200px;display:inline-block;">::PRODUCT_NAME::</div>
			<div style="width:200px;display:inline-block;">::PRODUCT_PRICE::</div>
			<div style="width:200px;display:inline-block;">::PRODUCT_MODEL::</div>
			<div style="width:200px;display:inline-block;">::PRODUCT_DESCRIPTION::</div>
			<div style="width:200px;display:inline-block;">::PRODUCT_MANUFACTURER::</div>
			<div style="width:200px;display:inline-block;">::BUSINESS_NAME::</div>
			<div style="width:200px;display:inline-block;">::BUSINESS_WEBSITE::</div>
			<div style="width:200px;display:inline-block;">::BUSINESS_ID::</div>
			<div style="width:200px;display:inline-block;">::BUSINESS_ADDRESS::</div>
			<div style="width:200px;display:inline-block;">::BUSINESS_CITY::</div>
			<div style="width:200px;display:inline-block;">::BUSINESS_STATE::</div>
			<div style="width:200px;display:inline-block;">::BUSINESS_ZIP::</div>
			<div style="width:200px;display:inline-block;">::BUSINESS_LOGO::</div>
         </td>
    </tr>
	<tr id="ImageEdit">
        <th>Image</th>        
        <td colspan="2">
        	<? $imgContent = @$_SESSION["formVars"]["isHTML"] == 1 ? "" : @$_SESSION["formVars"]["content"]; ?>
            <? imageUploadForm($imgContent,"banner_image","../","Please scale image to 939 x 188 pixels before uploading.",400,120); ?>
        </td>
    </tr>
	<tr>
        <th>Link</th>        
        <td><input type="text" name="link" id="link" value="<?=@$_SESSION["formVars"]["link"];?>" /></td>
        <td>Redirect the user to here if the banner is clicked. Leave blank if the link is embedded in custom HTML, or if you do not want to link the banner.</td>
    </tr>
	<tr>
        <th>Delay</th>        
        <td><input type="text" name="delay" id="delay" value="<?=@$_SESSION["formVars"]["delay"];?>"  style="width:30px;"/><?=required("delay");?></td>
        <td>The number of seconds this banner will stay on screen before transitioning to the next</td>
    </tr>
	<tr>
        <th>Pages</th>        
        <td><input name="which-pages" id="which-pages" type="text" /></td>
        <td>Leave blank to display on all product pages</td>
    </tr>

	<tr>
        <th></th>        
        <td><input type="submit" id="submit" name="submit" value="<?=$buttonLabel?>" class="blackBtn"/></td>
    	<td></td>
    </tr>    
</table>
</div>
<input type="hidden" name="id" value="<?=@$_GET["id"];?>"/>
<input type="hidden" name="returnURL" value="<?=curPageURL()?>" />
<input type="hidden" name="successURL" value="<?=$successURL;?>"/>
</form>

</div>
</body>
</html>
