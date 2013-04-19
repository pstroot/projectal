<?php
/*
Plugin Name: Custom About Author
Plugin URI: http://littlehandytips.com/plugins/custom-about-author/
Description: Display the author profile at the end of the post. There are options to create profiles for custom authors; which is great for guest bloggers who do not have an account on your wordpress site.
Version: 1.4.2
Author: Little Handy Tips
Author URI: http://littleHandyTips.com
License: 
  Copyright 2010  Little Handy Tips  (email : plugins@littleHandyTips.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
global $caa_plugin_dir_path;
$caa_plugin_dir_path = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__));

include_once(dirname(__FILE__).'/includes/includes-master-list.php');
include_once(dirname(__FILE__).'/admin/admin.php');


//Call the Installer/Upgrader when plugin is activated
function caa_activate()
{
	require_once(dirname(__FILE__).'/installer.php');
}

//Adding hooks
register_activation_hook(__FILE__, 'caa_activate');

//Adding CSS
wp_enqueue_style('cab_style', $caa_plugin_dir_path.'/cab-style.css');

$top_priority = 0;
//remove_filter('the_content','wpautop'); //This removes the <p> </p> tags from being automatically added in the the_content filter.
add_filter('the_content', 'caa_append_custom_author_bio', $top_priority);

?>
