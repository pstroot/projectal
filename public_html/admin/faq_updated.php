<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
include_once("includes/imageUploadFunctions.php");

$Query = "SELECT * FROM faq WHERE faq_id = " . $_GET["id"];
$Results = $db->Execute($Query);
while (!$Results->EOF) {	
	  $faq_id = $Results->fields['faq_id'];
	  $question = $Results->fields['question'];
	  $answer = $Results->fields['answer'];
	  $theOrder =  $Results->fields['theOrder'];
	  $isActive =  $Results->fields['isActive']; 
 	  $Results->MoveNext();
} 
        
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Admin - F.A.Q. Updated</title>  	
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css" />
  	<script src="../scripts/modernizr-1.5.min.js"></script>
    <link href="includes/adminstyles.css" rel="stylesheet" type="text/css" />
    <script src="includes/adminScripts.js"></script>
	<script>
		var ID = <?=$_GET["id"];?>;
		parent.updateData("question_"+ID,"<?=$question;?>")
		parent.delayLyteboxClose();
	</script>
</head>
<body id="popup" style="background-color:#FFFFFF;">

<h1>F.A.Q. Updated</h1>
<div class="popupContent">
	
	Thank You! This window will close momentarily.

</div>
</body>
</html>
