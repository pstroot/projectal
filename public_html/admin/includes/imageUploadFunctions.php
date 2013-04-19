<?
function imageUploadForm($passedInImage = "",$fieldname = 'image',$pathToImage = '../',$message='',$maxDisplayWidth=200,$maxDisplayHeight=200){

	?>
    <script type="text/javascript">
	function checkImageRadio(r,divName){
		if (r.value == "change"){
			document.getElementById(divName).style.display = 'inline'
		} else {
			document.getElementById(divName).style.display = 'none'
		}
	}
    </script>
	<?
	$existingfieldname = $fieldname . '_original';
	
	// IF AN IMAGE EXISTS, SHOW THE IMAGE, AND THREE RADIO BUTTON SELECTIONS
	if ($passedInImage != ""){
		 $h = 90; // set the height to 90 pixels
		 if (is_file($pathToImage . $passedInImage)){
			list($width, $height, $type, $attr) = getimagesize($pathToImage . $passedInImage);			
			$scaleRatioX = $maxDisplayWidth/$width;		
			$scaleRatioY = $maxDisplayHeight/$height;
			if($scaleRatioX < $scaleRatioY){
				$scaleRatio = $scaleRatioX;
			} else {
				$scaleRatio = $scaleRatioY;
			}
			if ($scaleRatio < 1){
					$height = $height * $scaleRatio; 
					$width = $width * $scaleRatio; 
			}
			
			print "<a href='$pathToImage"."$passedInImage' target='_blank'><img src='$pathToImage"."$passedInImage' border='0' height='$height' width='$width' style='margin-bottom:10px;'></a><BR>";	
		} else {
			print "Could not find $pathToImage"."$passedInImage.<BR>";
		}
	}
	
	// SHOW THE RADIO BUTTONS IF THE IMAGE EXISTS
	if ($passedInImage != ""){
		print"<div id='".$fieldname."_imageButtonsRegular'>
		<input type='radio' name='".$fieldname."_action' value='remove' onClick=\"checkImageRadio(this,'".$fieldname."_imageUploadRow')\" style='width:auto;border:none;'> Remove Image <BR>
		<input type='radio' name='".$fieldname."_action' value='change' onClick=\"checkImageRadio(this,'".$fieldname."_imageUploadRow')\" style='width:auto;border:none;'> Replace Image <BR>
		<input type='radio' name='".$fieldname."_action' value='same'   onClick=\"checkImageRadio(this,'".$fieldname."_imageUploadRow')\" style='width:auto;border:none;' checked> Leave Unchanged
		</div>";	
	}
    
	
    if ($passedInImage != ""){ $style = "display:none;"; }
    echo "<div  id='".$fieldname."_imageUploadRow' style='" . @$style . "' >";
	
	// ADD THE "EXPERT" TOGGLE CHECKBOX
	print "<div style='float:right;margin-left:10px;'><input type='checkbox' name='".$fieldname."_useExpert' style='width:auto;' onclick='toggleExpert(\"".$fieldname."\",this)'>expert</div>";
	
	// SHOW THE EXPERT EDITING FIELD
	echo "<input type='text' name='".$fieldname."_expert' id='".$fieldname."_expert' value='" . $passedInImage . "' style='display:none;width:300px;'>";
	
	// SHOW THE FILE UPLOAD FIELD
    echo "<input type='file' size='30' name='" . $fieldname . "' class='input' id='".$fieldname."_notexpert' style='display:block;'>";

	echo "</div>";
	   
		
	// ADD A HIDDEN FIELD WITH THE EXISTING IMAGE NAME
	echo "<input type='hidden' name='" . $existingfieldname . "' value='$passedInImage'>";
	
	
	// DISPLAY A MESSAGE IF ONE WAS PASSED IN
	if($message != ''){
		print "<div style='margin-top:10px;'>" . $message . "</div>";
	}
		
}


function imageUpload($fieldname,$dir,$pathToRoot){
	$originalImage = $_POST[$fieldname."_original"];
	// remove display image if checkbox is checked
	if (isset($_POST[$fieldname."_action"]) && ($_POST[$fieldname."_action"] == 'change' || $_POST[$fieldname."_action"] == 'remove')){
		if(is_file($pathToRoot . $originalImage	)){
			@unlink($pathToRoot . $originalImage);	
		} else {
			//print "Could not delete existing image. " . $pathToRoot . $originalImage . " Does not exist."; 	
			//return false;
		}		
		$finalImage = "";			
	} else {	
		//print "Final Image is the original image of ".$originalImage."<BR>";
		//if($originalImage != ""){
			$finalImage = $originalImage;
		//}else{
		//	return true;
		//}
	}
	
	// if the "expert" checkbox was checked, use the value of this field and don't go any further.
	if(isset($_POST[$fieldname."_useExpert"])){
		return $_POST[$fieldname."_expert"];
	}
	
	//////////////// UPLOAD IMAGE ///////////////////
	if (isset($_FILES[$fieldname]["name"]) && $_FILES[$fieldname]["name"] != ''){
		
		// Get the image name, if it already exists, then create a unique one
		$imageName = createUniqueFilename($_FILES[$fieldname]['name'],$pathToRoot . $dir);
		
		$finalImage = $dir . $imageName ; 
		
		
		// Standard upload
		//print "finalImage = $finalImage <BR>";
		if(move_uploaded_file($_FILES[$fieldname]['tmp_name'], $pathToRoot . $finalImage))  {
			//print "UPLOADED ".$finalImage . " <BR>";
			return $finalImage;
		} else {
			print "Could not upload \"".$_FILES[$fieldname]['name']."\" to the following location: \"" . $pathToRoot . $dir . "\" <BR><BR>";
			return false;
		}
		
		/*
		// ImageMagick Upload
		include_once('../includes/imagemagick_class.php');
		$imObj = new ImageMagick($_FILES[$fieldname]);
		//$imObj -> setVerbose(true);
		$imObj -> setTargetdir($pathFromHere);
		
		$imObj -> Convert('jpg');
	
		// make the display size image
		$imObj -> Resize(200,200, 'keep_aspect');
		$theImgFilename = $imObj -> Save(ereg_replace("[^a-zA-Z0-9_.]", '_', $_FILES[$fieldname]["name"]),$pathFromHere);		
		$imObj -> CleanUp();
	
		$finalImage = $dir . $theImgFilename;
		*/
	}
	////////////// END UPLOAD IMAGE /////////////////////////	
	
	return $finalImage;

}



function createUniqueFilename($imageName,$pathToImage = '',$counter = 1){
	if(!is_file($pathToImage . $imageName)){
		return $imageName;
	} else {
		$newFileName = appendFilename($imageName,"_".$counter);
		if(!is_file($pathToImage . $newFileName)){
			return $newFileName;
		} else {
			$counter = $counter+1;
			return createUniqueFilename($imageName,$pathToImage,$counter);
		}
	}
	
}

function appendFilename($filename,$appendWith){
	$dot = strripos($filename,".");
	$name = substr($filename,0,$dot);
	$ext = substr($filename,$dot);
	$newFilename = $name . $appendWith . $ext;
	return $newFilename;
}
?>