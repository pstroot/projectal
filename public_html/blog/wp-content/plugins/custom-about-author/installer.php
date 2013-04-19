<?php

/**
 * Activation of plugin
 *
 * Things that run once when the plugin is activated
 *
 * Reference Documentation
 * http://codex.wordpress.org/Function_Reference/wpdb_Class
 * http://codex.wordpress.org/Creating_Tables_with_Plugins
 */
include_once(dirname(__FILE__).'/includes/includes-master-list.php');

global $wpdb;
//create the custom author database
$custom_author_database_table = new CAA_Profile_DB($wpdb);
$custom_author_database_table->create_table();
$custom_author_database_table->upgrade_table();


?>