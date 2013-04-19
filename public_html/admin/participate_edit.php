<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
include_once("includes/imageUploadFunctions.php");


if(isset($_GET["id"]) && !isset($_SESSION["bounceFromErrors"])){
	$Query = "SELECT p.profession, p.participant_id, p.customer_id, c.customers_firstname, c.customers_lastname, p.date_created, c.customers_email_address, p.isActive FROM participants p
			  LEFT JOIN zen_customers c ON c.customers_id = p.customer_id
			  WHERE participant_id = " . $_GET["id"];
	$Results = $db->Execute($Query);
	while (!$Results->EOF) {
		  $_SESSION["formVars"]["id"] = 	$Results->fields['participant_id'];
		  $_SESSION["formVars"]["customer_id"] = 	$Results->fields['customer_id'];
		  $_SESSION["formVars"]["firstname"] = $Results->fields['customers_firstname'];
		  $_SESSION["formVars"]["lastname"] = $Results->fields['customers_lastname'];
		  $_SESSION["formVars"]["email"] = $Results->fields['customers_email_address'];
		  $_SESSION["formVars"]["profession"] = $Results->fields['profession'];
		  $_SESSION["formVars"]["since"] = date("F j, Y g:i a",strtotime($Results->fields['date_created']));
		  $_SESSION["formVars"]["isActive"] = $Results->fields['isActive'];
		  $Results->MoveNext();
	} 
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Admin - Edit Participant</title>  	
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css" />
  	<script src="../scripts/modernizr-1.5.min.js"></script>
    <link href="includes/adminstyles.css" rel="stylesheet" type="text/css" />
    <script src="includes/adminScripts.js"></script>
	<script type="text/javascript" src="../includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript" src="../includes/tinymce/tinymceScripts.js"></script>
 	
</head>
<body id="popup">
<h1>Edit Participant</h1>
<div class="popupContent">


<form action="participate_submit.php" method="post" enctype="multipart/form-data">
<div class="formTable">
<table>

	<tr>
        <th>First Name</th>        
        <td colspan="3"><input type="text" name="firstname" id="firstname" value="<?=@$_SESSION["formVars"]["firstname"];?>">
		<?=required("firstname");?></td>
    </tr>
	<tr>
        <th>Last Name</th>        
        <td colspan="3"><input type="text" name="lastname" id="lastname" value="<?=@$_SESSION["formVars"]["lastname"];?>">
		<?=required("lastname");?></td>
    </tr>
	<tr>
        <th>Email</th>        
        <td colspan="3"><input type="text" name="email" id="email" value="<?=@$_SESSION["formVars"]["email"];?>">
		<?=required("email");?></td>
    </tr>

	<tr>
        <th>Profession</th>        
        <td colspan="3"><input type="text" name="profession" id="profession" value="<?=@$_SESSION["formVars"]["profession"];?>">
		</td>
    </tr>

	<tr>
        <th></th>        
        <td colspan="3"><input type="submit" id="submit" name="submit" value="Edit" class="blackBtn"/></td>
    </tr>
    
</table>
</div>
<input type="hidden" name="customer_id" value="<?=@$_SESSION["formVars"]["customer_id"];?>"/>
<input type="hidden" name="id" value="<?=@$_GET["id"];?>"/>
<input type="hidden" name="returnURL" value="<?=curPageURL()?>" />
<input type="hidden" name="successURL" value="participate_updated.php"/>
</form>

</div>
</body>
</html>
