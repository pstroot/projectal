<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
include_once("includes/imageUploadFunctions.php");



if(isset($_GET["id"]) && !isset($_SESSION["bounceFromErrors"])){
	$Query = "SELECT * FROM faq WHERE faq_id = " . $_GET["id"];
	$Results = $db->Execute($Query);
	while (!$Results->EOF) {	
		  $_SESSION["formVars"]["id"] = stripslashes($Results->fields['faq_id']);
		  $_SESSION["formVars"]["cat_id"] = stripslashes($Results->fields['cat_id']);
		  $_SESSION["formVars"]["question"] = htmlspecialchars(stripslashes($Results->fields['question']));
		  $_SESSION["formVars"]["answer"] = htmlspecialchars(stripslashes($Results->fields['answer']));
		  $_SESSION["formVars"]["isActive"] =  $Results->fields['isActive']; 
		  $Results->MoveNext();
	}
	
}

if(isset($_GET["id"])) {
	$pageTitle = "Edit F.A.Q.";
	$buttonLabel = "Edit";
	$successURL = "faq_updated.php";
} else {
	$pageTitle = "Add New F.A.Q.";
	$buttonLabel = "Add";
	$successURL = "faq_added.php";
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


<form action="faq_submit.php" method="post" enctype="multipart/form-data">
<div class="formTable">
<table>

	<tr>
        <th>Category</th>        
        <td colspan="3">
        <select name="cat_id">
			<option value="0">Default Category</option>           
			<?
            $Query = "SELECT * FROM faq_categories ORDER BY theOrder";
            $Results = $db->Execute($Query);
            while (!$Results->EOF) {	
              $cat_name = $Results->fields['cat_name'];
              $cat_id = $Results->fields['cat_id'];
              $selected = '';
              if($cat_id==$_SESSION["formVars"]["cat_id"]) $selected = 'selected';
              print "<option value='$cat_id' $selected>$cat_name</option>\n";
              $Results->MoveNext();
            } 
            ?>
        </select>
		</td>
    </tr>
	<tr>
        <th>Question</th>        
        <td colspan="3"><textarea name="question" id="question" style="width:490px;height:120px;"><?=@$_SESSION["formVars"]["question"];?></textarea>
		<?=required("question");?></td>
    </tr>

	<tr>
        <th>Answer</th>        
        <td colspan="3"><textarea name="answer" id="answer" style="width:490px;height:120px;"><?=@$_SESSION["formVars"]["answer"];?></textarea>
		<?=required("answer");?></td>
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
