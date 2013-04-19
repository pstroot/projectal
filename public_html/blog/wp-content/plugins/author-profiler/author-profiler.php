<?php 
/*
Plugin Name: Author Profiler
Plugin URI: http://amancingh.com/blog/author-profiler/
Description: Plugin to help you upload author/user photo directly from user dashboard inside your WordPress.
Author: Amanpreet Singh
Author URI: http://amancingh.com/
Version: 1.0.1
*/

/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    
*/?>
<?php
	
	/**
	
	* Function to call the media uploader 
	
	*/
	function upload_profile_photo() {
		?>
		
		<script type="text/javascript">
			jQuery(document).ready(function() {
			jQuery(document).find("input[id^='uploadimage']").live('click', function(){
			//var num = this.id.split('-')[1];
			formfield = jQuery('#image').attr('name');
			tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		 		 
			window.send_to_editor = function(html) {
			imgurl = jQuery('img',html).attr('src');
			jQuery('#image').val(imgurl);
			tb_remove();
			}

		return false;
		});
	});
	</script>

<?php
	}
	/**
	
	* Adding the scripts for uploader and thickbox
	
	*/
	add_action('admin_head','upload_profile_photo');
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_style('thickbox'); //thickbox styles css

 	
	add_action( 'show_user_profile', 'add_author_photo_field' );
	add_action( 'edit_user_profile', 'add_author_photo_field' );
	
 /**
 
 * Adding extra Field to user profile area 
 
 */
function add_author_photo_field( $user ) { ?>

        <table class="form-table">
        <tr>
        <th><label for="image"><?php _e("Add Your Profile Photo"); ?></label></th>
        <td>
        <img src="<?php echo esc_attr( get_the_author_meta( 'image', $user->ID ) ); ?>" style="height:50px;"><br/>
        <input type="text" name="image" id="image" value="<?php echo esc_attr( get_the_author_meta( 'image', $user->ID ) ); ?>" class="regular-text" />
        <input type='button' class="button-primary" value="Upload Photo" id="uploadimage"/><br />
        <span class="description"><?php _e("Please Upload your photo."); ?></span>
        </td>        
        </tr>        
        </table>
<?php }
 
 /**
 
 * Saving the image to database
 
 */
  
	add_action( 'personal_options_update', 'save_add_author_photo_field' );
	add_action( 'edit_user_profile_update', 'save_add_author_photo_field' );
 
	function save_add_author_photo_field( $user_id ) {
 
		if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }
 
		update_user_meta( $user_id, 'image', $_POST['image'] );

	}

  /* 
  
  * Add filter to call the author image for post
  
  */

	add_filter( 'the_author', 'author_photo' );

	function author_photo ( $content ) {

		global $authordata;
		$content .= '<br><img src="'.get_the_author_meta('image', $authordata->ID).'" alt="'.get_the_author_meta('display_name').'" class="avatar" width=\'50\' height=\'50\'/>';
		return $content;
		
	} 
	/**
	
	* End author_photo hook()
	
	*/
	
	

?>
