<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
include_once("includes/imageUploadFunctions.php");

$pageTitle = "Add New Participant";
$buttonLabel = "Add";
$successURL = "participate_added.php";

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


<form action="participate_submit.php" method="post" enctype="multipart/form-data">
<div class="formTable">
<table>
	<tr>
        <th></th>        
        <td colspan="3">A participant must first be a user in our database. If you would like to add a participant that has not yet signed up, you can add the participant by going to the "Participate" section of the live site.
        <BR /><BR />
        If the user is in our system and you would like to flag them as a participant, fill out the form below.</td>
     </tr>
	<tr>
        <th>Person</th>        
        <td colspan="3">
        <select name="customer_id">
			<option value="">Please select one person.</option>           
			<?
            $Query = "SELECT * FROM zen_customers ORDER BY customers_lastname, customers_firstname";
            $Results = $db->Execute($Query);
            while (!$Results->EOF) {	
              $id = $Results->fields['customers_id'];
              $lastname = $Results->fields['customers_lastname'];
              $firstname = $Results->fields['customers_firstname'];
              $email = $Results->fields['customers_email_address'];            
              print "<option value='$id' $selected>$lastname, $firstname ($email)</option>\n";
              $Results->MoveNext();
            } 
            ?>
        </select><?=required("customer_id");?>
		</td>
    </tr>
	<tr>
        <th>Profession</th>        
        <td colspan="3"><input type="text" name="profession" id="profession"  value="<?=@$_SESSION["formVars"]["profession"];?>">
		<?=doValidate("profession");?></td>
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
