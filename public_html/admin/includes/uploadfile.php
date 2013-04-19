<? 
function uploadFile($theFile,$directory="",$prepend=""){
	$target = $directory . $prepend . basename( $theFile['name']) ; 
	if(move_uploaded_file($theFile['tmp_name'], $target))  {
		return true;
	} else {
		return false;
	}
 
}
?>