<?php

/**
 * Load all Pages
 */
require_once(dirname(__FILE__).'/display-all-custom-authors.php');
require_once(dirname(__FILE__).'/add-new-custom-author.php');
require_once(dirname(__FILE__).'/edit-custom-author.php');
require_once(dirname(__FILE__).'/extra-user-profile-fields.php');




/**
*  Add Settings pages
*/
function caa_add_menu_page() {

	//Custom Authors Page
	$settings_menu_title = "Custom Authors";
	$settings_menu_page_title = "Custom Authors";
	$capability = "manage_options";
	
	add_submenu_page( 'users.php', $settings_menu_page_title, $settings_menu_title, $capability, CAA_Config::display_all_custom_authors_page, CAA_Config::display_all_custom_authors_page);

	caa_add_pages_not_shown_in_menu($capability);

}

/**
 * Add admin pages which are not displayed in the menu side-bar
 */
function caa_add_pages_not_shown_in_menu($capability) {

	//Add Custom Author Page
	$add_custom_author_title = "Add Custom Author";
	$add_new_custom_author_page = add_submenu_page(__FILE__, $add_custom_author_title, $add_custom_author_title, $capability, CAA_Config::add_new_custom_author_page, CAA_Config::add_new_custom_author_page);
	add_action( 'admin_print_styles-' . $add_new_custom_author_page, 'caa_admin_styles' );
	
	//Edit Custom Author Page
	$edit_custom_author_title = "Edit Custom Author";
	$edit_custom_author_page = add_submenu_page(__FILE__, $edit_custom_author_title, $edit_custom_author_title, $capability, CAA_Config::edit_custom_author_page, CAA_Config::edit_custom_author_page);
	add_action( 'admin_print_styles-' . $edit_custom_author_page, 'caa_admin_styles' );
	
}

/**
* Style to be called only on the admin pages.
*/
function caa_admin_styles() {
	wp_enqueue_script("jquery");
	wp_enqueue_script( CAA_Config::caa_admin_js_name );
	wp_enqueue_script( CAA_Config::caa_htmlBox_colors_js_name );
	wp_enqueue_script( CAA_Config::caa_htmlBox_styles_js_name );
	wp_enqueue_script( CAA_Config::caa_htmlBox_syntax_js_name );
	wp_enqueue_script( CAA_Config::caa_htmlBox_js_name );
}


/**
 * Admin Settings Menu Page (Called by wordpress)
 *
 */
function caa_admin_settings_page() {
	?>
<div class="wrap">
<h1>Settings</h1>
</div>
<?php

}


/**
  * Register css stylesheets and javascript
 */
function caa_admin_init() {
	register_setting( 'caa-settings-group', CAA_Config::plugin_name );
	
	wp_register_script( CAA_Config::caa_admin_js_name, plugins_url( '/js/admin.js' , dirname(__FILE__) ) );
	wp_register_script( CAA_Config::caa_htmlBox_colors_js_name, plugins_url( '/utils/HtmlBox/htmlbox.colors.js' , dirname(__FILE__) ) );
	wp_register_script( CAA_Config::caa_htmlBox_styles_js_name, plugins_url( '/utils/HtmlBox/htmlbox.styles.js' , dirname(__FILE__) ) );
	wp_register_script( CAA_Config::caa_htmlBox_syntax_js_name, plugins_url( '/utils/HtmlBox/htmlbox.syntax.js' , dirname(__FILE__) ) );
	wp_register_script( CAA_Config::caa_htmlBox_js_name, plugins_url( '/utils/HtmlBox/htmlbox.full.js' , dirname(__FILE__) ) );
}

// create custom plugin settings menu
add_action('admin_init', 'caa_admin_init');
add_action('admin_menu', 'caa_add_menu_page');


?>
