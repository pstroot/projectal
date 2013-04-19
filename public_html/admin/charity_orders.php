<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
unset($_SESSION["bounceFromErrors"]);

$sortby = "c.name"; // default sort field 
$sortDir = "DESC"; // default sort order

if (isset($_GET["dir"])){
	$sortDir = "ASC";
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
    <title>Admin - Orders with Charity Contributions</title>  	
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
		var url = "charity_orders_edit.php"
		var title = "ADD CHARITY CONTRIBUTION"
		startLytebox(url,title,rev) 
	}
	function doEdit(id){	
		var rev = "width: 500px; height: 200px; scrolling: auto;border:3px solid #CC0000;"
		var url = "charity_orders_edit.php?id=" + id
		var title = "EDIT CHARITY CONTRIBUTION"
		startLytebox(url,title,rev) 
	}
	
	function doDelete(id){
		var rev = "width: 300px; height: 200px; scrolling: auto;border:3px solid #CC0000;"
		var url = "charity_orders_delete.php?id=" + id
		var title = "DELETE CHARITY CONTRIBUTION"
		startLytebox(url,title,rev) 
	}
	</script>

</head>
<body onLoad="activateNav('nav2')">
<a id="lytebox_misc"></a>
<? include("includes/inc_header.php"); ?>

<!--<input type="button" value="Add New Charity" style="float:right;margin-left:5px;" class="blackBtn" onclick="doAdd()" /> -->

<input type="button" value="View Charities" style="float:right;margin-left:5px;" class="blackBtn" onclick="window.location='charities.php';" />

<h1>Orders with Charity Contributions</h1>


<div class="dataTable">
<table id="theDataTable">
	<tr>
        <?
		// "createColumnHeader()" included in formScripts.php
		createColumnHeader('Order','order date');
		createColumnHeader('Charity','charity');
		createColumnHeader('Customer','customers_id');
		createColumnHeader('Subtotal','subtotal');
		createColumnHeader('Shipping','shipping_cost');
		createColumnHeader('Total','total');
		?>   
        <th></th>
    </tr>
    
    <?PHP
	
		$Query = "SELECT m.*, o.date_purchased, c.name, u.customers_firstname, u.customers_lastname
					FROM charity_orders_match m
					LEFT JOIN zen_orders o ON o.orders_id = m.orders_id
					LEFT JOIN charities c ON c.charity_id = m.charity
					LEFT JOIN zen_customers u ON u.customers_id = m.customers_id
					ORDER BY $sortby $sortDir";
		$Results = $db->Execute($Query);
		 while (!$Results->EOF) {
			 if($Results->fields['date_purchased'] != ""){
				 $order_link = "<a href='https://secure628.hostgator.com/~project/projectal_admin/orders.php?page=1&oID=" . $orders_id . "&action=edit'>" . date("M. d, Y - g:i a",strtotime($Results->fields['date_purchased'])) . "</a></div>";
			 } else {
				 $order_link = "X";
			 }
			  $match_id = $Results->fields['id'];
			  $orders_id = $Results->fields['orders_id'];
			  $customers_id = $Results->fields['customers_id'];
			  $customers_firstname = $Results->fields['customers_firstname'];
			  $customers_lastname = $Results->fields['customers_lastname'];
			  $subtotal = $Results->fields['subtotal'];
			  $shipping_cost = $Results->fields['shipping_cost'];
			  $total = $Results->fields['total'];
			  $charity_id = $Results->fields['charity'];
			  $charity_name = $Results->fields['name'];
			  
			  ?>
			  <tr onclick="" id="row_<?=$match_id; ?>">
				 <td><div id="date_<?=$match_id; ?>"><?=$order_link;?></div></td>
				 <td><div id="charity_<?=$match_id; ?>"><?= $charity_name; ?></div></td>
				 <td><div id="customer_<?=$match_id; ?>"><a href="https://secure628.hostgator.com/~project/projectal_admin/customers.php?page=1&cID=<?php echo $customers_id; ?>"><?= $customers_firstname . " " . $customers_lastname ; ?></a></div></td>
				 <td><div id="subtotal_<?=$match_id; ?>">$<?= $subtotal ; ?></div></td>
				 <td><div id="shipping_<?=$match_id; ?>">$<?= $shipping_cost ; ?></div></td>
				 <td><div id="total_<?=$match_id; ?>">$<?= $total ; ?></div></td>
                
				<td style="text-align:right;white-space:nowrap;">
                    <div id="buttons_<?=$match_id; ?>">
                    <!--<a onclick = "doEdit(<?=$match_id; ?>);" class="yellowBtn">edit</a> -->
                    <a onclick = "doDelete(<?=$match_id; ?>);" class="yellowBtn">delete</a>
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
