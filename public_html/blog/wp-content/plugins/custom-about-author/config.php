<?php

Class CAA_Config{

	const plugin_name = "custom-about-author";
	const version = "1.4";

	//Admin Pages
	const display_all_custom_authors_page = "caa_display_all_custom_authors_page";
	const add_new_custom_author_page = "caa_add_new_custom_author_page";
	const edit_custom_author_page = "caa_edit_custom_author_page";
	const settings_page = "caa_settings_page";
	
	
	//Social Media URL Prefix
	const twitter_url_prefix = "http://www.twitter.com/";
	const facebook_url_prefix = "http://www.facebook.com/";
	const google_plus_url_prefix = "http://profiles.google.com/";
	const linkedin_url_prefix = "http://www.linkedin.com/in/";
	const flickr_url_prefix = "http://www.flickr.com/photos/";
	const youtube_url_prefix = "http://www.youtube.com/user/";
	const vimeo_url_prefix = "http://vimeo.com";
	
	const custom_field_in_post_for_custom_author = "post-author";

	//Javascript names
	const caa_admin_js_name = "caa_admin_js";
	const caa_htmlBox_colors_js_name = "caa_htmlBox_colors_js";
	const caa_htmlBox_styles_js_name = "caa_htmlBox_styles_js";
	const caa_htmlBox_syntax_js_name = "caa_htmlBox_syntax_js";
	const caa_htmlBox_js_name = "caa_htmlBox_js";
	
	//Global configuration values
	const caa_global_display_on_home_page = "caa_global_display_on_home_page";
	const caa_global_display_on_page = "caa_global_display_on_page";
	const caa_global_display_on_archive_page = "caa_global_display_on_archive_page";
	const caa_global_display_on_single_post = "caa_global_display_on_single_post";
	const caa_global_display_on_top = "caa_global_display_on_top";
}


Class CAA_Author{
	//wordpress parameters
	const user_login = "user_login";
	const user_nicename = "user_nicename";
	const user_email = "user_email";
	const user_url = "user_url";
	const display_name = "display_name";
	const nickname = "nickname";
	const first_name = "first_name";
	const last_name = "last_name";
	const description = "description";
	const jabber = "jabber";
	const aim = "aim";
	const yim = "yim";
	const user_firstname = "user_firstname";
	const user_lastname = "user_lastname";
	const user_description = "user_description";
	const ID = "ID";
	
	//custom parameters
	const twitter = "twitter";
	const facebook = "facebook";
	const google_plus = "google_plus";
	const linkedin = "linkedin";
	const flickr = "flickr";
	const youtube = "youtube";
	const vimeo = "vimeo";
	const disable_about_author = "disable_about_author";
}

?>