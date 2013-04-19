<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
unset($_SESSION["bounceFromErrors"]);

$sortby = "theOrder, id"; // default sort field 
$sortDir = "ASC"; // default sort order

if (isset($_GET["dir"])){
	$sortDir = "DESC";
}
if (isset($_GET["sortby"])){
	$sortby = $_GET["sortby"];
}

unset($_SESSION["formNeedsCorrection"]);
unset($_SESSION["formVars"]);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Admin - Banners</title>  	
    <link rel="stylesheet" href="../includes/templates/Projectal/css/html5reset-1.6.1.css" />
  	<script src="../includes/templates/Projectal/scripts/modernizr-1.5.min.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
    <script type="text/javascript" src="includes/jquery.tablednd.0.7.min.js"></script>
    <script type="text/javascript" src="includes/RecordSortable.js"></script> 
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
		var rev = "width: 700px; height: 450px; scrolling: auto;border:3px solid #CC0000;"
		var url = "banners_edit.php"
		var title = "ADD BANNER"
		startLytebox(url,title,rev) 
	}
	function doEdit(id){	
		var rev = "width: 900px; height: 550px; scrolling: auto;border:3px solid #CC0000;"
		var url = "banners_edit.php?id=" + id
		var title = "EDIT BANNER"
		startLytebox(url,title,rev) 
	}
	
	function doDelete(id){
		var rev = "width: 500px; height: 200px; scrolling: auto;border:3px solid #CC0000;"
		var url = "banners_delete.php?id=" + id
		var title = "DELETE BANNER"
		startLytebox(url,title,rev) 
	}
	$(document).ready(function(){
		// MAKE REORDERABLE
		initTableDnD($("#theDataTable"))
		function initTableDnD($table){
			var dragAndDropper = $table.RecordSortable({				
				dbTable: 'rotating_banner',
				dbIdLabel: 'id',	
				dbOrderLabel: 'theOrder',
				complete: function(){},
				error: function(event, data){ alert("ERROR: "+data["msg"]);},
			});
		}
	});

	</script>
</head>
<body onLoad="activateNav('nav7')">
<a id="lytebox_misc"></a>
<? include("includes/inc_header.php"); ?>

<input type="button" value="Add New Banner" style="float:right;margin-left:5px;" class="blackBtn" onclick="doAdd()" />

<h1>Banners</h1>

<BR /><BR />
<div class="dataTable">
<table id="theDataTable">
	<thead>
	<tr>
        <?
		// "createColumnHeader()" included in formScripts.php
		createColumnHeader('is Active','isActive');
		createColumnHeader('Title','title');
		createColumnHeader('Content','content');
		createColumnHeader('delay','delay');
		?>   
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?PHP
	
		$Query = "SELECT * FROM rotating_banner ORDER BY $sortby $sortDir";
		$Results = $db->Execute($Query);
		 while (!$Results->EOF) {	
			  $id = $Results->fields['id'];
			  $isActive = $Results->fields['isActive'];
			  $title = $Results->fields['title'];
			  $content = strip_tags($Results->fields['content']);
			  $delay = $Results->fields['delay'];
			  $isHTML =  $Results->fields['isHTML'];
			  if($isHTML == 1){
				  $content = "HTML Content";
			  } else {
			  	if(count($content) > 100) $content = substr($content,0,100) . "...";
			  }
			  
			  
			  ?>
			  <tr onclick="" id="row_<?=$id; ?>">
				<td class="checkboxCell"><div id="isActive_<?=$id; ?>"><input type='checkbox' name='isActive' value='1' <?php if($isActive == 1) print "checked"; ?> /></div></td>
				<td><div id="title_<?=$id; ?>"><?= $title; ?></div></td>
                <td>
                <div id="content_<?=$id; ?>">
                <? 
				if ($isHTML == 1){
                	print stripslashes($content);
				} else {
					print '<img src="../' . ($content) . '" style="height:40px;">';
				}
				?>
                </div>
                </td>
                <td><div id="delay_<?=$id; ?>"><?= $delay; ?></div></td>
				<td class="editButtons">
                    
                    <div id="buttons_<?=$id; ?>">
                    <a onclick = "doEdit(<?=$id; ?>);" class="yellowBtn">edit</a>
                    <a onclick = "doDelete(<?= $id; ?>);" class="yellowBtn">delete</a>
              		</div>
                    
                </td>
			</tr>          
			<? 
			$Results->MoveNext();
		} 
		?>
        <div id="newRows"></div>
    </tbody>
</table>
</div>


	    

<? include_once("includes/inc_footer.php"); ?>

</body>
</html>
