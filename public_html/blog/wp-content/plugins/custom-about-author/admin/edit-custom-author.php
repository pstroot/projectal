<?php

/**
* Edit Custom Author (Wordpress Hook Entry)
*/
function caa_edit_custom_author_page(){
	$display_text = null;
	if(! isset($_GET["profile_id"])) {
		$display_text = caa_return_message("Error, Custom Author does not exist!", "error");
		return caa_display_all_custom_authors($display_text);	
	}
	
	if( isset($_POST["profile_id"])) {
		$display_text = caa_save_custom_author_changes();
	}
	
	$profile_id = $_GET["profile_id"];
	caa_edit_custom_author($display_text, $profile_id);
}

function caa_save_custom_author_changes(){
	global $wpdb;

	$profile_id = $_POST["profile_id"];
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
	$custom_author_table->edit_row($profile_id, $first_name, $last_name, $email, $url, $description, $twitter, $facebook, $google_plus, $linkedin, $flickr, $youtube, $vimeo, $use_custom_image, $custom_image_url, $display_html_block, $html_block);
	
	return caa_return_message("Custom Author Updated", "updated");
}

function caa_edit_custom_author($display_text, $profile_id){
	global $wpdb, $caa_plugin_dir_path;
	
	
	$custom_author_table = new CAA_Profile_DB($wpdb);
	$result = $custom_author_table->get_row_by_id($profile_id);
	
	?>
	<div class="wrap">
	<div style="display:none" id="plugin_url"><?php echo $caa_plugin_dir_path; ?></div>	<!-- Store plugin url path here here for use by javascript -->
	<div id="icon-users" class="icon32"><br /></div>
	<h2>Edit Custom Author <a href="admin.php?page=<?php echo CAA_Config::display_all_custom_authors_page; ?>" class='button'><?php _e('Back'); ?> </a></h2>
	<?php 
	echo $display_text;

	$author = caa_get_author_details_from_database($result->username, true);
	$author_display_box = caa_get_author_bio_html($author);
	echo $author_display_box;
	
	?>
	
	<form action="" method="post" id="createuser">
	<input type="hidden" name="profile_id" value="<?php echo $profile_id; ?>"/>
		<table class="form-table">
			<tr class="form-field form-required">
				<th scope="row"><label for="username">Username </label></th>
				<td><?php echo $result->username; ?></td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label for="short-code">Short-code </label></th>
				<td><strong>[custom_author=<?php echo $result->username; ?>]</strong></td>
			</tr>			
			<tr class="form-field">
				<th scope="row"><label for="email">E-mail</label></th>
				<td><input name="email" type="text" id="email" value="<?php echo $result->email; ?>" /></td>
			</tr>
			<tr class="form-field">
				<th scope="row"><label for="first_name">First Name</label></th>
				<td><input name="first_name" type="text" id="first_name" value="<?php echo $result->first_name; ?>" /></td>
			</tr>
			<tr class="form-field">
				<th scope="row"><label for="last_name">Last Name</label></th>
				<td><input name="last_name" type="text" id="last_name" value="<?php echo $result->last_name; ?>" /></td>
			</tr>
			<tr class="form-field">
				<th scope="row"><label for="url">Website</label></th>
				<td><input name="url" type="text" id="url" value="<?php echo $result->url; ?>" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="description">Description</label></th>
				<td><textarea name="description" id="description" rows="5" cols="50" ><?php echo $result->description; ?></textarea></td>
			</tr>
			<tr>
				<th scope="row"><label for="twitter">Twitter</label></th>
				<td><?php echo CAA_Config::twitter_url_prefix; ?><input name="twitter" type="text" id="twitter" value="<?php echo $result->twitter; ?>" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="facebook">Facebook</label></th>
				<td><?php echo CAA_Config::facebook_url_prefix; ?><input name="facebook" type="text" id="facebook" value="<?php echo $result->facebook; ?>" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="google_plus">Google+</label></th>
				<td><?php echo CAA_Config::google_plus_url_prefix; ?><input name="google_plus" type="text" id="google_plus" value="<?php echo $result->google_plus; ?>" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="linkedin">LinkedIn</label></th>
				<td><?php echo CAA_Config::linkedin_url_prefix; ?><input name="linkedin" type="text" id="linkedin" value="<?php echo $result->linkedin; ?>" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="flickr">Flickr</label></th>
				<td><?php echo CAA_Config::flickr_url_prefix; ?><input name="flickr" type="text" id="flickr" value="<?php echo $result->flickr; ?>" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="youtube">YouTube</label></th>
				<td><?php echo CAA_Config::youtube_url_prefix; ?><input name="youtube" type="text" id="youtube" value="<?php echo $result->youtube; ?>" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="vimeo">Vimeo</label></th>
				<td><?php echo CAA_Config::vimeo_url_prefix; ?><input name="vimeo" type="text" id="vimeo" value="<?php echo $result->vimeo; ?>" /></td>
			</tr>
			<tr>
				<th scope="row"><label for="use_custom_image">Use Custom Author Image <span class="description"><br/>(uses <a href="http://gravatar.com" target="_new">Gravatar</a> if unchecked)</span></label></th>
				<td><input name="use_custom_image" type="checkbox" id="use_custom_image"
				<?php if($result->use_custom_image){ echo "checked"; }?> 
				/></td>
			</tr>
			<tr class="form-field">
				<th scope="row"><label for="custom_image_url">Custom Author Image location <span class="description"><br/>(will be re-sized to 75x75 pixels)</span></label></th>
				<td><input name="custom_image_url" type="text" id="custom_image_url" value="<?php echo $result->custom_image_url; ?>" /></td>
			</tr>		
			<tr>
				<th scope="row"><label for="display_html_block">Use Custom HTML</label></th>
				<td><input name="display_html_block" type="checkbox" id="display_html_block"
				<?php if($result->display_html_block){ echo "checked"; }?> 
				/></td>
			</tr>
			<tr>
				<th scope="row"><label for="html_block">Custom HTML <span class="description"><br/>(use <img src="<?php echo $caa_plugin_dir_path; ?>/utils/HtmlBox/images/silk/code.png"/> to display HTML code)</span></label></th>
				<td><textarea name="html_block" id="html_block" rows="5" cols="50" ><?php echo $result->html_block; ?></textarea></td>
			</tr>		
		</table>
	
	<p class="submit">
	<input type="submit" name="createuser" id="createusersub" class="button-primary" value="Save Changes"  />
	<a href="admin.php?page=<?php echo CAA_Config::display_all_custom_authors_page ?>&action=delete&profile_id=<?php echo $profile_id;?>" 
		onclick="return confirm('Confirm Deletion of <?php echo $result->username; ?>?')">Delete</a>
	</p>
	</form>	
	</div>
	<?php 	
}
