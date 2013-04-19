<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
unset($_SESSION["bounceFromErrors"]);

$sortby = "list_order, lastname"; // default sort field 
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
    <title>Admin - Subscriptions</title>  	
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
		var url = "_delete.php_edit.php"
		var title = "ADD SUBSCRIPTION"
		startLytebox(url,title,rev) 
	}
	function doEdit(id){	
		var rev = "width: 600px; height: 700px; scrolling: auto;border:3px solid #CC0000;"
		var url = "_delete.php_edit.php?id=" + id
		var title = "EDIT SUBSCRIPTION"
		startLytebox(url,title,rev) 
	}
	
	function doDelete(id){
		var rev = "width: 500px; height: 200px; scrolling: auto;border:3px solid #CC0000;"
		var url = "subscriptions_delete.php?id=" + id
		var title = "DELETE SUBSCRIPTION"
		startLytebox(url,title,rev) 
	}
	

	</script>
</head>
<body onLoad="activateNav('nav4')">
<a id="lytebox_misc"></a>
<? include("includes/inc_header.php"); ?>

<!--<input type="button" value="Add New Charity" style="float:right;margin-left:5px;" class="blackBtn" onclick="doAdd()" /> -->
<h1>Subscriptions</h1>

Note: This list is currently "View Only" Until we decide what we want to do with it.
<BR /><BR />
<div class="dataTable">
<table id="theDataTable">
	<tr>
        <?
		// "createColumnHeader()" included in formScripts.php
		createColumnHeader('List','list_order, lastname');
		createColumnHeader('First Name','firstname, lastname');
		createColumnHeader('Last Name','lastname, firstname');
		createColumnHeader('Email','email');
		createColumnHeader('Status','status');
		?>   
        <th></th>
    </tr>
    
    <?PHP
	
		$Query = "SELECT * FROM mailinglist_emails e
				  LEFT JOIN mailinglists l ON l.list_id = e.list_id
				  LEFT JOIN zen_customers z ON z.customers_id = e.customer_id
				  ORDER BY $sortby $sortDir";
		$Results = $db->Execute($Query);
		 while (!$Results->EOF) {	
			  $id = $Results->fields['id'];
			  $list = $Results->fields['list_name'];
			  $firstname = $Results->fields['firstname'];
			  $lastname = $Results->fields['lastname'];
			  $email = $Results->fields['email'];
			  $status =  $Results->fields['status'];
			  $customer_id =  $Results->fields['customer_id'];
			  
			  if($firstname == "") $firstname = $Results->fields['customers_firstname'];
			  if($lastname == "") $lastname = $Results->fields['customers_lastname'];
			  if($email == "") $email = $Results->fields['customers_email_address'];
			  
			  
			  
			  ?>
			  <tr onclick="" id="row_<?=$id; ?>">
				<td><div id="list_<?=$id; ?>"><?= $list; ?></div></td>
				<td><div id="firstname_<?=$id; ?>"><?= $firstname; ?></div></td>
                <td><div id="lastname_<?=$id; ?>"><?= stripslashes($lastname); ?></div></td>
                <td><div id="email_<?=$id; ?>"><?= stripslashes($email); ?></div></td>
                <td><div id="status_<?=$id; ?>"><?= stripslashes($status); ?></div></td>
				<td style="text-align:right;white-space:nowrap;">
                    
                    <div id="buttons_<?=$id; ?>">
                    <!--<a onclick = "doAssign(<?=$id; ?>);" class="yellowBtn">Assign to Products</a>-->
                    <!--<a onclick = "doEdit(<?=$id; ?>);" class="yellowBtn">edit</a>-->
                    <a onclick = "doDelete(<?=$id; ?>);" class="yellowBtn">delete</a>
              		</div>
                    
                </td>
			</tr>          
			<? 
			$Results->MoveNext();
		} 
		?>
        <div id="newRows"></div>
</table>
</div>


	    

<? include_once("includes/inc_footer.php"); ?>

</body>
</html>
