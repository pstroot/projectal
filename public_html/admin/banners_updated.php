<?PHP
include_once("includes/admin_init.php");
include_once("../includes/templates/Projectal/scripts/formScripts.php");
include_once("includes/imageUploadFunctions.php");

$Query = "SELECT * FROM rotating_banner WHERE id = " . $_GET["id"];
$Results = $db->Execute($Query);
while (!$Results->EOF) {	
	  $id = $Results->fields['id'];
	  $title = $Results->fields['title'];
	  $content = $Results->fields['content'];
	  $theOrder = $Results->fields['theOrder'];
	  $isActive = $Results->fields['isActive'];
	  $delay = $Results->fields['delay'];
	  $isHTML = $Results->fields['isHTML'];	
	  
	  if ($isHTML == 1){
          $content = "HTML Content";
	  } else {
		  $content =  '<img src="../' . $content . '" style="height:40px;">';
	  }
				 	 
 	  $Results->MoveNext();	 
} 
        
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Admin - <?=$title?> Updated</title>  	
    <link rel="stylesheet" href="../css/html5reset-1.6.1.css" />
  	<script src="../scripts/modernizr-1.5.min.js"></script>
    <link href="includes/adminstyles.css" rel="stylesheet" type="text/css" />
    <script src="includes/adminScripts.js"></script>
	<script>
		var ID = <?php echo $_GET["id"];?>;
		var content = "<?php echo addslashes($content); ?>";
		
		parent.updateData("title_"+ID,"<?=$title;?>")
		parent.updateData("content_"+ID,content)
		parent.updateData("delay_"+ID,"<?=$delay;?>")
		parent.updateData("isActive_"+ID,"<input type='checkbox' value='<?=$id;?>' <? if($isActive == 1) print "checked"; ?> onChange='changeActiveState(this)'>")
		parent.delayLyteboxClose();
	</script>
</head>
<body id="popup" style="background-color:#FFFFFF;">

<h1><?=$title?> Updated</h1>
<div class="popupContent">
	
	Thank You! This window will close momentarily.

</div>
</body>
</html>
