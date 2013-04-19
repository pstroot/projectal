<?php
include_once(dirname(__FILE__).'/../includes/includes-master-list.php');


/**
 * Display all Custom Authors (Wordpress Hook Entry)
 */
function caa_display_all_custom_authors_page(){
	$display_text = null;
	if(isset($_GET["action"]) && $_GET["action"]=="delete" && isset($_GET["profile_id"])) {
		global $wpdb;
		$custom_authors_database = new CAA_Profile_DB($wpdb);
		$profile_id = $_GET["profile_id"];
		
		$custom_authors_database->delete_row_by_profile_id($profile_id);
		$display_text = caa_return_message("Custom Author Deleted!","updated");
	}
	caa_display_all_custom_authors($display_text);
}

function caa_display_all_custom_authors($display_text){
	global $wpdb;
	$custom_authors_database = new CAA_Profile_DB($wpdb);
	?>
	<div class="wrap">
		<div id="icon-users" class="icon32"><br /></div>
		<h2>Custom Authors	<a href="admin.php?page=<?php echo CAA_Config::add_new_custom_author_page; ?>" class="add-new-h2">Add New</a></h2>
		<p>&nbsp;</p>
	<?php 
		echo $display_text;
		$entryResults = $custom_authors_database->get_all_rows();
		if($entryResults){
			caa_display_entries_header_and_footer();
			foreach ($entryResults as $singleEntryResult) {
				$profile_id = $singleEntryResult->{CAA_Profile_DB::profile_id};
				$username = $singleEntryResult->{CAA_Profile_DB::username};
				$first_name = $singleEntryResult->{CAA_Profile_DB::first_name};
				$last_name = $singleEntryResult->{CAA_Profile_DB::last_name};
				$email = $singleEntryResult->{CAA_Profile_DB::email};
				
				$image_size = 40;
				$author_image = get_avatar( $email, $image_size);
					
				echo '<tr>';
				echo '<td>' . $author_image . $username;
				caa_include_row_options($profile_id, $username);
				echo '</td>';
				echo '<td>' . $first_name . '</td>';
				echo '<td>' . $last_name . '</td>';
				echo '<td>' . $email . '</td>';
				echo '</tr>';
			}
			caa_display_entries_close_table();
		}else{
			echo "<p>There are currently no custom authors. Why not <a href=\"admin.php?page=" . CAA_Config::add_new_custom_author_page . "\">add one</a>?</p>";
		}	
		
		caa_update_global_settings();
		echo caa_display_global_settings();
		
		echo caa_display_documentation_link();
		echo caa_display_donation_link();
		echo caa_display_promo_for_other_plugins();
	?>
	</div>
	<?php
		
}

function caa_update_global_settings(){

	if(! isset($_POST["is_form_submitted"])){
		//Form was not submitted.
		return;
	}
	$caa_global_display_on_home_page = NULL;
	if(isset($_POST["caa_global_display_on_home_page"])){
		$caa_global_display_on_home_page = $_POST["caa_global_display_on_home_page"];
	}
	add_option(CAA_Config::caa_global_display_on_home_page, $caa_global_display_on_home_page);
	update_option(CAA_Config::caa_global_display_on_home_page, $caa_global_display_on_home_page);

	
	$caa_global_display_on_page = NULL;
	if(isset($_POST["caa_global_display_on_page"])){
		$caa_global_display_on_page = $_POST["caa_global_display_on_page"];
	}
	add_option(CAA_Config::caa_global_display_on_page, $caa_global_display_on_page);
	update_option(CAA_Config::caa_global_display_on_page, $caa_global_display_on_page);
	
	$caa_global_display_on_single_post = NULL;
	if(isset($_POST["caa_global_display_on_single_post"])){
		$caa_global_display_on_single_post = $_POST["caa_global_display_on_single_post"];
	}
	add_option(CAA_Config::caa_global_display_on_single_post, $caa_global_display_on_single_post);
	update_option(CAA_Config::caa_global_display_on_single_post, $caa_global_display_on_single_post);
	
	$caa_global_display_on_archive_page = NULL;
	if(isset($_POST["caa_global_display_on_archive_page"])){
		$caa_global_display_on_archive_page = $_POST["caa_global_display_on_archive_page"];
	}
	add_option(CAA_Config::caa_global_display_on_archive_page, $caa_global_display_on_archive_page);
	update_option(CAA_Config::caa_global_display_on_archive_page, $caa_global_display_on_archive_page);
	
	$caa_global_display_on_top = NULL;
	if(isset($_POST["caa_global_display_on_top"])){
		$caa_global_display_on_top = $_POST["caa_global_display_on_top"];
	}
	add_option(CAA_Config::caa_global_display_on_top, $caa_global_display_on_top);
	update_option(CAA_Config::caa_global_display_on_top, $caa_global_display_on_top);
	
	echo caa_return_message("Global settings Updated!", "updated");
}

function caa_display_global_settings(){
	
	$caa_global_display_on_home_page = get_option( CAA_Config::caa_global_display_on_home_page );		// is_home()
	$caa_global_display_on_page = get_option( CAA_Config::caa_global_display_on_page );					// is_page()
	$caa_global_display_on_single_post = get_option( CAA_Config::caa_global_display_on_single_post );	// is_single()
	$caa_global_display_on_archive_page = get_option( CAA_Config::caa_global_display_on_archive_page );	// is_archive()
	$caa_global_display_on_top = get_option( CAA_Config::caa_global_display_on_top );					// is_top()
	?>
	<h3>Global Settings </h3>
		Choose which type of post to automatically display on:
		<form action="" method="post" id="global-settings">
		<input type="hidden" name="is_form_submitted" value="yes"/>
			<table class="form-table">
				<tr>
					<th scope="row"><label for="caa_global_display_on_home_page">Home Page </label></th>
					<td><input name="caa_global_display_on_home_page" type="checkbox" id="caa_global_display_on_home_page" 
					<?php 	if($caa_global_display_on_home_page){	echo "checked"; }	?> 
					/></td>
				</tr>
				<tr>
					<th scope="row"><label for="caa_global_display_on_page">Single Page </label></th>
					<td><input name="caa_global_display_on_page" type="checkbox" id="caa_global_display_on_page" 
					<?php 	if($caa_global_display_on_page){	echo "checked"; }	?> 
					/></td>
				</tr>
				<tr>
					<th scope="row"><label for="caa_global_display_on_single_post">Single Post</label></th>
					<td><input name="caa_global_display_on_single_post" type="checkbox" id="caa_global_display_on_single_post" 
					<?php 	if($caa_global_display_on_single_post){	echo "checked"; }	?> 
					/></td>
				</tr>
				<tr>
					<th scope="row"><label for="caa_global_display_on_archive_page">Archive Page </label></th>
					<td><input name="caa_global_display_on_archive_page" type="checkbox" id="caa_global_display_on_archive_page" 
					<?php 	if($caa_global_display_on_archive_page){	echo "checked"; }	?> 
					/></td>
				</tr>
			</table>
		<br />
		Choose display location (defaults to bottom of post when unchecked):
			<table class="form-table">
				<tr>
					<th scope="row"><label for="caa_global_display_on_top">Top</label></th>
					<td><input name="caa_global_display_on_top" type="checkbox" id="caa_global_display_on_top" 
					<?php 	if($caa_global_display_on_top){	echo "checked"; }	?> 
					/></td>
				</tr>
			</table>
		
		<p class="submit">
		<input type="submit" name="createuser" id="createusersub" class="button-primary" value="Save Changes"  />
		</p>
		</form>	
		<?php 
}


function caa_include_row_options($profile_id, $username){
	?>
<div class="row-actions">
	<span><a
		href="admin.php?page=<?php echo CAA_Config::edit_custom_author_page; ?>&profile_id=<?php echo $profile_id;?>">Edit</a>
		|</span> <span><a
		href="admin.php?page=<?php echo CAA_Config::display_all_custom_authors_page ?>&action=delete&profile_id=<?php echo $profile_id;?>" 
		onclick="return confirm('Confirm Deletion of <?php echo $username; ?>?')">Delete</a>
	</span>
</div>




<?php
}


function caa_display_entries_header_and_footer(){
	?>
<table class="widefat fixed">
        <thead>
                <tr class="thead">
                        <th scope="col" style="">Username</th>
                        <th scope="col" style="">First Name</th>
                        <th scope="col" style="">Last Name</th>
                        <th scope="col" style="">Email</th>
                </tr>
        </thead>
        <tfoot>
                <tr class="thead">
                        <th scope="col" style="">Username</th>
                        <th scope="col" style="">First Name</th>
                        <th scope="col" style="">Last Name</th>
                        <th scope="col" style="">Email</th>
                </tr>
        </tfoot>
        <tbody>
        <?php
}

function caa_display_entries_close_table(){
        ?>
        </tbody>
</table>
<?php
}
