<?php

/**
 * Called by the wordpress the_content filter
 */
function caa_append_custom_author_bio($content) {
	global $post;

	$caa_global_display_on_home_page = get_option( CAA_Config::caa_global_display_on_home_page );		// is_home()
	$caa_global_display_on_page = get_option( CAA_Config::caa_global_display_on_page );					// is_page()
	$caa_global_display_on_single_post = get_option( CAA_Config::caa_global_display_on_single_post );	// is_single()
	$caa_global_display_on_archive_page = get_option( CAA_Config::caa_global_display_on_archive_page );	// is_archive()
	$caa_global_display_on_top = get_option( CAA_Config::caa_global_display_on_top );					// display author box on top of post
	
	$content=preg_replace_callback("/\[custom_author=.*\]/",'caa_filter_shortcode_callback',$content);
	$content=preg_replace_callback("/\[custom_author\]/",'caa_filter_shortcode_with_no_username_callback',$content);
	
	if( is_home() && $caa_global_display_on_home_page){
		if($caa_global_display_on_top){
			$content = caa_get_current_author_bio() . $content;
		}else{
			$content .= caa_get_current_author_bio();
		}
	}
	if( is_page() && $caa_global_display_on_page){
		if($caa_global_display_on_top){
			$content = caa_get_current_author_bio() . $content;
		}else{
			$content .= caa_get_current_author_bio();
		}
	}
	if( is_single() && $caa_global_display_on_single_post){
		if($caa_global_display_on_top){
			$content = caa_get_current_author_bio() . $content;
		}else{
			$content .= caa_get_current_author_bio();
		}
	}
	if( is_archive() && $caa_global_display_on_archive_page){
		if($caa_global_display_on_top){
			$content = caa_get_current_author_bio() . $content;
		}else{
			$content .= caa_get_current_author_bio();
		}
	}
	
	
// 	if ( ! is_page() ){
		
// 		$content=preg_replace_callback("/\[custom_author=.*\]/",'caa_filter_shortcode_callback',$content);
// 		$content=preg_replace_callback("/\[custom_author\]/",'caa_filter_shortcode_with_no_username_callback',$content);
		
// 		if( ! is_home() ){
// 			$content .= caa_get_current_author_bio();
// 		}
// 	}
	return $content;
}

function caa_filter_shortcode_callback($matches){
	$user_login = str_replace("[custom_author=","",$matches[0]);
	$user_login = str_replace("]","",$user_login);

	$content = caa_get_author_bio($user_login);
	return $content;
}

function caa_filter_shortcode_with_no_username_callback($matches){
	$user_login = get_the_author_meta(CAA_Author::user_login);
	$content = caa_get_author_bio($user_login);
	return $content;
}

function caa_get_current_author_bio(){
	$user_login = get_the_author_meta(CAA_Author::user_login);
	return caa_get_author_bio($user_login);
}


/**
 * Create the HTML code for the author bio
 * 1) check if author is in "Do not display" list"
 * 2) check custom fields to see if author is specified
 *  - If author is in "Do not display" list and there is no custom field, return.
 * 3) check if author exists in custom_author_db
 * 4) if not in custom_author_db, use wordpress profile
 */
function caa_get_author_bio($username){
	
	$display_author = caa_can_display_user($username);
	
	$custom_username = caa_get_author_from_custom_field();
	if($custom_username){
		$username = $custom_username;
	}
	
	if($display_author == false && $custom_username == null){
		//Author is "Do not display" and there is no custom field.
		return;
	}
	
	$author = caa_get_author_details_from_database($username);
	
	if($author == null && $custom_username==null && $display_author){
		$author = caa_get_author_details_from_wordpress_user_profile();
	}
	
	return caa_get_author_bio_html($author);
}

function caa_get_author_bio_html($author){
	if($author == null){
		//No author to display
		return;
	}
	
	if($author[CAA_Profile_DB::display_html_block]){
		//use custom block if it is enabled
		return caa_html_entity_decode( $author[CAA_Profile_DB::html_block] );
	}
	
	$author_email = $author[CAA_Author::user_email];
	$author_post_link = get_the_author_link();
	$author_name = $author[CAA_Author::first_name] . " " . $author[CAA_Author::last_name];
	$author_url = $author[CAA_Author::user_url];
	if($author_url){
		$author_name = '<a href="'.$author_url.'" rel="author" class="cab-author-name">'.$author_name.'</a>';
	}
	$author_description = caa_html_entity_decode( $author[CAA_Author::description] );
	
	$image_size = 75;
	$author_image = get_avatar( $author_email, $image_size);
	
	if($author[CAA_Profile_DB::use_custom_image]){
		//use custom author image url
		$author_image_url = caa_html_entity_decode( $author[CAA_Profile_DB::custom_image_url]);
		$author_image = '<img src="'.$author_image_url.'"  width="'.$image_size.'" height="'.$image_size.'" />';
	}
	
	$author_bio = '
		<div id="cab-author" class="cab-author">
			<div class="cab-author-inner">
				<div class="cab-author-image">
					' . $author_image . '
					<div class="cab-author-overlay"></div>
				</div> <!-- .cab-author-image -->
				<div class="cab-author-info">
					<div class="cab-author-name">' . $author_name . '</div>
		            <p>' . $author_description .'</p>' .
	caa_get_all_social_media($author) .'
				</div> <!-- .cab-author-info -->
			</div> <!-- .cab-author-inner -->
		</div> <!-- .cab-author-shortcodes -->
		';
	
	return $author_bio;	
}

/**
 * Check whether the about author box for this author should be displayed
 * @param unknown_type $username
 * @return boolean
 */
function caa_can_display_user($username){
	$display_author = true;
	
	if(get_the_author_meta(CAA_Author::disable_about_author)){
		$display_author = false;
	}
	
	return $display_author;
}

/**
 * Get custom field for custom author (if any)
 */
function caa_get_author_from_custom_field(){
	global $post;
	
	return get_post_meta($post->ID, CAA_Config::custom_field_in_post_for_custom_author, true);
}

/**
 * Get Author details from wordpress user profile
 * @return multitype:string NULL
 */
function caa_get_author_details_from_wordpress_user_profile(){
	$wordpres_author_details = array( 	
		CAA_Author::first_name => get_the_author_meta(CAA_Author::user_firstname),
		CAA_Author::last_name => get_the_author_meta(CAA_Author::user_lastname),
		CAA_Author::user_email => get_the_author_meta(CAA_Author::user_email),
		CAA_Author::user_url => get_the_author_meta(CAA_Author::user_url),
		CAA_Author::description => get_the_author_meta(CAA_Author::description),
		CAA_Author::twitter => get_the_author_meta(CAA_Author::twitter),
		CAA_Author::facebook => get_the_author_meta(CAA_Author::facebook),
		CAA_Author::google_plus => get_the_author_meta(CAA_Author::google_plus),
		CAA_Author::linkedin => get_the_author_meta(CAA_Author::linkedin),
		CAA_Author::flickr => get_the_author_meta(CAA_Author::flickr),
		CAA_Author::youtube => get_the_author_meta(CAA_Author::youtube),
		CAA_Author::vimeo => get_the_author_meta(CAA_Author::vimeo),
		CAA_Profile_DB::display_html_block => null
		);
	return $wordpres_author_details;
}

function caa_get_author_details_from_database($username, $convert_line_feeds_to_br = false){
	global $wpdb;
	
	$database_author_details=null;
	$author_profile_db = new CAA_Profile_DB($wpdb);
	$result = $author_profile_db->get_row_by_username($username);
	if($result){
		$author_description = $result->description;
		if($convert_line_feeds_to_br){
			$author_description = caa_replace_linefeeds_to_br($author_description);
		}
		
		$database_author_details = array(
			CAA_Author::first_name => $result->first_name,
			CAA_Author::last_name => $result->last_name,
			CAA_Author::user_email => $result->email,
			CAA_Author::user_url => $result->url,
			CAA_Author::description => $author_description,
			CAA_Author::twitter => $result->twitter,
			CAA_Author::facebook => $result->facebook,
			CAA_Author::google_plus => $result->google_plus,
			CAA_Author::linkedin => $result->linkedin,
			CAA_Author::flickr => $result->flickr,
			CAA_Author::youtube => $result->youtube,
			CAA_Author::vimeo => $result->vimeo,
			CAA_Profile_DB::use_custom_image => $result->use_custom_image,
			CAA_Profile_DB::custom_image_url => $result->custom_image_url,
			CAA_Profile_DB::display_html_block => $result->display_html_block,
			CAA_Profile_DB::html_block => $result->html_block
		);		
	}
	return $database_author_details;
}

function caa_get_all_social_media($author){
	$content = caa_add_social_media($author, CAA_Author::facebook) .
	caa_add_social_media($author, CAA_Author::twitter).
	caa_add_social_media($author, CAA_Author::linkedin).
	caa_add_social_media($author, CAA_Author::google_plus).
	caa_add_social_media($author, CAA_Author::flickr).
	caa_add_social_media($author, CAA_Author::youtube).
	caa_add_social_media($author, CAA_Author::vimeo);
	if($content!=""){
		$content = "<p>".$content."</p>";
	}
	return $content;
}

function caa_add_social_media($author, $media_type){
	global $caa_plugin_dir_path;
	
	$social_media_id = $author[$media_type];
	if($social_media_id==null || $social_media_id==""){
		//User does not use this social media
		return "";
	}

	$social_media_content = '';
	if($media_type == CAA_Author::twitter){
		$social_media_content .= '<a href="'.CAA_Config::twitter_url_prefix.$social_media_id.'" rel="external nofollow Twitter me" id="cab-twitter">' .
									'<img title="Twitter" src="' . $caa_plugin_dir_path . '/images/social_media/twitter.png" alt="Twitter"  border="0" /></a>&nbsp;';
	}else if($media_type == CAA_Author::facebook){
		$social_media_content .= '<a href="'.CAA_Config::facebook_url_prefix. $social_media_id.'" rel="external nofollow Facebook me" id="cab-facebook">' .
									'<img title="Facebook" src="' . $caa_plugin_dir_path . '/images/social_media/facebook.png" alt="Facebook" border="0" /></a>&nbsp;';
	}else if($media_type == CAA_Author::google_plus){
		$social_media_content .= '<a href="'.CAA_Config::google_plus_url_prefix.$social_media_id.'" rel="external nofollow Google+ me" id="cab-google_plus">' .
									'<img title="Google+" src="' . $caa_plugin_dir_path . '/images/social_media/google_plus.png" alt="Google+" border="0" /></a>&nbsp;';
	}else if($media_type == CAA_Author::linkedin){
		$social_media_content .= '<a href="'.CAA_Config::linkedin_url_prefix.$social_media_id.'" rel="external nofollow LinkedIn me" id="cab-linkedin">' .
									'<img title="LinkedIn" src="' . $caa_plugin_dir_path . '/images/social_media/linkedin.png" alt="LinkedIn" border="0" /></a>&nbsp;';
	}else if($media_type == CAA_Author::flickr){
		$social_media_content .= '<a href="'.CAA_Config::flickr_url_prefix.$social_media_id.'" rel="external nofollow Flickr me" id="cab-flickr">' .
									'<img title="Flickr" src="' . $caa_plugin_dir_path . '/images/social_media/flickr.png" alt="Flickr" border="0" /></a>&nbsp;';
	}else if($media_type == CAA_Author::youtube){
		$social_media_content .= '<a href="'.CAA_Config::youtube_url_prefix.$social_media_id.'" rel="external nofollow YouTube me" id="cab-youtube">' .
									'<img title="YouTube" src="' . $caa_plugin_dir_path . '/images/social_media/youtube.png" alt="YouTube" border="0" /></a>&nbsp;';
	}else if($media_type == CAA_Author::vimeo){
		$social_media_content .= '<a href="'.CAA_Config::vimeo_url_prefix.$social_media_id.'" rel="external nofollow Vimeo me" id="cab-vimeo">' .
									'<img title="Vimeo" src="' . $caa_plugin_dir_path . '/images/social_media/vimeo.png" alt="Vimeo" border="0" /></a>&nbsp;';
	}
	return $social_media_content;
}
