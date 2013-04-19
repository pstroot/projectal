<?php
/*
Plugin Name: Blog Icons
Version: 0.5.1
Plugin URI: http://yoast.com/wordpress/blog-icons/
Description: A simple method of adding favicons and iPod Touch / iPhone icons to your blog.
Author: Joost de Valk
Author URI: http://yoast.com/

Copyright 2008-2009 Joost de Valk (email: joost@yoast.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

if ( ! class_exists( 'BlogIcons_Admin' ) ) {

	require_once('yst_plugin_tools.php');
	
	class BlogIcons_Admin extends Yoast_Plugin_Admin {
		
		var $hook 		= 'blog-icons';
		var $longname	= 'Blog Icons Configuration';
		var $shortname	= 'Blog Icons';
		var $filename	= 'blog-icons/blogicons.php';
		var $homepage	= 'http://yoast.com/wordpress/blog-icons/';
		
		function register_settings_page() {
			add_theme_page($this->longname, $this->shortname, $this->accesslvl, $this->hook, array(&$this,'config_page'));
		}
			
		function plugin_options_url() {
			return admin_url( 'themes.php?page='.$this->hook );
		}
		
		function config_page_scripts() {
			if (isset($_GET['page']) && $_GET['page'] == $this->hook) {
				wp_enqueue_script('jquery');
				wp_enqueue_script('postbox');
				wp_enqueue_script('dashboard');
				wp_enqueue_script('thickbox');
				wp_enqueue_script('media-upload');
				wp_enqueue_script('blog-icon-select',WP_CONTENT_URL . '/plugins/' . plugin_basename(dirname(__FILE__)). '/blog-icon-select.js', array('thickbox','media-upload'), '1.0', true);
			}
		}
		
		function config_page() {
			$options = get_option('blogiconsoptions');
			
			if ( isset($_POST['submit']) ) {
				if (!current_user_can('manage_options')) die(__('You cannot edit the Blog Icons options.'));
				check_admin_referer('blogicons-config');

				$inputs = array(
					"touchicon" => array( 
						"label" => "Touch Icon", 
						"type" => "image", 
						"sizeshaperestrict" => "square", 
					),
					"adminurl" => array( 
						"label" => "Admin Favicon", 
						"type" => "image",
						"sizeshaperestrict" => "square", 
					),
					"adminicon" => array( 
						"type" => "text", 
					),
					"favicon" => array( 
						"label" => "Favicon", 
						"type" => "image", 
						"sizeshaperestrict" => "square", 
					),
					"touchbootimg" => array( 
						"label" => "iPhone Boot Image", 
						"type" => "image", 
						"sizeshaperestrict" => "sizeexact", 
						"sizew" => 320, 
						"sizeh" => 460
					),
					"feedimage" => array( 
						"label" => "Feed Image", 
						"type" => "image", 
						"sizeshaperestrict" => "sizemax", 
						"sizew" => 144, 
						"sizeh" => 400
					),
				);
				
				// If we're not using the admin icon that was uploaded, don't test it either, and don't save it.
				if ($_POST['adminicon'] == "other")
					$_POST['adminurl'] = '';
				
				// Check the uploaded images for the right sizes and shapes
				foreach ($inputs as $key => $val) {
					if (isset($_POST[$key]) && trim($_POST[$key]) != "") {
						if ($val['type'] == "image") {
							$url = $_POST[$key];
							$img = wp_remote_fopen($url);
							if (!$img) {
								$error .= "<p><strong>Error:</strong> Where's the ".$val['label']."? URL: ".$url."</p>";
							}
							if (isset($val['sizeshaperestrict'])) {
								$size = getimagesize($url);
								if (!is_array($size)) {
									$error .= "<p><strong>Error:</strong> An unkown error occurred with the ".$val['label'].".</p>";
								}
								switch ($val['sizeshaperestrict']) {
									case 'sizemax':
										if ($size[0] > $val['sizew'] || $size[1] > $val['sizeh']) {
											$error .= "<p><strong>Error:</strong> The ".$val['label']." is too big. It's maximum width is ".$val['sizew']."px, the maximum height is ".$val['sizeh']."x.</p>";
										} else {
											$options[$key] = $_POST[$key];
										}
										break;
									case 'sizeexact':
										if ($size[0] != $val['sizew'] || $size[1] != $val['sizeh']) {
											$error .= "<p><strong>Error:</strong> The ".$val['label']." is not the right size. It should be ".$val['sizew']."px wide by ".$val['sizeh']."px high.</p>";
										} else {
											$options[$key] = $_POST[$key];
										}
										break;
									case 'square':
										if ($size[0] != $size[1]) {
											$error .= "<p><strong>Error:</strong> The ".$val['label']." you uploaded is not square, while it should be.</p>";
										} else {
											$options[$key] = $_POST[$key];
										}
										break;
								}
							}
						} else {
							$options[$key] = $_POST[$key];
						}
					} else {
						$options[$key] = "";
					}
				}
			
				if (get_option('blogiconsoptions') != $options) {
					update_option('blogiconsoptions', $options);
					$message = "<p>Blog Icons settings have been updated.</p>";
				}
			}
			
			$options = get_option('blogiconsoptions');
			
			if (isset($error) && $error != "") {
				echo "<div id=\"message\" class=\"error\">$error</div>\n";
			} elseif (isset($message) && $message != "") {
				echo "<div id=\"updatemessage\" class=\"updated fade\">$message</div>\n";
				echo "<script type=\"text/javascript\">setTimeout(function(){jQuery('#updatemessage').hide('slow');}, 3000);</script>";
			}
			?>
			<div class="wrap">
				<a href="http://yoast.com/"><div id="yoast-icon" style="background: url(http://cdn.yoast.com/theme/yoast-32x32.png) no-repeat;" class="icon32"><br /></div></a>
				<h2>Blog Icons options</h2>
				<div class="postbox-container" style="width:70%;">
					<div class="metabox-holder">	
						<div class="meta-box-sortables">
							<form action="" method="post" id="blogicons-conf" enctype="multipart/form-data">
								<?php
								if ( function_exists('wp_nonce_field') )
									wp_nonce_field('blogicons-config');
																
								$content .= '<input class="text" type="text" value="'.$options['favicon'].'" name="favicon" id="favicon"/><input type="button" class="button" value="'.__('Select Image').'" onclick="show_image_uploader(\'favicon\');"/>';
											
								$rows = array ();
								$rows[] = array(
											'id' => 'favicon',
											'label' => 'Favicon',
											'desc' => '16x16px png, jpg, gif or ico file',
											'content' => $content
										);

								if ( isset($options['favicon']) && $options['favicon'] != "" ) {
									$rows[] = array(
										'id' => '',
										'label' => 'Current Favicon:',
										'content' => '<img src="'.$options['favicon'].'" alt="current favicon"/>'
									);
								}
						
								$this->postbox('favicon','Favicon', $this->form_table($rows));

								$content = '<input type="radio" name="adminicon" value="global" id="useglobalfavicon"'.checked($options['adminicon'],"global",false).'/>';
								$content .= ' <label for="useglobalfavicon">Use the frontpage favicon.</label><br/>';
								$content .= '<input type="radio" value="wp" name="adminicon" id="usewpfavicon"'.checked($options['adminicon'],"wp",false).'/>';
								$content .= ' <label for="usewpfavicon">Use the WordPress favicon, which looks like this:';
								$content .= '<img src="'.WP_CONTENT_URL . '/plugins/' . plugin_basename(dirname(__FILE__)).'/wpfavicon.ico" width="16" height="16" alt="WordPress favicon"/></label><br/>';
								$content .= '<input type="radio" value="other" name="adminicon" id="useother" '. checked($options['adminicon'],'other',false).'/>';
								$content .= ' <label for="useother">Use another favicon:</label><br/>'; // '<br/>';
								$content .= '<input name="adminurl" class="text" type="text" id="adminurl" value="'.$options['adminurl'].'"/>';
								$content .= '<input type="button" class="button" value="'.__('Select Image').'" onclick="show_image_uploader(\'adminurl\');"/>';
								$rows = array();
								$rows[] = array(
									'id' => 'adminicon',
									'label' => 'Admin Favicon',
									'desc' => 'Used for the Admin Directory',
									'content' => $content
								);
								
								$this->postbox( 'adminicon', 'Admin Favicon', $this->form_table( $rows ) );
								
								$rows = array();
								$rows[] = array(
									'id' => 'touchicon',
									'label' => 'Web Clip icon',
									'desc' => '57x57px png, jpg, gif file',
									'content' => '<input name="touchicon" type="text" id="touchicon" value="'.$options['touchicon'].'" class="text" /><input type="button" class="button" value="'.__('Select Image').'" onclick="show_image_uploader(\'touchicon\');"/>'
								);
								if (isset($options['touchicon']) && $options['touchicon'] != "") {
									$rows[] = array(
										'label' => 'Current Web Clip icon',
										'content' => '<img src="'.$options['touchicon'].'" alt="current iPod Touch / iPhone icon"/>'
									);
								}
								$rows[] = array(
									'id' => 'touchbootimg',
									'label' => 'Boot image',
									'desc' => '320x460px png, jpg, gif file',
									'content' => '<input type="text" value="'.$options['touchbootimg'].'" name="touchbootimg" id="touchbootimg" class="text"/><input type="button" class="button" value="'.__('Select Image').'" onclick="show_image_uploader(\'touchbootimg\');"/>'
								);
								if (isset($options['touchbootimg']) && $options['touchbootimg'] != "") {
									$rows[] = array(
										'label' => 'Current Boot Image',
										'desc' => 'Scaled, click for original size',
										'content' => '<a href="'.$options['touchbootimg'].'" class="thickbox"><img style="height:80px; border:1px solid #aaa;" src="'.$options['touchbootimg'].'" alt="current iPod Touch / iPhone boot image"/></a>'
									);
								}
								$this->postbox( 'iphoneicon', 'iPod Touch / iPhone icon &amp; boot image', $this->form_table( $rows ) );
								
								$rows = array();
								$rows[] = array(
									'id' => 'feedimage',
									'label' => 'Feed Image',
									'desc' => 'Maximum size 144x400px, default 88x31px png, jpg or gif file',
									'content' => '<input type="text" value="'.$options['feedimage'].'" name="feedimage" id="feedimage" class="text"/><input type="button" class="button" value="'.__('Select Image').'" onclick="show_image_uploader(\'feedimage\');"/>'
								);
								if (isset($options['feedimage']) && $options['feedimage'] != "") {
									$rows[] = array(
										'label' => 'Current Feed Image',
										'desc' => 'Width: '.$options['feedimagewidth'].'px<br/>Height: '.$options['feedimageheight'].'px<br/>Scaled, click for original size',
										'content' => '<a href="'.$options['feedimage'].'" class="thickbox"><img style="height:50px; border: 1px solid #aaa;" src="'.$options['feedimage'].'" alt="Current feed image"/></a>'
									);
								}
								$this->postbox( 'feedimage', 'Feed Image', $this->form_table( $rows ) );
								?>
								<div class="submit">
									<input type="submit" class="button-primary" name="submit" value="Update Your Blog Icons &raquo;" />
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="postbox-container" style="width:20%;">
					<div class="metabox-holder">	
						<div class="meta-box-sortables">
							<?php
								$this->plugin_like('blog-icons');
								$this->plugin_support('blog-icons');
								$this->news(); 
							?>
						</div>
						<br/><br/><br/>
					</div>
				</div>
			</div>
<?php		
			}
	}
	$bsa = new BlogIcons_Admin();
}

function blogiconshead() {
	$options = get_option('blogiconsoptions');
	if ( isset($options['favicon']) && $options['favicon'] != "" ) {
		echo '<link rel="shortcut icon" href="'.$options['favicon'].'"/>'."\n";
	}
	if ( isset($options['touchicon']) && $options['touchicon'] != "" ) {
		echo '<link rel="apple-touch-icon" href="'.$options['touchicon'].'"/>'."\n";
	}
	if ( isset($options['touchbootimg']) && $options['touchbootimg'] != "" ) {
		echo '<link rel="apple-touch-startup-image" href="'.$options['touchbootimg'].'"/>';
		echo '<meta name="apple-mobile-web-app-capable" content="yes" />';
	}
}

function blogicons_adminhead() {
	$options = get_option('blogiconsoptions');
	if ( isset( $options['adminicon'] ) && $options['adminicon'] != "" ) {
		if ( $options['adminicon'] == "wp" ) {
			echo '<link rel="shortcut icon" href="'.WP_CONTENT_URL . '/plugins/' . plugin_basename(dirname(__FILE__)) . '/wpfavicon.ico"' . "/>\n";
		} else if ( $options['adminicon'] == "global" ) {
			echo '<link rel="shortcut icon" href="'.$options['favicon'].'"/>'."\n";
		} else {
			echo '<link rel="shortcut icon" href="'.$options['adminicon'].'"/>'."\n";
		}
	}
}

function addRssImage() {
	$options = get_option('blogiconsoptions');
	if (isset($options['feedimage']) && $options['feedimage'] != "") {
		echo "<image>
			<title>" . get_bloginfo('name') . "</title>
			<url>" . $options['feedimage'] . "</url>
			<link>" . get_bloginfo('url') ."</link>
			<width>" . $options['feedimagewidth'] . "</width>
			<height>" . $options['feedimageheight'] . "</height>
			<description>" . get_bloginfo('description') . "</description>
		</image>";		
	}	
}

function addAtomImage() {
	$options = get_option('blogiconsoptions');
	if (isset($options['favicon']) && $options['favicon'] != "") {
		echo "<icon>" . $options['favicon'] . "</icon>";
	}
	if (isset($options['feedimage']) && $options['feedimage'] != "") {
		echo "<logo>" . $options['feedimage'] . "</logo>";
	}
}

add_action('wp_head', 'blogiconshead');
add_action('admin_head', 'blogicons_adminhead');
add_action('rss_head', 'addRssImage');
add_action('rss2_head', 'addRssImage');
add_action('atom_head', 'addAtomImage');

?>