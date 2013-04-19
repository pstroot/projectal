<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
unset($_SESSION["bounceFromErrors"]);

$sortby = "theme_name"; // default sort field 
$sortDir = "ASC"; // default sort order

if (isset($_GET["dir"])){
	$sortDir = "DESC";
}
if (isset($_GET["sortby"])){
	$sortby = $_GET["sortby"];
}


if (isset($_GET["setActiveState"])){
	$Query = "UPDATE themes SET isActive = {$_GET["setActiveState"]} WHERE theme_id = {$_GET["id"]}";
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
    <title>Admin - Themes</title>  	
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
		var rev = "width: 500px; height: 200px; scrolling: auto;border:3px solid #CC0000;"
		var url = "business_theme_edit.php"
		var title = "ADD NEW THEME"
		startLytebox(url,title,rev) 
	}
	function doEdit(id){	
		var rev = "width: 500px; height: 200px; scrolling: auto;border:3px solid #CC0000;"
		var url = "business_theme_edit.php?id=" + id
		var title = "EDIT THEME"
		startLytebox(url,title,rev) 
	}
	
	function doDelete(id){
		var rev = "width: 500px; height: 200px; scrolling: auto;border:3px solid #CC0000;"
		var url = "business_theme_delete.php?id=" + id
		var title = "DELETE BUSINESS THEME"
		startLytebox(url,title,rev) 
	}
	

	
	</script>
</head>
<body onLoad="activateNav('nav1')">
<a id="lytebox_misc"></a>
<? include("includes/inc_header.php"); ?>

<input type="button" value="Add New Business Theme" style="float:right;margin-left:5px;" class="blackBtn" onclick="doAdd()" />
<h1>Business Themes</h1>

<div class="dataTable">
<table id="theDataTable">
	<tr>
        <?
		// "createColumnHeader()" included in formScripts.php
		createColumnHeader('Active','isActive');
		createColumnHeader('Name','theme_name');
		?>   
        <th></th>
    </tr>
    
    <?PHP
		$Query = "SELECT * FROM themes ORDER BY $sortby $sortDir";
		$Results = $db->Execute($Query);
		 while (!$Results->EOF) {	
			  $id = $Results->fields['theme_id'];
			  $name = $Results->fields['theme_name'];
			  $isActive =  $Results->fields['isActive'];
			  ?>
			  <tr onclick="" id="row_<?=$id; ?>">
				<td align="center"><div id="active_<?=$id; ?>"><input type="checkbox" value="<?=$id;?>" <? if($isActive == 1) print "checked"; ?> onChange="changeActiveState(this)"></div></td>
                <td><div id="name_<?=$id; ?>"><?= $name; ?></div></td>
				<td style="text-align:right;white-space:nowrap;">
                    <div id="buttons_<?=$id; ?>">
                    <a onclick = "doEdit(<?=$id; ?>);" class="yellowBtn">edit</a>
                    <a onclick = "doDelete(<?=$id; ?>);" class="yellowBtn">delete</a>
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
