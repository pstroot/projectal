<?

// to improve security and prevent special-character attacks, user data that is passed to programs should be processed with the shellclean() function
function shellclean($array, $index, $maxlength){
	if (isset($array["[$index]"])){
		$input = substr($array["[$index]"],0,$maxlength);
		$input = EscapeShellArg($input);
		return ($input);
	}
	return NULL;
}
		

// untaintData
function cleanUp($array, $index){
	if (isset($array["{$index}"])){
		if (count($array["{$index}"]) <= 1){
			$maxlength = 10000;
			$input = @trim(substr($array["{$index}"],0,$maxlength));
			$input = addslashes($input);
		} else {
			$input = array();
			foreach ($array["{$index}"] as $val){
				$thisVar = addslashes($val);
				array_push($input,$thisVar);
			}
		}
		return ($input);
	}
	return NULL;
}




function displayError($var, $template){
	if (isset($_SESSION["formErrors"][$var])) {
		print str_replace("::ERROR::",$_SESSION['formErrors'][$var],$template);
	}
}

function isBlank($var){
	if (empty($var)){
		return false;
	}
	return true;
}

function validateEmailDomain($email){
	if (function_exists("getmxrr") && function_exists("gethostbyname")){
		// extract the domain of the email address
		$maildomain = substr(strstr($email, '@'), 1);
		if (!(getmxrr($maildomain, $temp) || gethostbyname($maildomain) != $maildomain)){
			//print "the domain in the email address does not exist.<BR>\n";
			return false;
		}
	}
	return true;
}

function validateEmail($email){
	//$validEmailExpr = "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*\.(\w{2}|(com|net|org|edu|int|mil|gov|arpa|biz|aero|name|coop|info|pro|museum))$/"
	$validEmailExpr = "^[0-9a-z~!#$%&_-]([.]?[0-9a-z~!#$%&_-])*" . "@(([-_a-z0-9]+\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$";			  
	if (!empty($email)){
		if (!eregi($validEmailExpr, $email)){
			//print "the email must be in the name@domain format.<BR>\n";
			return false;
		} 
	}	
	return true;
}




function validatePhone($phone){
	if (isBlank($phone)){
		$validPhoneExpr = '^[(]?[2-9]{1}[0-9]{2}[) -\.]{0,2}' . '[0-9]{3}[-\. ]?' . '[0-9]{4}[ ]?' . '((x|ext)[.]?[ ]?[0-9]{1,5})?$';
		if (!ereg($validPhoneExpr, $phone)) {
			return false;
		}
		
	}
	return true;
}

function validateZip($var){
	if (isBlank($var)){
		$validZipExpr = "^[0-9]{5}([-\. ][0-9]{4})?$";
		if (!eregi($validZipExpr,$var)){
			return false;
		}
	}
	return true;
}



function isDollerAmount($var){
	if (isBlank($var)){
		//$validMoneyExpr = "\\\$[0-9]{1,}\\.[0-9]{2,2}"; // with dollar sign
		$validMoneyExpr = "^[0-9]*(\.[0-9]{2})?$";
		if (!eregi($validMoneyExpr,$var)){
			//print "This must be a properly formatted doller amount<BR>";
			return false;
		}
	}
	return true;
}

function isInteger($var){
	if (isBlank($var)){
		$validIntegerExpr = "^[0-9]*$";
		if (!eregi($validIntegerExpr,$var)){
			//print "This must be a properly formatted doller amount<BR>";
			return false;
		}
	}
	return true;
}

?>