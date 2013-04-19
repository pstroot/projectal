<?php

/**
* Add Custom Author (Wordpress Hook Entry)
*/
function caa_add_new_custom_author_page(){

	$display_text = null;
	if(isset($_POST["username"])) {
		$display_text = caa_save_new_custom_author();
	}
	
	

	caa_display_add_new_custom_author_page($display_text);
}

function caa_save_new_custom_author(){
	global $wpdb;
	
	//Save New Author
	$username = $_POST["username"];
	if($username ==""){
		return caa_return_message("Error! Username is required!", "error");
	}
	$email = $_POST["email"];
	$first_name = $_POST["first_name"];
	$last_name = $_POST["last_name"];
	$url = $_POST["url"];
	$description = $_POST["description"];
	$twitter = $_POST["twitter"];
	$facebook = $_POST["facebook"];
	$google_plus = $_POST["google_plus"];
	$linkedin = $_POST["linkedin"];
	$flickr = $_POST["flickr"];
	$youtube = $_POST["youtube"];
	$vimeo = $_POST["vimeo"];
	$use_custom_image = null;
	if(isset($_POST['use_custom_image']))
	{
		$use_custom_image = $_POST["use_custom_image"];
	}
	$custom_image_url = $_POST["custom_image_url"];
	$display_html_block = null;
	if(isset($_POST['display_html_block']))
	{
		$display_html_block = $_POST["display_html_block"];
	}
	$html_block = $_POST["html_block"];
	
	$custom_author_table = new CAA_Profile_DB($wpdb);	
	if($custom_author_table->get_row_by_username($username) != null){
		//username already exist
		return caa_return_message("Error! Username <strong>". $username."</stong> already exists!", "error");
	}
	$profile_id = $custom_author_table->create_new_row($username, $first_name, $last_name, $email, $url, $description, $twitter, $facebook, $google_plus, $linkedin, $flickr, $youtube, $vimeo, $use_custom_image, $custom_image_url, $display_html_block, $html_block);
	if($profile_id){
		$message = "Custom Author Successfully Added " .
					"<a href='admin.php?page=" . CAA_Config::display_all_custom_authors_page . "'>Back to all custom authors</a>";
		return caa_return_message($message, "updated");
	}else{
		return caa_return_message("Error Adding Custom Author!", "error");
	}
	
}

function caa_display_add_new_custom_author_page($display_text){
	global $caa_plugin_dir_path;
	?>
	<div class="wrap">
	<div style="display:none" id="plugin_url"><?php echo $caa_plugin_dir_path; ?></div>	<!-- Store plugin url path here here for use by javascript -->
	<div id="icon-users" class="icon32"><br /></div>
	<h2 id="add-new-user"> Add New Custom Author
	</h2>
	<?php echo $display_text; ?>
		
	<form action="" method="post" name="createuser" id="createuser" class="add:users: validate">
		<input name="action" type="hidden" value="createuser" />
		<table class="form-table">
			<tr class="form-field form-required">
				<th scope="row"><label for="username">Username <span class="description">(required)</span></label></th>
				<td><input name="username" type="text" id="username" value="" /></td>
			</tr>
			<tr class="form-field">
				<th scope="row"><label for="email">E-mail</label></th>
				<td><input name="email" type="text" id="email" value="" /></td>
			</tr>
			<tr class="form-field">
				<th scope="row"><label for="first_name">First Name</label></th>
				<td><input name="first_name" type="text" id="first_name" value="" /></td>
			</tr>
			<tr class="form-field">
				<th scope="row"><label for="last_name">Last Name</label></th>
				<td><input name="last_name" type="text" id="last_name" value="" /></td>
			</tr>
			<tr class="form-field">
				<th scope="row"><label for="url">Website</label></th>
				<td><input name="url" type="text" id="url" value="" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="description">Description</label></th>
				<td><textarea name="description" id="description" rows="5" cols="50" ></textarea></td>
			</tr>
			<tr class="form-field">
				<th scope="row"><label for="twitter">Twitter</label></th>
				<td><?php echo CAA_Config::twitter_url_prefix; ?><input name="twitter" type="text" id="twitter" value="" /></td>
			</tr>
			<tr class="form-field">
				<th scope="row"><label for="facebook">Facebook</label></th>
				<td><?php echo CAA_Config::facebook_url_prefix; ?><input name="facebook" type="text" id="facebook" value="" /></td>
			</tr>
			<tr class="form-field">
				<th scope="row"><label for="google_plus">Google+</label></th>
				<td><?php echo CAA_Config::google_plus_url_prefix; ?><input name="google_plus" type="text" id="google_plus" value="" /></td>
			</tr>
			<tr class="form-field">
				<th scope="row"><label for="linkedin">LinkedIn</label></th>
				<td><?php echo CAA_Config::linkedin_url_prefix; ?><input name="linkedin" type="text" id="linkedin" value="" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="flickr">Flickr</label></th>
				<td><?php echo CAA_Config::flickr_url_prefix; ?><input name="flickr" type="text" id="flickr" value="" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="youtube">YouTube</label></th>
				<td><?php echo CAA_Config::youtube_url_prefix; ?><input name="youtube" type="text" id="youtube" value="" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="vimeo">Vimeo</label></th>
				<td><?php echo CAA_Config::vimeo_url_prefix; ?><input name="vimeo" type="text" id="vimeo" value="" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="use_custom_image">Use Custom Author Image <span class="description"><br/>(uses <a href="http://gravatar.com" target="_new">Gravatar</a> if unchecked)</span></label></th>
				<td><input name="use_custom_image" type="checkbox" id="use_custom_image" /></td>
			</tr>
			<tr class="form-field">
				<th scope="row"><label for="custom_image_url">Custom Author Image location <span class="description"><br/>(will be re-sized to 75x75 pixels)</span></label></th>
				<td><input name="custom_image_url" type="text" id="custom_image_url" value="" /></td>
			</tr>					
			<tr>
				<th scope="row"><label for="display_html_block">Use Custom HTML</label></th>
				<td><input name="display_html_block" type="checkbox" id="display_html_block" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="html_block">Custom HTML<span class="description"><br/>(use <img src="<?php echo $caa_plugin_dir_path; ?>/utils/HtmlBox/images/silk/code.png"/> to display HTML code)</span></label></th>
				<td><textarea name="html_block" id="html_block" rows="5" cols="50" ></textarea></td>
			</tr>		
		</table>
	
	<p class="submit">
	<input type="submit" name="createuser" id="createusersub" class="button-primary" value="Add New User"  />
	<a href="admin.php?page=<?php echo CAA_Config::display_all_custom_authors_page; ?>">Cancel</a>
	</p>
	</form>	
	</div>
	<?php 	
}
