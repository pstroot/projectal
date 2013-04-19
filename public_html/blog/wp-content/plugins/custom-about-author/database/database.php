<?php

/**
 * Stores the profile information
 * @author edkwan
 *
 */
Class CAA_Profile_DB{
	//This might not be the actual table name if wordpress adds a prefix to it.
	const table_name = 'caa_profile_db';
	const db_version_number = 1.3;
	
	//Column names
	const profile_id = "profile_id";
	const username = "username";
	const first_name = "first_name";
	const last_name = "last_name";
	const email = "email";
	const url = "url";
	const description = "description";
	const twitter = "twitter";
	const facebook = "facebook";
	const google_plus = "google_plus";
	const linkedin = "linkedin";
	const display_html_block = "display_html_block";	//Y/N
	const html_block = "html_block";
	
	//Added columns in db_version_number 1.1
	const use_custom_image = "use_custom_image";	// Y/N
	const custom_image_url = "custom_image_url";

	//Added columns in db_version_number 1.2
	const flickr = "flickr";
	const youtube = "youtube";
	const vimeo = "vimeo";
	
	public $wpdb;
	public $full_table_name;	
	
	function __construct( $wpdb ) {
		$this->wpdb = $wpdb;
		$this->full_table_name = $this->get_table_name();
		
		//This is because register_activation_hook is no longer called in an upgrade path
		$this->upgrade_table();
	}
	
	public function get_table_name() {
		return $this->wpdb->prefix . self::table_name;
	}
		
	function create_table(){
		if(!$this->does_table_exist()) {
			$sql = "CREATE TABLE " . $this->full_table_name . " (" .
			self::profile_id . " mediumint(9) NOT NULL AUTO_INCREMENT, " .
			self::username . " VARCHAR(50), " .
			self::first_name . " VARCHAR(100), " .
			self::last_name . " VARCHAR(100), " .
			self::email . " VARCHAR(500), " .
			self::url . " VARCHAR(500), " .
			self::description . " VARCHAR(4000), " .
			self::twitter . " VARCHAR(100), " .
			self::facebook . " VARCHAR(100), " .
			self::google_plus . " VARCHAR(100), " .
			self::linkedin . " VARCHAR(100), " .
			self::flickr . " VARCHAR(100), " .
			self::youtube . " VARCHAR(100), " .
			self::vimeo . " VARCHAR(100), " .
			self::use_custom_image . " VARCHAR(5), " .
			self::custom_image_url . " VARCHAR(4000), " .
			self::display_html_block . " VARCHAR(5), " .
			self::html_block . " VARCHAR(4000),
				  	  UNIQUE KEY id (profile_id)			
							);";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
			$this->register_db_version();
			
			add_option(CAA_Config::caa_global_display_on_single_post, "on");
		}		
	}

	/**
	 * Perform any database changes as part of upgrade
	 */
	public function upgrade_table(){
		$installed_ver = get_option( $this->full_table_name . "_db_version" );
		if($installed_ver < 1.2){
			//added new social media fields in 1.2 (flickr, youtube & vimeo)
			$this->wpdb->query("ALTER TABLE ". $this->full_table_name . " ADD " . self::vimeo . " VARCHAR(100) AFTER " . self::linkedin);
			$this->wpdb->query("ALTER TABLE ". $this->full_table_name . " ADD " . self::youtube . " VARCHAR(100) AFTER " . self::linkedin);
			$this->wpdb->query("ALTER TABLE ". $this->full_table_name . " ADD " . self::flickr . " VARCHAR(100) AFTER " . self::linkedin);
				
			$this->update_db_version();
			
			add_option(CAA_Config::caa_global_display_on_single_post, "on");
		}
		if($installed_ver < 1.3){
			$this->wpdb->query("ALTER TABLE ". $this->full_table_name . " ADD " . self::use_custom_image . " VARCHAR(5) AFTER " . self::vimeo);
			$this->wpdb->query("ALTER TABLE ". $this->full_table_name . " ADD " . self::custom_image_url . " VARCHAR(4000) AFTER " . self::use_custom_image);
			$this->update_db_version();
		}
	}
	
	public function does_table_exist() {
		return  $this->wpdb->get_var("show tables like '$this->full_table_name'") == $this->full_table_name;
	}
	
	private function update_db_version() {
		update_option($this->full_table_name . "_db_version", self::db_version_number);
	}
	
	private function register_db_version() {
		add_option($this->full_table_name . "_db_version", self::db_version_number);
	}
	
		
	function get_all_rows(){
		return $this->wpdb->get_results("SELECT * FROM ".$this->full_table_name." ORDER BY " . self::username);
	}
	
	function get_row_by_id($profile_id){
		return $this->wpdb->get_row("SELECT * FROM ".$this->full_table_name." WHERE " . self::profile_id . "= '" . $profile_id . "' ORDER BY " . self::username);
	}
	
	function get_row_by_username($username){
		return $this->wpdb->get_row("SELECT * FROM ".$this->full_table_name." WHERE " . self::username . "= '" . $username . "' ORDER BY " . self::username);
	}
	
	function create_new_row($username, $first_name, $last_name, $email, $url, $description, 
							$twitter, $facebook, $google_plus, $linkedin, $flickr, $youtube, $vimeo,
							$use_custom_image, $custom_image_url,
							$display_html_block, $html_block){

		$description = caa_html_entity_encode($description);
		$custom_image_url = caa_html_entity_encode($custom_image_url);
		$html_block = caa_html_entity_encode($html_block);
		
		$data_values = array( 	self::username => $username,
								self::first_name => $first_name,
								self::last_name => $last_name,
								self::email => $email,
								self::url => $url,
								self::description => $description,
								self::twitter => $twitter,
								self::facebook => $facebook,
								self::google_plus => $google_plus,
								self::linkedin => $linkedin,
								self::flickr => $flickr,
								self::youtube =>$youtube,
								self::vimeo => $vimeo,
								self::use_custom_image => $use_custom_image,
								self::custom_image_url => $custom_image_url,
								self::display_html_block => $display_html_block,
								self::html_block => $html_block );
		
		$rows_affected = $this->wpdb->insert( $this->full_table_name, $data_values);
		
		return $this->wpdb->insert_id;		
	}

	function edit_row($profile_id, $first_name, $last_name, $email, $url, $description,
							$twitter, $facebook, $google_plus, $linkedin, $flickr, $youtube, $vimeo,
							$use_custom_image, $custom_image_url,
							$display_html_block, $html_block){
		
		$description = caa_html_entity_encode($description);
		$custom_image_url = caa_html_entity_encode($custom_image_url);
		$html_block = caa_html_entity_encode($html_block);

		$update_array = array( 	self::first_name => $first_name,
								self::last_name => $last_name,
								self::email => $email,
								self::url => $url,
								self::description => $description,
								self::twitter => $twitter,
								self::facebook => $facebook,
								self::google_plus => $google_plus,
								self::linkedin => $linkedin,
								self::flickr => $flickr,
								self::youtube =>$youtube,
								self::vimeo => $vimeo,
								self::use_custom_image => $use_custom_image,
								self::custom_image_url => $custom_image_url,
								self::display_html_block => $display_html_block,
								self::html_block => $html_block);
		$where_array = array( self::profile_id => $profile_id);
		
		//update table. returns false if errors
		$this->wpdb->update($this->full_table_name, $update_array, $where_array);		
	}
	
	function delete_row_by_profile_id($profile_id){
		$this->wpdb->query( $this->wpdb->prepare(
										"DELETE FROM $this->full_table_name WHERE " . self::profile_id ."=%d", 
										array($profile_id)
										)
							);		
	}
	
}