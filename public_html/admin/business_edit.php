<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
include_once("includes/imageUploadFunctions.php");



if(isset($_GET["id"]) && !isset($_SESSION["bounceFromErrors"])){
	$Query = "SELECT * FROM businesses WHERE business_id = " . $_GET["id"];
	$Results = $db->Execute($Query);
	while (!$Results->EOF) {	
		  $_SESSION["formVars"]["business_name"] = $Results->fields['business_name'];
		  $_SESSION["formVars"]["business_website"] = $Results->fields['business_website'];
		  $_SESSION["formVars"]["business_description"] = stripslashes($Results->fields['business_description']);
		  $_SESSION["formVars"]["business_id"] =  $Results->fields['business_id'];
		  $_SESSION["formVars"]["business_logo"] =  $Results->fields['business_logo'];
		  $_SESSION["formVars"]["business_image"] =  $Results->fields['business_image'];
		  $_SESSION["formVars"]["business_address"] =  $Results->fields['business_address'];
		  $_SESSION["formVars"]["business_city"] =  $Results->fields['business_city'];
		  $_SESSION["formVars"]["business_state"] =  $Results->fields['business_state'];
		  $_SESSION["formVars"]["business_zip"] =  $Results->fields['business_zip'];
		  $_SESSION["formVars"]["isActive"] =  $Results->fields['isActive']; 
		  $Results->MoveNext();
	}
	
	$neighborhoodArray = array();
	$Query = "SELECT * FROM business_to_neighborhood WHERE business_id = " . $_GET["id"];
	$Results = $db->Execute($Query);
	while (!$Results->EOF) {	
		  array_push($neighborhoodArray,$Results->fields['neighborhood_id']);
		  $Results->MoveNext();
	}
	$typeArray = array();
	$Query = "SELECT * FROM business_to_type WHERE business_id = " . $_GET["id"];
	$Results = $db->Execute($Query);
	while (!$Results->EOF) {	
		  array_push($typeArray,$Results->fields['type_id']);
		  $Results->MoveNext();
	}
	$themeArray = array();
	$Query = "SELECT * FROM business_to_theme WHERE business_id = " . $_GET["id"];
	$Results = $db->Execute($Query);
	while (!$Results->EOF) {	
		  array_push($themeArray,$Results->fields['theme_id']);
		  $Results->MoveNext();
	}
	

}

if(isset($_GET["id"])) {
	$pageTitle = "Edit \"" . @stripslashes($_SESSION["formVars"]["business_name"]) . "\"";
	$buttonLabel = "Edit";
	$successURL = "business_updated.php";
} else {
	$pageTitle = "Add New Business";
	$buttonLabel = "Add";
	$successURL = "business_added.php";
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
 	
</head>
<body id="popup">
<h1><?=$pageTitle?></h1>
<div class="popupContent">


<form action="business_submit.php" method="post" enctype="multipart/form-data">
<div class="formTable">
<table>

	<tr>
        <th>Name</th>        
        <td colspan="3"><input type="text" name="business_name" id="business_name" value="<?=@str_replace('"','&quot;',stripslashes($_SESSION["formVars"]["business_name"]));?>" /><?=required("business_name");?></td>
    </tr>

	<tr>
        <th>Address</th>        
        <td colspan="3"><input type="text" name="business_address" value="<?=@$_SESSION["formVars"]["business_address"];?>" /></td>
    </tr>

	<tr>
        <th>City</th>        
        <td colspan="3"><input type="text" name="business_city" value="<?=@$_SESSION["formVars"]["business_city"];?>" /></td>
    </tr>

	<tr>
        <th>State</th>        
        <td><?= addState(@$_SESSION["formVars"]["business_state"],"business_state"); ?></td>

        <th>Zip</th>        
        <td><input type="text" name="business_zip" value="<?=@$_SESSION["formVars"]["business_zip"];?>" style="width:150px;" /></td>
    </tr>

	<tr>
        <th>Phone</th>        
        <td colspan="3"><input type="text" name="business_phone" value="<?=@$_SESSION["formVars"]["business_phone"];?>" /></td>
    </tr>

	<tr>
        <th>Website</th>        
        <td colspan="3"><input type="text" name="business_website" value="<?=@$_SESSION["formVars"]["business_website"];?>" /></td>
    </tr>

	<tr>
        <th>Description</th>        
        <td colspan="3"><textarea name="description"><?=@$_SESSION["formVars"]["business_description"];?></textarea></td>
    </tr>

	<tr>
        <th>Business Type</th>        
        <td colspan="3"><select name="business_type[]" multiple size='4'>
        <?
        $Query = "SELECT * FROM business_types ORDER BY type_name";
		$Results = $db->Execute($Query);
		while (!$Results->EOF) {	
		  $type_name = $Results->fields['type_name'];
		  $type_id = $Results->fields['type_id'];
		  $selected = '';
		  if(in_array($type_id,$typeArray)) $selected = 'selected';
		  print "<option value='$type_id' $selected>$type_name</option>\n";
		  $Results->MoveNext();
		} 
		?>
        </select></td>
    </tr>

	<tr>
        <th>Neighborhood</th>        
        <td colspan="3"><select name="business_neighborhood[]">
        <option value="">NONE</option>
        <?
        $Query = "SELECT * FROM neighborhoods ORDER BY neighborhood_name";
		$Results = $db->Execute($Query);
		while (!$Results->EOF) {	
		  $neighborhood_name = $Results->fields['neighborhood_name'];
		  $neighborhood_id = $Results->fields['neighborhood_id'];
		  $selected = '';
		  if(in_array($neighborhood_id,$neighborhoodArray)) $selected = 'selected';
		  print "<option value='$neighborhood_id' $selected>$neighborhood_name</option>\n";
		  $Results->MoveNext();
		} 
		?>
        </select></td>
    </tr>

	<tr>
        <th>Minnesota Theme</th>        
        <td colspan="3"><select name="business_theme[]">
        <option value="">NONE</option>
        <?
        $Query = "SELECT * FROM themes ORDER BY theOrder";
		$Results = $db->Execute($Query);
		while (!$Results->EOF) {	
		  $theme_name = $Results->fields['theme_name'];
		  $theme_id = $Results->fields['theme_id'];
		  $selected = '';
		  if(in_array($theme_id,$themeArray)) $selected = 'selected';
		  print "<option value='$theme_id' $selected>$theme_name</option>\n";
		  $Results->MoveNext();
		} 
		?>
        </select></td>
    </tr>

	<tr>
        <th>Logo</th>        
        <td colspan="3"><? imageUploadForm(@$_SESSION["formVars"]["business_logo"],"business_logo","../",'',400,120); ?></td>
    </tr>

	<tr>
        <th>Image</th>        
        <td colspan="3"><? imageUploadForm(@$_SESSION["formVars"]["business_image"],"business_image","../","Please scale image to 459 x 103 pixels before uploading.",400,120); ?></td>
    </tr>

	<tr>
        <th></th>        
        <td colspan="3"><input type="submit" id="submit" name="submit" value="<?=$buttonLabel?>" class="blackBtn"/></td>
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
