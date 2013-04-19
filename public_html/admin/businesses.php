<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
unset($_SESSION["bounceFromErrors"]);

//include_once("includes/init.php") ;
//include_once("form_validation.php") ;
$sortby = "business_name"; // default sort field 
$sortDir = "ASC"; // default sort order

if (isset($_GET["dir"])){
	$sortDir = "DESC";
}
if (isset($_GET["sortby"])){
	$sortby = $_GET["sortby"];
}


if (isset($_GET["setActiveState"])){
	$Query = "UPDATE businesses SET isActive = {$_GET["setActiveState"]} WHERE business_id = {$_GET["id"]}";
	$Results = $db->Execute($Query);
	header("location:" . $_GET["ref"]);
}

unset($_SESSION["formNeedsCorrection"]);
unset($_SESSION["formVars"]);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Admin - Businesses</title>  	
    <link rel="stylesheet" href="../includes/templates/Projectal/css/html5reset-1.6.1.css" />
  	<script src="../includes/templates/Projectal/scripts/modernizr-1.5.min.js"></script>
    <link href="includes/adminstyles.css" rel="stylesheet" type="text/css" />
    <script src="includes/adminScripts.js"></script>
    <!--[if IE]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script> 
    <script src="../includes/templates/Projectal/scripts/excanvas.js"></script>
    <![endif]-->
    <!--[if lte IE 6]> 
    <script defer type="text/javascript" src="../includes/templates/Projectal/scripts/pngfix.js"></script>
    <![endif]-->
	<script type="text/javascript" src="../includes/templates/Projectal/includes/lytebox/lytebox.js"></script>
    <link rel="stylesheet" href="../includes/templates/Projectal/includes/lytebox/lytebox.css" type="text/css" media="screen" />

    <script>
	function doAdd(){	
		var rev = "width: 600px; height: 700px; scrolling: auto;border:3px solid #CC0000;"
		var url = "business_edit.php"
		var title = "ADD BUSINESS"
		startLytebox(url,title,rev) 
	}
	function doEdit(id){	
		var rev = "width: 600px; height: 700px; scrolling: auto;border:3px solid #CC0000;"
		var url = "business_edit.php?id=" + id
		var title = "EDIT BUSINESS"
		startLytebox(url,title,rev) 
	}
	
	function doDelete(id){
		var rev = "width: 500px; height: 200px; scrolling: auto;border:3px solid #CC0000;"
		var url = "business_delete.php?id=" + id
		var title = "DELETE BUSINESS"
		startLytebox(url,title,rev) 
	}
	
	function doAssign(id){
		var rev = "width: 500px; height: 500px; scrolling: auto;border:3px solid #CC0000;"
		var url = "business_assign.php?id=" + id
		var title = "ASSIGN BUSINESS TO PRODUCTS"
		startLytebox(url,title,rev) 
	}
	
	</script>
</head>
<body onLoad="activateNav('nav1')">
<a id="lytebox_misc"></a>
<? include("includes/inc_header.php"); ?>

<input type="button" value="Add New Business" style="float:right;margin-left:5px;" class="blackBtn" onclick="doAdd()" />
<input type="button" value="Edit Neighborhoods" style="float:right;margin-left:5px;" class="blackBtn" onclick="window.location = 'neighborhoods.php';" />
<input type="button" value="Edit Business Types" style="float:right;margin-left:5px;" class="blackBtn" onclick="window.location = 'business_types.php';" />
<input type="button" value="Edit Themes" style="float:right;margin-left:5px;" class="blackBtn" onclick="window.location = 'business_themes.php';" />
<h1>Businesses</h1>

<div class="dataTable">
<table id="theDataTable">
	<tr>
        <?
		// "createColumnHeader()" included in formScripts.php
		createColumnHeader('Active','isActive');
		createColumnHeader('Name','business_name');
		createColumnHeader('Logo','business_logo');
		createColumnHeader('Address','business_address');
		createColumnHeader('City','business_city');
		createColumnHeader('Zip','business_zip');
		?>   
        <th></th>
    </tr>
    
    <?PHP
		$Query = "SELECT * FROM businesses ORDER BY $sortby $sortDir";
		$Results = $db->Execute($Query);
		 while (!$Results->EOF) {	
			  $business_name = $Results->fields['business_name'];
			  $business_website = $Results->fields['business_website'];
			  $business_description = $Results->fields['business_description'];
			  $business_id =  $Results->fields['business_id'];
			  $business_logo =  $Results->fields['business_logo'];
			  $business_image =  $Results->fields['business_image'];
			  $business_address =  $Results->fields['business_address'];
			  $business_city =  $Results->fields['business_city'];
			  $business_state =  $Results->fields['business_state'];
			  $business_zip =  $Results->fields['business_zip'];
			  $isActive =  $Results->fields['isActive'];
			  ?>
			  <tr onclick="" id="row_<?=$business_id; ?>">
				<td align="center"><div id="active_<?=$business_id; ?>"><input type="checkbox" value="<?=$business_id;?>" <? if($isActive == 1) print "checked"; ?> onChange="changeActiveState(this)"></div></td>
                <td><div id="name_<?=$business_id; ?>"><?= $business_name; ?></div></td>
                <td><div id="logo_<?=$business_id; ?>"><img src="../<?= stripslashes($business_logo); ?>" height="60"/></div></td>
                <td><div id="address_<?=$business_id; ?>"><?= stripslashes($business_address); ?></div></td>
                <td><div id="city_<?=$business_id; ?>"><?= stripslashes($business_city); ?></div></td>
                <td><div id="zip_<?=$business_id; ?>"><?= stripslashes($business_zip); ?></div></td>
				<td style="text-align:right;white-space:nowrap;">
                    <div id="buttons_<?=$business_id; ?>">
                    <a onclick = "doAssign(<?=$business_id; ?>);" class="yellowBtn">Assign to Products</a>
                    <a onclick = "doEdit(<?=$business_id; ?>);" class="yellowBtn">edit</a>
                    <a onclick = "doDelete(<?=$business_id; ?>);" class="yellowBtn">delete</a>
              		</div>
                </td>
			</tr>          
			<? 
			$Results->MoveNext();
		} ?>
        <div id="newRows"></div>
</table>
</div>

	    

<? include_once("includes/inc_footer.php"); ?>

</body>
</html>
