<?php
add_action( 'show_user_profile', 'caa_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'caa_show_extra_profile_fields' );

function caa_show_extra_profile_fields( $user ) { 
?>
<h3>Social Media</h3>
<table class="form-table">
	<tr>
		<th><label for="twitter">Twitter</label></th>
		<td>
			<?php echo CAA_Config::twitter_url_prefix; ?>
			<input name="twitter" type="text" id="twitter" 
					value="<?php echo esc_attr( get_the_author_meta( CAA_Author::twitter, $user->ID ) ); ?>" />
		</td>
	</tr>
	<tr>
		<th><label for="facebook">Facebook</label></th>
		<td>
			<?php echo CAA_Config::facebook_url_prefix; ?>
			<input name="facebook" type="text" id="facebook" 
				value="<?php echo esc_attr( get_the_author_meta( CAA_Author::facebook, $user->ID ) ); ?>" />
		</td>
	</tr>
	<tr>
		<th><label for="google_plus">Google+</label></th>
		<td>
			<?php echo CAA_Config::google_plus_url_prefix; ?>
			<input name="google_plus" type="text" id="google_plus" 
				value="<?php echo esc_attr( get_the_author_meta( CAA_Author::google_plus, $user->ID ) ); ?>" />
		</td>
	</tr>
	<tr>
		<th><label for="linkedin">LinkedIn</label></th>
		<td>
			<?php echo CAA_Config::linkedin_url_prefix; ?>
			<input name="linkedin" type="text" id="linkedin" 
				value="<?php echo esc_attr( get_the_author_meta( CAA_Author::linkedin, $user->ID ) ); ?>" />
		</td>
	</tr>	
	<tr>
		<th><label for="flickr">Flickr</label></th>
		<td>
			<?php echo CAA_Config::flickr_url_prefix; ?>
			<input name="flickr" type="text" id="flickr" 
				value="<?php echo esc_attr( get_the_author_meta( CAA_Author::flickr, $user->ID ) ); ?>" />
		</td>
	</tr>	
	<tr>
		<th><label for="youtube">YouTube</label></th>
		<td>
			<?php echo CAA_Config::youtube_url_prefix; ?>
			<input name="youtube" type="text" id="youtube" 
				value="<?php echo esc_attr( get_the_author_meta( CAA_Author::youtube, $user->ID ) ); ?>" />
		</td>
	</tr>	
	<tr>
		<th><label for="vimeo">Vimeo</label></th>
		<td>
			<?php echo CAA_Config::vimeo_url_prefix; ?>
			<input name="vimeo" type="text" id="vimeo" 
				value="<?php echo esc_attr( get_the_author_meta( CAA_Author::vimeo, $user->ID ) ); ?>" />
		</td>
	</tr>	
	<tr>
		<th><label for="disable_about_author">Disable about author display</label></th>
		<td>
			<input name="disable_about_author" type="checkbox" id="disable_about_author" 
			<?php if( get_the_author_meta( CAA_Author::disable_about_author, $user->ID ) ){ echo "checked";} ?>
				/>
			<span class="description">Check this box to not have author's profile displayed at bottom of each post.</span>
		</td>
	</tr>
</table>
<?php 
}

add_action( 'personal_options_update', 'caa_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'caa_save_extra_profile_fields' );

function caa_save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
	return false;

	/* Save social media information */
	update_user_meta( $user_id, CAA_Author::twitter, $_POST["twitter"] );
	update_user_meta( $user_id, CAA_Author::facebook, $_POST["facebook"] );
	update_user_meta( $user_id, CAA_Author::google_plus, $_POST["google_plus"] );
	update_user_meta( $user_id, CAA_Author::linkedin, $_POST["linkedin"] );
	update_user_meta( $user_id, CAA_Author::flickr, $_POST["flickr"] );
	update_user_meta( $user_id, CAA_Author::youtube, $_POST["youtube"] );
	update_user_meta( $user_id, CAA_Author::vimeo, $_POST["vimeo"] );
	
	$disable_about_author = "";
	if(isset($_POST["disable_about_author"])){
		$disable_about_author=$_POST["disable_about_author"];
	}
	update_user_meta( $user_id, CAA_Author::disable_about_author, $disable_about_author );
}