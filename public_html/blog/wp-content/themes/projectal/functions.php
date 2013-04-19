<?php

add_action( 'widgets_init', 'projectal_widgets_init',999 );

add_action('widgets_init','projectal_register_widgets');

function projectal_register_widgets(){
	register_widget('projectal_Widget_Categories');
}

function projectal_widgets_init() {
	
	//register_widget('PROJECTAL_Widget_Archives');
	
	// Area 1, located at the top of the sidebar.
	unregister_sidebar( 'primary-widget-area' );
	unregister_sidebar( 'secondary-widget-area' );
	
	register_sidebar( array(
		'name' => __( 'Primary Widget Area', 'twentyten' ),
		'id' => 'primary-widget-area',
		'description' => __( 'The primary widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</ul></li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3><ul>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Secondary Widget Area', 'twentyten' ),
		'id' => 'secondary-widget-area',
		'description' => __( 'The secondary widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</ul></li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3><ul>',
	) );

}








class projectal_Widget_Categories extends WP_Widget {

	function __construct() {

		$widget_ops = array( 
			'classname' => 'widget_categories', 
			'description' => __( "A list or dropdown of categories for ProjectAl" ) 
		);
		$this->WP_Widget('p_categories', __('ProjectAl Categories','projectal'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'ProjectAl Categories' ) : $instance['title'], $instance, $this->id_base);
		$c = ! empty( $instance['count'] ) ? '1' : '0';
		$h = ! empty( $instance['hierarchical'] ) ? '1' : '0';
		$d = ! empty( $instance['dropdown'] ) ? '1' : '0';

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		$cat_args = array('orderby' => 'name', 'show_count' => $c, 'hierarchical' => $h);

		if ( $d ) {
			$cat_args['show_option_none'] = __('Select Category');
			echo "<ul>";
			wp_dropdown_categories(apply_filters('widget_categories_dropdown_args', $cat_args));
			echo "</ul>";
			?>
			
			<script type='text/javascript'>
			/* <![CDATA[ */
				var dropdown = document.getElementById("cat");
				function onCatChange() {
					if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
						location.href = "<?php echo home_url(); ?>?main_page=wordpress&cat="+dropdown.options[dropdown.selectedIndex].value;
					}
				}
				dropdown.onchange = onCatChange;
			/* ]]> */
			</script>
			
			<?php
		} else {
			?>
			<ul>
			<?php
			$cat_args['title_li'] = '';
			wp_list_categories(apply_filters('widget_categories_args', $cat_args));
			?>
			</ul>
			<?php
		}

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;
		$instance['hierarchical'] = !empty($new_instance['hierarchical']) ? 1 : 0;
		$instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;

		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = esc_attr( $instance['title'] );
		$count = isset($instance['count']) ? (bool) $instance['count'] :false;
		$hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : false;
		$dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked( $dropdown ); ?> />
		<label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e( 'Display as dropdown' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $count ); ?> />
		<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hierarchical'); ?>" name="<?php echo $this->get_field_name('hierarchical'); ?>"<?php checked( $hierarchical ); ?> />
		<label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e( 'Show hierarchy' ); ?></label></p>
<?php
	}

}






function my_init() {
	wp_enqueue_script('jquery');	
}
function WOZ_includes(){
	wp_enqueue_script(
		'custom-WOZ-script',
		WP_CONTENT_URL . '/js/custom_WOZ_script.js',
		array('jquery')
	);
}

add_action('init', 'my_init');
add_action ( 'wp_head', 'WOZ_includes');





add_filter('comment_post_redirect', 'redirect_after_comment');
function redirect_after_comment($location)
{
	return $_SERVER["HTTP_REFERER"];
}
?>
