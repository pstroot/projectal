<?php

function caa_return_message($message, $message_type){
	return '<div id="message" class="' . $message_type .'">
			<p><strong>' . $message . '</strong></p>
			</div>';
}

/**
 * Remove any \ from the html 
 * such as \\ => \
 *         \" => "
 * @param unknown_type $text
 * @return mixed
 */
function caa_html_entity_encode($text){

	$text = str_replace('\"','"',$text);
	$text = str_replace("\'","'",$text);
	$text = htmlentities($text);
	$text = str_replace("\\\\","&#92;",$text);
	
	return $text;
}

function caa_html_entity_decode($text){
	$text = html_entity_decode($text);
	return $text;
}

function caa_replace_linefeeds_to_br($text){
	return str_replace("\n","<br/>",$text);
}

function caa_display_documentation_link(){
	$content = '<h3>Need Help?</h3>';
	$content .= '<a href="http://littlehandytips.com/plugins/custom-about-author/">Documentation</a> | ';
	$content .= '<a href="http://forum.littlehandytips.com">Support Forum</a>';
	$content .= '<h3>Feedback</h3>';
	$content .= 'If you have any feedback for this plugin, such as how we can make it better, <br/>';
	$content .= 'what features you like to see added or like to report any bugs, we\'ll love to hear from you.<br/><br/>';
	$content .= 'Please leave your feedback in the comments section at <a href="http://littlehandytips.com/plugins/custom-about-author/">http://littlehandytips.com/plugins/custom-about-author/</a>.<br/>';
	$content .= 'All feedback is greatly appreciated. Thank you!';
	
	return $content;
}

function caa_display_donation_link(){
	global $caa_plugin_dir_path;
	
	$content = '<h3>Donation</h3>';
	$content .= 'If you like our plugin and would like to support what we\'re doing, please make a donation via paypal using the link below.<br/>';
	$content .= '<a href="http://littlehandytips.com/support/">';
	$content .='<img title="Donation" src="' . $caa_plugin_dir_path . '/images/paypal-donate-button.jpg" alt="Donation" border="0" /></a>';
	$content .= '</a><br/>';
	$content .= 'Thank You!';
	return $content;
}

function caa_display_promo_for_other_plugins(){
	$content = "<h3>Have you checked out the other Little Handy Plugins?</h3>";
	
	//Google Custom Search
	$content .= '<a href="http://littlehandytips.com/plugins/google-custom-search/"><img src="http://littlehandytips.com/wp-content/uploads/google-custom-search-ad-300x125.png"></a>';
	
	return $content;
}