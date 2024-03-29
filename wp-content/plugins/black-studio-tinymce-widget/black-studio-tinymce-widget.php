<?php
/*
Plugin Name: Black Studio TinyMCE Widget
Plugin URI: http://wordpress.org/extend/plugins/black-studio-tinymce-widget/
Description: Adds a WYSIWYG widget based on the standard TinyMCE WordPress visual editor.
Version: 0.9
Author: Black Studio
Author URI: http://www.blackstudio.it
License: GPL2
*/

global $black_studio_tinymce_widget_version;
$black_studio_tinymce_widget_version = "0.9"; // This is used internally - should be the same reported on the plugin header

/* Widget class */
class WP_Widget_Black_Studio_TinyMCE extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_black_studio_tinymce', 'description' => __('Arbitrary text or HTML with visual editor', 'black-studio-tinymce-widget'));
		$control_ops = array('width' => 600, 'height' => 500);
		parent::__construct('black-studio-tinymce', __('Black Studio TinyMCE', 'black-studio-tinymce-widget'), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$text = apply_filters( 'widget_text', $instance['text'], $instance );
		if( function_exists( 'icl_t' )) {
			$title = icl_t( "Widgets", 'widget title - ' . md5 ( $title ), $title, $hasTranslation, true );
			$text = icl_t( "Widgets", 'widget body - ' . $this->id_base . '-' . $this->number /*md5 ( $text )*/, $text, $hasTranslation, true );
		}
		$text = do_shortcode( $text );
		echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>
			<div class="textwidget"><?php echo $text; ?></div>
		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
		$instance['type'] = strip_tags($new_instance['type']);
		if( function_exists( 'icl_register_string' )) {
			//icl_register_string( "Widgets", 'widget title - ' . $this->id_base . '-' . $this->number /* md5 ( apply_filters( 'widget_title', $instance['title'] ))*/, apply_filters( 'widget_title', $instance['title'] )); // This is handled automatically by WPML
			icl_register_string( "Widgets", 'widget body - ' . $this->id_base . '-' . $this->number  /* md5 ( apply_filters( 'widget_text', $instance['text'] ))*/, apply_filters( 'widget_text', $instance['text'] ));
		}
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '', 'type' => 'visual' ) );
		$title = strip_tags($instance['title']);
		if (function_exists('esc_textarea')) {
			$text = esc_textarea($instance['text']);
		}
		else {
			$text = stripslashes( wp_filter_post_kses( addslashes( $instance['text'] ) ) );
		}
		$type = esc_attr($instance['type']);
?>
		<input id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>" type="hidden" value="<?php echo esc_attr($type); ?>" />
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
        <div class="editor_toggle_buttons hide-if-no-js">
            <a id="widget-<?php echo $this->id_base; ?>-<?php echo $this->number; ?>-html"<?php if ($type == 'html') {?> class="active"<?php }?>><?php _e('HTML'); ?></a>
            <a id="widget-<?php echo $this->id_base; ?>-<?php echo $this->number; ?>-visual"<?php if($type == 'visual') {?> class="active"<?php }?>><?php _e('Visual'); ?></a>
        </div>
		<div class="editor_media_buttons hide-if-no-js">
			<?php	do_action( 'media_buttons' ); ?>
		</div>
		<div class="editor_container">
			<textarea class="widefat" rows="16" cols="40" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
        </div>
        <?php
	}
}

/* Load localization */
load_plugin_textdomain('black-studio-tinymce-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 

/* Widget initialization */
add_action('widgets_init', 'black_studio_tinymce_widgets_init');
function black_studio_tinymce_widgets_init() {
	if ( !is_blog_installed() )
		return;
	register_widget('WP_Widget_Black_Studio_TinyMCE');
}

/* Add actions and filters (only in widgets admin page) */
add_action('admin_init', 'black_studio_tinymce_admin_init');
function black_studio_tinymce_admin_init() {
	global $pagenow;
	if ($pagenow == "widgets.php") {
		add_action( 'admin_head', 'black_studio_tinymce_load_tiny_mce');
		add_filter( 'tiny_mce_before_init', 'black_studio_tinymce_init_editor', 20);
		add_action( 'admin_print_scripts', 'black_studio_tinymce_scripts');
		add_action( 'admin_print_styles', 'black_studio_tinymce_styles');
		add_action( 'admin_print_footer_scripts', 'black_studio_tinymce_footer_scripts');
	}
}

/* Instantiate tinyMCE editor */
function black_studio_tinymce_load_tiny_mce() {
	// Remove filters added from "After the deadline" plugin, to avoid conflicts
	remove_filter( 'mce_external_plugins', 'add_AtD_tinymce_plugin' );
	remove_filter( 'mce_buttons', 'register_AtD_button' );
	remove_filter( 'tiny_mce_before_init', 'AtD_change_mce_settings' );
	//remove_all_filters('mce_external_plugins');
}

/* TinyMCE setup customization */
function black_studio_tinymce_init_editor($initArray) {
	// Remove WP fullscreen mode and set the native tinyMCE fullscreen mode
	if (get_bloginfo('version') < "3.3") {
		$plugins = explode(',', $initArray['plugins']);
		if (isset($plugins['wpfullscreen'])) {
			unset($plugins['wpfullscreen']);
		}
		if (!isset($plugins['fullscreen'])) {
			$plugins[] = 'fullscreen';
		}
		$initArray['plugins'] = implode(',', $plugins);
	}
	// Remove the "More" toolbar button
	$initArray['theme_advanced_buttons1'] = str_replace(',wp_more', '', $initArray['theme_advanced_buttons1']);
	// Do not remove linebreaks
	$initArray['remove_linebreaks'] = false;
	// Convert newline characters to BR tags
	$initArray['convert_newlines_to_brs'] = false; 
	// Force P newlines
	$initArray['force_p_newlines'] = true; 
	// Force P newlines
	$initArray['force_br_newlines'] = false; 
	// Do not remove redundant BR tags
	$initArray['remove_redundant_brs'] = false;
	// Force p block
	$initArray['forced_root_block'] = 'p';
	// Apply source formatting
	$initArray['apply_source_formatting '] = true;
	// Return modified settings
	return $initArray;
}

/* Widget js loading */
function black_studio_tinymce_scripts() {
	global $black_studio_tinymce_widget_version;
	add_thickbox();
	if (get_bloginfo('version') >= "3.3") {
		wp_enqueue_script('wplink');
		wp_enqueue_script('wpdialogs-popup');
	}
	wp_enqueue_script('media-upload');
    wp_enqueue_script('black-studio-tinymce-widget', plugins_url('black-studio-tinymce-widget.js', __FILE__), array('jquery'), $black_studio_tinymce_widget_version);
}

/* Widget css loading */
function black_studio_tinymce_styles() {
	global $black_studio_tinymce_widget_version;
	if (get_bloginfo('version') < "3.3") {
		wp_enqueue_style('thickbox');
	}
	else {
		wp_enqueue_style('wp-jquery-ui-dialog');
	}
    wp_enqueue_style('black-studio-tinymce-widget', plugins_url('black-studio-tinymce-widget.css', __FILE__), array(), $black_studio_tinymce_widget_version);
}


/* Footer script */
function black_studio_tinymce_footer_scripts() {
	// Setup for WP 3.1 and previous versions
	if (get_bloginfo('version') < "3.2") {
		if (function_exists('wp_tiny_mce')) {
			wp_tiny_mce(false, array());
		}
		if(function_exists('wp_tiny_mce_preload_dialogs')) {
			wp_tiny_mce_preload_dialogs();
		}
	}
	// Setup for WP 3.2.x
	else if (get_bloginfo('version') < "3.3") {
		if (function_exists('wp_tiny_mce')) {
			wp_tiny_mce(false, array());
		}
		if(function_exists('wp_preload_dialogs')) {
			wp_preload_dialogs( array( 'plugins' => 'wpdialogs,wplink,wpfullscreen' ) );
		}
	}
	// Setup for WP 3.3 - New Editor API
	else {
		wp_editor('', 'black-studio-tinymce-widget');
	}
}

?>